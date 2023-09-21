@extends('layout.index')

@section('content')
    @component('asset.content', [
        'asset' => $asset
    ])@endcomponent
@endsection