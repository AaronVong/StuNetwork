@extends("layouts.admin")
@section("admin-content")
<div id="admin-info" class="w-full mb-3 bg-indigo-500 text-gray-200">
    <h3 class="text-2xl p-3 text-center font-semibold">
        Thông tin tài khoản
    </h3>
</div>
<list-toasts v-bind:toasts="{{ json_encode($toasts) }}"></list-toasts>
@endsection
