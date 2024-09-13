

<?php $__env->startSection('content'); ?>
    <section class="text-white bg-gray-700">
        <div class="max-w-screen-xl px-4 py-32 mx-auto lg:flex lg:h-screen lg:items-center">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-3xl font-extrabold text-transparent bg-gradient-to-r from-green-300 via-blue-500 to-purple-600 bg-clip-text sm:text-5xl">
                <a id="typewriter"></a> Malwares. <span class="sm:block"> Get Money. </span>
                </h1>
        
                <p class="max-w-xl mx-auto mt-4 sm:text-xl/relaxed">
                Best marketplace to trade malwares. We provide a secure platform to buy and sell malwares.
                </p>
        
                <div class="flex flex-wrap justify-center gap-4 mt-8">
                <a
                    class="block w-full px-12 py-3 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded hover:bg-transparent hover:text-white focus:outline-none focus:ring active:text-opacity-75 sm:w-auto"
                    href="#"
                >
                    Get Started
                </a>
                </div>
            </div>
        </div>
    </section>

    <div class="flex items-center justify-center w-full h-full">
        <h1 id="typewriter" class="text-4xl font-bold"></h1>
    </div>
    
    <script>
    const words = ["Buy", "Sell"];
    let i = 0;
    let j = 0;
    let currentWord = "";
    let isDeleting = false;
    
    function type() {
      currentWord = words[i];
      if (isDeleting) {
        document.getElementById("typewriter").textContent = currentWord.substring(0, j-1);
        j--;
        if (j == 0) {
          isDeleting = false;
          i++;
          if (i == words.length) {
            i = 0;
          }
        }
      } else {
        document.getElementById("typewriter").textContent = currentWord.substring(0, j+1);
        j++;
        if (j == currentWord.length) {
          isDeleting = true;
        }
      }
      setTimeout(type, 300);
    }
    
    type();
    </script>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\kki-market\resources\views/index.blade.php ENDPATH**/ ?>