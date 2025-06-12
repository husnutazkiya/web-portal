<?php

class UI_model extends CI_Model
{    
    public function hapus_checklist($id)
    {
        $this->db->delete('t_ui-ux', ['id' => $id]);
    }
    public function getBugUIByKode($kode)
    {
        $this->db->select('*');
        $this->db->from('t_ui-ux');
        $this->db->where('t_ui-ux.kode',$kode);
        $query = $this->db->get();
        return $query->result();
    }

    public function getBugListReadyByKode($kode)
    {
        $this->db->select('*');
        $this->db->from('t_ui-ux');
        $this->db->where('status', 'Ready to test');
        $this->db->where('kode', $kode);
        $query = $this->db->get();
        return $query->result();
    }

    public function getbuglistopenByKode($kode)
    {
        $this->db->select('*');
        $this->db->from('t_ui-ux');
        $this->db->where('status', 'Open');
        $this->db->where('kode', $kode);
        $query = $this->db->get();
        return $query->result();  
    }

    public function getbuglistcloseByKode($kode)
    {
        $this->db->select('*');
        $this->db->from('t_ui-ux');
        $this->db->where('status', 'Close');
        $this->db->where('kode', $kode);
        $query = $this->db->get();
        return $query->result();  
    }

    public function add_buglist($data)
    {
        return $this->db->insert('t_ui-ux', $data);
    }
}

