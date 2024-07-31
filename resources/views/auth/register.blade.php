@extends('layouts.app')
@section('title', 'Register')
@section('content')
  <section class="grid h-full w-full items-center">
    <div class="container max-w-md">
      <h1 class="text-3xl font-extrabold">Create Account</h1>
      <p class="text-muted-foreground">Enter information below to register</p>
      <form action="{{ route('auth.register') }}" method="POST" class="space-y-8">
        @csrf
        <div class="space-y-4">
          <div class="space-y-1">
            <label class="form-label">Your Name</label>
            <input type="name" name="name" value="{{ old('name') }}" class="form-input"
              placeholder="Enter your name">
            @error('name')
              <p class="form-error">{{ $message }}</p>
            @enderror
          </div>
          <div class="space-y-1">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-input"
              placeholder="Enter your email address">
            @error('email')
              <p class="form-error">{{ $message }}</p>
            @enderror
          </div>
          <div class="space-y-1">
            <label class="form-label">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" class="form-input"
              placeholder="Enter your username">
            @error('username')
              <p class="form-error">{{ $message }}</p>
            @enderror
          </div>
          <div class="space-y-1">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-input" placeholder="Enter your password">
            @error('password')
              <p class="form-error">{{ $message }}</p>
            @enderror
          </div>
        </div>
        <div class="space-y-4">
          <button x-bind:disabled="loading" type="submit" class="btn btn-primary btn-lg w-full">
            <i x-cloak x-show="loading" class="size-4 i-lucide-loader-circle mr-2 animate-spin"></i>
            <span>Create Account</span>
          </button>
          <div class="text-center text-muted-foreground">
            Already have account?
            <a class="text-primary underline" href="{{ route('auth.login') }}">Login</a>
          </div>
        </div>
      </form>
    </div>
  </section>
@endsection
