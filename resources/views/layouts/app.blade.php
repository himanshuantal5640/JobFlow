<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>JobFlow — Dynamic Job Application Tracker</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script>
        // Scroll reveal
        const reveals = document.querySelectorAll('.reveal');
        const io = new IntersectionObserver((entries) => {
            entries.forEach((e, i) => {
                if (e.isIntersecting) {
                    setTimeout(() => e.target.classList.add('visible'), i * 80);
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.12 });
        reveals.forEach(el => io.observe(el));

        // Nav scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (nav) {
                nav.style.background = window.scrollY > 60 ? 'rgba(6,6,15,0.92)' : 'rgba(6,6,15,0.7)';
            }
        });

        // Animated number counter
        document.querySelectorAll('.hero-stat .number').forEach(el => {
            const text = el.textContent;
            const match = text.match(/[\d.]+/);
            if (!match) return;
            const target = parseFloat(match[0]);
            const suffix = text.replace(match[0], '');
            let current = 0;
            const inc = target / 60;
            const timer = setInterval(() => {
                current = Math.min(current + inc, target);
                el.textContent = (Number.isInteger(target) ? Math.floor(current) : current.toFixed(1)) + suffix;
                if (current >= target) clearInterval(timer);
            }, 16);
        });
    </script>
</body>
</html>