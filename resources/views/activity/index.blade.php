@extends('layout.index')

@section('content')
    @component('activity.hero')@endcomponent
    @component('activity.content', [
        'mostView' => $mostView
    ])@endcomponent
@endsection