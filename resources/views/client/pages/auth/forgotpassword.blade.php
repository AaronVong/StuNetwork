@extends("layouts.auth")
@section("auth-form")
    <h1 class="text-4xl capitalize text-center py-2 text-gray-600">Khôi phục mật khẩu</h1>
    <forgot-password-form :native_route="'{{ route('password.email') }}'"></forgot-password-form>
@endsection