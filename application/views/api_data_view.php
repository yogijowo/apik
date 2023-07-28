<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data API</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- ApexCharts CSS -->
    <link href="https://cdn.jsdelivr.net/npm/apexcharts@3.28.3/dist/apexcharts.min.css" rel="stylesheet">
</head>
<body>
    <?php $this->load->view('navbar'); ?>

    <div class="container mt-4">
        <h1 class="mb-4">Rekap Perencanaan Satuan Kerja</h1>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Belanja Pengadaan Struktur Anggaran</h5>
                        <h5><?= format_rupiah($totalBelanjaPengadaanData); ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div id="radialBarChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Pagu Paket RUP Terumumkan</h5>
                        <h5><?= format_rupiah($totalPaguData); ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="font-size: 11px;" id="data-table">
                        <thead class="text-center">
                            <tr>
                                <th rowspan="2">Satuan Kerja</th>
                                <th colspan="3">Paket</th>
                                <th rowspan="2">Total Paket</th>
                                <th colspan="3">Pagu</th>
                                <th rowspan="2">Total Pagu</th>
                                <th rowspan="2">Belanja Pengadaan</th>
                                <th rowspan="2">% Pagu</th>
                                <th colspan="3">PDN</th>
                                <th colspan="3">UKM</th>
                            </tr>
                            <tr>
                                <th>P</th>
                                <th>S</th>
                                <th>PDS</th>
                                <th>P</th>
                                <th>S</th>
                                <th>PDS</th>
                                <th>Paket</th>
                                <th>Pagu</th>
                                <th>%</th>
                                <th>Paket</th>
                                <th>Pagu</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grouped_data as $data) : ?>
                                <tr>
                                    <td><?= $data['nama_satker']; ?></td>
                                    <td><?= $data['paket_p']; ?></td>
                                    <td><?= $data['paket_s']; ?></td>
                                    <td><?= $data['paket_pds']; ?></td>
                                    <td><?= $data['total_paket']; ?></td>
                                    <td><?= format_rupiah($data['pagu_p']); ?></td>
                                    <td><?= format_rupiah($data['pagu_s']); ?></td>
                                    <td><?= format_rupiah($data['pagu_pds']); ?></td>
                                    <td><?= format_rupiah($data['total_pagu']); ?></td>
                                    <td><?= format_rupiah($data['belanja_pengadaan']); ?></td>
                                    <td><?= $data['percent_pagu']; ?>%</td>
                                    <td><?= $data['paket_pdn']; ?></td>
                                    <td><?= format_rupiah($data['pagu_pdn']); ?></td>
                                    <td><?= $data['percent_pdn']; ?>%</td>
                                    <td><?= $data['paket_ukm']; ?></td>
                                    <td><?= format_rupiah($data['pagu_ukm']); ?></td>
                                    <td><?= $data['percent_ukm']; ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

   <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- ApexCharts JS -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.28.3/dist/apexcharts.min.js"></script>

    <script>
        // Data untuk Radial Bar Chart
        var totalBelanjaPengadaanData = <?= $totalBelanjaPengadaanData; ?>;
        var totalPaguData = <?= $totalPaguData; ?>;
        var persentaseBelanjaPengadaan = (totalBelanjaPengadaanData / totalPaguData) * 100;
        var radialBarChart = new ApexCharts(document.getElementById('radialBarChart'), {
            chart: {
                height: 350,
                type: 'radialBar'
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '60%',
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            fontSize: '34px',
                            show: true,
                            offsetY: -10,
                            formatter: function (val) {
                                return val.toFixed(2) + '%';
                            }
                        }
                    }
                }
            },
            series: [persentaseBelanjaPengadaan],
            labels: ['Persentase Belanja Pengadaan']
        });
        radialBarChart.render();
    </script>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            $('#data-table').DataTable();
        });
    </script>

    <?php
    function format_rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
    ?>
</body>
</html>
