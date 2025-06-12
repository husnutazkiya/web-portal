<?php

class Main_model extends CI_Model
{
    public function getFitlerSearch($url){
        $query = "SELECT fs.*
            FROM mapping_filter ft
            LEFT JOIN user_sub_menu mn ON ft.menu_id = mn.id 
            LEFT JOIN filter_search fs ON ft.filter_id = fs.id 
            WHERE mn.url = '$url'";

        return $this->db->query($query)->result();
    }

    public function getParamtersByType($type){
        $query = "SELECT * 
            FROM bug_parameters
            WHERE param_type = '$type' ";

        return $this->db->query($query)->result();
    }

    public function getParamtersByCode($code){
        $query = "SELECT * 
            FROM bug_parameters
            WHERE param_code = '$code' ";

        return $this->db->query($query)->result();
    }

    public function getParamtersByTypeAndCode($type , $code){
        $query = "SELECT * 
            FROM bug_parameters
            WHERE param_type = '$type'
            AND param_code = '$code' ";
        
        return $this->db->query($query)->result();
    }

    public function getUser($code){
        $query = "SELECT * 
            FROM tb_user
            WHERE kode = '$code'";
        
        return $this->db->query($query)->result();
        
    }
}
