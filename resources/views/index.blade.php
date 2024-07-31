@extends('layouts.app')
@section('title', 'Links')
@section('content')
  <section class="mx-auto max-w-5xl space-y-10" x-data="indexPage">
    <h1 class="text-3xl font-extrabold tracking-tight">Create Shorten URL</h1>
    <form x-on:submit="loading=true" action="{{ route('links.store') }}" method="POST" class="space-y-4">
      @csrf
      <div class="space-y-1">
        <input type="url" name="destination_url" x-on:blur="checkUrl" value="{{ old('destination_url') }}"
          placeholder="https://example.com/my-awesome-long-url" class="form-input" required>
        @error('destination_url')
          <p class="form-error">{{ $message }}</p>
        @enderror
      </div>
      <button x-bind:disabled="loading" type="submit" class="btn btn-primary btn-lg group">
        <i x-cloak x-show="loading" class="size-6 i-lucide-loader-circle mr-2 animate-spin"></i>
        <span>Make it shortter!</span>
        <i x-show="!loading" class="size-6 i-lucide-arrow-right ml-2 transition-transform group-hover:-rotate-45"></i>
      </button>
    </form>
    <div>
      <div class="mb-4 flex flex-col justify-between gap-2 md:flex-row md:items-center">
        <h3 class="text-2xl font-bold">Created History</h3>
        <form class="relative w-full md:w-[280px]">
          <input class="form-input peer !h-12 text-sm" name="q" value="{{ request('q') }}"
            placeholder="Search your links">
          <button
            class="absolute right-0 top-0 inline-flex h-full items-center pr-3 text-muted-foreground transition-colors hover:text-foreground peer-focus:text-foreground">
            <i class="size-4 i-lucide-search"></i>
          </button>
        </form>
      </div>
      <div class="grid gap-y-4">
        @forelse ($links as $link)
          <div class="flex flex-col gap-2 rounded-lg border border-border p-6 md:flex-row">
            <img src="https://www.google.com/s2/favicons?domain={{ $link->destination_url }}&sz=64"
              alt="{{ $link->title }}" class="size-10 mx-auto rounded-full md:mx-0">
            <div class="grid flex-grow">
              <h5 class="mb-2 line-clamp-1 break-words text-lg font-semibold">
                {{ $link->title }}
              </h5>
              <a href="{{ $link->shortLink() }}" target="_blank" rel="nofollow" class="text-sky-500 hover:underline">
                {{ $link->shortLink() }}
              </a>
              <a href="{{ $link->destination_url }}" target="_blank" rel="nofollow"
                class="text-sm text-secondary-foreground hover:underline">
                {{ $link->destination_url }}
              </a>
              <div>
                <span class="group relative">
                  <time class="cursor-default text-sm text-muted-foreground"
                    datetime="{{ $link->created_at->format('Y-m-d') }}">
                    {{ $link->created_at->diffForHumans() }}
                  </time>
                  <div
                    class="invisible absolute -top-6 z-10 max-w-[20ch] scale-90 truncate whitespace-nowrap rounded-md bg-primary px-2 py-1 text-sm text-primary-foreground transition-all duration-200 ease-in-out group-hover:visible group-hover:scale-100">
                    {{ $link->created_at->format('d F Y H:i') }}
                  </div>
                </span>
              </div>
            </div>
            <div>
              <div class="flex flex-col gap-1 md:flex-row">
                <button x-on:click="handleCopy(`{{ $link->shortLink() }}`)"
                  class="btn btn-outline rounded-sm px-2 py-1 text-sm">
                  <i class="size-4 i-lucide-copy mr-1"></i>
                  <span x-text="onCopy ?'Copied': 'Copy'">Copy</span>
                </button>
                <button x-on:click="handleShare(`{{ $link->shortLink() }}`, `{{ $link->title }}`)"
                  class="btn btn-outline rounded-sm px-2 py-1 text-sm">
                  <i class="size-4 i-lucide-share-2 mr-1"></i>
                  <span>Share</span>
                </button>
                <div class="relative">
                  <button x-on:click="openConfirmDelete=true"
                    class="btn w-full rounded-sm border border-destructive px-2 py-1 text-sm text-destructive hover:bg-destructive hover:text-destructive-foreground">
                    <i class="size-4 i-lucide-trash-2 mr-1"></i>
                    <span>Delete</span>
                  </button>
                  <div x-cloak x-show="openConfirmDelete">
                    <div x-on:click.away="openConfirmDelete=false" x-show="openConfirmDelete"
                      x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in"
                      x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                      class="fixed inset-0 z-10 bg-primary/50 backdrop-blur-xl"></div>
                    <div class="fixed inset-0 z-20 grid place-items-center px-4">
                      <div x-show="openConfirmDelete" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="translate-y-10 opacity-0"
                        x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in"
                        x-transition:leave-start="translate-y-0 opacity-100"
                        x-transition:leave-end="translate-y-10 opacity-0"
                        class="w-full min-w-0 rounded-lg bg-white p-8 md:max-w-[25rem]">
                        <h4 class="mb-4 text-xl font-semibold">Are you sure?</h4>
                        <p class="mb-4 text-muted-foreground">Do you really want to delete this link? This process
                          cannot be undone.</p>
                        <div class="flex justify-end gap-x-2">
                          <button x-on:click="openConfirmDelete=false" class="btn btn-md btn-secondary">Cancel</button>
                          <form action="{{ route('links.destroy', $link) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-primary btn-md">Confirm</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="rounded-lg bg-muted p-6 text-center font-medium text-muted-foreground">
            @if (request()->has('q') && strlen(request('q')) > 0)
              <p>Result not found</p>
            @else
              <p class="leading-tight">
                You don't have any links yet. <br>
                Start by creating one.
              </p>
            @endif
          </div>
        @endforelse
      </div>
      <div class="mt-4 flex justify-end">
        {{ $links->appends(['q' => request()->get('q')])->links() }}
      </div>
    </div>
  </section>
@endsection
@push('footer')
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('indexPage', () => ({
        loading: false,
        onCopy: false,
        openConfirmDelete: false,
        checkUrl(e) {
          const url = e.target.value
          if (!url) return
          if (!url.startsWith('http://') && !url.startsWith('https://')) {
            e.target.value = `https://${url}`
          }
        },
        handleCopy(url) {
          navigator.clipboard.writeText(url).then(() => {
            this.onCopy = true
            setTimeout(() => {
              this.onCopy = false
            }, 2000)
          })
        },
        handleShare(url, title) {
          navigator.share({
            title,
            url
          })
        }
      }))
    })
  </script>
@endpush
