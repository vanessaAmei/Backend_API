<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class customer_model extends CI_Model
{
    private $table = 'customer'; 

    public $id_customer;
    public $nama;
    public $tgl_lahir;
    public $alamat;
    public $no_telp;
    public $created_at;
    public $updated_at;
    public $deleted_at; 
    public $created_by;
    public $updated_by;
    public $deleted_by; 
    
    
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
            'field' => 'alamat',
            'label' => 'alamat',
            'rules' => 'required'
        ],

        [
            'field' => 'no_telp',
            'label' => 'no_telp',
            'rules' => 'required|numeric|max_length[13]'
        ]
    ];
    public function Rules() { return $this->rule; } //Fungsi untuk return nilai rule dimana untuk di cek

    public function getAll() {
        $this->db->select('*');
        $this->db->from('customer');
        $this->db->where('deleted_at IS NULL');
        return $this->db->get()->result_array();
        // return $this->db->get()->result();
    }

    public function getAllData() {
        $this->db->select('*');
        $this->db->from('customer');
        return $this->db->get()->result_array();
        // return $this->db->get()->result();
    }

    public function store($request) {   //Fungsi untuk menyimpan data
        $this->nama = $request->nama;   //Gunakan $Request untuk mengambil data yang diinputkan oleh user
        $this->tgl_lahir = $request->tgl_lahir;
        $this->alamat = $request->alamat;
        $this->no_telp = $request->no_telp;
        $this->created_at = date("Y-m-d H:i:s"); //Mengambil nilai date dari local sesuai format, jadi untuk format ini menggunakan Timestamp
        // $this->created_at = date("Y-m-d"); Yang ini menggunakan format Date
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Input Customer','error'=>false];
        }
            return ['msg'=>'Gagal Input Customer','error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data

        $this->updated_at = date("Y-m-d H:i:s"); 
        $updateData = ['nama' => $request->nama , 'tgl_lahir' => $request->tgl_lahir, 'alamat' => $request->alamat, 'no_telp' => $request->no_telp,
        'updated_at' => $this->updated_at]; //Memasukan nilai data update terbaru

        if($this->db->where('id_customer',$id)->update($this->table, $updateData)) //Query Update dimana data nya yaitu $updateData
        {
            return ['msg'=>'Berhasil Update Customer','error'=>false];
        }
            return ['msg'=>'Gagal Update Customer','error'=>true];
    }
       
    public function destroy($id) { //Fungsi untuk Soft Delete
 
        $this->deleted_at = date("Y-m-d H:i:s");
        $updateData = ['deleted_at' => $this->deleted_at];

        if($this->db->where('id_customer',$id)->update($this->table, $updateData)) //Query Update karena akan melakukan Soft Delete sehingga jangan delete melainkan update
        {
            return ['msg'=>'Berhasil Hapus Customer','error'=>false];
        }
            return ['msg'=>'Gagal Hapus Customer','error'=>true];
    }
}
?>