@extends("layouts.root")
@section("content")
    <div class="w-full h-full lg:container lg:mx-auto grid grid-cols-4 md:auto-cols-min divide-x divide-gray-200 overflow-hidden">
        <div class="relative flex w-full justify-center grid-cols-1">
            <div class="w-full">
            <!-- Web Main Navigator Here -->
               @include("client/components.mainnav")
            </div>
        </div>
        <div class="relative col-span-3 xl:col-span-2">
            <!-- Web Contents Here -->
            <chat-app v-bind:user="{{ auth()->user() }}"></chat-app>
        </div>
        <chat-list v-bind:chat_list="{{ auth()->user()->followings }}" v-bind:strangers="{{ $strangers }}"></chat-list>
    </div>
@endsection