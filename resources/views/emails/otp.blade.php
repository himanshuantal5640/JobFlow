<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #06060F; color: #F0F0FF; margin: 0; padding: 40px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #0E0E1C; border-radius: 24px; padding: 40px; border: 1px solid rgba(255,255,255,0.05); }
        .logo { font-size: 24px; font-weight: 800; color: #7C6FFF; margin-bottom: 30px; text-align: center; }
        .title { font-size: 20px; font-weight: 700; margin-bottom: 10px; color: #FFFFFF; }
        .otp { font-size: 42px; font-weight: 800; color: #7C6FFF; letter-spacing: 10px; margin: 30px 0; text-align: center; }
        .footer { font-size: 12px; color: #5C5C7A; margin-top: 40px; text-align: center; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">JobFlow</div>
        <div class="title">Verify your email address</div>
        <p>Hello {{ $name }},</p>
        <p>Your one-time verification code is below. This code will expire in 10 minutes.</p>
        
        <div class="otp">{{ $otp }}</div>
        
        <p>If you didn't request this, you can safely ignore this email.</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} JobFlow AI. All rights reserved.<br/>
            Made with &hearts; for the future of work.
        </div>
    </div>
</body>
</html>
