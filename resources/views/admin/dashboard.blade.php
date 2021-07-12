@extends("layouts.admin")
@section("admin-content")
<div id="summary" class="w-full grid grid-cols-4 grid-rows-auto gap-4 justify-center mb-3">
    <div class="col-span-4 h-32 bg-red-500 rounded-xl hover:bg-red-700 cursor-pointer transition-colors md:col-span-2 lg:col-span-1">
        <a href="#list-users" class="text-2xl font-md text-gray-100 w-full h-full flex flex-col justify-center items-center">
            <span>Tài khoản</span>
            <span>{{ $userCount }}</span>
        </a>
    </div>
    <div class="col-span-4 h-32 bg-green-500 rounded-xl hover:bg-green-700 cursor-pointer transition-colors md:col-span-2 lg:col-span-1">
        <a href="#list-toasts" class="text-2xl font-md text-gray-100 w-full h-full flex flex-col justify-center items-center">
            <span>Bài đăng</span>
            <span>{{ $toastCount }}</span>
        </a>
    </div>
    <div class="col-span-4 h-32 bg-blue-500 rounded-xl hover:bg-blue-700 cursor-pointer transition-colors md:col-span-2 lg:col-span-1">
        <a href="#list-members" class="text-2xl font-md text-gray-100 w-full h-full flex flex-col justify-center items-center">
            <span>Quản trị</span>
            <span>{{ $memberCount }}</span>
        </a>
    </div>
    <div class="col-span-4 h-32 bg-yellow-500 rounded-xl hover:bg-yellow-700 cursor-pointer transition-colors md:col-span-2 lg:col-span-1">
        <a href="#list-messages" class="text-2xl font-md text-gray-100 w-full h-full flex flex-col justify-center items-center">
            <span>Tin nhắn</span>
            <span>99</span>
        </a>
    </div>
</div>
<div id="admin-info" class="w-full mb-3 bg-indigo-500 text-gray-200">
    <h3 class="text-2xl p-3 text-center font-semibold">
        Thông tin quản trị viên
    </h3>
    <admin-info :admin="{{ auth()->user() }}" :profile="{{ auth()->user()->profile }}" :permissions="{{ auth()->user()->getAllPermissions() }}"></admin-info>
</div>
<div id="list-messages" class="w-full mb-3 text-gray-200 bg-yellow-500">
    <h3 class="text-2xl p-3 text-center font-semibold">
        Tin nhắn
    </h3>
</div>
<div id="list-users" class="w-full mb-3 text-gray-200 bg-red-500">
    <h3 class="text-2xl p-3 text-center font-semibold">
        Danh sách tài khoản
    </h3>
    <list-users v-bind:users="{{ json_encode($users) }}"></list-users>
</div>
<div id="list-toasts" class="w-full mb-3 text-gray-200 bg-green-500">
    <h3 class="text-2xl p-3 text-center font-semibold">
        Danh sách toast
    </h3>
    <list-toasts v-bind:toasts="{{ json_encode($toasts) }}"></list-toasts>
</div>
<div id="list-members" class="w-full mb-3 text-gray-200 bg-blue-500">
    <h3 class="text-2xl p-3 text-center font-semibold">
        Danh sách quản trị viên
    </h3>
</div>
@endsection
