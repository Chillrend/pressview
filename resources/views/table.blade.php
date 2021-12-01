@extends('template.index')
@section('content')
    <section>
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5">
                <div class="col-lg-12">
                    <h1 class="mt-5">List Buku</h1>
                    <p>Daftar buku-buku yang telah diterbitkan PNJ Press</p>
                    <form action="" method="GET">
                        <div class="input-group mb-3 w-50">
                            <input required type="text" class="form-control" name="search" placeholder="Cari Buku" aria-label="Cari Buku" aria-describedby="button-addon2">
                            <button class="btn btn-info" type="submit" id="button-addon2">Cari</button>
                        </div>
                    </form>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Judul</th>
                                <th scope="col">Tanggal Rilis</th>
                                <th scope="col">Data Buku</th>
                                <th scope="col">Lihat buku</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $buku)
                            <tr>
                                <td>{{$buku['title']}}</td>
                                <td>{{$buku['date']}}</td>
                                <td>{{$buku['content']}}</td>
                                <td><a href="{{$buku['link']}}" target="_blank" class="btn btn-primary">Lihat</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <p>Total buku: {{$meta['X-WP-Total']}}</p>
                    <p>Halaman {{$meta['page']}} dari {{$meta['X-WP-TotalPages']}}</p>

                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            @for($i = 0; $i < $meta['X-WP-TotalPages']; $i++ )
                                <li class="page-item @if($i+1 == $meta['page']) disabled @endif"><a class="page-link" href="{{url('/table?page=' . ($i + 1))}}">{{$i + 1}}</a></li>
                            @endfor
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script>
        let dataTable = new DataTable('#bookTable',{

        });
    </script>
@endsection
