@extends('layouts.base')

@section('content')
	<div class="min-h-screen hero bg-base-300">
		<div class="text-center hero-content">
			<div class="max-w-md">
                <h1 class="text-5xl font-extrabold text-transparent bg-gradient-to-r from-green-300 via-blue-500 to-purple-600 bg-clip-text sm:text-5xl">
					<a id="typewriter"></a> Malwares. <span class="sm:block"> Get Money. </span>
				</h1>
				
				<p class="py-6 text-xl">
					Best marketplace to trade malwares. We provide a secure platform to buy and sell malwares.
				</p>
				<a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
			</div>
		</div>
	</div>
	
    <script>
		const words = ["Buy", "Sell"], speed = 300;
		let i = 0, j = 0, isDeleting = false;

		(function type() {
			const currentWord = words[i];
			document.getElementById("typewriter").textContent = currentWord.substring(0, j + (isDeleting ? -1 : 1));
			j += isDeleting ? -1 : 1;

			if (!isDeleting && j === currentWord.length) {
				isDeleting = true;
			}
			else if (isDeleting && j === 0) {
				isDeleting = false;
				i = (i + 1) % words.length;
			}

			setTimeout(type, speed);
		})();
    </script>
    
@endsection