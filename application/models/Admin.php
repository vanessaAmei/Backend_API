<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Model
{
    //fungsi cek session
    function logged_id()
    {
        return $this->session->userdata('user_id');
    }

    //fungsi check login
    function check_login($table, $field1, $field2)
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($field1);
        $this->db->where($field2);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return $query->result();
        }
    }

    function fetch_pass($session_id)
	{
            $fetch_pass=$this->db->query("select * from pegawai where id_pegawai='$session_id'");
            $res=$fetch_pass->result();
    }
    
    function change_pass($session_id,$new_pass)
    {
            $update_pass=$this->db->query("UPDATE pegawai SET password='$new_pass'  where id_pegawai='$session_id'");
	}
}