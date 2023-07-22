<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiDataController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load helper URL untuk menggunakan fungsi "file_get_contents"
        $this->load->helper('url');
    }

    public function index() {
        // URL API Penyedia
        $api_penyedia_url = 'https://isb.lkpp.go.id/isb-2/api/XXXX';

        // Ambil data dari API Penyedia
        $data_penyedia = file_get_contents($api_penyedia_url);

        // Konversi data JSON menjadi array asosiatif
        $data_penyedia = json_decode($data_penyedia, true);

        // URL API Swakelola
        $api_swakelola_url = 'https://isb.lkpp.go.id/isb-2/api/XXXX';

        // Ambil data dari API Swakelola
        $data_swakelola = file_get_contents($api_swakelola_url);

        // Konversi data JSON menjadi array asosiatif
        $data_swakelola = json_decode($data_swakelola, true);

        // Gabungkan data dari API Penyedia dan API Swakelola berdasarkan KD_SATKER
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

        // Hitung % PDN dan % UKM
        foreach ($grouped_data as $kd_satker => $data) {
            $grouped_data[$kd_satker]['percent_pdn'] = $data['total_paket'] != 0 ? round(($data['paket_pdn'] / $data['total_paket']) * 100, 2) : 0;
            $grouped_data[$kd_satker]['percent_ukm'] = $data['total_paket'] != 0 ? round(($data['paket_ukm'] / $data['total_paket']) * 100, 2) : 0;
        }

        // Kirim data ke view
        $data['grouped_data'] = $grouped_data;
        $this->load->view('api_data_view', $data);
    }
}
