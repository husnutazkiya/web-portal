<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH .'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Fitur extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('UI_model');
    } 

    // buglist dev
    public function index()
    {
        $uri_string = uri_string();
        $data['filter_search'] = $this->Main_model->getFitlerSearch($uri_string);
        
        $data['title'] = 'Bug List';
        $data['user'] = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();

        $kode = $data['user']['kode'];

        $data["kode"] = $kode;
        if ($data['user']['role_id'] == 1) {
            $data['buglist'] = $this->Logbook_model->getBugtabel($kode);
        } else {
            $data['buglist'] = $this->Logbook_model->getBugtabel($kode);
        }

        $data['developer']= $this->Logbook_model->getdeveloper($kode);
        $developerBadgeClasses = array();
        $colors = ['danger', 'warning', 'info', 'secondary', 'primary', 'success', 'dark'];
        $colorIndex = 0;

        foreach ($data['developer'] as $developer) {
            $developerBadgeClasses[$developer->name] = 'badge badge-pill badge-' . $colors[$colorIndex % count($colors)];
            $colorIndex++;
        }
        $developerBadgeClasses['No pic'] = 'badge badge-pill badge-secondary';

        $data['developerBadgeClasses'] = $developerBadgeClasses;

        // Calculate progress for each developer
        $developerProgress = array();
        foreach ($data['developer'] as $developer) {
            $developerProgress[$developer->name] = 0;
        }
        $developerProgress['No pic'] = 0;

        foreach ($data['buglist'] as $bug) {
            if (isset($developerProgress[$bug->pic])) {
                $developerProgress[$bug->pic]++;
            } else {
                $developerProgress['No pic']++;
            }
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('buglist/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function hapus_buglist($id)
    {
        $this->Logbook_model->hapus_buglist($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('fitur');
    }

    public function edit_buglist($id)
    {
        $data['title'] = 'Update Bug List';
        $data['user'] = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();

        $data['buglist'] = $this->Logbook_model->getBuglistById($id);
        $data['liststatus'] = [
            '1' => 'Open',
            '2' => 'Ready to Test',
            '3' => 'Close'
        ];

        $kode = $data['user']['kode'];
        $developers = $this->Logbook_model->getdeveloper($kode);
        $listpic = [];
            foreach ($developers as $developer) {
                $listpic[$developer->id] = $developer->name; 
            }
            
            if (!in_array('No pic', $listpic)) {
                $listpic[] = 'No pic';
            }
        $data['listpic'] = $listpic;
        
        $data['listseverity'] = [
            '1' => 'Low',
            '2' => 'Medium',
            '3' => 'High'
        ];

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('modul', 'Modul', 'required');
        $this->form_validation->set_rules('test_case', 'Test', 'required');
        $this->form_validation->set_rules('judul', 'Judul', 'required');
        $this->form_validation->set_rules('ket', 'Keterangan', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('buglist/edit_buglist', $data);
            $this->load->view('templates/footer');
        }
    }

    public function changeBook(){
        $id = $this->input->post('id', true); 
        $buglist = $this->Logbook_model->getBuglistById($id);

        $old_image = $buglist['screenshoot'];

        $upload_image = $_FILES['attachment']['name'];

        if ($upload_image) {
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '2048'; // Ukuran maksimum file (2MB)
            $config['upload_path'] = './assets/lampiran/';
            $config['file_name'] = uniqid();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('attachment')) {
                if ($old_image && $old_image != $upload_image && file_exists(FCPATH . 'assets/lampiran/' . $old_image)) {
                    unlink(FCPATH . 'assets/lampiran/' . $old_image);
                }
                $new_image = $this->upload->data();
                $screenshoot = $new_image['file_name'];

                if ($old_image != $screenshoot) {
                    $this->db->set('screenshoot', $screenshoot);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
                redirect('fitur/edit_buglist/' . $id); 
            }
        }

        $logbook_data = [
            'tanggal' => $this->input->post('tanggal'),
            'modul' => $this->input->post('modul'),
            'test_case' => $this->input->post('test_case'),
            'test_step' => $this->input->post('test_step'),
            'status' => $this->input->post('status'),
            'qa_note' => $this->input->post('qa_note'),
            'pic' => $this->input->post('pic'),
            'dev_note' => $this->input->post('dev_note'),
            'severity' => $this->input->post('severity')
        ];

        $this->Logbook_model->editBuglist($id, $logbook_data); 
        $this->session->set_flashdata('flash', 'Diubah');
        redirect('fitur');
    }

    public function add_buglist(){
        $data['title'] = 'Add Bug List Entry';
        $data['user'] = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $upload_config['upload_path'] = './assets/lampiran/';
            $upload_config['allowed_types'] = 'gif|jpg|png|jpeg';
            $upload_config['max_size'] = '10000';
            $upload_config['file_name'] = uniqid();

            $this->load->library('upload', $upload_config);

            try {
                if (!$this->upload->do_upload('attachment')) {
                    throw new Exception($this->upload->display_errors());
                }

                $upload_data = $this->upload->data();
                $screenshoot = $upload_data['file_name'];

                $logbook_data = [
                    'tanggal' => $this->input->post('tanggal'),
                    'kode' => $this->input->post('kode'),
                    'modul' => $this->input->post('modul'),
                    'test_case' => $this->input->post('test_case'),
                    'test_step' => $this->input->post('test_step'),
                    'screenshoot' => $screenshoot,
                    'status' => $this->input->post('status'),
                    'qa_note' => $this->input->post('qa_note'),
                    'dev_note' => $this->input->post('dev_note'),
                    'pic' => $this->input->post('pic'),
                    'dev_pic' => $this->input->post('dev_pic'),
                    'severity' => $this->input->post('severity')
                ];

                if (!$this->Logbook_model->addBuglist($logbook_data)) {
                    throw new Exception('Gagal menyimpan data ke database.');
                }

                // Set flashdata
                $this->session->set_flashdata('flash', 'Ditambah');

                // Redirect ke halaman lain jika perlu
                redirect('fitur', 'refresh');
            } catch (Exception $e) {
                // Penanganan kesalahan, bisa berupa pesan error atau tindakan lain sesuai kebutuhan
                $this->session->set_flashdata('flash', 'Gagal menambahkan data: ' . $e->getMessage());
                redirect('fitur', 'refresh');
            }
        }
    }


    public function UIbuglist()
    {
        $data['title'] = 'UI UX Buglist';
        $user = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();
        $kode = $user['kode'];
        $data['developer']= $this->Logbook_model->getdeveloper($kode);
        
        $data['username'] = isset($user['username']) ? $user['username'] : '';
    
        if ($user['role_id'] == 1) {
            $data['buglist'] = $this->UI_model->getBugUIByKode($kode);
        } else {
            $data['buglist'] = $this->UI_model->getBugUIByKode($kode);
        }
    
        $data['user'] = $user;
    
        // Load view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('ui-buglist/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function add_UIbuglist(){
        $data['title'] = 'Add Bug List Entry';
        $data['user'] = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $upload_config['upload_path'] = './assets/lampiran/';
            $upload_config['allowed_types'] = 'gif|jpg|png|jpeg';
            $upload_config['max_size'] = '10000';
            $upload_config['file_name'] = uniqid(); 

            $this->load->library('upload', $upload_config);

            try {
                if (!$this->upload->do_upload('attachment')) {
                    throw new Exception($this->upload->display_errors());
                }

                $upload_data = $this->upload->data();
                $screenshoot = $upload_data['file_name'];

                $logbook_data = [
                    'tanggal' => $this->input->post('tanggal'),
                    'kode' => $this->input->post('kode'),
                    'modul' => $this->input->post('modul'),
                    'message' => $this->input->post('message'),
                    'test_step' => $this->input->post('test_step'),
                    'pic' => $this->input->post('pic'),
                    'screenshoot' => $screenshoot,
                    'status' => $this->input->post('status'),
                    'qa_note' => $this->input->post('qa_note'),
                    'dev_note' => $this->input->post('dev_note'),
                    'severity' => $this->input->post('severity')
                ];

                if (!$this->Logbook_model->addBuglist($logbook_data)) {
                    throw new Exception('Gagal menyimpan data ke database.');
                }

                // Set flashdata
                $this->session->set_flashdata('flash', 'Ditambah');

                // Redirect ke halaman lain jika perlu
                redirect('fitur', 'refresh');
            } catch (Exception $e) {
                // Penanganan kesalahan, bisa berupa pesan error atau tindakan lain sesuai kebutuhan
                $this->session->set_flashdata('flash', 'Gagal menambahkan data: ' . $e->getMessage());
                redirect('fitur', 'refresh');
            }
        }
    }

    public function QueryFilter(){
        $query = $this->input->get('query');
        $kode = $this->input->get('kode');

        $param = array(
            "query" => $query,
            "kode" => $kode
        );
        $data["buglist"] = $this->Logbook_model->QueryFilter($param);

        $this->load->view('buglist/render_list' , $data);
    }
    
}
