<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH .'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();

        $user_id = $this->session->userdata('user_id'); // Gantilah dengan cara Anda mendapatkan ID pengguna (user_id).
        $user_nip = $this->session->userdata('nip'); // Gantilah dengan cara Anda mendapatkan ID pengguna (user_id).
        $user_kode = $this->session->userdata('kode'); // Gantilah dengan cara Anda mendapatkan ID pengguna (user_id).
        if ($user_id == 1) {
            $data['buglist'] = $this->Admin_model->getAllbuglist();
        } else {
            $data['buglist'] = $this->Admin_model->getAllbuglist();
        }
        

        $kode = $data['user']['kode'];

        $data['log'] = [];
        $data['ready'] = [];
        $data['close'] = [];
        $openCount = 0;
        $readyCount = 0;
        $closeCount = 0;

        if (!empty($kode)) {
            $data['log'] = $this->Admin_model->getbuglistopenByKode($kode);
            $data['ready'] = $this->Admin_model->getBugListReadyByKode($kode);
            $data['close'] = $this->Admin_model->getbuglistcloseByKode($kode);

            $openCount = count($this->Admin_model->getbuglistopenByKode($kode));
            $readyCount = count($this->Admin_model->getBugListReadyByKode($kode));
            $closeCount = count($this->Admin_model->getbuglistcloseByKode($kode));
        }

        // Add counts to data array
        $data['openCount'] = $openCount;
        $data['readyCount'] = $readyCount;
        $data['closeCount'] = $closeCount;

        //count developer
        $data['developer']= $this->Logbook_model->getdeveloper($kode);
        $developerProgress = array();
        foreach ($data['developer'] as $developer) {
            $developerProgress[$developer->id] = 0;
        }
        $developerProgress['No pic'] = 0;

        foreach ($data['buglist'] as $bug) {
            if (isset($developerProgress[$bug->pic])) {
                $developerProgress[$bug->pic]++;
            } else {
                $developerProgress['No pic']++;
            }
        }

        $developerProgressByName = array();
        foreach ($developerProgress as $pic => $count) {
            $name = ($pic == 'No pic') ? 'No pic' : $this->db->get_where('tb_user', ['id' => $pic])->row()->name;
            $developerProgressByName[$name] = $count;
        }

        $data['developerProgress'] = $developerProgressByName;

        $bugCount = array();
        $total = 0;
        foreach ($data['developer'] as $developer) {
            $bugCount[$developer->name] = array(
                '1' => 0, // Open
                '3' => 0, // Close
                '2' => 0, // Ready to Test
                'Total' => 0
            );
        }
    
        foreach ($data['buglist'] as $bug) {
            foreach ($data['developer'] as $developer) {
                if ($developer->id == $bug->pic) {
                    switch ($bug->status) {
                        case '1':
                            $bugCount[$developer->name]['1']++;
                            break;
                        case '3':
                            $bugCount[$developer->name]['3']++;
                            break;
                        case '2':
                            $bugCount[$developer->name]['2']++;
                            break;
                    }
                    $bugCount[$developer->name]['Total']++;
                    $total++;
                }
            }
        }
    
        $data['bugCount'] = $bugCount;
        $data['total'] = $total;
    
        $data['unit'] = $this->User_model->getUnitbyNip();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }

    public function role()
    {
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('admin/role', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('user_role', ['role' => $this->input->post('role')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New role added!</div>');
            redirect('admin/role');
        }
    }

    public function hapus($id)
    {
        $this->Admin_model->hapusDataRole($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('admin/role');
    }

    public function edit_role($id)
    {
        $data['title'] = 'Edit Role';
        $data['user'] = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();
        $data['user_role'] = $this->Admin_model->getRoleById($id);

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('admin/edit_role', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Admin_model->editDataRole();
            $this->session->set_flashdata('flash', 'Diubah');
            redirect('admin/role');
        }
    }

    public function roleaccess($role_id)
    {
        $data['title'] = 'Role Access';
        $data['user'] = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer');
    }

    public function changeAccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Akses level berhasil diubah!</div>');
    }

    public function user()
    {
        $data['title'] = 'User';
        $data['user'] = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();

        $data['unit'] = $this->db->get('tb_unit')->result_array();
        $data['alluser'] = $this->User_model->getAllUser();

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[tb_user.username]|min_length[5]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('admin/user', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Admin_model->addUser();
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New user added!</div>');
            redirect('admin/user');
        }
    }

    public function hapus_user($id)
    {
        $this->Admin_model->hapusUser($id);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('admin/user');
    }

    public function unit()
    {
        $data['title'] = 'Unit';
        $data['user'] = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();

        $data['unit'] = $this->db->get('tb_unit')->result_array();

        $this->form_validation->set_rules('kode', 'Kode', 'required');
        $this->form_validation->set_rules('unit', 'Unit', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('admin/unit', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Admin_model->addUnit();
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New unit added!</div>');
            redirect('admin/unit');
        }
    }

    public function hapus_unit($id_unit)
    {
        $this->Admin_model->hapusUnit($id_unit);
        $this->session->set_flashdata('flash', 'Dihapus');
        redirect('admin/unit');
    }

    public function changeStatus($logbook_id)
    {
        // Mengambil data status yang dikirim melalui HTTP POST
        $new_status = $this->input->post('status', true);

        // Memastikan data status yang dikirim adalah valid
        if ($new_status === 'Open' || $new_status === 'Waiting Close') {
            // Memanggil model Logbook_model untuk melakukan pembaruan status
            $this->load->model('Logbook_model');
            $this->Logbook_model->changeStatus($logbook_id, $new_status);

            // Redirect atau tampilkan pesan sukses
            redirect('admin/datauser'); // Ganti 'datauser' dengan URL tujuan yang sesuai
        } else {
            // Status yang dikirim tidak valid, tampilkan pesan kesalahan atau sesuaikan dengan kebutuhan Anda
            echo "Status tidak valid.";
        }
    }
}
