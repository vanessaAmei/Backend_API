<?php
use Restserver \Libraries\REST_Controller;

Class Laporan extends REST_Controller
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
        parent::__construct();
        // $this->load->model("user_model");
        // if($this->user_model->isNotLogin()) redirect(site_url('admin/login'));
        $this->load->model('laporan_model'); //Akses Model data nya untuk Controller ini
        $this->load->library('form_validation');
    }

    public function pendapatanProduk_get(){
        $tahun = $this->get('tahun');
        $query = $this->laporan_model->getPendapatanProduk($tahun);
        echo json_encode($query);
    }

    public function pendapatanLayanan_get(){
        $tahun = $this->get('tahun');
        $query = $this->laporan_model->getPendapatanLayanan($tahun);
        echo json_encode($query);
    }

    public function produkTerlaris_get(){
        $tahun = $this->get('tahun');
        $bulan = $this->get('bulan');
        $query = $this->laporan_model->produkTerlaris($bulan, $tahun);
        echo json_encode($query);
    }
    
    public function layananTerlaris_get(){
        $tahun = $this->get('tahun');
        $bulan = $this->get('bulan');
        $query = $this->laporan_model->layananTerlaris($bulan, $tahun);
        echo json_encode($query);
    }
    

    public function pendapatanBulananProduk_get(){
        $tahun = $this->get('tahun');
        $bulan = $this->get('bulan');
        $query = $this->laporan_model->pendapatanProdukBulanan($bulan, $tahun);
        echo json_encode($query);
    }

    public function pendapatanBulananLayanan_get(){
        $tahun = $this->get('tahun');
        $bulan = $this->get('bulan');
        $query = $this->laporan_model->layananBulanan($bulan, $tahun);
        echo json_encode($query);
    }

    public function pengadaanTahunan_get(){
        $tahun = $this->get('tahun');
        $query = $this->laporan_model->pengadaanTahunan($tahun);
        echo json_encode($query);
    }

    public function pengadaanBulanan_get(){
        $tahun = $this->get('tahun');
        $bulan = $this->get('bulan');
        $query = $this->laporan_model->pengadaanBulanan($bulan, $tahun);
        echo json_encode($query);
    }

    public function returnData($msg,$error) //Fungsi untuk me-return kan Nilai balikkan setelah melakukan Method apapun seperti Error dan Pesan
    {
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}
