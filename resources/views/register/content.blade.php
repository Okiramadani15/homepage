<section id="contact" class="contact">
    <div class="container">
        <div class="mt-5" style="position: relative; ">
            <div class="w-100 mx-auto pt-5">
                @if($banner)
                    <img src="{{$banner->path}}" class="w-100" />
                @else
                    <img src="{{asset('assets/illustrations/comming_soon.png')}}" class="w-100" />
                @endif
            </div>
            @if(!$banner)
                <h1 class="fs-4 text-center">Coming soon, under construction :)</h1>
            @endif
        </div>
        {{-- <div class="section-title" data-aos="fade-up">
            <h2>Pendaftaran</h2>
            <p>Tata Cara Pendaftaran Siswa/i Baru</p>
        </div>

        <div class="mb-5">
            <h5 class="text-decoration-underline">Syarat Pendaftaran</h5>
            <ul class="list-group">
                <li class="list-group-item" aria-disabled="true">Beragama Islam</li>
                <li class="list-group-item">Sehat Jasmani dan Rohani</li>
                <li class="list-group-item">Sudah memiliki ijazah minimal jenjang pendidikan Sekolah Dasar (sederajat) atau Sekolah Menengah Pertama (sederajat).</li>
            </ul>
        </div>

        <div class="mb-5">
            <h5 class="text-decoration-underline">Alur Pendaftaran</h5>
            <ul class="list-group">
                <li class="list-group-item" aria-disabled="true">Melakukan pendaftaran secara offline melalui badan kepengurusan pesantren.</li>
                <li class="list-group-item" aria-disabled="true">Melunasi biaya pendaftaran.</li>
                <li class="list-group-item" aria-disabled="true">Melengkapi seluruh formulir pendaftaran.</li>
                <li class="list-group-item" aria-disabled="true">Mendapatkan nomor ujian</li>
                <li class="list-group-item" aria-disabled="true">Mencetak berkas pendaftaran</li>
                <li class="list-group-item" aria-disabled="true">Hadir di pondok yang telah ditentukan pada saat pelaksanaan ujian</li>
                <li class="list-group-item" aria-disabled="true">Menyerahkan berkas/ dokumen</li>
                <li class="list-group-item" aria-disabled="true">Bukti Pendaftaran Calon Pelajar.</li>
                <li class="list-group-item" aria-disabled="true">Bukti Pendaftaran Calon Pelajar.</li>
                <li class="list-group-item" aria-disabled="true">Surat Pernyataan Wali bermeterai 10000.</li>
                <li class="list-group-item" aria-disabled="true">Surat Permohonan Calon Pelajar.</li>
            </ul>
        </div>

        <div class="mb-5">
            <h5 class="text-decoration-underline">Biaya Pendaftaran</h5>
            <table class="table">
                <thead>
                    <tr>
                        <td colspan="3">Rincian</td>
                        <td class="text-center">Putra</td>
                        <td class="text-center">Putri</td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-active">
                        <td>1</td>
                        <td colspan="2">Uang Pangkal Masuk KMI</td>
                        <td colspan="2" class="text-center">1.700.000</td>
                    </tr>
                    <tr class="">
                        <td>2</td>
                        <td colspan="2">Uang Penambahan Bangunan Baru</td>
                        <td colspan="2" class="text-center">850.000</td>
                    </tr>
                    <tr class="table-active">
                        <td>3</td>
                        <td colspan="2">Uang Kepanitiaan Awal Tahun Ajaran</td>
                        <td colspan="2" class="text-center">220.000</td>
                    </tr>
                    <tr class="">
                        <td>4</td>
                        <td colspan="2">Uang Asrama dan Sekolah (Setiap Bulan)</td>
                        <td colspan="2" class="text-center">380.000</td>
                    </tr>
                    <tr class="table-active">
                        <td>5</td>
                        <td colspan="2">Uang Makan (Setiap Bulan)</td>
                        <td colspan="2" class="text-center">220.000</td>
                    </tr>
                    <tr class="">
                        <td>6</td>
                        <td colspan="2">Uang Kesehatan</td>
                        <td colspan="2" class="text-center">350.000</td>
                    </tr>
                    <tr class="table-active">
                        <td>7</td>
                        <td colspan="2">Uang Organisasi dan Pramuka</td>
                        <td class="text-center">828.000</td>
                        <td class="text-center">601.000</td>
                    </tr>
                    <tr class="">
                        <td>8</td>
                        <td colspan="2">Uang Buku Pelajaran</td>
                        <td class="text-center">518.500</td>
                        <td class="text-center">533.500</td>
                    </tr>
                    <tr class="table-active">
                        <td></td>
                        <td colspan="2" class="pl-10">Jumlah</td>
                        <td class="text-center">5.681.000</td>
                        <td class="text-center">4.854.500</td>
                    </tr>
                </tbody>
            </table>
        </div> --}}
    </div>
</section>