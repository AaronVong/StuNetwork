@extends('layouts/auth')
@section('auth-form')
    <!-- Vue Regitser Form Here -->
    <register-form v-bind:native_route="'{{ route('register') }}'" v-bind:login_route="'{{ route('login') }}'"></register-form>
@endsection