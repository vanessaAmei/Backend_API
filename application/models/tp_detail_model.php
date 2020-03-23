<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class tp_detail_model extends CI_Model
{
    private $table = 'detail_tp'; 

    public $id_detail_tp;
    public $id_tp;
    public $id_produk;
    public $jumlah;
    public $total; 
    
    
    public $rule = [
        [
            'field' => 'id_tp',
            'label' => 'id_tp',
            'rules' => 'required'
        ],

        [
            'field' => 'id_produk',
            'label' => 'id_produk',
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

        $this->db->select('a.id_detail_tp, a.id_tp, b.nama as "produk", a.jumlah , a.total');
        $this->db->from('detail_tp a'); 
        $this->db->join('produk b', 'id_produk');
        return $this->db->get()->result_array();

        // $this->db->select('*');
        // $this->db->from('detail_tp');
        // return $this->db->get()->result_array();

    }

    public function store($request) {   //Fungsi untuk menyimpan data
        $this->id_tp = $request->id_tp;   //Gunakan $Request untuk mengambil data yang diinputkan oleh user
        $this->id_produk = $request->id_produk;
        $this->jumlah = $request->jumlah;
        $this->total = $request->total;
      //Mengambil nilai date dari local sesuai format, jadi untuk format ini menggunakan Timestamp
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Menambahkan Transaksi Produk','error'=>false];
        }
            return ['msg'=>'Gagal Menambahkan Transaksi Produk','error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data

        $this->updated_at = date("Y-m-d H:i:s"); 
        $updateData = ['id_tp' => $request->id_tp, 
                        'id_produk' => $request->id_produk, 
                        'jumlah' => $request->jumlah, 
                        'total' => $request->total]; //Memasukan nilai data update terbaru
        
        if($this->db->where('id_detail_tp',$id)->update($this->table, $updateData)) //Query Update dimana data nya yaitu $updateData
        {
            return ['msg'=>'Berhasil Update Transaksi Produk','error'=>false];
        }
            return ['msg'=>'Gagal Update Transaksi Produk','error'=>true];
    }
       
    public function destroy($id) { //Fungsi untuk Soft Delete

        if(empty($this->db->select('*')->where(array('id_tp' => $id))->get($this->table)->row())) 
            return ['msg' => 'Id tidak ditemukan', 'error'=>true];

        // if($this->db->where('id_tp',$id)->update($this->table, $updateData)) //Query Update karena akan melakukan Soft Delete sehingga jangan delete melainkan update
        // {
        //     return ['msg'=>'Bisa Melanjutkan ke Pembayaran','error'=>false];
        // }
        //     return ['msg'=>'Gagal untuk lanjut ke pembayaran','error'=>true];

        if($this->db->delete($this->table, array('id_detail_tp'=> $id))){
            return ['msg' => 'Berhasil', 'error'=>false];
        }
        return ['msg' => 'Gagal', 'error'=>true];
    }

    public function getById($id)
    {
        return $this->db->get_where($this->_table, ["id_tp" => $id])->row();
    }
    
}
?>