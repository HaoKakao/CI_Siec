<?php

function refresh()
{
	$CI =& get_instance();
	return redirect( $CI->uri->uri_string() , 'refresh' );
}

function random_string()
{
	$string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	$randomString = '';
	for ( $i = 1; $i <= 100; $i++ )
	{
		$randomString .= $string[rand( 0 , strlen( $string ) - 1 )];
	}

	$time = time();

	$md5hash = md5( $time . $randomString );

	return $md5hash;
}

function logged_in()
{
	$CI =& get_instance();
	return $CI->session->userdata( 'logged_in' );
}

function get_sk_id()
{
	$CI =& get_instance();
	echo $CI->session->userdata( 'id' );
}
	
function admin()
{
	$CI =& get_instance();
	return $CI->session->userdata( 'admin' );
}

function super_admin()
{
	$CI =& get_instance();
	return $CI->session->userdata( 'is_super_admin' );
}

function get_hala()
{
	$CI =& get_instance();
	return $CI->session->userdata( 'hala' );
}

function karnet_in()
{
	$CI =& get_instance();
	return $CI->session->userdata( 'karnet_in' );
}

function alias( $str )
{
	$str = convert_accented_characters( $str );
	$str = url_title( $str , '-' , true );
	return $str;
}


function is_admin( $value )
{
	$CI =& get_instance();

	$user_id = $CI->session->userdata( 'id' );
	$where = array('id' => $user_id);
	$CI->load->model( 'Admin_model' );
	$user_is_admin = $CI->Admin_model->get_single( 'users' , $where );

	if ($user_is_admin->is_admin == 1)
	{
		return true;
	}
	else
	{
		return false;
	}

}

function notatka()
{
	$CI =& get_instance();

	$CI->load->model( 'Site_model' );
	$where = array('odczyt' => '0');
	$notka = $CI->Site_model->get( 'notatki' , $where );

	if (!empty($notka))
	{
		return true;
	}
	else
	{
		return false;
	}

}

function pracownik($value)
{
	$CI =& get_instance();

	$CI->load->model( 'Site_model' );
	$where = array('id' => $value);
	$prac = $CI->Site_model->get_single( 'users' , $where );

	if (!empty($prac))
	{
		echo $prac->imie. ' ' .$prac->nazwisko;
	}
	else
	{
		return false;
	}

}


function cenadolad($value)
{
	$CI =& get_instance();

	$CI->load->model( 'Site_model' );
	$where = array('id' => $value);
	$towar = $CI->Site_model->get_single( 'towar' , $where );

	if (!empty($towar))
	{
		echo $towar->cena. 'zł';
	}
	else
	{
		echo "---";
	}

}

function stats($year, $month, $day)
{
	$CI =& get_instance();

	$aktualnyrok = date("Y",time());
	$aktualnymiesiac = date("m",time());
	$aktualnydzien = date("d",time());

	$rok = $year;
	$miesiac = $month;
	$dzien = $day;


	if ($year == "aktualny")
	{
		$query = $CI->db->query("SELECT Sum(sum_ceny) AS suma FROM `transakcje_id` WHERE `data` LIKE '%" . $aktualnyrok . "-" . $miesiac . "%'");
		$chquery = $CI->db->query("SELECT Sum(sum_ceny) AS suma FROM `ch_transakcje_id` WHERE `data` LIKE '%" . $aktualnyrok . "-" . $miesiac . "%'");
	}
	else
	{
		$query = $CI->db->query("SELECT Sum(sum_ceny) AS suma FROM `transakcje_id` WHERE `data` LIKE '%" . $rok . "-" . $miesiac . "%'");
		$chquery = $CI->db->query("SELECT Sum(sum_ceny) AS suma FROM `ch_transakcje_id` WHERE `data` LIKE '%" . $rok . "-" . $miesiac . "%'");
	}
	$row = $query->row();
	$chrow = $chquery->row();
	if (!empty($row->suma))
	{
	        echo $row->suma+$chrow->suma;
	}
	else
	{
		echo "0";
	}

}


function statsdolad($typ, $rok, $miesiac)
{
	$CI =& get_instance();

	if($rok == 1)
	{
		$aktualnyrok = date("Y",time());
	}
	else
	{
		$aktualnyrok = $rok;
	}
	
	$aktualnymiesiac = date("m",time());
	$aktualnydzien = date("d",time());

		$query = $CI->db->query("select tem.zakup, count(*) AS iloo from(select zakup from transakcje WHERE `data` LIKE '%" . $aktualnyrok . "%' AND id_tow <= 10 union all select zakup from ch_transakcje WHERE `data` LIKE '%" . $aktualnyrok . "%' AND id_tow <= 10) as tem group by zakup ORDER BY iloo DESC");


		if ($query->num_rows() > 0) {
		foreach($query->result() as $row) {
		    $data[$row->zakup] = $row->iloo;
		    }
		}
		else
		{
			$data["brak"] = 0;
		}

	    $ustr = array('\u0104','\u0106','\u0118','\u0141','\u0143','\u00d3','\u015a','\u0179','\u017b','\u0105','\u0107','\u0119','\u0142','\u0144','\u00f3','\u015b','\u017a','\u017c');
	    $plstr = array('Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','ą','ć','ę','ł','ń','ó','ś','ź','ż');
	     
	    $json = str_replace($ustr,$plstr,json_encode($data));

	     echo $json;

}

function statstow($typ, $rok, $miesiac)
{
	$CI =& get_instance();

		if($rok == 1)
	{
		$aktualnyrok = date("Y",time());
	}
	else
	{
		$aktualnyrok = $rok;
	}
	
	$aktualnymiesiac = date("m",time());
	$aktualnydzien = date("d",time());




	if($typ == 1)
	{

		$query = $CI->db->query("SELECT zakup, count(*) AS iloo FROM transakcje WHERE `data` LIKE '%" . $aktualnyrok . "%' AND id_tow > 10 GROUP BY zakup ORDER BY iloo DESC LIMIT 10");


		if ($query->num_rows() > 0) {
		foreach($query->result() as $row) {
		    $data[$row->zakup] = $row->iloo;
		    }
		}
				else
		{
			$data["brak"] = 0;
		}

	    $ustr = array('\u0104','\u0106','\u0118','\u0141','\u0143','\u00d3','\u015a','\u0179','\u017b','\u0105','\u0107','\u0119','\u0142','\u0144','\u00f3','\u015b','\u017a','\u017c');
	    $plstr = array('Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','ą','ć','ę','ł','ń','ó','ś','ź','ż');
	     
	    $json = str_replace($ustr,$plstr,json_encode($data));

	     echo $json;
     }
     else
     {
     	$query = $CI->db->query("SELECT zakup, count(*) AS iloo FROM transakcje WHERE `data` LIKE '%" . $rok . " = " . $miesiac . "%' AND id_tow > 10 GROUP BY zakup ORDER BY iloo DESC ");

     	if ($query->num_rows() > 0) {
		foreach($query->result() as $row) {
		    $data[$row->zakup] = $row->iloo;
		    }
		}
				else
		{
			$data["brak"] = 0;
		}

	    $ustr = array('\u0104','\u0106','\u0118','\u0141','\u0143','\u00d3','\u015a','\u0179','\u017b','\u0105','\u0107','\u0119','\u0142','\u0144','\u00f3','\u015b','\u017a','\u017c');
	    $plstr = array('Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','ą','ć','ę','ł','ń','ó','ś','ź','ż');
	     
	    $json = str_replace($ustr,$plstr,json_encode($data));

	     echo $json;
     }



}



function config($value)
{
	$CI =& get_instance();

	$CI->load->model( 'Admin_model' );
	$where = array('id' => 1);
	$prac = $CI->Site_model->get_single( 'config' , $where );

	if (!empty($prac))
	{
		echo $prac->$value;
	}
	else
	{
		return false;
	}

}

function chconfig($value)
{
	$CI =& get_instance();
	
	$CI->load->model( 'Admin_model' );
	$where = array('id' => 1);
	$prac = $CI->Site_model->get_single( 'ch_config' , $where );

	if (!empty($prac))
	{
		echo $prac->$value;
	}
	else
	{
		return false;
	}

}

function conf_karta()
{
	$CI =& get_instance();
	$aktualnadata = date("Y-m-d",time());
	$CI->load->model( 'Admin_model' );
	$where = array('id' => 1);
	$query = $CI->db->query("SELECT Sum(sum_ceny) AS suma FROM `transakcje_id` WHERE `zaplata` = 2 AND `data` LIKE '%" . $aktualnadata . "%'");
	
	$row = $query->row();
	if (!empty($row->suma))
	{
	        echo $row->suma;
	}
	else
	{
		echo "0";
	}
}

function ch_conf_karta()
{
	$CI =& get_instance();
	$aktualnadata = date("Y-m-d",time());
	$CI->load->model( 'Admin_model' );
	$where = array('id' => 1);
	$query = $CI->db->query("SELECT Sum(sum_ceny) AS suma FROM `ch_transakcje_id` WHERE `zaplata` = 2 AND `data` LIKE '%" . $aktualnadata . "%'");
	
	$row = $query->row();
	if (!empty($row->suma))
	{
	        echo $row->suma;
	}
	else
	{
		echo "0";
	}
}

function count_notatka()
{
	$CI =& get_instance();

	$CI->load->model( 'Admin_model' );
	$where = array('odczyt' => '0');
	$notka = $CI->Admin_model->get( 'notatki' , $where );

	if (!empty($notka))
	{
		echo count($notka);
	}
	else
	{
		return false;
	}

}

function check_group( $group_id )
{
	$CI =& get_instance();

	$user_id = $CI->session->userdata( 'id' );

	$where = array(
		'user_id' => $user_id,
		'group_id' => $group_id
	);
	$CI->load->model( 'Admin_model' );
	$user_is_admin = $CI->Admin_model->get_single( 'users_groups' , $where );

	if ( empty( $user_is_admin ) )
	{
		return false;
	}
	else
	{
		return true;
	}