<section id="contact" class="contact">
    <div class="container">

        <div class="section-title" data-aos="fade-up">
          <h2>Kontak</h2>
          <p>Hubungi Kami</p>
        </div>

        <div class="row">

          <div class="col-lg-4" data-aos="fade-right" data-aos-delay="100">
            <div class="info">
              <div class="address">
                <i class="bi bi-geo-alt"></i>
                <h4>Alamat:</h4>
                <p><Td>Tebing Tinggi, Sumatera Utara </Td> 20623</p>
              </div>

              <div class="email">
                <i class="bi bi-envelope"></i>
                <h4>Email:</h4>
                <p>alhasyimiyahtebingtinggi@gmail.com</p>
              </div>

              <div class="phone">
                <i class="bi bi-phone"></i>
                <h4>Telepon:</h4>
                <p>0621-24409</p>
              </div>

              <div class="phone">
                <i class="bi bi-clock-fill"></i>
                <h4>Jam Kerja : </h4>
                <p>Senin - Sabtu : 08:00 - 18:00</p>
              </div>

            </div>

          </div>

          <div class="col-lg-8 mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="200">

            <form action="/hubungi-kami/create" method="post" class="php-email-form">
              @csrf
              <div class="row">
                <div class="col-md-6 form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Nama" required @error('name') is-invalid @enderror>
                  @error('name')
                      <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Email" required @error('email') is-invalid @enderror>
                  @error('email')
                      <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="form-group mt-3">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required @error('subject') is-invalid @enderror>
                @error('subject')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group mt-3">
                <textarea class="form-control" name="message" id="message" rows="5" placeholder="Pesan" required @error('message') is-invalid @enderror></textarea>
                @error('message')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="my-3">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div>
              </div>
              <div class="text-center"><button type="submit" id="submits" class="btn btn-success">Kirim Pesan</button></div>
            </form>

          </div>

        </div>

    </div>
</section>