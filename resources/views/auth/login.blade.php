@extends('layouts.app')
@section('title', 'Login')
@section('content')
  <section class="grid h-full w-full place-items-center">
    <div class="container max-w-md" x-data="{ loading: false }">
      <h1 class="text-3xl font-extrabold">Login</h1>
      <p class="text-muted-foreground">Enter information below to sign-in</p>
      <form class="space-y-8" x-on:submit="loading = true" action="{{ route('auth.login') }}" method="POST">
        @csrf
        <div class="space-y-4">
          <div class="space-y-1">
            <label class="form-label">Username/Email</label>
            <input type="text" name="username" value="{{ old('username') }}" class="form-input">
            @error('username')
              <p class="form-error">{{ $message }}</p>
            @enderror
          </div>
          <div class="space-y-1">
            <label class="text-sm font-medium">Password</label>
            <input type="password" name="password" class="form-input">
            @error('password')
              <p class="form-error">{{ $message }}</p>
            @enderror
            <div class="text-right">
              <a href="{{ route('password.email') }}"
                class="text-sm text-muted-foreground transition-colors hover:text-primary hover:underline">Forgot
                Password?</a>
            </div>
          </div>
        </div>
        <div class="space-y-4">
          <button x-bind:disabled="loading" type="submit" class="btn btn-primary btn-lg w-full">
            <i x-cloak x-show="loading" class="size-6 i-lucide-loader-circle mr-2 animate-spin"></i>
            <span>Login</span>
          </button>
          <div class="text-center text-muted-foreground">
            Don't have account?
            <a class="text-primary underline" href="{{ route('auth.register') }}">Register</a>
          </div>
        </div>
      </form>
    </div>
  </section>
@endsection
