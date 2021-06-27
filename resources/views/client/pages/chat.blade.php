@extends("layouts.root")
@section("content")
    <div class="w-full h-full lg:container lg:mx-auto grid grid-cols-4 md:auto-cols-min divide-x divide-gray-200 overflow-hidden">
        <div class="relative flex w-full justify-center grid-cols-1">
            <div class="w-full">
            <!-- Web Main Navigator Here -->
               @include("client/components.mainnav")
            </div>
        </div>
        <div class="relative col-span-3 lg:col-span-2">
            <!-- Web Contents Here -->
            Chat App
        </div>
        <chat-list></chat-list>
    </div>
@endsection