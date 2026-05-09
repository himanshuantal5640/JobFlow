<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobFlow — Career Command Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/auth.css'])
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
</body>
</html>