<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Ruang Rias' }}</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
    <body class="{{ request()->routeIs('login') ? 'login-page' : (request()->routeIs('register') ? 'register-page' : (request()->routeIs('admin.dashboard') ? 'admin-dashboard-page' : (request()->routeIs('dashboard') ? (auth()->user()?->role === 'customer' ? 'customer-dashboard-page' : 'dashboard-page') : ''))) }}">
    <header class="topbar">
        <a class="brand" href="{{ auth()->check() && auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}"><span>RR</span> Ruang <em>Rias</em></a>
        @auth
            <div class="user-menu"><span>{{ auth()->user()->name }} <small>{{ ucfirst(auth()->user()->role) }}</small></span>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="button button-quiet" type="submit">Keluar</button></form>
            </div>
        @endauth
    </header>
    <main class="page-shell">
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if ($errors->any())<div class="alert alert-error"><strong>Periksa kembali:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @yield('content')
    </main>
</body>
</html>
