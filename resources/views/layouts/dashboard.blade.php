<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ config('app.name', 'JobFlow') }} — @yield('title', 'Dashboard')</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet">

@vite(['resources/css/dashboard.css', 'resources/css/animations.css', 'resources/js/app.js'])

@yield('head')
@yield('styles')
</head>
<body class="dashboard-body">

    <div class="app-container" style="display: flex; width: 100%; min-height: 100vh;">
        <!-- SIDEBAR -->
        @include('components.dashboard.sidebar')

        <!-- MAIN CONTENT -->
        <main class="main" style="flex: 1; padding: 24px 40px; overflow-y: auto;">
            @yield('content')
        </main>
    </div>

    @yield('scripts')

    <script>
        // Common Dashboard JS
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (this.getAttribute('href') === '#') e.preventDefault();
            });
        });

        // Toast functionality
        window.showToast = function(msg) {
            const t = document.createElement('div');
            t.style.cssText = `position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(10px);background:var(--teal);border-radius:12px;padding:12px 24px;font-size:14px;color:#060912;font-weight:700;z-index:9999;transition:all 0.3s ease;opacity:0;box-shadow:0 8px 24px rgba(0,212,170,0.3);`;
            t.textContent = msg;
            document.body.appendChild(t);
            requestAnimationFrame(() => { t.style.opacity = '1'; t.style.transform = 'translateX(-50%) translateY(0)'; });
            setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3000);
        };
    </script>

    @if(session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.showToast === 'function') {
            window.showToast(@json(session('success')));
        }
    });
    </script>
    @endif
</body>
</html>
