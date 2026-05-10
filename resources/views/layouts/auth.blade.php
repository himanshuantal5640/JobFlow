<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobFlow — Career Command Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/auth.css', 'resources/css/animations.css', 'resources/js/app.js'])
</head>
<body style="background: #06060F;">
    <header style="position: absolute; top: 0; left: 0; right: 0; height: 80px; display: flex; align-items: center; justify-content: space-between; padding: 0 40px; z-index: 100;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 36px; height: 36px; background: #7C6FFF; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: white;">J</div>
            <span style="font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 800; color: white;">JobFlow</span>
        </div>
        <a href="/" style="color: #9A9AB8; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
            Back to home
        </a>
    </header>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    
    <main>
        @yield('content')
    </main>

    <script>
        document.querySelectorAll('.eye-toggle').forEach(button => {
            button.addEventListener('click', () => {
                const input = button.parentElement.querySelector('input');
                if (input.type === 'password') {
                    input.type = 'text';
                    button.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
                } else {
                    input.type = 'password';
                    button.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
                }
            });
        });
    </script>
</body>
</html>