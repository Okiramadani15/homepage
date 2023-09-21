@extends('layout.index')

@section('content')
    {{-- @component('register.hero')@endcomponent --}}
    @component('register.content', [
        'banner' => $banner
    ])@endcomponent
@endsection
