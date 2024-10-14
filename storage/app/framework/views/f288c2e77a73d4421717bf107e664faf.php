

<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginale2ee1e87b986756b484e228b1eb91e85 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale2ee1e87b986756b484e228b1eb91e85 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.error-popup','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('error-popup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale2ee1e87b986756b484e228b1eb91e85)): ?>
<?php $attributes = $__attributesOriginale2ee1e87b986756b484e228b1eb91e85; ?>
<?php unset($__attributesOriginale2ee1e87b986756b484e228b1eb91e85); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale2ee1e87b986756b484e228b1eb91e85)): ?>
<?php $component = $__componentOriginale2ee1e87b986756b484e228b1eb91e85; ?>
<?php unset($__componentOriginale2ee1e87b986756b484e228b1eb91e85); ?>
<?php endif; ?>
<div class="flex items-center justify-center min-h-screen p-4 bg-base-300">
    <div class="w-full max-w-md p-6 space-y-6 rounded-lg shadow-lg sm:p-8 bg-base-100">
        <h1 class="text-3xl font-bold text-center sm:text-4xl text-primary">Login</h1>
        <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4 sm:space-y-6">
            <?php echo csrf_field(); ?>
            
            <div class="form-control">
                <label for="email" class="label">
                    <span class="label-text">Email</span>
                </label>
                <input type="email" id="email" name="email" placeholder="Email" class="w-full input input-bordered" required />
            </div>
            <div class="form-control">
                <label for="password" class="label">
                    <span class="label-text">Password</span>
                </label>
                <input type="password" id="password" name="password" placeholder="Password" class="w-full input input-bordered" required />
            </div>
            <div class="text-center">
                <p class="text-sm">
                    No account? 
                    <a href="<?php echo e(route('register')); ?>" class="text-primary hover:underline">Create one here</a>.
                </p>
            </div>
            <div class="mt-6 form-control">
                <button type="submit" class="w-full btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\kki-market\resources\views/auth/login.blade.php ENDPATH**/ ?>