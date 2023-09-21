<section id="contact" class="contact mt-5">
    <div class="row mt-5 px-2">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3 mx-auto">
            @if($asset && $asset->photo)
                <img src="{!! asset($asset->photo) !!}" alt="Gambat Asset" width="100%" />
            @else
                <img src="{!! asset('assets/illustrations/no-image.png') !!}" alt="Gambat Asset" width="100%" />
            @endif
        </div>
        <div class="col-12 col-sm-12 col-md-8 col-lg-9">
            <table class="table table-bordered table-hover p-0 m-0">
                <tbody>
                    <tr>
                        <td colspan="3" class="bg-dark text-white fs-4">Detail Barang</td>
                    </tr>
                    <tr>
                        <th scope="row">1</th>
                        <td>Nama Barang</td>
                        <td>{{ $asset && $asset->name ? $asset->name : "-" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Merk / Model</td>
                        <td>{{ $asset && $asset->merk ? $asset->merk : "-" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>No Seri Pabrik</td>
                        <td>{{ $asset && $asset->serial_number ? $asset->serial_number : "-" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">4</th>
                        <td>Ukuran</td>
                        <td>{{ $asset && $asset->size ? $asset->size : "-" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">5</th>
                        <td>Bahan</td>
                        <td>{{ $asset && $asset->serial_number ? $asset->material : "-" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">6</th>
                        <td>Tahun Pembuatan / Pembelian</td>
                        <td>{{ $asset && $asset->date_of_purchase ? $asset->date_of_purchase : "-" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">7</th>
                        <td>Kode Barang</td>
                        <td>{{ $asset && $asset->asset_code ? $asset->asset_code : "-" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">8</th>
                        <td>No Registrasi</td>
                        <td>{{ $asset && $asset->code ? $asset->code : "-" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">9</th>
                        <td>Golongan</td>
                        <td>{{ $asset && $asset->group_of_code ? $asset->group_of_code->name : "-" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">10</th>
                        <td>Lokasi Pembelian</td>
                        <td>{{ $asset && $asset->purchase_location ? $asset->purchase_location->name : "-" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">11</th>
                        <td>Lokasi Asset</td>
                        <td>{{ $asset && $asset->location ? $asset->location : "-" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">12</th>
                        <td>Satuan Kerja</td>
                        <td>{{ $asset && $asset->work_unit ? $asset->work_unit->name : "-" }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>