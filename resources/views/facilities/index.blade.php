@extends('layout.index')

@section('content')
    @component('facilities.hero')@endcomponent
    @component('facilities.content')@endcomponent
    @component('facilities.galery')@endcomponent
    {{-- @component('facilities.hostel-galery')@endcomponent --}}
@endsection

@section('script')
    <script>
        $('#mosque').on('click', function(){
            $(".gallery-lightbox")[0].click();
        });

        $('#hostel').on('click', function(){
            $(".gallery-lightbox")[4].click();
        });

        $('#class-room').on('click', function(){
            $(".gallery-lightbox")[7].click();
        });

        $('#lab-computer').on('click', function(){
            $(".gallery-lightbox")[8].click();
        });

        $('#lab-language').on('click', function(){
            $(".gallery-lightbox")[10].click();
        });
        
        $('#sport-field').on('click', function(){
            $(".gallery-lightbox")[12].click();
        });

        $('#craft-room').on('click', function(){
            $(".gallery-lightbox")[17].click();
        });

        $('#health-post').on('click', function(){
            $(".gallery-lightbox")[19].click();
        });

        $('#library').on('click', function(){
            $(".gallery-lightbox")[22].click();
        });

        $('#canteen').on('click', function(){
            $(".gallery-lightbox")[24].click();
        });

        $('#laundry').on('click', function(){
            $(".gallery-lightbox")[26].click();
        });

        $('#main-room').on('click', function(){
            $(".gallery-lightbox")[28].click();
        });

        $('#security-post').on('click', function(){
            $(".gallery-lightbox")[32].click();
        });

        $('#bus').on('click', function(){
            $(".gallery-lightbox")[34].click();
        });

    </script>
@endsection