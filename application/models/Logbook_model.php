<?php

class Logbook_model extends CI_Model
{   
    public function addBuglist($data){
        return $this->db->insert('t_buglist', $data);
    }

    public function hapus_buglist($id){
        $this->db->delete('t_buglist', ['id' => $id]);
    }
        
    public function getBugByKode($kode){
        $this->db->select('*');
        $this->db->from('t_buglist');
        $this->db->where('t_buglist.kode', $kode);
        $query = $this->db->get();
        return $query->result();
    }

    public function getBug(){
        $this->db->select('*');
        $this->db->from('t_buglist');
        $query = $this->db->get();
        return $query->result();
    }

    public function getBugtabel($kode){
        // $this->db->select('*');
        // $this->db->from('t_buglist');
        // $this->db->where('t_buglist.kode', $kode);
        // $this->db->where_not_in('t_buglist.status', ['3']);

        $query = "SELECT tb.* , st.param_desc as status_desc 
            , sv.param_desc as severity_desc
            , tu.name  as user_fullname
            FROM t_buglist tb 
            LEFT JOIN bug_parameters st ON tb.status = st.param_value AND  st.param_type = 'APPLICATION' AND  st.param_code = 'STATUS_BUG' AND  st.is_active = '1'
            LEFT JOIN bug_parameters sv ON tb.severity = sv.param_value AND  sv.param_type = 'APPLICATION' AND  sv.param_code = 'SEVERITY_BUG' AND  sv.is_active = '1'
            LEFT JOIN tb_user tu ON tb.pic = tu.id
            WHERE tb.kode = '$kode'
        ";

        
        $data = $this->db->query($query);
        return $data->result();
    }

    public function getBugclosed($kode){
        $this->db->select('*');
        $this->db->from('t_buglist');
        $this->db->where('t_buglist.kode', $kode);
        $this->db->where_in('t_buglist.status', ['Closed']);
        $query = $this->db->get();
        return $query->result();
    }

    public function getBuglistById($id) //update buglist
    {
        return $this->db->get_where('t_buglist', ['id' => $id])->row_array();
        return $query->result();
    }

    public function editBuglist($id, $data){
        $this->db->where('id', $id);
        $this->db->update('t_buglist', $data);
    }

    public function getdeveloper($kode){
        $query = $this->db
            ->select('id, name')
            ->where('kode', $kode)
            ->get('tb_user'); // Adjust table name to 'tb_user'

        return $query->result();
    }   

    public function QueryFilter($param){
        $query = $param["query"];
        $kode = $param["kode"];

        $query_arr = explode("#" , $query);
        $query = "SELECT tb.* , st.param_desc as status_desc 
                    , sv.param_desc as severity_desc
                    , tu.name  as user_fullname
                    FROM t_buglist tb 
                    LEFT JOIN bug_parameters st ON tb.status = st.param_value AND  st.param_type = 'APPLICATION' AND  st.param_code = 'STATUS_BUG' AND  st.is_active = '1'
                    LEFT JOIN bug_parameters sv ON tb.severity = sv.param_value AND  sv.param_type = 'APPLICATION' AND  sv.param_code = 'SEVERITY_BUG' AND  sv.is_active = '1'
                    LEFT JOIN tb_user tu ON tb.pic = tu.id
                    WHERE tb.kode = '$kode'";

        switch($query_arr[0]){
            case "fixed_text":
                $query .=" AND tb.$query_arr[1] = '$query_arr[2]'";
            break;

            case "free_text":
                $query .=" AND tb.$query_arr[1] like '%$query_arr[2]%'";
            break;
        }
        $data = $this->db->query($query);
        return $data->result();

    }
}