<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class pegawai_model extends CI_Model
{
    private $table = 'pegawai'; 

    public $id_pegawai;
    public $nama;
    public $tgl_lahir;
    public $alamat;
    public $peran;
    public $no_telp;
    public $username;
    public $password;
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
            'field' => 'peran',
            'label' => 'peran',
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
        ],

        [
            'field' => 'username',
            'label' => 'username',
            'rules' => 'required'
        ],

        [
            'field' => 'password',
            'label' => 'password',
            'rules' => 'required'
        ]
    ];
    public function Rules() { return $this->rule; } //Fungsi untuk return nilai rule dimana untuk di cek

    public function getAll() {
        $this->db->select('*');
        $this->db->from('pegawai');
        $this->db->where('deleted_at IS NULL');
        return $this->db->get()->result_array();
        // return $this->db->get()->result();

    }

    public function store($request) {   //Fungsi untuk menyimpan data
        $this->nama = $request->nama;   //Gunakan $Request untuk mengambil data yang diinputkan oleh user
        $this->tgl_lahir = $request->tgl_lahir;
        $this->peran = $request->peran;
        $this->alamat = $request->alamat;
        $this->no_telp = $request->no_telp;
        $this->username = $request->username;
        $this->password = password_hash($request->password, PASSWORD_BCRYPT);
        $this->created_at = date("Y-m-d H:i:s"); //Mengambil nilai date dari local sesuai format, jadi untuk format ini menggunakan Timestamp
        // $this->created_at = date("Y-m-d"); Yang ini menggunakan format Date
        if($this->db->insert($this->table, $this))
        {
            return ['msg'=>'Berhasil Input Pegawai','error'=>false];
        }
            return ['msg'=>'Gagal Input Pegawai','error'=>true];
    }

    public function update($request,$id) { //Fungsi untuk update data

        $this->updated_at = date("Y-m-d H:i:s"); 
        $updateData = ['nama' => $request->nama , 'tgl_lahir' => $request->tgl_lahir, 'peran' => $request->peran, 'alamat' => $request->alamat, 'no_telp' => $request->no_telp,
        'username' => $request->username, 'password' => $request->password, 'updated_at' => $this->updated_at]; //Memasukan nilai data update terbaru

        if($this->db->where('id_pegawai',$id)->update($this->table, $updateData)) //Query Update dimana data nya yaitu $updateData
        {
            return ['msg'=>'Berhasil Update Pegawai','error'=>false];
        }
            return ['msg'=>'Gagal Update Pegawai','error'=>true];
    }
       
    public function destroy($id) { //Fungsi untuk Soft Delete
 
        $this->deleted_at = date("Y-m-d H:i:s");
        $updateData = ['deleted_at' => $this->deleted_at];

        if($this->db->where('id_pegawai',$id)->update($this->table, $updateData)) //Query Update karena akan melakukan Soft Delete sehingga jangan delete melainkan update
        {
            return ['msg'=>'Berhasil Hapus Pegawai','error'=>false];
        }
            return ['msg'=>'Gagal Hapus Pegawai','error'=>true];
    }

    public function getById($id)
    {
        return $this->db->get_where($this->_table, ["id_pegawai" => $id])->row();
    }

    public function doLogin(){
		$post = $this->input->post();

        // cari user berdasarkan email dan username
        $this->db->where('username', $post["username"]);
                // ->or_where('username', $post["email"]);
        $pegawai = $this->db->get($this->_table)->row();

        // jika user terdaftar
        if($pegawai){
            // periksa password-nya
            $isPasswordTrue = password_verify($post["password"], $pegawai->password);
            // periksa role-nya
            $isAdmin = $pegawai->peran == "Kasir" || $pegawai->peran == "Owner" || $pegawai->peran == "Customer Service";

            // jika password benar dan dia admin
            if($isPasswordTrue && $isAdmin){ 
                // login sukses yay!
                $this->session->set_userdata(['user_logged' => $pegawai]);
                $this->_updateLastLogin($pegawai->id_pegawai);
                return true;
            }
        }
        
        // login gagal
		return false;
    }

    public function isNotLogin(){
        return $this->session->userdata('user_logged') === null;
    }

    // private function _updateLastLogin($id_pegawai){
    //     $sql = "UPDATE {$this->_table} SET last_login=now() WHERE user_id={$user_id}";
    //     $this->db->query($sql);
    // }

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