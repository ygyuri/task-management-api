<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel Vue App')</title>
    
    @vite(['resources/css/app.css', 'resources/js/main.js'])  <!-- Load Vite and Vue -->
</head>
<body>
    <div id="app">
        @yield('content')  <!-- This is where Vue or Blade content goes -->
    </div>
</body>
</html>
