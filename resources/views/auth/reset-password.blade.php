@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
  <section class="grid h-full w-full place-items-center">
    <div class="container max-w-md">
      <pre>{{ $errors }}</pre>
      <form action={{ route('password.reset') }} method="POST" class="space-y-8">
        @csrf
        <input type="hidden" name="token" value="{{ request()->get('token') }}">
        <div class="space-y-1">
          <label class="form-label">Email</label>
          <input type="email" class="form-input bg-slate-100 text-muted-foreground" name="email" placeholder="Email"
            value="{{ old('email', request()->get('email')) }}" readonly>
          @error('email')
            <p class="form-error">{{ $message }}</p>
          @enderror
        </div>
        <div class="space-y-1">
          <label class="form-label">New Password</label>
          <input type="password" class="form-input" name="password">
          @error('password')
            <p class="form-error">{{ $message }}</p>
          @enderror
        </div>
        <div class="space-y-1">
          <label class="form-label">New Password Confirmation</label>
          <input type="password" class="form-input" name="password_confirmation">
          @error('password_confirmation')
            <p class="form-error">{{ $message }}</p>
          @enderror
        </div>
        <button type="submit" class="btn btn-lg btn-primary w-full">Reset Password</button>
      </form>
    </div>
  </section>
@endsection
