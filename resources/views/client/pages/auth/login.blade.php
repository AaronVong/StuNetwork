@extends('layouts.auth')
@section('auth-form')
    <!-- Vue Login Form Here -->
    <login-form v-bind:register_route="'{{ route('register') }}'" v-bind:password_route="'{{ route('password.request') }}'"></login-form>
@endsection