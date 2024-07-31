@extends('layouts.app')
@section('title', 'Manage Links')
@section('content')
<section class="space-y-10" x-data="manageLinkPage">
  <div class="flex items-center justify-between">
    <h1 class="text-3xl font-extrabold tracking-tight">Manage Links</h1>
    <form action="">
      <div class="relative">
        <input class="form-input !h-12 w-full md:w-[300px]" name="q" value="{{ request()->get('q') }}"
          placeholder="Search links">
        <button type="submit" class="absolute right-0 top-0 inline-flex h-full items-center px-3">
          <i class="size-4 i-lucide-search text-muted-foreground hover:text-primary"></i>
        </button>
      </div>
    </form>
  </div>

  <div class="relative overflow-x-auto">
    <table class="table w-full">
      <thead>
        <tr>
          <th>Destination URL</th>
          <th>Short URL</th>
          <th>Title</th>
          <th>User</th>
          <th>Created At</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($links as $link)
        <tr>
          <td class="">
            <a target="_blank" class="underline hover:text-primary/80" href="{{ $link->destination_url }}">{{
              $link->destination_url }}</a>
          </td>
          <td>
            <a target="_blank" class="underline hover:text-primary/80" href="{{ $link->shortLink() }}">{{
              $link->shortLink() }}</a>
          </td>
          <td class="max-w-[25ch] truncate">
            {{ $link->title }}
          </td>
          <td>
            <a href="{{ route('admin.users.show', ['user' => $link->user]) }}" class="underline hover:text-primary/80">
              {{ $link->user->name }}
            </a>
          </td>
          <td>
            {{ $link->created_at->format('d F Y H:i') }}
          </td>
          <td>
            <div class="flex justify-end gap-2">
              <a href="{{ route('admin.links.edit', compact('link')) }}" title="edit">
                <i class="size-4 i-lucide-edit"></i>
              </a>
              <button title="delete" x-on:click="openConfirmDeleteDialog(`{{ $link->id }}`,`{{ $link->title }}`)">
                <i class="size-4 i-lucide-trash-2 text-destructive"></i>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="bg-muted text-center text-muted-foreground">
            @if (request()->has('q') && request()->filled('q'))
            Not found link with keyword "{{ request()->get('q') }}"
            @else
            No link found
            @endif
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-4">
      {{ $links->links() }}
    </div>
  </div>
  <div x-cloak x-show="openConfirmDelete">
    <div x-on:click.away="openConfirmDelete=false" x-show="openConfirmDelete"
      x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in"
      x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
      class="fixed inset-0 z-10 bg-primary/50 backdrop-blur-xl"></div>
    <div class="fixed inset-0 z-20 grid place-items-center px-4">
      <div x-show="openConfirmDelete" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-10 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in" x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-10 opacity-0"
        class="w-full min-w-0 rounded-lg bg-white p-8 md:max-w-[25rem]">
        <h4 class="mb-4 text-xl font-semibold">Are you sure?</h4>
        <p class="mb-4 text-muted-foreground">Do you want to delete <strong x-text="link?.title"></strong>?</p>
        <div class="flex justify-end gap-x-2">
          <button x-bind:disabled="loading" x-on:click="openConfirmDelete=false"
            class="btn btn-md btn-secondary">Cancel</button>
          <form x-on:submit="loading=true" x-bind:action="`/admin/links/${link?.id}`" method="POST">
            @csrf
            @method('DELETE')
            <button x-bind:disabled="loading" type="submit" class="btn btn-md btn-primary">
              <i x-cloak x-show="loading" class="size-4 i-lucide-loader-circle mr-2"></i>
              <span>Confirm</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@push('footer')
<script>
  document.addEventListener('alpine:init', () => {
      Alpine.data('manageLinkPage', () => ({
        openConfirmDelete: false,
        loading: false,
        link: null,
        openConfirmDeleteDialog(id, title) {
          this.link = {
            id,
            title
          }
          this.openConfirmDelete = true
        }
      }))
    })
</script>
@endpush
