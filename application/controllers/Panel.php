<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Panel extends Site_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		logged_in() == true || redirect( 'account/sk_login' );
		$this->load->view('connectshopxyz');
	}

	public function dane()
	{
		logged_in() == true || redirect( 'account/sk_login' );

		$sk_id = $this->session->userdata('id');
		$where = array('sk_id' => $sk_id);
		$data['sk_info'] = $this->Site_model->get_single('sk_info', $where);


		$this->load->view('panel/dane', $data);
	}

	public function towary()
	{
		logged_in() == true || redirect( 'account/sk_login' );




		$sk_id = $this->session->userdata('id');
		$where = array('sk_id' => $sk_id);
		$data['sk_items'] = $this->Site_model->get('sk_items', $where);


		$this->load->view('panel/towary', $data);
	}


}
