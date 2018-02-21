<!DOCTYPE html>
<html>
<head>
	<title>ShopConnect</title>
	<link type="text/css" href="<?php echo base_url();?>style/styles.css" rel="stylesheet">
	<link type="text/css" href="<?php echo base_url();?>style/css/font-awesome.min.css" rel="stylesheet">
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.min.js"></script>

</head>

<body>
  <div class="l_box">

  	<div class="l_title">
  		<i class="fa fa-sign-in" aria-hidden="true"></i> Logowanie do Systemu
  	</div>

  	<div class="l_inputs">

  		<div class="login_box">
	  		<span class="sp_login"><i class="fa fa-user-o log_ico" aria-hidden="true"></i></span>
	  		<input id="shop_login" type="text" class="i_s_login" name="shop_login" placeholder="Login">
	  	</div>
  		<div class="login_box l_bottom">
	  		<span class="sp_login"><i class="fa fa-key log_ico" aria-hidden="true"></i></span>
	  		<input id="shop_password" type="password" class="i_s_login" name="shop_password" placeholder="Hasło">
	  	</div>

	  	<button type="button" class="login_button" onclick="sk_login();">Zaloguj</button>
  	</div>

  	<div class="get_password">
  	<i class="fa fa-leaf" aria-hidden="true"></i> Przypomnij hasło
  	</div>

  </div>
  <div class="admin_login">
  	<i class="fa fa-cubes" aria-hidden="true"></i> Panel Administratora
  </div>
</body>



<script>
$(function() {
    $(".l_inputs").click(function(e) {
      	if ($(".l_error").length > 0 || $(".ui-effects-placeholder").length > 0) 
      	{
      		$(".l_error").hide("fade", 1000);
      		$(".ui-effects-placeholder").hide("fade", 1000);
      	}
    });
})
function sk_login()
{
	s_login = $( "#shop_login" ).val();
	s_pass = $( "#shop_password" ).val();
  $.ajax(
  {
    method: "POST",
    url: "<?php echo base_url();?>account/sk_login_js",
    data: {login: s_login, password: s_pass},
    dataType: "json",
    cache : false,
    success: function(data)
    {
      if(data.success == true)
      {
        window.location.href = "<?php echo base_url();?>connectshopxyz";
      }
      if(data.sk_off == true)
      {
        if ($(".l_error").length > 0 || $(".ui-effects-placeholder").length > 0) 
      	{
      		$( ".l_error" ).remove();
      		$( ".ui-effects-placeholder" ).remove();
      	}
      		$( "<div class='l_error'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Konto zostało dezaktywowane!</div>" ).appendTo( "body" );
          $( ".l_error" ).show( "bounce", 1000 );
      }
      if(data.badlogin == true)
      {
      	if ($(".l_error").length > 0 || $(".ui-effects-placeholder").length > 0) 
      	{
      		$( ".l_error" ).remove();
      		$( ".ui-effects-placeholder" ).remove();
      	}
      		$( "<div class='l_error'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Niepoprawny login!</div>" ).appendTo( "body" );
          $( ".l_error" ).show( "bounce", 1000 );
      }
      if(data.badpass == true)
      {
      	if ($(".l_error").length > 0 || $(".ui-effects-placeholder").length > 0) 
      	{
      		$( ".l_error" ).remove();
      		$( ".ui-effects-placeholder" ).remove();
      	}
      		$( "<div class='l_error'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i> Niepoprawne hasło!</div>" ).appendTo( "body" );
          $( ".l_error" ).show( "bounce", 1000 );
      }

    }
  });
}

</script>
</html>