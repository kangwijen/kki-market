<?php if($errors->any()): ?>
    <div id="error-popup" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative p-6 bg-white rounded-lg shadow-xl">
            <h2 class="mb-4 text-xl font-bold text-red-600">Error</h2>
            <ul class="mb-4 text-sm text-gray-700">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button onclick="document.getElementById('error-popup').remove()" class="px-4 py-2 text-white bg-red-600 rounded hover:bg-red-700">
                Close
            </button>
        </div>
    </div>
<?php endif; ?><?php /**PATH D:\kki-market\resources\views/components/error-popup.blade.php ENDPATH**/ ?>