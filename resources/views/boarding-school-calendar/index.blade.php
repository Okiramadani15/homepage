@extends('layout.index')

@section('content')
    @component('boarding-school-calendar.hero')@endcomponent
    @component('boarding-school-calendar.content', [
        'ta' => $ta,
        'ganjil' => $ganjil,
        'genap' => $genap,
    ])@endcomponent
@endsection