<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KKI Market</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="navbar bg-base-100">
        <div class="flex-1">
            <a href="{{ route('index') }}" class="text-xl btn btn-ghost">KKI-Market</a>
        </div>
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        </div>          
  </div>

    @yield('content')

    <footer class="p-4 footer footer-center bg-base-100 text-base-content">
        <aside>
          <p>Copyright 2024 - KKI-Market</p>
        </aside>
    </footer>
</body>
</html>