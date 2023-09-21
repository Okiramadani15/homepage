@extends('layout.index')

@section('content')
    @component('home.hero')@endcomponent
    @component('home.why', [
        'banner' => $banner,
    ])@endcomponent
    @component('home.count')@endcomponent
    @component('home.detail')@endcomponent
    {{-- @component('home.activity')@endcomponent --}}
    @component('home.galery', [
        'galery' => $galery,
    ])@endcomponent
@endsection