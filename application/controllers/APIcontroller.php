<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class APIcontroller extends RestController {

	public function index_get()
	{
		$response = $this->Logbook_model->getBug();

		$this->response($response);
	}


	public function addBuglist_post()
	{
		$data = array(
            'kode' => $this->post('kode'),
			'tanggal' => $this->post('tanggal'),
            'modul' => $this->post('modul'),
			'test_step' => $this->post('test_step'),
            'test_case' => $this->post('test_case'),
            'status' => $this->post('status')
        );

        $response = $this->Logbook_model->addBuglist($data);
	}

}
