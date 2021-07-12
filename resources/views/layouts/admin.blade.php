@extends("layouts.root")
@section("content")
<div id="admin-layout" class="w-full h-full">
    <admin-nav></admin-nav>
    <div class="col-span-1 admin-content p-2 md:p-4">
        <h1 id="top" class="text-5xl capitalize text-center py-3 mb-3 text-gray-600 bg-gray-200">
            <span class="main-red">S</span>
            <span class="main-blue">T</span>
            <span class="main-red">U</span>
            <span class="text-lg text-gray-900">Network</span>
        </h1>
        @yield("admin-content")
    </div>
</div>
@endsection
