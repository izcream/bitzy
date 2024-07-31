@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')
  <section class="grid h-full w-full place-items-center">
    <div class="container max-w-md">
      <form action={{ route('password.email') }} method="POST" class="space-y-8">
        @csrf
        <div class="space-y-1">
          <label class="form-label">Email</label>
          <input type="email" class="form-input" name="email" placeholder="Email" required>
          @error('email')
            <p class="form-error">{{ $message }}</p>
          @enderror
        </div>
        <button class="btn btn-lg btn-primary w-full">Reset Password</button>
      </form>
    </div>
  </section>
@endsection
