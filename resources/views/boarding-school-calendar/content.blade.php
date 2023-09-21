<section>
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Kalender Pendidikan</h2>
            <p class="text-capitalize">Pondok Pesantren Modern Al-Hasyimiyah T.A. {{$ta}}</p>
        </div>
        <!-- <table class="table table-bordered table-responsive">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Kegiatan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @php $i = 1; @endphp
                @foreach($ganjil as $value)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->description}}</td>
                        <td>{{$value->date}}</td>
                    </tr>
                    @php $i += 1; @endphp
                @endforeach
            </tbody>
        </table>

        <h3 class="fw-bold mt-5">Semester Genap</h3>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Kegiatan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @php $i = 1; @endphp
                @foreach($genap as $value)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->description}}</td>
                        <td>{{$value->date}}</td>
                    </tr>
                    @php $i += 1; @endphp
                @endforeach
            </tbody>
        </table> -->
        <div id="calendar"></div>
        
    </div>
</section>

@section('script')
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                defaultView: 'timeGridDay',
                events: '/data-calendar',
                // selectOverlap: function (event) {
                //     return event.rendering === 'background';
                // },
                // displayEventTime: true,
            });

            calendar.setOption('locale', 'id');
            calendar.render();
        });
    </script>
@endsection