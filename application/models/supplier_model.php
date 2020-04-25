<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class supplier_model extends CI_Model
{
    private $table = 'supplier'; 

    public $id_supplier;
    public $nama;
    public $no_telp;
    public $alamat;
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
        $this->db->from('supplier');
        $this->db->where('deleted_at IS NULL');
        return $this->db->get()->result_array();
        // return $this->db->get()->result();

    }

    public function getById($id)
    {
        $this->db->select('*');
        $this->db->from('supplier');
        $this->db->where('id_supplier', $id);
        return $this->db->get()->result_array();
    }

    public function store($request) {   //Fungsi untuk menyimpan data
        $this->nama = $request->nama;   //Gunakan $Request untuk mengambil data yang diinputkan oleh user
        $this->alamat = $request->alamat;
        $this->no_telp = $request->no_telp;
        $this->created_at = date("Y-m-d H:i:s"); //Mengambil nilai date dari local sesuai format, jadi untuk format ini menggunakan Timestamp
        // $this->created_at = date("Y-m-d"); Yang ini menggunakan format Date
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Input Supplier','error'=>false];
        }
            return ['msg'=>'Gagal Input Supplier','error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data

        $this->updated_at = date("Y-m-d H:i:s"); 
        $updateData = ['nama' => $request->nama ,'alamat' => $request->alamat, 'no_telp' => $request->no_telp,
        'updated_at' => $this->updated_at]; //Memasukan nilai data update terbaru

        if($this->db->where('id_supplier',$id)->update($this->table, $updateData)) //Query Update dimana data nya yaitu $updateData
        {
            return ['msg'=>'Berhasil Update Supplier','error'=>false];
        }
            return ['msg'=>'Gagal Update Supplier','error'=>true];
    }
       
    public function destroy($id) { //Fungsi untuk Soft Delete
 
        $this->deleted_at = date("Y-m-d H:i:s");
        $updateData = ['deleted_at' => $this->deleted_at];

        if($this->db->where('id_supplier',$id)->update($this->table, $updateData)) //Query Update karena akan melakukan Soft Delete sehingga jangan delete melainkan update
        {
            return ['msg'=>'Berhasil Hapus Supplier','error'=>false];
        }
            return ['msg'=>'Gagal Hapus Supplier','error'=>true];
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