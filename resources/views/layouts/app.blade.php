<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="font-size: 13px;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ExpenseAssistant') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        * { scrollbar-width: thin; scrollbar-color: #CBD5E1 transparent; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        window.showToast = function(message, type) {
            type = type || 'success';
            const colors = { success: 'bg-sea-600', error: 'bg-red-600', warning: 'bg-amber-500', info: 'bg-sky-600' };
            const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
            const toast = { id: Date.now() + Math.random(), message, type, color: colors[type] || colors.info, icon: icons[type] || icons.info, leaving: false };
            const app = document.querySelector('[x-data]').__x.$data;
            app.toasts.push(toast);
            setTimeout(() => { toast.leaving = true; }, 4000);
            setTimeout(() => { app.toasts = app.toasts.filter(t => t.id !== toast.id); }, 4500);
        };
    </script>
</head>
<body x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', toasts: [] }"
      x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))"
      class="bg-[#f8fafc] text-slate-800 font-sans h-screen flex overflow-hidden">

    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col flex-shrink-0 bg-white border-r border-slate-200 h-full transition-all duration-300"
           x-bind:class="sidebarCollapsed ? 'w-16' : 'w-56'">
        <div class="flex items-center h-16 flex-shrink-0 overflow-hidden border-b border-slate-100" x-bind:class="sidebarCollapsed ? 'justify-center px-0' : 'px-6'">
            <a href="{{ route('dashboard') }}" class="flex items-center" x-bind:class="sidebarCollapsed ? '' : 'gap-2'">
                <span x-show="!sidebarCollapsed" class="text-lg font-bold text-slate-900 whitespace-nowrap">Expense<span class="text-sea-600">Assistant</span></span>
                <span x-show="sidebarCollapsed" class="text-lg font-bold text-sea-600">EA</span>
            </a>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto py-3" x-bind:class="sidebarCollapsed ? 'px-2' : 'px-3'">
            <a href="{{ route('dashboard') }}"
               class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-200 overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-sea-50 text-sea-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
               x-bind:class="sidebarCollapsed ? 'justify-center px-0' : 'gap-3 px-3'">
                <i class="fas fa-home w-5 text-center flex-shrink-0"></i>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap text-sm">Dashboard</span>
            </a>
            <a href="{{ route('receipts.index') }}"
               class="flex items-center py-2.5 rounded-lg font-medium transition-all duration-200 overflow-hidden {{ request()->routeIs('receipts.*') ? 'bg-sea-50 text-sea-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
               x-bind:class="sidebarCollapsed ? 'justify-center px-0' : 'gap-3 px-3'">
                <i class="fas fa-file-invoice w-5 text-center flex-shrink-0"></i>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap text-sm">Receipts</span>
            </a>
        </nav>

        <div class="flex-shrink-0 border-t border-slate-100 py-3" x-bind:class="sidebarCollapsed ? 'px-2' : 'px-3'">
            <button @click="sidebarCollapsed = !sidebarCollapsed"
                    class="flex items-center w-full py-2.5 rounded-lg font-medium transition-all duration-200 overflow-hidden text-slate-500 hover:bg-slate-100 hover:text-slate-700"
                    x-bind:class="sidebarCollapsed ? 'justify-center px-0' : 'gap-3 px-3'">
                <i class="fas fa-chevron-left w-5 text-center flex-shrink-0 transition-transform duration-300" x-bind:class="sidebarCollapsed ? 'rotate-180' : ''"></i>
                <span x-show="!sidebarCollapsed" class="whitespace-nowrap text-sm">Collapse</span>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit"
                        class="flex items-center w-full py-2.5 rounded-lg font-medium transition-all duration-200 overflow-hidden text-slate-500 hover:bg-slate-100 hover:text-slate-700"
                        x-bind:class="sidebarCollapsed ? 'justify-center px-0' : 'gap-3 px-3'">
                    <i class="fas fa-sign-out-alt w-5 text-center flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed" class="whitespace-nowrap text-sm">Log out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 lg:hidden bg-white w-56 shadow-xl transition-transform duration-300 ease-in-out border-r border-slate-200"
           x-bind:class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
        <div class="flex items-center h-16 px-6 border-b border-slate-100">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-sea-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-receipt text-white text-sm"></i>
                </div>
                <span class="text-lg font-bold text-slate-900">Expense<span class="text-sea-600">Assistant</span></span>
            </a>
        </div>
        <nav class="space-y-1 px-3 py-3">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm {{ request()->routeIs('dashboard') ? 'bg-sea-50 text-sea-700' : 'text-slate-600 hover:bg-slate-100' }}">
                <i class="fas fa-home w-5 text-center"></i>Dashboard
            </a>
            <a href="{{ route('receipts.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm {{ request()->routeIs('receipts.*') ? 'bg-sea-50 text-sea-700' : 'text-slate-600 hover:bg-slate-100' }}">
                <i class="fas fa-file-invoice w-5 text-center"></i>Receipts
            </a>
        </nav>
        <div class="absolute bottom-0 left-0 right-0 border-t border-slate-100 px-3 py-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg font-medium text-sm text-slate-500 hover:bg-slate-100">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>Log out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex flex-col flex-1 min-w-0">
        <!-- Top bar -->
        <header class="sticky top-0 z-30 flex items-center h-16 px-4 bg-white/80 backdrop-blur-xl border-b border-slate-200 lg:px-6">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <div class="flex-1 flex items-center justify-between ml-2 lg:ml-0">
                <h1 class="text-lg font-bold text-slate-900">
{{ isset($header) ? $header : 'ExpenseAssistant' }}
                </h1>
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 cursor-pointer">
                        <div class="w-8 h-8 rounded-full bg-sea-600 text-white flex items-center justify-center text-sm font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-sm text-slate-700 hidden sm:inline">{{ Auth::user()->name }} <i class="fas fa-chevron-down text-[10px] ml-1 text-slate-400"></i></span>
                    </a>
                </div>
            </div>
        </header>

        <!-- Toast container -->
        <div class="fixed top-4 right-4 z-[100] flex flex-col gap-2 pointer-events-none" x-cloak>
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="!toast.leaving"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-4"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-x-0"
                     x-transition:leave-end="opacity-0 translate-x-4"
                     class="pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-white text-sm font-medium min-w-[280px] max-w-sm"
                     :class="toast.color">
                    <i class="fas" :class="toast.icon"></i>
                    <span x-text="toast.message"></span>
                    <button @click="toast.leaving = true; setTimeout(() => { toasts = toasts.filter(t => t.id !== toast.id); }, 500)" class="ml-auto text-white/70 hover:text-white transition-colors">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </template>
        </div>

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
