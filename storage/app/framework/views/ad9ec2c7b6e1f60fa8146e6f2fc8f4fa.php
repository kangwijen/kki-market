

<?php $__env->startSection('content'); ?>
    <div class="flex items-center justify-center min-h-screen bg-base-300">
        <div class="text-center">
            <h1 class="font-black text-red-500 text-9xl">404</h1>

            <p class="text-2xl font-bold tracking-tight text-white sm:text-4xl">Uh-oh!</p>

            <p class="mt-4 text-white">We can't find that page.</p>

            <a href="<?php echo e(route('index')); ?>" class="mt-6 btn btn-primary">
                Go Back Home
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\kki-market\resources\views/errors/404.blade.php ENDPATH**/ ?>