@extends("layouts.app")
@section("center")
<div class="w-full h-full">
    <div class="w-full">
        <!-- Toast List Here -->
        <toast-list v-bind:owner="{{ auth()->user()->id }}" v-bind:toast_list="{{ json_encode($toast) }}"></toast-list>
        <toast-comments v-bind:comments="{{ $comments }}" v-bind:replies="{{ $replies }}"></toast-comments>
    </div>
</div>
@endsection
