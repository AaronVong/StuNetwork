@extends("layouts.app")
@section("center")
    <div class="w-full" id="profile-head">
    <div class="w-full">
            <profile v-bind:user_profile="{{ $user->profile }}" v-bind:username="'{{ $user->username }}'" v-bind:owned="{{ json_encode(auth()->user()->can('update', $user->profile)) }}" v-bind:roles="{{ $user->roles }}" v-bind:followable="{{json_encode(auth()->user()->can('follow', $user->profile))}}" v-bind:visitor="{{ auth()->user()->id }}"
            v-bind:toastCount="{{$user->toasts()->count()}}"
            v-bind:followingCount="{{ $user->followings()->count() }}"
            v-bind:followedCount="{{$user->followedCount()}}"
            ></profile>
        </div>
    </div>
@endsection