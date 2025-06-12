<?php

class Admin_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllbuglist()
    {
        $this->db->select('b.*, u.name as pic_name');
        $this->db->from('t_buglist b');
        $this->db->join('tb_user u', 'b.pic = u.id', 'left');
        return $this->db->get()->result();

    }


    public function getBugListReadyByKode($kode)
    {
        $this->db->select('*');
        $this->db->from('t_buglist');
        $this->db->where('status', '2');
        $this->db->where('kode', $kode);
        $query = $this->db->get();
        return $query->result();
    }
    

    public function getbuglistopenByKode($kode)
    {
        $this->db->select('*');
        $this->db->from('t_buglist');
        $this->db->where('status', '1');
        $this->db->where('kode', $kode);
        $query = $this->db->get();
        return $query->result();  
    }

    public function getbuglistcloseByKode($kode)
    {
        $this->db->select('*');
        $this->db->from('t_buglist');
        $this->db->where('status', '3');
        $this->db->where('kode', $kode);
        $query = $this->db->get();
        return $query->result();  
    }

    public function getAllRole()
    {
        return $this->db->get('user_role')->result_array();
    }

    public function getRoleById($id)
    {
        return $this->db->get_where('user_role', ['id' => $id])->row_array();
    }

    public function hapusDataRole($id)
    {
        $this->db->delete('user_role', ['id' => $id]);
    }

    public function editDataRole()
    {
        $data = [
            "role" => $this->input->post('role', true)
        ];
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('user_role', $data);
    }

    public function addUser()
    {
        $base_url = base_url();
        $data = [
            "name" => $this->input->post('name', true),
            "username" => $this->input->post('username', true),
            "password" => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'image' => 'default.jpg',
            "jabatan" => $this->input->post('jabatan', true),
            "kode" => $this->input->post('kode', true),
            "role_id" => $this->input->post('role', true),
        ];
        $this->db->where('id', $this->input->post('id'));
        $this->db->insert('tb_user', $data);
    }

    public function hapusUser($id)
    {
        $data['gambar'] = $this->db->get_where('tb_user', ['id' => $id])->row_array();
        $image = $data['gambar']['qr_code'];
        unlink(FCPATH . 'assets/qrcode/' . $image);
        $this->db->delete('tb_user', ['id' => $id]);
    }

    public function addUnit()
    {
        $data = [
            "kode" => $this->input->post('kode', true),
            "unit" => $this->input->post('unit', true),
        ];
        $this->db->where('id_unit', $this->input->post('id_unit'));
        $this->db->insert('tb_unit', $data);
    }

    public function hapusUnit($id_unit)
    {
        $this->db->delete('tb_unit', ['id_unit' => $id_unit]);
    }

}
