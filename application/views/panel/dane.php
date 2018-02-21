<!DOCTYPE html>
<html>
<head>
	<title>ShopConnect</title>
	<link type="text/css" href="<?php echo base_url();?>style/styles.css" rel="stylesheet">
	<link type="text/css" href="<?php echo base_url();?>style/css/font-awesome.min.css" rel="stylesheet">
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
	<script src="<?php echo base_url();?>js/socket.io.js"></script>

</head>

<body>
	<div class="global">
		<div class="head_bar">



		</div>
		<div class="left_bar">
			<div class="panel_panel">
				<div class="left_bar_title">
  				<i class="fa fa-bars" aria-hidden="true"></i> Panel Sklepowy
  			</div>
  			<a class="panel_button" href="javascript;"><i class="fa fa-home p_ico" aria-hidden="true"></i> Strona Główna</a>
  			<a class="panel_button"><i class="fa fa-list-alt p_ico" aria-hidden="true"></i> Dane Sklepu</a>
  			<a class="panel_button"><i class="fa fa-database p_ico" aria-hidden="true"></i> Towary</a>
  			<a class="panel_button"><i class="fa fa-balance-scale p_ico" aria-hidden="true"></i> Transakcje</a>
  			<a class="panel_button"><i class="fa fa-cogs p_ico" aria-hidden="true"></i> Ustawienia</a>
  			<a class="panel_button"><i class="fa fa-money p_ico" aria-hidden="true"></i> Rozliczenie</a>
  			<a class="panel_button p_b_end"><i class="fa fa-sign-out p_ico" aria-hidden="true"></i> Wyloguj</a>
			</div>
		</div>
		<div class="content">
			<div class="load_sk_dane">
				<div class="global_bar_title">
	  				<i class="fa fa-list-alt" aria-hidden="true"></i> Dane Sklepu
	  			</div>
	  			<label class="sk_dane_label">Nazwa firmy:</label> <input id="kl_name" type="text" class="sk_input_dane" name="sk_nazwa" placeholder="Nazwa Firmy" value="<?php if(!empty($sk_info->nazwa_firmy)){echo $sk_info->nazwa_firmy;}?>"><br />
	  			<label class="sk_dane_label">E-Mail:</label> <input id="kl_name" type="text" class="sk_input_dane" name="sk_mail" placeholder="Adres E-Mail" value="<?php if(!empty($sk_info->email)){echo $sk_info->email;}?>"><br />
	  			<label class="sk_dane_label">Telefon:</label> <input id="kl_name" type="text" class="sk_input_dane" name="sk_tel" placeholder="Telefon" value="<?php if(!empty($sk_info->telefon)){echo $sk_info->telefon;}?>"><br />
	  			<label class="sk_dane_label">Adres:</label> <input id="kl_name" type="text" class="sk_input_dane" name="sk_adres" placeholder="Adres" value="<?php if(!empty($sk_info->adres)){echo $sk_info->adres;}?>"><br />
	  			<label class="sk_dane_label">Miasto:</label> <input id="kl_name" type="text" class="sk_input_dane" name="sk_miasto" placeholder="Miasto" value="<?php if(!empty($sk_info->miasto)){echo $sk_info->miasto;}?>"><br />
	  			<label class="sk_dane_label">Kod Pocztowy:</label> <input id="kl_name" type="text" class="sk_input_dane" name="sk_kod_pocztowy" placeholder="Kod Pocztowy" value="<?php if(!empty($sk_info->kod_pocztowy)){echo $sk_info->kod_pocztowy;}?>"><br />
	  			<label class="sk_dane_label">Województwo:</label> <select name="sk_woje" class="sk_input_dane">
	  				<option></option>
					<option>dolnośląskie</option>
					<option>kujawsko-pomorskie</option>
					<option>lubelskie</option>
					<option>lubuskie</option>
					<option>łódzkie</option>
					<option>małopolskie</option>
					<option>mazowieckie</option>
					<option>opolskie</option>
					<option>podkarpackie</option>
					<option>podlaskie</option>
					<option>pomorskie</option>
					<option>śląskie</option>
					<option>świętokrzyskie</option>
					<option>warmińsko-mazurskie</option>
					<option>wielkopolskie</option>
					<option>zachodniopomorskie</option>
				</select><br />
	  			<label class="sk_dane_label">NIP:</label> <input id="kl_name" type="text" class="sk_input_dane" name="sk_nip" placeholder="NIP" value="<?php if(!empty($sk_info->nip)){echo $sk_info->nip;}?>"><br />
	  			<button type="button" class="look_button" onclick="sk_login();">Zapisz dane</button><br />
			</div>

  		</div>
	</div>

</body>
</html>