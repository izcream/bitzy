@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')
<section class="grid h-full w-full place-items-center">
  <div class="container max-w-md" x-data="{loading:false}">
    <form x-on:submit="loading=true" action={{ route('password.email') }} method="POST" class="space-y-8">
      @csrf
      <div class="space-y-1">
        <label class="form-label">Email</label>
        <input type="email" class="form-input" name="email" placeholder="Email" required>
        @error('email')
        <p class="form-error">{{ $message }}</p>
        @enderror
      </div>
      <button x-bind:disabled="loading" class="btn btn-lg btn-primary w-full">
        <i x-cloak x-show="loading" class="i-lucide-loader-circle size-5 mr-2 animate-spin"></i>
        Reset Password
      </button>
    </form>
  </div>
</section>
@endsection
