<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data API</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Data API</h1>
        <table class="table table-striped table-bordered" id="data-table">
            <thead>
                <tr>
                    <th>Satuan Kerja</th>
                    <th colspan="3">Paket</th>
                    <th>Total Paket</th>
                    <th colspan="3">Pagu</th>
                    <th>Total Pagu</th>
                    <th>Belanja Pengadaan</th>
                    <th colspan="2">PDN</th>
                    <th colspan="2">UKM</th>
                    <th>% PDN</th>
                    <th>% UKM</th>
                </tr>
                <tr>
                    <th></th>
                    <th>P</th>
                    <th>S</th>
                    <th>PDS</th>
                    <th></th>
                    <th>P</th>
                    <th>S</th>
                    <th>PDS</th>
                    <th></th>
                    <th></th>
                    <th>Paket PDN</th>
                    <th>Pagu PDN</th>
                    <th>Paket UKM</th>
                    <th>Pagu UKM</th>
                    <th></th>
                    <th></th>
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
                        <td></td>
                        <td><?= $data['paket_pdn']; ?></td>
                        <td><?= format_rupiah($data['pagu_pdn']); ?></td>
                        <td><?= $data['paket_ukm']; ?></td>
                        <td><?= format_rupiah($data['pagu_ukm']); ?></td>
                        <td><?= $data['percent_pdn']; ?>%</td>
                        <td><?= $data['percent_ukm']; ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

   <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables
            $('#data-table').DataTable();
        });
    </script>

    <!-- PHP Function to Format Rupiah -->
    <?php
    function format_rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
    ?>
</body>
</html>
