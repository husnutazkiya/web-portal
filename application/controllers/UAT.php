<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH .'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class UAT extends CI_Controller
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
        $data['title'] = 'Closed Bug Developer';
        $data['user'] = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();

        $kode = $data['user']['kode'];

        $data['log'] = [];
        $data['ready'] = [];
        $data['closedev'] = [];
        $openCount = 0;
        $readyCount = 0;
        $closeCount = 0;

        if (!empty($kode)) {
            $data['log'] = $this->Logbook_model->getBugclosed($kode);
            $data['ready'] = $this->Admin_model->getBugListReadyByKode($kode);
            $data['closedev'] = $this->Admin_model->getbuglistcloseByKode($kode);
        }

        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('ClosedBUg/Closedbugdev', $data);
        $this->load->view('templates/footer');
    }


    public function closedbugUI()
    {
        $data['title'] = 'Closed Bug UI/UX';
        $data['user'] = $this->db->get_where('tb_user', ['username' => $this->session->userdata('username')])->row_array();

        $kode = $data['user']['kode'];

        $data['log'] = [];
        $data['closeui'] = [];
        if (!empty($kode)) {
            $data['log'] = $this->UI_model->getbuglistopenByKode($kode);
            $data['closeui'] = $this->UI_model->getbuglistcloseByKode($kode);
        }

        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('ClosedBUg/closedbugui', $data);
        $this->load->view('templates/footer');
    }

    public function export_excel() {
        $nip = $this->session->userdata('nip');
        $this->load->model('Logbook_model');
    
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'No.');
            $sheet->setCellValue('B1', 'Tanggal');
            $sheet->setCellValue('C1', 'Modul');
            $sheet->setCellValue('D1', 'Message');
            $sheet->setCellValue('E1', 'screenshoot');
            $sheet->setCellValue('F1', 'pic');
            $sheet->setCellValue('G1', 'severity');
    
            $row = 2;
            $no = 1; 
    
            foreach ($closedev as $log) {
                $sheet->setCellValue('A'.$row, $no);
                $sheet->setCellValue('B'.$row, $log->tanggal);
                $sheet->setCellValue('C'.$row, $log->modul);
                $sheet->setCellValue('D'.$row, $log->test_case);
                $sheet->setCellValue('E'.$row, $log->screenshoot);
                $sheet->setCellValue('F'.$row, $log->pic);
                $sheet->setCellValue('G'.$row, $log->severity);
                $row++;
                $no++;
            }
    
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="logbook.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit(); // Ensure script execution ends after saving
        } catch (Exception $e) {
            // Handle any exceptions here
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }


    public function export_excelUI() {
        $nip = $this->session->userdata('nip');
        $this->load->model('Logbook_model');
    
        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'No.');
            $sheet->setCellValue('B1', 'Tanggal');
            $sheet->setCellValue('C1', 'Modul');
            $sheet->setCellValue('D1', 'Message');
            $sheet->setCellValue('E1', 'screenshoot');
            $sheet->setCellValue('F1', 'pic');
            $sheet->setCellValue('G1', 'severity');
    
            $row = 2;
            $no = 1; 
    
            foreach ($closeui as $log) {
                $sheet->setCellValue('A'.$row, $no);
                $sheet->setCellValue('B'.$row, $log->tanggal);
                $sheet->setCellValue('C'.$row, $log->modul);
                $sheet->setCellValue('D'.$row, $log->test_case);
                $sheet->setCellValue('E'.$row, $log->screenshoot);
                $sheet->setCellValue('F'.$row, $log->pic);
                $sheet->setCellValue('G'.$row, $log->severity);
                $row++;
                $no++;
            }
    
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="logbook.xlsx"');
            header('Cache-Control: max-age=0');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit(); // Ensure script execution ends after saving
        } catch (Exception $e) {
            // Handle any exceptions here
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    
    
}
