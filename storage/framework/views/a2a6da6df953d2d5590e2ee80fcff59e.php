<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KKI Market</title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
</head>
<body>
    <header class="bg-gray-800">
        <div class="max-w-screen-xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
            <div class="md:flex md:items-center md:gap-12">
                <a class="block text-white" href="#">
                <span class="sr-only">Home</span>
                KKI-Market
                </a>
            </div>

            <div class="hidden md:block">
                <nav aria-label="Global">
                <ul class="flex items-center gap-6 text-sm">
                    <li>
                    <a class="text-white transition hover:text-white/75" href="/about"> About </a>
                    </li>
                    
                    <li>
                    <a class="text-white transition hover:text-white/75" href="#"> Services </a>
                    </li>

                    <li>
                    <a class="text-white transition hover:text-white/75" href="#"> Blog </a>
                    </li>
                </ul>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <div class="sm:flex sm:gap-4">
                <a
                    class="rounded-md bg-gray-600 px-5 py-2.5 text-sm font-medium text-white shadow"
                    href="#"
                >
                    Login
                </a>

                <div class="hidden sm:flex">
                    <a
                    class="rounded-md bg-gray-100 px-5 py-2.5 text-sm font-medium text-black-600"
                    href="#"
                    >
                    Register
                    </a>
                </div>
                </div>

                <div class="block md:hidden">
                <button class="p-2 text-gray-600 transition bg-gray-100 rounded hover:text-gray-600/75">
                    <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="size-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                    >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                </div>
            </div>
            </div>
        </div>
    </header>

    <?php echo $__env->yieldContent('content'); ?>

    <footer class="bg-gray-800">
        <div class="max-w-screen-xl px-4 py-8 mx-auto sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center sm:justify-between">
            <div class="flex justify-center text-white sm:justify-start">
                <a href="#" class="text-2xl font-bold">KKI-Market</a>
            </div>

            <p class="mt-4 text-sm text-center text-white lg:mt-0 lg:text-right">
                Copyright &copy; 2024. All rights reserved.
            </p>
            </div>
        </div>
    </footer>

</body>
</html><?php /**PATH D:\kki-market\resources\views/layouts/base.blade.php ENDPATH**/ ?>