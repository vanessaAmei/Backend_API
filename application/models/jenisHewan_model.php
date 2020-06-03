<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class jenisHewan_model extends CI_Model
{
    private $table = 'jenis_hewan'; 

    public $id_jenis_hewan;
    public $nama;
    public $created_at;
    public $updated_at;
    public $deleted_at; 

    
    public $rule = [
        [
            'field' => 'nama',
            'label' => 'nama',
            'rules' => 'required|alpha'
        ]
    ];
    public function Rules() { return $this->rule; } //Fungsi untuk return nilai rule dimana untuk di cek

    public function getAll() {
        $this->db->select('*');
        $this->db->from('jenis_hewan');
        $this->db->where('deleted_at IS NULL');
        return $this->db->get()->result_array();
        // return $this->db->get()->result();

    }

    public function getAllData() {
        $this->db->select('*');
        $this->db->from('jenis_hewan');
        return $this->db->get()->result_array();
        // return $this->db->get()->result();

    }
    public function store($request) {   //Fungsi untuk menyimpan data
        $this->nama = $request->nama;   //Gunakan $Request untuk mengambil data yang diinputkan oleh user
        $this->created_at = date("Y-m-d H:i:s"); //Mengambil nilai date dari local sesuai format, jadi untuk format ini menggunakan Timestamp
        // $this->created_at = date("Y-m-d"); Yang ini menggunakan format Date
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Input Jenis Hewan','error'=>false];
        }
            return ['msg'=>'Gagal Input Jenis Hewan','error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data

        $this->updated_at = date("Y-m-d H:i:s"); 
        $updateData = ['nama' => $request->nama , 'updated_at' => $this->updated_at]; //Memasukan nilai data update terbaru

        if($this->db->where('id_jenis_hewan',$id)->update($this->table, $updateData)) //Query Update dimana data nya yaitu $updateData
        {
            return ['msg'=>'Berhasil Update Jenis Hewan','error'=>false];
        }
            return ['msg'=>'Gagal Update Jenis Hewan','error'=>true];
    }
       
    public function destroy($id) { //Fungsi untuk Soft Delete
 
        $this->deleted_at = date("Y-m-d H:i:s");
        $updateData = ['deleted_at' => $this->deleted_at];

        if($this->db->where('id_jenis_hewan',$id)->update($this->table, $updateData)) //Query Update karena akan melakukan Soft Delete sehingga jangan delete melainkan update
        {
            return ['msg'=>'Berhasil Hapus Jenis Hewan','error'=>false];
        }
            return ['msg'=>'Gagal Hapus Jenis Hewan','error'=>true];
    }

    public function getById($id)
    {
        return $this->db->get_where($this->_table, ["id_jenis_hewan" => $id])->row();
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