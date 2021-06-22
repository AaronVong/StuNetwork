@extends('layouts/auth')
@section('auth-form')
    <!-- Vue Regitser Form Here -->
    <register-form v-bind:login_route="'{{ route('login') }}'" v-bind:password_route="'{{ route('password.request') }}'"></register-form>
@endsection