@extends('layouts.app')
@section('title', 'Profile')
@section('content')
<section class="mx-auto max-w-5xl space-y-10">
  <h1 class="text-3xl font-extrabold tracking-tight">Profile</h1>
  <form action="{{ route('profile') }}" method="POST" class="space-y-10">
    @csrf
    @method('PATCH')
    <div class="space-y-4">
      <div class="space-y-1">
        <label class="form-label">Your Name</label>
        <input type="name" name="name" value="{{ old('name', $user->name) }}" class="form-input"
          placeholder="Enter your name" required>
        @error('name')
        <p class="form-error">{{ $message }}</p>
        @enderror
      </div>
      <div class="space-y-1">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input"
          placeholder="Enter your email address" required>
        @error('email')
        <p class="form-error">{{ $message }}</p>
        @enderror
      </div>
      <div class="space-y-1">
        <label class="form-label">Username</label>
        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="form-input"
          placeholder="Enter your username" required>
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
    <div class="flex items-center gap-x-3">
      <button class="btn btn-lg btn-secondary">Cancel</button>
      <button type="submit" class="btn btn-lg btn-primary">Save</button>
    </div>
  </form>
</section>
@endsection
