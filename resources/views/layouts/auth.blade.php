@extends("layouts.root")
@section("content")
<div class="auth-container relative w-full h-screen flex justify-center items-center px-2">
    <div class="w-full h-auto p-4 rounded-xl bg-white md:w-2/4 lg:w-3/6 xl:w-2/6">
        @yield("auth-form")
    </div>
</div>
@endsection