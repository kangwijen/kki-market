<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KKI Market</title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
</head>
<body>
    <div class="navbar bg-base-100">
        <div class="flex-1">
            <a href="<?php echo e(route('index')); ?>" class="text-xl btn btn-ghost">KKI-Market</a>
        </div>

        <?php if(auth()->guard()->guest()): ?>
            <div class="flex-none">
                <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Login</a>
            </div>
        <?php endif; ?>

        <?php if(auth()->guard()->check()): ?>
            <div>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-secondary">Logout</button>
                </form>
            </div>
            <?php endif; ?>
    </div>

    <?php echo $__env->yieldContent('content'); ?>

    <footer class="p-4 footer footer-center bg-base-100 text-base-content">
        <aside>
          <p>Copyright 2024 - KKI-Market</p>
        </aside>
    </footer>
</body>
</html><?php /**PATH D:\kki-market\resources\views/layouts/base.blade.php ENDPATH**/ ?>