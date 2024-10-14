

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen hero bg-base-300">
        <div class="flex flex-col items-center justify-center text-center hero-content sm:flex-row">
            <div class="max-w-md px-4">
                <h1 class="text-4xl font-extrabold text-transparent sm:text-5xl bg-gradient-to-r from-green-300 via-blue-500 to-purple-600 bg-clip-text">
                    <a id="typewriter"></a>Malwares. 
                    <span class="block"> Get Money. </span>
                </h1>
                
                <p class="py-6 text-lg sm:text-xl">
                    Best marketplace to trade malwares. We provide a secure platform to buy and sell malwares.
                </p>
                <?php if(auth()->check()): ?>
                    <a href="<?php echo e(url('/search')); ?>" class="btn btn-primary">Go to Products</a>
                <?php else: ?>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.10/typed.min.js" integrity="sha512-hIlMpy2enepx9maXZF1gn0hsvPLerXoLHdb095CmRY5HG3bZfN7XPBZ14g+TUDH1aGgfLyPHmY9/zuU53smuMw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const options = {
            strings: ["Buy", "Sell"],
            typeSpeed: 250,
            backSpeed: 150,
            loop: true,
        };

        const typed = new Typed('#typewriter', options);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\kki-market\resources\views/generic/index.blade.php ENDPATH**/ ?>