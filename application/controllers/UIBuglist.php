<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH .'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UIBuglist extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('UI_model');
    } 

    public function index()
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
    
}
