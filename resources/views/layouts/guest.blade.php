<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ExpenseAssistant') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-sea-50 via-white to-cyan-50">
        <div class="mb-6">
            <a href="/" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-sea-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-receipt text-white text-lg"></i>
                </div>
                <span class="text-xl font-bold text-slate-900">Expense<span class="text-sea-600">Assistant</span></span>
            </a>
        </div>
        <div class="w-full sm:max-w-md px-6 py-6 bg-white shadow-lg shadow-sea-100/50 sm:rounded-xl border border-slate-200">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
