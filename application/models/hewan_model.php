<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class hewan_model extends CI_Model
{
    private $table = 'hewan'; 

    public $id_hewan;
    public $id_ukuran_hewan;
    public $id_jenis_hewan;
    public $tgl_lahir;
    public $created_at;
    public $updated_at;
    public $deleted_at; 

    
    public $rule = [
        [
            'field' => 'nama',
            'label' => 'nama',
            'rules' => 'required|alpha'
        ],

        [
            'field' => 'tgl_lahir',
            'label' => 'tgl_lahir',
            'rules' => 'required'
        ],

        [
            'field' => 'id_jenis_hewan',
            'label' => 'id_jenis_hewan',
            'rules' => 'required'
        ],

        [
            'field' => 'id_ukuran_hewan',
            'label' => 'id_ukuran_hewan',
            'rules' => 'required'
        ],

        [
            'field' => 'id_customer',
            'label' => 'id_customer',
            'rules' => 'required'
        ]
    ];
    public function Rules() { return $this->rule; } //Fungsi untuk return nilai rule dimana untuk di cek

    public function getAll()
    {
        $this->db->select('h.id_hewan as "id_hewan", h.nama as "nama", h.tgl_lahir as "tgl_lahir", jh.nama as "jenis_hewan", uh.id_ukuran_hewan as "id_ukuran_hewan", uh.nama as "ukuran_hewan", c.nama as "customer"');
        $this->db->from('hewan h');
        $this->db->join('jenis_hewan jh', 'id_jenis_hewan');
        $this->db->join('ukuran_hewan uh', 'id_ukuran_hewan');
        $this->db->join('customer c', 'id_customer');
        $this->db->where('h.deleted_at IS NULL');
        return $query = $this->db->get()->result_array(); 
    }

    public function store($request) {   //Fungsi untuk menyimpan data
        $this->nama = $request->nama;   //Gunakan $Request untuk mengambil data yang diinputkan oleh user
        $this->tgl_lahir = $request->tgl_lahir;
        $this->id_jenis_hewan = $request->id_jenis_hewan;
        $this->id_ukuran_hewan = $request->id_ukuran_hewan;
        $this->id_customer = $request->id_customer;
        $this->created_at = date("Y-m-d H:i:s"); //Mengambil nilai date dari local sesuai format, jadi untuk format ini menggunakan Timestamp
        // $this->created_at = date("Y-m-d"); Yang ini menggunakan format Date
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Input Hewan','error'=>false];
        }
            return ['msg'=>'Gagal Input Hewan','error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data

        $this->updated_at = date("Y-m-d H:i:s"); 
        $updateData = ['nama' => $request->nama , 'tgl_lahir' => $request->tgl_lahir, 'id_ukuran_hewan' => $request->id_ukuran_hewan, 'id_jenis_hewan' => $request->id_jenis_hewan,
        'id_customer' => $request->id_customer, 'updated_at' => $this->updated_at]; //Memasukan nilai data update terbaru

        if($this->db->where('id_hewan',$id)->update($this->table, $updateData)) //Query Update dimana data nya yaitu $updateData
        {
            return ['msg'=>'Berhasil Update Hewan','error'=>false];
        }
            return ['msg'=>'Gagal Update Hewan','error'=>true];
    }
       
    public function destroy($id) { //Fungsi untuk Soft Delete
 
        $this->deleted_at = date("Y-m-d H:i:s");
        $updateData = ['deleted_at' => $this->deleted_at];

        if($this->db->where('id_hewan',$id)->update($this->table, $updateData)) //Query Update karena akan melakukan Soft Delete sehingga jangan delete melainkan update
        {
            return ['msg'=>'Berhasil Hapus Hewan','error'=>false];
        }
            return ['msg'=>'Gagal Hapus Hewan','error'=>true];
    }

    public function getById($id)
    {
        $this->db->select('a.nama as "nama", a.no_telp as "no_telp"');
        $this->db->from('customer a'); 
        $this->db->join('hewan b', 'id_customer');
        $this->db->where('id_hewan',$id);
        return $this->db->get()->result_array();
    }
   
}
?>