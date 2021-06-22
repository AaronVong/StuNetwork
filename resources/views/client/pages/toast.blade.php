@extends("layouts.app")
@section("center")
<div class="w-full h-full">
    <div class="w-full border-b">
        <!-- Toast List Here -->
 <toast-list v-bind:owner="{{ auth()->user()->id }}" v-bind:toast_list="{{ json_encode($toast) }}"></toast-list>
    </div>
</div>
@endsection
