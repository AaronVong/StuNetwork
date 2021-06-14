@extends('layouts.auth')
@section('auth-form')
    <!-- Vue Verify Email Form Here -->
    <verify-email-form :native_route="'{{ route('verification.send') }}'"></verify-email-form>
@endsection