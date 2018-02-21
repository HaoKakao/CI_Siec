<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends Site_Controller {

public function sk_login()
{

	$this->load->view('sk_login');
}

public function sk_login_js()
	{

		

		if (!empty( $_POST ))
		{
			$login = $this->input->post('login' , true);
			$password = $this->input->post('password' , true);

			$where = array('login' => $login);
			$user = $this->Site_model->get_single('sk_users' , $where);

			if (!empty($user))
			{
				if (password_verify($password , $user->password) == 1)
				{
					// Czy użytkownik jest aktywny
					if ($user->active == 1)
					{

						$data_login = array(
							'id' => $user->id,
							'logged_in' => true);

						$arr['success'] = true;
						$this->session->set_userdata($data_login);
					}
					else
					{
						$arr['sk_off'] = true;
					}
				}
				else
				{
					$arr['badpass'] = true;
				}
			}
			else
			{
				$arr['badlogin'] = true;
			}
			echo json_encode($arr);
		}
	}
}
