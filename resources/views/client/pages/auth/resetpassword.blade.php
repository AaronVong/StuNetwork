@extends('layouts.auth')
@section('auth-form')
<h1 class="text-4xl capitalize text-center py-2 text-gray-600">
    Khôi phục mật khẩu
</h1>
<i class="text-sm block text-center text-gray-600">
    Sức khỏe - Trí tuệ - Ước vọng
</i>
<reset-password-form :token="'{{ $token }}'"></reset-password-form>
@endsection