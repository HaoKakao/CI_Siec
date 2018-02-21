<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

	}

}

class Admin_Controller extends My_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model( 'Admin_model' );

		if ( logged_in() == true )
		{
			// 1 - grupa admina
			if ( !is_admin( 1 ) )
			{
				$this->session->set_flashdata( 'alert' , 'Nie masz dostępu do tej części serwisu.' );
				redirect( '' );
				exit();
			}
		}
		else
		{
			$this->session->set_flashdata( 'alert' , 'Nie jesteś zalogowany.' );
			redirect( 'account/login' );
		}



	}

}

class Site_Controller extends My_Controller {

    public function __construct()
    {
        parent::__construct();

		$this->load->model('Site_model' );

    }
}
/* End of file MY_Controller.php */
/* Location: ./application/controllers/MY_Controller.php */