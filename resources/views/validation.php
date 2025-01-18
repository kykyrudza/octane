<?php include 'layout/start.php'?>


<form
    action="http://127.0.0.1:8000/test-validation"
    method="post"
    class="grid grid-cols-1 max-w-lg mx-auto h-screen place-content-center gap-4"
>
    <div class="text-lg px-2">
        <label class="w-2/6" for="name">Name</label>
        <input
                class="p-2 w-full border"
                type="text"
                id="name"
                name="name"
                placeholder="Your name"
                value="<?php echo request()->old('name') ?? ''; ?>"
        >
    </div>
    <div class="text-lg px-2">
        <label class="w-2/6" for="email">Email</label>
        <input
                class="p-2 w-full border"
                type="email"
                id="email"
                name="email"
                placeholder="Your email"
                value="<?php echo request()->old('email') ?? ''; ?>"
        >
    </div>

    <div class="text-lg px-2">
        <label class="w-2/6" for="password">Password</label>
        <input
                class="p-2 w-full border"
                type="password"
                id="password"
                name="password"
                placeholder="Your password"
        >
    </div>

    <div class="text-lg px-2">
        <label class="w-2/6" for="password_confirmation">Password Confirm</label>
        <input
                class="p-2 w-full border"
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Your password"
        >
    </div>

    <button class="border mx-2 py-2 text-lg" type="submit">Submit</button>
</form>


<?php include 'layout/end.php'?>
