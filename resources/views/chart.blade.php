@extends('template.index')
@section('content')
    <section>
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5">
                <div class="col-lg-6">
                    <h1 class="mt-5">Statistik</h1>
                    <p>Berikut adalah grafik statistik jumlah buku yang diterbitkan oleh PNJ PRESS</p>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container px-4 px-lg-5 mb-5">
            <div class="row gx-4 gx-lg-5">
                <div class="col-lg-6">
                    <h3 class="mt-5 text-center">Jumlah Buku Per Jurusan</h3>
                    <p class="text-center">Jumlah buku yang telah diterbitkan PNJ Press per Jurusan</p>
                    <canvas id="bookPerDept"></canvas>
                </div>
                <div class="col-lg-6">
                    <div class="row-1">
                        <h3 class="mt-5 text-center">Jumlah Buku Per Tahun</h3>
                        <p class="text-center">Jumlah buku yang telah diterbitkan PNJ Press per Tahun</p>
                        <canvas id="bookPerYear"></canvas>
                    </div>
                    <div class="row-1">
                        <h3 class="mt-5 text-center">Kategori Buku</h3>
                        <p class="text-center">Kategori buku-buku yang telah diterbikan PNJ Press</p>
                        <canvas class="m-auto d-flex justify-content-center" id="bookWordCloud"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script src="js/chart.min.js"></script>
    <script src="js/wordcloud2.js"></script>

    <script>
        function randomColors(){
            let r = Math.floor(Math.random() * 255);
            let g = Math.floor(Math.random() * 255);
            let b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        }

        var perJurusanChart, perYearChart;
        fetch('/chart/perDept')
            .then(response => response.json())
            .then(data => {
                let colors = [];

                for (let i = 0; i < data.data.length; i++) {
                    colors.push(randomColors());
                }

                const perJurusanChartContext = document.getElementById('bookPerDept');
                perJurusanChart = new Chart(perJurusanChartContext, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Jumlah buku',
                            data: data.data,
                            backgroundColor: colors,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                })
            });

        fetch('/chart/perYear')
            .then(response => response.json())
            .then(data => {
                let colors = [];

                for (let i = 0; i < data.data.length; i++) {
                    colors.push(randomColors());
                }

                const perYearChartContext = document.getElementById('bookPerYear');
                perYearChart = new Chart(perYearChartContext, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Buku Per Tahun',
                            data: data.data,
                            backgroundColor: 'blue',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                })
            });

        fetch('/chart/wordCloud')
            .then(response => response.json())
            .then(data => {
                WordCloud(document.getElementById('bookWordCloud'), { list: data } );
            })
    </script>

    <script>

    </script>
@endsection
