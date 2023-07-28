<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ApiDataController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ApiDataModel'); // Load model ApiDataModel
        $this->load->helper('url');
    }

    public function index() {
        // Load model dan ambil data dari API Penyedia, Swakelola dan StrukturAnggaran
        $apiDataModel = new ApiDataModel();
        $data_penyedia = $apiDataModel->getDataPenyedia();
        $data_swakelola = $apiDataModel->getDataSwakelola();
        $data_struktur_anggaran = $apiDataModel->getDataStrukturAnggaran();

        // Gabungkan data dari API Penyedia, API Swakelola, dan API StrukturAnggaran berdasarkan KD_SATKER
        $grouped_data = array();
        foreach ($data_penyedia as $item) {
            $kd_satker = $item['kd_satker'];
            if (!isset($grouped_data[$kd_satker])) {
                $grouped_data[$kd_satker] = array(
                    'nama_satker' => $item['nama_satker'],
                    'paket_p' => 0,
                    'paket_s' => 0,
                    'paket_pds' => 0,
                    'total_paket' => 0,
                    'pagu_p' => 0,
                    'pagu_s' => 0,
                    'pagu_pds' => 0,
                    'total_pagu' => 0,
                    'paket_pdn' => 0,
                    'pagu_pdn' => 0,
                    'paket_ukm' => 0,
                    'pagu_ukm' => 0,
                    'belanja_pengadaan' => 0,
                );
            }

            if ($item['tipe_paket'] == 'Penyedia') {
                $grouped_data[$kd_satker]['paket_p']++;
                $grouped_data[$kd_satker]['pagu_p'] += $item['pagu'];
            } else {
                $grouped_data[$kd_satker]['paket_pds']++;
                $grouped_data[$kd_satker]['pagu_pds'] += $item['pagu'];
            }
        }

        foreach ($data_swakelola as $item) {
            $kd_satker = $item['kd_satker'];
            if (!isset($grouped_data[$kd_satker])) {
                $grouped_data[$kd_satker] = array(
                    'nama_satker' => $item['nama_satker'],
                    'paket_p' => 0,
                    'paket_s' => 0,
                    'paket_pds' => 0,
                    'total_paket' => 0,
                    'pagu_p' => 0,
                    'pagu_s' => 0,
                    'pagu_pds' => 0,
                    'total_pagu' => 0,
                    'paket_pdn' => 0,
                    'pagu_pdn' => 0,
                    'paket_ukm' => 0,
                    'pagu_ukm' => 0,
                    'belanja_pengadaan' => 0,
                );
            }

            $grouped_data[$kd_satker]['paket_s']++;
            $grouped_data[$kd_satker]['pagu_s'] += $item['pagu'];
        }

        // Hitung Total Paket dan Total Pagu
        foreach ($grouped_data as $kd_satker => $data) {
            $grouped_data[$kd_satker]['total_paket'] = $data['paket_p'] + $data['paket_s'] + $data['paket_pds'];
            $grouped_data[$kd_satker]['total_pagu'] = $data['pagu_p'] + $data['pagu_s'] + $data['pagu_pds'];
        }

        // Hitung Total Paket PDN dan Total Pagu PDN
        foreach ($data_penyedia as $item) {
            $kd_satker = $item['kd_satker'];
            if ($item['status_pdn'] == 'PDN') {
                $grouped_data[$kd_satker]['paket_pdn']++;
                $grouped_data[$kd_satker]['pagu_pdn'] += $item['pagu'];
            }
        }

        // Hitung Total Paket UKM dan Total Pagu UKM
        foreach ($data_penyedia as $item) {
            $kd_satker = $item['kd_satker'];
            if ($item['status_ukm'] == 'UKM') {
                $grouped_data[$kd_satker]['paket_ukm']++;
                $grouped_data[$kd_satker]['pagu_ukm'] += $item['pagu'];
            }
        }

        // Menambahkan nilai "Belanja Pengadaan" berdasarkan data dari API StrukturAnggaran
        foreach ($data_struktur_anggaran as $item) {
            $kd_satker = $item['kd_satker'];
            if (isset($grouped_data[$kd_satker])) {
                $grouped_data[$kd_satker]['belanja_pengadaan'] = $item['belanja_pengadaan'];
            }
        }

        // Hitung % PDN, % UKM, dan % Pengadaan terhadap Total Pagu
        foreach ($grouped_data as $kd_satker => $data) {
            $grouped_data[$kd_satker]['percent_pdn'] = $data['total_pagu'] != 0 ? round(($data['pagu_pdn'] / $data['total_pagu']) * 100, 2) : 0;
            $grouped_data[$kd_satker]['percent_ukm'] = $data['total_pagu'] != 0 ? round(($data['pagu_ukm'] / $data['total_pagu']) * 100, 2) : 0;
            $grouped_data[$kd_satker]['percent_pagu'] = $data['total_pagu'] != 0 ? round(($data['belanja_pengadaan'] / $data['total_pagu']) * 100, 2) : 0;
        }

        // Menghitung total seluruh belanja_pengadaan
        $totalBelanjaPengadaanData = 0;
        foreach ($data_struktur_anggaran as $item) {
            $totalBelanjaPengadaanData += $item['belanja_pengadaan'];
        }

        // Menghitung total pagu Penyedia
        $totalPaguPenyedia = 0;
        foreach ($data_penyedia as $item) {
            $totalPaguPenyedia += $item['pagu'];
        }

        // Menghitung total pagu Swakelola
        $totalPaguSwakelola = 0;
        foreach ($data_swakelola as $item) {
            $totalPaguSwakelola += $item['pagu'];
        }

        // Menghitung total seluruh pagu (Pagu Penyedia + Pagu Swakelola)
        $totalPaguData = $totalPaguPenyedia + $totalPaguSwakelola;

        // Menghitung persentase dari total pagu dan total belanja pengadaan
        $percentPaguData = $totalPaguData != 0 ? round(($totalPaguData / $totalPaguData) * 100, 2) : 0;
        $percentBelanjaPengadaanData = $totalPaguData != 0 ? round(($totalBelanjaPengadaanData / $totalPaguData) * 100, 2) : 0;

        // Kirim data ke view
        $data['grouped_data'] = $grouped_data;
        $data['totalBelanjaPengadaanData'] = $totalBelanjaPengadaanData;
        $data['totalPaguData'] = $totalPaguData;
        $data['percentPaguData'] = $percentPaguData;
        $data['percentBelanjaPengadaanData'] = $percentBelanjaPengadaanData;
        $data['active_menu'] = 'rekap';
        $this->load->view('api_data_view', $data);
    }

}
