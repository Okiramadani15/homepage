<section id="gallery" class="gallery">
    <div class="container">

        <div class="section-title" data-aos="fade-up">
            <h2>Galeri</h2>
            <p>Galeri Al-Hasyimiyah</p>
        </div>

        <div class="row g-0" data-aos="fade-left">
            @foreach ($galery as $item)
                <div class="col-lg-3 col-md-4">
                    <div class="gallery-item" data-aos="zoom-in" data-aos-delay="150">
                        <a href="{{$item->path}}" class="gallery-lightbox">
                            <div style="position: : relative; display: block;">
                                <img src="{{$item->path}}" alt="" class="img-fluid" style="object-fit: cover; aspect-ratio: 16/9;";>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>