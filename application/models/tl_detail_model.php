<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class tl_detail_model extends CI_Model
{
    private $table = 'detail_tl'; 

    public $id_detail_tl;
    public $id_tl;
    public $id_layanan;
    public $jumlah;
    public $total; 
    
    
    public $rule = [
        [
            'field' => 'id_tl',
            'label' => 'id_tl',
            'rules' => 'required'
        ],

        [
            'field' => 'id_layanan',
            'label' => 'id_layanan',
            'rules' => 'required'
        ],

        [
            'field' => 'jumlah',
            'label' => 'jumlah',
            'rules' => 'required'
        ],
        [
            'field' => 'total',
            'label' => 'total',
            'rules' => 'required'
        ]
    ];
    public function Rules() { return $this->rule; } //Fungsi untuk return nilai rule dimana untuk di cek

    public function getAll() {

        $this->db->select('a.id_detail_tl, c.kode as "kode", a.id_tl, b.nama as "layanan", a.jumlah , a.total');
        $this->db->from('detail_tl a'); 
        $this->db->join('layanan b', 'id_layanan');
        $this->db->join('transaksi_layanan c', 'id_tl');
        return $this->db->get()->result_array();

        // $this->db->select('*');
        // $this->db->from('detail_tl');
        // return $this->db->get()->result_array();

    }

    public function store($request) {   //Fungsi untuk menyimpan data
        $this->id_tl = $request->id_tl;   //Gunakan $Request untuk mengambil data yang diinputkan oleh user
        $this->id_layanan = $request->id_layanan;
        $this->jumlah = $request->jumlah;
        $this->total = $request->total;
      //Mengambil nilai date dari local sesuai format, jadi untuk format ini menggunakan Timestamp
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Menambahkan Transaksi layanan','error'=>false];
        }
            return ['msg'=>'Gagal Menambahkan Transaksi layanan','error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data

        $this->updated_at = date("Y-m-d H:i:s"); 
        $updateData = ['id_tl' => $request->id_tl, 
                        'id_layanan' => $request->id_layanan, 
                        'jumlah' => $request->jumlah, 
                        'total' => $request->total]; //Memasukan nilai data update terbaru
        
        if($this->db->where('id_detail_tl',$id)->update($this->table, $updateData)) //Query Update dimana data nya yaitu $updateData
        {
            return ['msg'=>'Berhasil Update Transaksi layanan','error'=>false];
        }
            return ['msg'=>'Gagal Update Transaksi layanan','error'=>true];
    }
       
    public function destroy($id) { //Fungsi untuk Soft Delete

        if(empty($this->db->select('*')->where(array('id_detail_tl' => $id))->get($this->table)->row())) 
            return ['msg' => 'Id tidak ditemukan', 'error'=>true];

        // if($this->db->where('id_tl',$id)->update($this->table, $updateData)) //Query Update karena akan melakukan Soft Delete sehingga jangan delete melainkan update
        // {
        //     return ['msg'=>'Bisa Melanjutkan ke Pembayaran','error'=>false];
        // }
        //     return ['msg'=>'Gagal untuk lanjut ke pembayaran','error'=>true];

        if($this->db->delete($this->table, array('id_detail_tl'=> $id))){
            return ['msg' => 'Berhasil', 'error'=>false];
        }
        return ['msg' => 'Gagal', 'error'=>true];
    }

    public function getById($id)
    {
        $this->db->select('a.id_detail_tl, c.kode as "kode", a.id_tl, b.nama as "layanan", a.jumlah , a.total');
        $this->db->from('detail_tl a'); 
        $this->db->join('layanan b', 'id_layanan');
        $this->db->join('transaksi_layanan c', 'id_tl');
        $this->db->where('id_tl',$id);
        return $this->db->get()->result_array();
    }
    
}
?>