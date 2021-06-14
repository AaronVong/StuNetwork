@extends("layouts.root")
@section("content")
    <div class="w-full h-full lg:container lg:mx-auto grid grid-cols-4 md:auto-cols-min divide-x divide-gray-200">
        <div class="relative flex w-full justify-center grid-cols-1">
            <div class="w-full">
            <!-- Web Main Navigator Here -->
               @include("client/components.mainnav")
            </div>
        </div>
        <div class="relative col-span-3 lg:col-span-2">
            <!-- Web Contents Here -->
            @yield("center")
        </div>
        <div id="sidebar" class="relative hidden md:gird-cols-1 lg:block">
            <div class="w-full">
            <!-- Web Side Bar Here -->
                <h1 class="text-center"> right</h1>
            </div>
        </div>   
    </div>
@endsection