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
    <meta property="og:image" content="{{ asset($ad->image) }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <script>
        // Redirect to the app
        window.location.href = "{{ $shareLink }}";
        // If the app isn't installed, redirect to app store after delay
        setTimeout(function() {
            window.location.href = "{{ $appStoreUrl }}";
        }, 1000); // 1-second delay
    </script>
</head>

<body>
    <!-- Web Fallback Content (shown if not mobile or if redirection fails) -->
    <div class="ad-container">
        <h1>{{ $ad->title }}</h1>
        <img src="{{ asset($ad->image) }}" alt="{{ $ad->title }}" style="max-width: 100%;">
        <p>{{ $ad->description }}</p>
        <p><strong>Price:</strong> ${{ number_format($ad->price, 2) }}</p>

        <!-- Button to open the app (manual fallback) -->
        <button onclick="window.location.href='bazarapp://advertisement/{{ $ad->id }}'">
            Open in App
        </button>
    </div>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .ad-container {
            max-width: 600px;
            margin: 0 auto;
        }

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
