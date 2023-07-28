<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApiDataModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getDataPenyedia() {
        // URL API Penyedia
        $api_penyedia_url = 'https://isb.lkpp.go.id/isb-2/api/XXXX';
        $data_penyedia = file_get_contents($api_penyedia_url);
        return json_decode($data_penyedia, true);
    }

    public function getDataSwakelola() {
        // URL API Swakelola
        $api_swakelola_url = 'https://isb.lkpp.go.id/isb-2/api/XXXX';
        $data_swakelola = file_get_contents($api_swakelola_url);
        return json_decode($data_swakelola, true);
    }

    public function getDataStrukturAnggaran() {
        // URL API StrukturAnggaran
        $api_struktur_anggaran_url = 'https://isb.lkpp.go.id/isb-2/api/XXXX';
        $data_struktur_anggaran = file_get_contents($api_struktur_anggaran_url);
        return json_decode($data_struktur_anggaran, true);
    }
}
