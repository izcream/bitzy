@extends('layouts.app')
@section('title', 'Manage Users')
@section('content')
  <section class="space-y-10">
    <h1 class="text-3xl font-extrabold tracking-tight">Create User</h1>
    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-8">
      @csrf
      <div class="space-y-1">
        <label class="form-label">Fullname</label>
        <input type="text" class="form-input" name="name" value="{{ old('name') }}">
        @error('name')
          <p class="form-error">{{ $message }}</p>
        @enderror
      </div>
      <div class="space-y-1">
        <label class="form-label">Email</label>
        <input type="email" class="form-input" name="email" value="{{ old('email') }}">
        @error('email')
          <p class="form-error">{{ $message }}</p>
        @enderror
      </div>
      <div class="space-y-1">
        <label class="form-label">Username</label>
        <input type="text" class="form-input" name="username" value="{{ old('username') }}">
        @error('username')
          <p class="form-error">{{ $message }}</p>
        @enderror
      </div>
      <div class="space-y-1">
        <label class="form-label">Password</label>
        <input type="text" class="form-input" name="password" value="{{ old('password') }}">
        @error('password')
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
