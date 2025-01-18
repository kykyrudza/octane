<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $code ?> - <?= $message ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-950">

<div class="mx-auto text-white flex items-center justify-center h-screen">
    <p class="text-zinc-700 font-light text-2xl">
        <?= $code ?> | <?= $message ?>
    </p>
</div>

</body>
</html>
