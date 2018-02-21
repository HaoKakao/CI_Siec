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
			<div class="look_kl">
				 <div class="left_bar_title">
  				<i class="fa fa-search" aria-hidden="true"></i> Wyszukaj klienta
  			</div>
  			<input id="kl_nr" type="text" class="sk_input mar_look_top10 mar_look_bot5" name="kl_nr" placeholder="Numer karty"><br />
				<input id="kl_name" type="text" class="sk_input mar_look_bot5" name="kl_name" placeholder="Imię"><br />
				<input id="kl_surename" type="text" class="sk_input mar_look_bot10" name="kl_surename" placeholder="Nazwisko"><br />
				<button type="button" class="look_button" onclick="sk_login();">Szukaj</button>
			</div>
			<div class="panel">
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
			<div class="load_kl">

				<div class="global_bar_title">
  				
  			</div>

  			<div class="kl_info">
	  			<div class="kl_name">
	  				
	  			</div>
	  			<div class="kl_mail">
	  				
	  			</div>
	  			<div class="kl_points">
	  			</div>
  			</div>
			</div>

			<div class="kl_shop">

				<div class="global_bar_title_shop">
  				<i class="fa fa-shopping-basket" aria-hidden="true"></i> Koszyk zakupów
  			</div>
  			<div class="shop_area">
		  			<div class="shop_item">
		  				<div class="sh_ico head_basket"><i class="fa fa-shopping-cart" aria-hidden="true"></i></div>
		  				<div class="sh_product head_basket">Produkt</div>
		  				<div class="sh_trade_points head_basket"><i class="fa fa-star" aria-hidden="true"></i></div>
		  				<div class="sh_price head_basket">Cena</div>
		  				<div class="sh_option head_basket"><i class="fa fa-cog" aria-hidden="true"></i></div>
		  			</div>
		  			<div class="shop_item">
		  				<div class="sh_ico item_basket"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
		  				<div class="sh_product item_basket">Woda Mineralna</div>
		  				<div class="sh_trade_points item_basket"><input type="checkbox"></div>
		  				<div class="sh_price item_basket">2zł</div>
		  				<div class="sh_option item_basket"><i class="fa fa-times" aria-hidden="true"></i></div>
		  			</div>
		  			<div class="shop_item">
		  				<div class="sh_ico item_basket"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
		  				<div class="sh_product item_basket">Woda Mineralna</div>
		  				<div class="sh_trade_points item_basket"><input id="ch_points" type="checkbox"></div>
		  				<div class="sh_price item_basket">2zł</div>
		  				<div class="sh_option item_basket"><i class="fa fa-times" aria-hidden="true"></i></div>
		  			</div>
		  			<div class="shop_item">
		  				<div class="sh_ico item_basket"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
		  				<div class="sh_product item_basket">Woda Mineralna</div>
		  				<div class="sh_trade_points item_basket"><input type="checkbox"></div>
		  				<div class="sh_price item_basket">2zł</div>
		  				<div class="sh_option item_basket"><i class="fa fa-times" aria-hidden="true"></i></div>
		  			</div>
  			</div>
  				</div>


  			</div>


			</div>



		</div>



	</div>

</body>
  

<script>
var socket = io('http://155.133.46.41:3000');

var karta_id = "";
var towarcode="";
var item_id_b = "";


///// SKANOWANIE KARTY
$(document).ready(function() 
{
  var barcode="";
  $(document).keydown(function(e) 
  {
    var code = (e.keyCode ? e.keyCode : e.which);
    if(code==13 && barcode.length==8)// odpowiada za skanowanie karnetów
      {
        karta_id = barcode;
       	socket.emit('s_load_client', { idkarty: karta_id });

        barcode=""; // po zeskanowaniu resetuje zmienną
      }
      else
      {
        barcode=barcode+String.fromCharCode(code);
        if (barcode.length < 8 && barcode.length <= 1)
        {
          $timerkwyes = setTimeout(function() // funkcja po 2 sec czyści zmienna, potrzebne aby skaner działał poprawnie
          {
            barcode="";
          }, 2000);
        }
      }
  });            
});

$("#ch_points").click(function(e) {
	if($("#ch_points").is(':checked'))
	{
   alert("clicked on flash_holder");
	}
});

var sk_id = "<?php get_sk_id(); ?>"
console.log(sk_id);

///// SKANOWANIE TOWARU
$(document).ready(function() 
{
  var towcode="";
  $(document).keydown(function(e) 
  {
    var tcode = (e.keyCode ? e.keyCode : e.which);
    if(tcode==13 && towcode.length==13)// odpowiada za skanowanie towaru
      {
        towarcode = towcode;
        socket.emit('s_load_item', { item_ean: towarcode, sklep_id: sk_id });
        towcode=""; // po zeskanowaniu resetuje zmienną
      }
      else
      {
        towcode=towcode+String.fromCharCode(tcode);
        if (towcode.length < 13 && towcode.length <= 1)
        {
          $timerkwyess = setTimeout(function() // funkcja po 2 sec czyści zmienna, potrzebne aby skaner działał poprawnie
          {
            towcode="";
          }, 5000);
        }
      }
  });   

});

function remove_item(id)
{
	socket.emit('s_remove_item', { list_item: id });
}


socket.on('r_load_client', function (data) 
{
  $('.global_bar_title').html('<i class="fa fa-id-badge" aria-hidden="true"></i> ' + data.id_karty);
	$('.kl_name').html('<i class="fa fa-user-circle-o" aria-hidden="true"></i> ' + data.imie + ' ' + data.nazwisko);
	$('.kl_mail').html('<i class="fa fa-envelope-o" aria-hidden="true"></i> ' + data.mail);
	$('.kl_points').html('<i class="fa fa-star" aria-hidden="true"></i> ' + data.punkty + ' pkt');
	$( ".load_kl" ).show( "blind", 1000 );
});

socket.on('r_load_item', function (data) 
{
	item_id_b++;
	var item_p_unable = "disabled";
	if(data.item_p_unable == 1)
	{
		item_p_unable = ""
	}
	var item_add = '<div id="item_' + item_id_b + '" class="shop_item"><div class="sh_ico item_basket"><i class="fa fa-chevron-right" aria-hidden="true"></i></div><div class="sh_product item_basket">'+ data.item_name +'</div><div class="sh_trade_points item_basket"><input type="checkbox" ' + item_p_unable + '></div><div class="sh_price item_basket">'+ data.item_price +' zł</div><div class="sh_option item_basket"><i onclick="remove_item(' + item_id_b + ');" class="fa fa-times" aria-hidden="true"></i></div></div>';
	$(item_add).appendTo($(".shop_area"));

});

socket.on('r_remove_item', function (data) 
{
	$( '#item_'+ data.item_list_id ).remove();
});




  
</script>
</html>