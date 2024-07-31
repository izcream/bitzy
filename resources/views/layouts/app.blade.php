<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Welcome') | {{ env('APP_NAME', 'Bitzy') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
  @stack('header')
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
  <div class="relative flex h-screen w-full flex-col">
    <nav class="w-full border-b border-border md:h-[60px]">
      <div class="container flex flex-col gap-y-4 md:gap-y-0 items-center justify-between py-3 md:flex-row">
        <a href="{{ route('index') }}" class="text-lg font-extrabold">{{ env('APP_NAME') }}</a>
        <div class="flex items-center w-full md:w-auto gap-x-4">
          @auth
          @can('manage-link')
          <a href="{{ route('admin.links.index') }}"
            class="text-sm font-medium text-muted-foreground transition-colors hover:text-primary">
            Links
          </a>
          @endcan
          @can('manage-user')
          <a href="{{ route('admin.users.index') }}"
            class="text-sm font-medium text-muted-foreground transition-colors hover:text-primary">
            Users
          </a>
          @endcan
          <div>
            <a class="btn btn-primary rounded-md px-4 py-2 text-sm" href="{{ route('index') }}">
              <i class="size-4 i-lucide-plus-circle mr-2"></i>
              Create Link
            </a>
          </div>
          <div class="ml-auto flex items-center" x-data="{ open: false, openDropdown: false, loading: false }">
            <div class="relative">
              <button class="btn rounded px-3 py-2 text-sm hover:bg-muted" x-on:click="openDropdown=true">
                <span>{{ auth()->user()->name }}</span>
                <i x-bind:class="openDropdown ? 'rotate-180' : 'rotate-0'"
                  class="size-4 i-lucide-chevron-down ml-1 inline-block text-muted-foreground transition-transform"></i>
              </button>
              <div x-show="openDropdown" x-on:click.away="openDropdown=false" x-transition:enter="ease-out duration-200"
                x-transition:enter-start="-translate-y-2" x-transition:enter-end="translate-y-0"
                class="absolute left-1/2 top-0 z-50 mt-10 w-36 -translate-x-1/2" x-cloak>
                <div class="mt-1 rounded-md bg-white p-1 shadow-md">
                  <div class="grid gap-y-1">
                    <a href="{{ route('profile') }}" class="inline-flex items-center rounded px-2 py-1 hover:bg-muted">
                      <i class="size-4 i-lucide-user mr-2"></i>
                      <span>Profile</span>
                    </a>
                    <div class="relative">
                      <button x-on:click="open=true;openDropdown=false"
                        class="inline-flex w-full items-center rounded px-2 py-1 hover:bg-muted">
                        <i class="size-4 i-lucide-log-out mr-1"></i>
                        <span>Logout</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="relative">
              <div x-cloak x-show="open">
                <div x-on:click.away="open=false" x-show="open" x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                  x-transition:leave="transition ease-in" x-transition:leave-start="opacity-100"
                  x-transition:leave-end="opacity-0" class="fixed inset-0 z-10 bg-primary/50 backdrop-blur-xl"></div>
                <div class="fixed inset-0 z-20 grid place-items-center px-4">
                  <div x-show="open" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-y-10 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in"
                    x-transition:leave-start="translate-y-0 opacity-100"
                    x-transition:leave-end="translate-y-10 opacity-0"
                    class="w-full min-w-0 rounded-lg bg-white p-8 md:max-w-[25rem]">
                    <h4 class="mb-4 text-xl font-semibold">Logout</h4>
                    <p class="mb-4 text-muted-foreground">Oh no! You're leaving...<br /> Are you sure?</p>
                    <div class="flex justify-end gap-x-2">
                      <button x-bind:disabled="loading" x-on:click="open=false" class="btn btn-md btn-secondary">Nah,
                        Just Kidding</button>
                      <form x-on:submit="loading=true" action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button x-bind:disabled="loading" type="submit" class="btn btn-md btn-primary">
                          <i x-cloak x-show="loading" class="size-4 i-lucide-loader-circle mr-2"></i>
                          <span>Yes, Log Me Out</span>
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endauth
          @guest
          <div class="flex items-center gap-x-4">
            <a href="{{ route('auth.login') }}"
              class="text-sm font-medium text-muted-foreground transition-colors hover:text-primary">Login</a>
            <a href="{{ route('auth.register') }}"
              class="text-sm font-medium text-muted-foreground transition-colors hover:text-primary">Register</a>
            @endguest
          </div>
        </div>
    </nav>
    <main class="container flex-1 py-4 lg:py-8">
      @session('success')
      <div x-data="{ open: true }" x-show="open"
        class="max-w-5xl min-w-0 mx-auto relative mb-4 flex items-center rounded-md border border-emerald-600 bg-emerald-600/10 text-sm md:text-base px-3 py-4 font-medium text-emerald-600 lg:mb-8">
        <i class="size-5 i-lucide-circle-check mr-2"></i>
        <p>{{ $value }}</p>
        <button x-on:click="open=false" class="absolute right-2 top-2 hover:text-emerald-700">
          <i class="size-4 i-lucide-x"></i>
        </button>
      </div>
      @endsession
      @yield('content')
    </main>
  </div>
  @stack('footer')
</body>

</html>
