
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribed - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md bg-white p-8 rounded-lg shadow-lg text-center">
            <div class="mx-auto flex items-center justify-center w-16 h-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Unsubscribed Successfully</h1>
            <p class="text-gray-600 mb-6">You've been removed from our mailing list.</p>
            <a href="{{ route('news.index') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Back to Berita</a>
        </div>
    </div>   
</body>
</html>