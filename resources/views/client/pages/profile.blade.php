@extends("layouts.app")
@section("center")
    <div class="w-full" id="profile-head">
    <div class="w-full">
            <profile v-bind:user_profile="{{ $user->profile }}" v-bind:username="'{{ $user->username }}'" v-bind:owned="{{ auth()->user()->can('update', $user->profile) ? 1 : 0 }}" v-bind:roles="{{ $user->roles }}" v-bind:followable="{{auth()->user()->can('follow', $user->profile)}}" v-bind:visitor="{{ auth()->user()->id }}"></profile>
        </div>
    </div>
@endsection