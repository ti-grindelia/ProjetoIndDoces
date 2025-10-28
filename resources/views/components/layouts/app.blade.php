<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            {{-- BRAND --}}
            <a href="/" wire:navigate class="px-5 pt-4">
                <!-- Hidden when collapsed -->
                <div {{ $attributes->class(["hidden-when-collapsed"]) }}>
                    <div class="flex items-center gap-2 w-fit">
                        <span class="text-4xl">🍬</span>
                        <span class="font-bold text-3xl me-3 bg-gradient-to-r from-blue-500 to-purple-300 bg-clip-text text-transparent">
                            Indústria
                        </span>
                    </div>
                </div>

                <!-- Display when collapsed -->
                <div class="display-when-collapsed hidden mx-5 mt-5 mb-1 h-[48px]">
                    <span class="text-5xl">🍬</span>
                </div>
            </a>
{{--            <x-app-brand class="px-5 pt-4" />--}}

            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}
                @if($user = auth()->user())
                    <x-menu-separator />

                    <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="-mx-2 !-my-2 rounded">
                        <x-slot:actions>
                            <div class="tooltip tooltip-left" data-tip="logoff">
                                <x-button
                                    icon="o-power"
                                    class="btn-circle btn-ghost btn-xs"
                                    @click="$dispatch('logout')">
                                </x-button>
                            </div>
                        </x-slot:actions>
                    </x-list-item>

                    <x-menu-separator />
                @endif

                <x-menu-item title="Home" icon="o-home" link="/" />

                <x-menu-sub title="Cadastros" icon="o-pencil-square">
                    <x-menu-item title="Usuário" icon="o-user" :link="route('usuario')" />
                    <x-menu-item title="Empresa" icon="o-home-modern" :link="route('usuario')" />
                    <x-menu-item title="Matéria-Prima" icon="o-eye-dropper" :link="route('materia-prima')" />
                    <x-menu-item title="Produto" icon="o-cake" :link="route('produto')" />
                </x-menu-sub>

                <x-menu-sub title="Ordem de Produção" icon="o-truck">
                    <x-menu-item title="Usuário" icon="o-user" link="#" />
                </x-menu-sub>
            </x-menu>
        </x-slot:sidebar>

        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    <x-toast />

    <livewire:autenticacao.logout />
</body>
</html>
