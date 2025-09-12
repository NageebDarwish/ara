<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>

<body
    style="margin: 0; padding: 20px; font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif; background-color: #f5f7fa;">
    <!-- Main Card Container -->
    <div
        style="max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;">
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #7367f0 0%, #9a8cf8 100%); padding: 24px; text-align: center;">
            <h1 style="color: white; margin: 0; font-size: 22px; font-weight: 600; letter-spacing: 0.5px;">
                {{ $subject }}</h1>
        </div>

        <!-- Body Content -->
        <div style="padding: 32px; color: #4a4a4a; line-height: 1.6; font-size: 15px;">
            {!! $body !!}
        </div>

        <!-- Footer (minimal) -->
        <div style="padding: 16px; text-align: center; background-color: #f9f9f9; font-size: 12px; color: #888;">
            © {{ date('Y') }}
        </div>
    </div>
</body>

</html>
