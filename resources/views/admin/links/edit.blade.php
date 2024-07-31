@extends('layouts.app')
@section('title', 'Edit Link')
@section('content')
  <section class="space-y-10">
    <h1 class="text-3xl font-extrabold tracking-tight">Edit Link</h1>
    <form class="space-y-8" method="POST" action="{{ route('admin.links.update', compact('link')) }}">
      @csrf
      @method('PATCH')
      <div class="space-y-1">
        <label class="form-label">
          Shorten URL
        </label>
        <div class="flex items-center gap-2">
          <div class="font-medium text-muted-foreground">{{ env('APP_URL') }}/go/</div>
          <div class="flex-grow">
            <input type="text" class="form-input" name="shortened_url"
              value="{{ old('shortened_url', $link->shortened_url) }}">
            @error('shortened_url')
              <p class="form-error">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>
      <div class="space-y-1">
        <label class="form-label">Destination Url</label>
        <input type="url" class="form-input" name="destination_url"
          value="{{ old('destination_url', $link->destination_url) }}">
        @error('destination_url')
          <p class="form-error">{{ $message }}</p>
        @enderror
      </div>
      <div class="flex items-center gap-x-3">
        <button class="btn btn-secondary btn-lg" type="reset">Cancel</button>
        <button class="btn btn-primary btn-lg" type="submit">Save</button>
      </div>
    </form>
  </section>
@endsection
