@extends("layouts.app")
@section("center")
    <div class="w-full" id="profile-head">
    <div class="w-full">
            <profile v-bind:user_profile="{{ $profile }}" v-bind:owned="{{ json_encode(auth()->user()->can('update', $profile)) }}" v-bind:followable="{{json_encode(auth()->user()->can('follow', $profile))}}" v-bind:visitor="{{ auth()->user()->id }}"
            v-bind:toastCount="{{$profile->user->toasts->count()}}"
            v-bind:followingCount="{{ $profile->user->followings->count() }}"
            v-bind:followedCount="{{$profile->followers->count()}}"
            ></profile>
        </div>
    </div>
@endsection