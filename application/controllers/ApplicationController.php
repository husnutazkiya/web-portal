<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH .'vendor/autoload.php';

class ApplicationController extends CI_Controller
{
    public function getOption(){
        $filter = $this->input->get('value');
        $kode = $this->input->get('kode');
        $option = "<option value='0'>Select</option>";

        switch($filter){
            case 'pic':
                $user_list = $this->Main_model->getUser($kode);
                foreach($user_list as $key => $value){
                    $option .="<option value='".$value->id."'>".$value->name."</option>";
                }
            break;

            case 'status':
                $status_list = $this->Main_model->getParamtersByTypeAndCode("APPLICATION" , "STATUS_BUG");

                foreach($status_list as $key => $value){
                    $option .="<option value='".$value->param_value."'>".$value->param_desc."</option>";
                }
            break;

            case 'severity':
                $severity_list = $this->Main_model->getParamtersByTypeAndCode("APPLICATION" , "SEVERITY_BUG");

                foreach($severity_list as $key => $value){
                    $option .="<option value='".$value->param_value."'>".$value->param_desc."</option>";
                }
            break;
        }
        echo json_encode([$option]);
        $this->load->view('templates/blank');

    }

}
