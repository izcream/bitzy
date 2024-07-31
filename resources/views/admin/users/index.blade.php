@extends('layouts.app')
@section('title', 'Manage Users')
@section('content')
<section class="space-y-10" x-data="manageUserPage">
  <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
    <h1 class="text-3xl font-extrabold tracking-tight">Manage User</h1>
    <div class="flex gap-4">
      <form action="" class="flex w-full justify-end">
        <div class="relative">
          <input class="form-input !h-12 w-full md:w-[300px]" name="q" value="{{ request()->get('q') }}"
            placeholder="Search users">
          <button type="submit" class="absolute right-0 top-0 inline-flex h-full items-center px-3">
            <i class="size-4 i-lucide-search text-muted-foreground hover:text-primary"></i>
          </button>
        </div>
      </form>
      <a href="{{ route('admin.users.create') }}" class="btn btn-md btn-primary flex-shrink-0">
        <i class="size-4 i-lucide-plus mr-2"></i>
        <span>Create User</span>
      </a>
    </div>
  </div>
  <div class="relative overflow-x-auto">
    <table class="table w-full">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Username</th>
          <th>Created Link</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->username }}</td>
          <td>{{ $user->links_count }}</td>
          <td>
            <div class="flex justify-end gap-2">
              <a href="{{ route('admin.users.show', compact('user')) }}" title="show">
                <i class="size-4 i-lucide-eye"></i>
              </a>
              <a href="{{ route('admin.users.edit', compact('user')) }}" title="edit">
                <i class="size-4 i-lucide-edit"></i>
              </a>
              <button title="delete" x-on:click="openConfirmDeleteDialog(`{{ $user->id }}`, `{{ $user->name }}`)">
                <i class="size-4 i-lucide-trash-2 text-destructive"></i>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="bg-muted text-center text-muted-foreground">
            @if (request()->has('q') && request()->filled('q'))
            Not found user with keyword "{{ request()->get('q') }}"
            @else
            No user found
            @endif
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
    <div class="mt-4">
      {{ $users->links() }}
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
        <p class="mb-4 text-muted-foreground">Do you want to delete <strong x-text="user?.name"></strong>?</p>
        <div class="flex justify-end gap-x-2">
          <button x-bind:disabled="loading" x-on:click="openConfirmDelete=false"
            class="btn btn-md btn-secondary">Cancel</button>
          <form x-on:submit="loading=true" x-bind:action="`/admin/users/${user?.id}`" method="POST">
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
      Alpine.data('manageUserPage', () => ({
        openConfirmDelete: false,
        loading: false,
        user: null,
        openConfirmDeleteDialog(id, name) {
          this.user = {
            id,
            name
          }
          this.openConfirmDelete = true
        }
      }))
    })
</script>
@endpush
