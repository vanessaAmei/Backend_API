<?php
class Page extends CI_Controller{
  function __construct(){
    
    parent::__construct();

    $this->load->helper('url');

    $this->load->model('customer_model');
    $this->load->model('hewan_model');
    $this->load->model('supplier_model');
    $this->load->model('pegawai_model');
    $this->load->model('produk_model');
    $this->load->model('jenisHewan_model');
    $this->load->model('ukuranHewan_model');
    $this->load->model('layanan_model');
    // $this->load->model('trPenjualan_model');
    // $this->load->model('Transaksi_model');

    if($this->session->userdata('logged_in') !== TRUE){
      redirect('index.php/Welcome');
    }
    
  }

  function admin(){
    
      if($this->session->userdata('peran') === 'Owner'){

          $data['content'] = 'content/home';
          $this->load->view('admin/dashboard',$data);
      }else{
          echo "Access Denied";
      }
 
  }
 
  function customerservice(){

    if($this->session->userdata('PERAN')==='Customer Service'){
          
          $data['content'] = 'Content/home';
          $this->load->view('admin/dashboard',$data);
      }else{
          echo "Access Denied";
      }
  }

  function kasir(){

    if($this->session->userdata('PERAN')==='Kasir'){
          
          $data['content'] = 'Content/home';
          $this->load->view('admin/dashboard',$data);
      }else{
          echo "Access Denied";
      }
  }

  function dashboard(){

    if($this->session->userdata('PERAN')==='Owner')
    {
      $this->admin();
    }else if($this->session->userdata('PERAN')==='Customer Service')
    {
      $this->customerservice();
    }

  }

  function Other(){ 
    $this->load->view('Other/index');
  }

  function input_supplier(){

    $data['supplier'] = $this->supplier_model->getAll();
    $data['temp'] = 0;
    $data['search_keyword'] = " ";
    $data['content'] = 'Content/input_supplier';
    $this->load->view('Template/dashboard',$data);
  }

  function input_hewan(){

    $data['hewan'] = $this->hewan_model->getAll();
    $data['temp'] = 0;
    $data['search_keyword'] = " ";
    $data['content'] = 'Content/input_hewan';
    $this->load->view('Template/dashboard',$data);
  }

  function input_customer(){

    $data['customer'] = $this->customer_model->getAll();
    $data['temp'] = 0;
    $data['search_keyword'] = " ";
    $data['content'] = 'Content/input_customer';
    $this->load->view('Template/dashboard',$data);
  }

  function input_pegawai(){

    $data['pegawai'] = $this->pegawai_model->getAll();
    //$data['pegawai_role'] = $this->pegawai_model->getAllRole();
    $data['temp'] = 0;
    $data['search_keyword'] = " ";
    $data['content'] = 'Content/input_pegawai';
    $this->load->view('Template/dashboard',$data);
  }

  function input_jenisHewan(){

    $data['jenis_hewan'] = $this->jenisHewan_model->getAll();
    $data['temp'] = 0;
    $data['search_keyword'] = " ";
    $data['content'] = 'Content/input_jenisHewan';
    $this->load->view('Template/dashboard',$data);
  }

  function input_layanan(){

    $data['layanan'] = $this->layanan_model->getAll();
    $data['temp'] = 0;
    $data['search_keyword'] = " ";
    $data['content'] = 'Content/layanan';
    $this->load->view('Template/dashboard',$data);
  }

  function input_produk(){

    $data['produk'] = $this->produk_model->getAll();
    $data['temp'] = 0;
    $data['search_keyword'] = " ";
    $data['content'] = 'Content/input_produk';
    $this->load->view('Template/dashboard',$data);
  }

  /* --------------------------------------- TRANSAKSI ---------------------------------------*/


  // function TransaksiPembelianSparepart(){

  //   $data['idPembelian'] = $this->OrderSparepart_model->idPembelian();
  //   $data['content'] = 'Content/Transaksi/PembelianSparepart';
  //   $this->load->view('Template/dashboard',$data);
  // }

  // function HistoryPembelianSparepart(){

  //   $data['content'] = 'Content/Transaksi/HistoryPembelianSparepart';
  //   $this->load->view('Template/dashboard',$data);
  // }

  // function TransaksiMaster(){
    
  //   $data['id_transaksi'] = $this->Transaksi_model->id_transaksi();
  //   $data['content'] = 'Content/Transaksi/TransaksiMaster';
  //   $this->load->view('Template/dashboard',$data);
  // }

  // function TransaksiPenjualan(){

  //   $data['idtransaksi'] = $this->trPenjualan_model->idtransaksi();
  //   $data['content'] = 'Content/Transaksi/TrPenjualan';
  //   $this->load->view('Template/dashboard',$data);
  // }

  // function HistoryTransaksi(){

  //   $data['content'] = 'Content/Transaksi/HistoryTr';
  //   $this->load->view('Template/dashboard',$data);
  // }

  // function RiwayatDataSparepart(){

  //   $data['content'] = 'Content/Transaksi/riwayat_data_sparepart';
  //   $this->load->view('Template/dashboard',$data);
  // }

  // function LapSparepart(){

  //   $data['content'] = 'Content/Transaksi/LapSPTerlaris';
  //   $this->load->view('Template/dashboard',$data);
  // }

  // function LapPendapatanBulanan(){

  //   $data['content'] = 'Content/Transaksi/LapPendapatanBulanan';
  //   $this->load->view('Template/dashboard',$data);
  // }
}