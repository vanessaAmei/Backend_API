<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class layanan_model extends CI_Model
{
    private $table = 'layanan'; 

    public $id_layanan;
    public $nama;
    public $id_ukuran_hewan;
    public $harga;
    public $created_at;
    public $updated_at;
    public $deleted_at; 

    
    public $rule = [
        [
            'field' => 'nama',
            'label' => 'nama',
            'rules' => 'required'
        ],
        [
            'field' => 'harga',
            'label' => 'harga',
            'rules' => 'required'
        ],
        [
            'field' => 'id_ukuran_hewan',
            'label' => 'id_ukuran_hewan',
            'rules' => 'required'
        ]
    ];
    public function Rules() { return $this->rule; } //Fungsi untuk return nilai rule dimana untuk di cek

    public function getAll() {
        $this->db->select('a.id_layanan as "id_layanan", a.nama as "layanan", c.nama as "ukuran" , a.harga as "harga"');
        $this->db->from('layanan a');
        $this->db->join('ukuran_hewan c', 'id_ukuran_hewan');
        $this->db->where('a.deleted_at IS NULL');        
        return $this->db->get()->result_array();
    }

    public function getAllData() {
        $this->db->select('a.id_layanan as "id_layanan", a.nama as "layanan", c.nama as "ukuran" , a.harga as "harga", a.created_at as "created_at", a.updated_at as "updated_at", a.deleted_at as "deleted_at"');
        $this->db->from('layanan a');
        $this->db->join('ukuran_hewan c', 'id_ukuran_hewan');     
        return $this->db->get()->result_array();
    }

    public function getLayanan($id_ukuran_hewan) {
        $this->db->select('a.id_layanan as "id_layanan", a.nama as "layanan", c.nama as "ukuran" , a.harga as "harga"');
        $this->db->from('layanan a');
        $this->db->join('ukuran_hewan c', 'id_ukuran_hewan');
        $this->db->where('a.deleted_at IS NULL');
        $this->db->where('a.id_ukuran_hewan', $id_ukuran_hewan);        
        return $this->db->get()->result_array();
    }

    public function store($request) {   //Fungsi untuk menyimpan data
        $this->nama = $request->nama;
        $this->harga = $request->harga;
        $this->id_ukuran_hewan = $request->id_ukuran_hewan;
        $this->created_at = date("Y-m-d H:i:s"); //Mengambil nilai date dari local sesuai format, jadi untuk format ini menggunakan Timestamp
        // $this->created_at = date("Y-m-d"); Yang ini menggunakan format Date
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Input Layanan','error'=>false];
        }
            return ['msg'=>'Gagal Input Layanan','error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data

        $this->updated_at = date("Y-m-d H:i:s"); 
        $updateData = ['nama' => $request->nama ,'harga' => $request->harga, 
        'id_ukuran_hewan' => $request->id_ukuran_hewan, 
        'updated_at' => $this->updated_at]; //Memasukan nilai data update terbaru

        if($this->db->where('id_layanan',$id)->update($this->table, $updateData)) //Query Update dimana data nya yaitu $updateData
        {
            return ['msg'=>'Berhasil Update Layanan','error'=>false];
        }
            return ['msg'=>'Gagal Update Layanan','error'=>true];
    }
       
    public function destroy($id) { //Fungsi untuk Soft Delete
 
        $this->deleted_at = date("Y-m-d H:i:s");
        $updateData = ['deleted_at' => $this->deleted_at];

        if($this->db->where('id_layanan',$id)->update($this->table, $updateData)) //Query Update karena akan melakukan Soft Delete sehingga jangan delete melainkan update
        {
            return ['msg'=>'Berhasil Hapus Layanan','error'=>false];
        }
            return ['msg'=>'Gagal Hapus Layanan','error'=>true];
    }

    public function getById($id)
    {
        $this->db->select('*');
        $this->db->from('layanan'); 
        $this->db->where('id_layanan',$id);
        return $this->db->get()->result_array();
    }

    // public function destroy($id)  //Fungsi Untuk Delete
    // {
    //     if (empty($this->db->select('*')->where(array('id' => $id))->get($this->table)->row())) return ['msg' => 'Id tidak ditemukan', 'error' => true];

    //     if ($this->db->delete($this->table, array('id' => $id))) {
    //         return ['msg' => 'Berhasil', 'error' => false];
    //     }
    //     return ['msg' => 'Gagal', 'error' => true];
    // }
   
}
?>