@extends('layouts.app')
@section('title', 'Link Created History from user')
@section('content')
  <section class="space-y-10">
    <div class="flex justify-between">
      <h1 class="text-3xl font-extrabold tracking-tight">{{ $user->name }}'s Created Link History</h1>
      <form action="">
        <div class="relative">
          <input class="form-input !h-12 w-full md:w-[300px]" name="q" value="{{ request()->get('q') }}"
            placeholder="Search {{ $user->name }}'s links">
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
            <th>
              Destination URL
            </th>
            <th>
              Short URL
            </th>
            <th>
              Title
            </th>
            <th>
              Created At
            </th>
          </tr>
        </thead>
        <tbody>
          @forelse($links as $link)
            <tr>
              <td>
                <a target="_blank" class="underline hover:text-primary/80"
                  href="{{ $link->destination_url }}">{{ $link->destination_url }}</a>
              </td>
              <td>
                <a target="_blank" class="underline hover:text-primary/80"
                  href="{{ $link->shortLink() }}">{{ $link->shortLink() }}</a>
              </td>
              <td>
                {{ $link->title }}
              </td>
              <td>
                {{ $link->created_at->format('Y-m-d H:i') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="bg-muted text-center text-muted-foreground">
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
  </section>
@endsection
