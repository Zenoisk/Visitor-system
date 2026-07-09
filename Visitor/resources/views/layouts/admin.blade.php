<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'AIROD Visitor Management System' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 450, 'GRAD' 0, 'opsz' 24;
        }

        select:not([multiple]) {
            border-radius: 0.875rem;
            background-color: #ffffff;
        }

        .modern-select-menu {
            border-radius: 1rem;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-[#eef3f9] text-slate-900 antialiased" style="font-family: Inter, ui-sans-serif, system-ui, sans-serif;">
    <div class="min-h-screen lg:flex">
        <aside class="border-r border-white/10 bg-[#061426] text-white lg:fixed lg:inset-y-0 lg:w-72">
            <div class="border-b border-white/10 px-5 py-5">
                <div class="rounded-2xl border border-white/10 bg-white px-4 py-3 shadow-xl shadow-black/20">
                    <img src="{{ asset('images/airod-logo-cropped.webp') }}" alt="AIROD" class="mx-auto h-auto w-48 object-contain">
                </div>
                <p class="mt-4 px-2 text-[11px] font-extrabold uppercase tracking-[0.24em] text-blue-100/80">Security Console</p>
            </div>

            <nav class="space-y-2 px-4 py-5">
                @php
                    $isAdmin = auth()->user()?->role === 'admin';
                    
                    $links = [
                        ['Dashboard', 'admin.dashboard', 'dashboard'],
                        ['Visitors', 'admin.visitors.index', 'groups'],
                        ['QR Code', 'admin.qr.index', 'qr_code_2'],
                    ];

                    if ($isAdmin) {
                        $links[] = ['Reports', 'admin.reports.index', 'analytics'];
                    }

                    $links[] = ['Emergency List', 'admin.emergency.index', 'emergency'];

                    if ($isAdmin) {
                        $links[] = ['Watchlist', 'admin.watchlists.index', 'shield'];
                        $links[] = ['Activity Logs', 'admin.activity-logs.index', 'manage_history'];
                        $links[] = ['Form Builder', 'admin.form-fields.index', 'dynamic_form'];
                        $links[] = ['Settings', 'admin.settings.index', 'settings'];
                    }
                @endphp
                @foreach ($links as [$label, $route, $icon])
                    <a href="{{ $route === '#' ? '#' : route($route) }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-bold transition {{ request()->routeIs($route) ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/30' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        @if (request()->routeIs($route) && $route !== '#')
                            <span class="absolute -left-4 h-7 w-1 rounded-r-full bg-sky-300"></span>
                        @endif
                        <span class="material-symbols-outlined text-[21px] transition group-hover:scale-105">{{ $icon }}</span>
                        <span>{{ $label }}</span>
                        @if($route === '#')
                            <span class="ml-auto rounded bg-slate-700 px-1.5 py-0.5 text-[10px] font-bold text-slate-300">Soon</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="mx-4 mt-4 rounded-2xl border border-white/10 bg-white/[0.06] p-4 shadow-inner shadow-white/5">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-blue-200">Active User</p>
                <p class="mt-2 truncate text-sm font-extrabold text-white">{{ auth()->user()->name }}</p>
                <p class="mt-1 text-xs font-medium capitalize text-slate-400">{{ str_replace('_', ' ', auth()->user()->role ?? 'security') }}</p>
            </div>
        </aside>

        <div class="flex-1 lg:ml-72">
            <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/85 backdrop-blur-xl">
                <div class="flex min-h-20 flex-col justify-center gap-4 px-5 py-4 md:flex-row md:items-center md:justify-between lg:px-8">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-700">{{ now()->format('l, d M Y') }}</p>
                        <h2 class="mt-1 text-[26px] font-extrabold tracking-tight text-slate-950">{{ $heading ?? 'Dashboard' }}</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('visitor-registration.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-extrabold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-md">
                            <span class="material-symbols-outlined text-[19px]">app_registration</span>
                            Public Form
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="inline-flex items-center gap-2 rounded-xl bg-slate-950 px-4 py-2.5 text-sm font-extrabold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-800 hover:shadow-md">
                                <span class="material-symbols-outlined text-[19px]">logout</span>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="px-5 py-8 lg:px-8">
                @if (session('success'))
                    <div id="flash-success" class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800 transition-opacity duration-500">
                        <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div id="flash-error" class="mb-6 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-800">
                        <span class="material-symbols-outlined text-rose-600">error</span>
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const closeSelects = (except = null) => {
                document.querySelectorAll('[data-modern-select-menu]').forEach((menu) => {
                    if (menu !== except) {
                        menu.classList.add('hidden');
                        const menuIcon = menu.previousElementSibling?.querySelector('.material-symbols-outlined');

                        if (menuIcon) {
                            menuIcon.textContent = 'expand_more';
                        }
                    }
                });
            };

            document.querySelectorAll('select').forEach((select) => {
                if (select.multiple || select.dataset.nativeSelect === 'true' || select.dataset.enhancedSelect === 'true') {
                    return;
                }

                select.dataset.enhancedSelect = 'true';

                const wrapper = document.createElement('div');
                wrapper.className = 'relative w-full';

                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'flex w-full items-center justify-between gap-3 rounded-xl border border-slate-300 bg-white px-4 py-3 text-left text-sm font-bold text-slate-900 shadow-sm transition hover:border-blue-300 hover:bg-slate-50 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400';
                button.disabled = select.disabled;

                const label = document.createElement('span');
                label.className = 'truncate';

                const icon = document.createElement('span');
                icon.className = 'material-symbols-outlined text-[20px] text-slate-500 transition';
                icon.textContent = 'expand_more';

                button.append(label, icon);

                const menu = document.createElement('div');
                menu.dataset.modernSelectMenu = 'true';
                menu.className = 'modern-select-menu absolute left-0 right-0 z-50 mt-2 hidden max-h-64 overflow-y-auto border border-slate-200 bg-white p-1 shadow-2xl shadow-slate-900/15 ring-1 ring-slate-900/5';

                const render = () => {
                    const selected = select.options[select.selectedIndex] ?? select.options[0];
                    label.textContent = selected?.textContent?.trim() || 'Select option';

                    menu.querySelectorAll('button[data-value]').forEach((optionButton) => {
                        const isSelected = optionButton.dataset.value === select.value;
                        optionButton.className = [
                            'flex w-full items-center justify-between rounded-xl px-3 py-2.5 text-left text-sm font-semibold transition',
                            isSelected ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-700 hover:bg-blue-50 hover:text-blue-700',
                        ].join(' ');
                    });
                };

                Array.from(select.options).forEach((option) => {
                    const optionButton = document.createElement('button');
                    optionButton.type = 'button';
                    optionButton.dataset.value = option.value;

                    const optionLabel = document.createElement('span');
                    optionLabel.className = 'truncate';
                    optionLabel.textContent = option.textContent.trim();
                    optionButton.appendChild(optionLabel);

                    optionButton.addEventListener('click', () => {
                        select.value = option.value;
                        select.dispatchEvent(new Event('change', { bubbles: true }));
                        render();
                        menu.classList.add('hidden');
                    });

                    menu.appendChild(optionButton);
                });

                button.addEventListener('click', (event) => {
                    event.stopPropagation();
                    const isHidden = menu.classList.contains('hidden');
                    closeSelects(menu);
                    menu.classList.toggle('hidden', !isHidden);
                    icon.textContent = isHidden ? 'expand_less' : 'expand_more';
                });

                document.addEventListener('click', () => {
                    menu.classList.add('hidden');
                    icon.textContent = 'expand_more';
                });

                select.addEventListener('change', render);
                select.style.display = 'none';
                select.parentNode.insertBefore(wrapper, select.nextSibling);
                wrapper.append(button, menu);
                render();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const flash = document.getElementById('flash-success');
            if (flash) {
                setTimeout(() => {
                    flash.style.opacity = '0';
                    setTimeout(() => flash.remove(), 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>
