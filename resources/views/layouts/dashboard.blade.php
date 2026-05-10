<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ config('app.name', 'JobFlow') }} — @yield('title', 'Dashboard')</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

@vite(['resources/css/dashboard.css', 'resources/css/animations.css', 'resources/js/app.js'])

@yield('head')
@yield('styles')
</head>
<body class="dashboard-body">

    <!-- SIDEBAR -->
    @include('components.dashboard.sidebar')

    <!-- TOPBAR -->
    @include('components.dashboard.topbar')

    <!-- MAIN CONTENT -->
    <main class="main">
        @yield('content')
    </main>

    @yield('scripts')

    <script>
        // Common Dashboard JS
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // If it's a real link, don't prevent default
                if (this.getAttribute('href') !== '#') return;
                
                e.preventDefault();
                document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Toast functionality
        window.showToast = function(msg) {
            const t = document.createElement('div');
            t.style.cssText = `position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(10px);background:var(--surface3);border:1px solid var(--border2);border-radius:10px;padding:10px 18px;font-size:13px;color:var(--text);z-index:999;transition:all 0.3s ease;opacity:0;font-family:'DM Sans',sans-serif;box-shadow:0 8px 24px rgba(0,0,0,0.4);`;
            t.textContent = msg;
            document.body.appendChild(t);
            requestAnimationFrame(() => { t.style.opacity = '1'; t.style.transform = 'translateX(-50%) translateY(0)'; });
            setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 2000);
        };
    </script>
</body>
</html>
