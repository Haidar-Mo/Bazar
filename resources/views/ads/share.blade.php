<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $ad->title }} - Shared Ad</title>
    <meta name="description" content="{{ Str::limit($ad->description, 150) }}">
    
    <!-- Open Graph (for social sharing) -->
    <meta property="og:title" content="{{ $ad->title }}">
    <meta property="og:description" content="{{ Str::limit($ad->description, 150) }}">
    <meta property="og:image" content="{{ asset($ad->image_url) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <script>
        // Detect if the user is on mobile
        const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
        const isiOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);

        // Try to open the app first (using deep link)
        if (isMobile) {
            // Android: Custom Scheme (e.g., yourapp://ads/{{ $ad->id }})
            // iOS: Universal Link (e.g., https://yourapp.com/ads/{{ $ad->id }})
            const appDeepLink = isiOS 
                ? `https://bazar.almowafraty.com/api/v1/mobile/advertisements/{{ $ad->id }}`  // Replace with your Universal Link
                : `yourapp://ads/{{ $ad->id }}`;         // Replace with your Custom Scheme

            // Redirect to the app
            window.location.href = appDeepLink;

            // If the app isn't installed, redirect to app store after delay
            setTimeout(function() {
                if (isiOS) {
                    window.location.href = "https://apps.apple.com/app/idYOUR_APP_STORE_ID"; // iOS App Store
                } else {
                    window.location.href = "https://play.google.com/store/apps/details?id=YOUR_PACKAGE_NAME"; // Android Play Store
                }
            }, 1000); // 1-second delay
        }
    </script>
</head>
<body>
    <!-- Web Fallback Content (shown if not mobile or if redirection fails) -->
    <div class="ad-container">
        <h1>{{ $ad->title }}</h1>
        <img src="{{ asset($ad->image_url) }}" alt="{{ $ad->title }}" style="max-width: 100%;">
        <p>{{ $ad->description }}</p>
        <p><strong>Price:</strong> ${{ number_format($ad->price, 2) }}</p>
        
        <!-- Button to open the app (manual fallback) -->
        <button onclick="window.location.href='yourapp://ads/{{ $ad->id }}'">
            Open in App
        </button>
    </div>

    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .ad-container { max-width: 600px; margin: 0 auto; }
        button { 
            background: #007bff; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            cursor: pointer; 
        }
    </style>
</body>
</html>