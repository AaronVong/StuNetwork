@extends("layouts.app")
@section("center")
<div class="w-full h-full">
   <div class="w-full border-b">
        <!-- Dynamic Header Title Here -->
        <h1 class="font-bold text-xl p-3">Trang chủ</h1>
   </div>
    <div class="w-full border-b">
        <div class="w-full p-3">
            <!-- Toast Form Here -->
            <toast-form></toast-form>
        </div>
    </div>
    <div class="w-full">
        <!-- Toast List Here -->
        <toast-list v-bind:owner="{{ auth()->user()->id }}" v-bind:guest="{{ auth()->user()->id }}" v-bind:toast_list="{{ json_encode($toasts) }}"></toast-list>
    </div>
</div>
@endsection
