<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error Occurred</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="py-10 bg-gray-950">

        <div class="mx-auto px-6 lg:max-w-screen lg:px-8">
            <div class="grid gap-4 grid-cols-1">
                <div class="relative col-span-2">
                    <div class="w-full py-10 px-8 flex items-center">
                        <div class="w-32 flex items-center justify-end">
                            <img src="icon/error.svg" alt="Error Icon" class="min-w-32 min-h-32">
                        </div>
                        <div class="w-full ml-10">
                            <p class="text-4xl font-bold text-white max-lg:text-center">
                                Unexpected Error
                            </p>
                        </div>
                    </div>
                </div>
                <div class="relative col-span-2">
                    <div class="relative bg-gray-900 flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-[calc(2rem+1px)] lg:rounded-[calc(2rem+1px)]">
                        <div class="px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10">
                            <p class="mt-2 text-2xl text-white max-lg:text-center">
                                <?= $errorMessage ?>
                            </p>
                            <p class="mt-2 max-w-lg text-xl text-zinc-400 max-lg:text-center">
                                <?= $errorFile ?> (Line <?= $errorLine ?>)
                            </p>
                        </div>
                        <div class="relative min-h-[50rem] w-full grow">
                            <div class="absolute bottom-0 left-10 right-0 top-10 overflow-y-scroll overflow-hidden rounded-tl-xl bg-gray-800 shadow-2xl">
                                <div class="flex ring-1 ring-white/5">
                                    <div class="border-b border-r border-b-white/20 border-r-white/10 bg-white/5 px-4 py-2 text-white">
                                        <?= basename($errorFile) ?>
                                    </div>
                                </div>
                                <div class="mx-6 text-white text-md">
                                    <pre class=" whitespace-pre-wrap"><?php
                                        function displayCodeContext(string $errorFile, int $errorLine): void
                                        {
                                            if (file_exists($errorFile)) {
                                                $fileContents = file($errorFile);
                                                $lineCount = count($fileContents);
                                                $startLine = max(1, $errorLine - 15);
                                                $endLine = min($lineCount, $errorLine + 15);

                                                echo "<div class='overflow-x-auto'><table class='table-auto border-collapse w-full'>";

                                                foreach (range($startLine, $endLine) as $currentLine) {
                                                    $lineContent = htmlspecialchars($fileContents[$currentLine - 1]);

                                                    $lineNumber = str_pad($currentLine, 3, '0', STR_PAD_LEFT);
                                                    if ($currentLine == $errorLine) {
                                                        echo "<tr><td class='bg-red-400 text-red-600 font-bold'>{$lineNumber}</td><td class='bg-red-300 text-rose-800'>{$lineContent}</td></tr>";
                                                    } else {
                                                        echo "<tr><td class='text-gray-500'>{$lineNumber}</td><td>{$lineContent}</td></tr>";
                                                    }
                                                }

                                                echo '</table></div>';

                                            } else {
                                                echo "<span class='text-red-500'>File not found: {$errorFile}</span>";
                                            }
                                        }
                                displayCodeContext($errorFile, $errorLine);
                                ?></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
