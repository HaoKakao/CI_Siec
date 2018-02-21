<!DOCTYPE html>
<html>
<head>
	<title>ShopConnect</title>
	<link type="text/css" href="<?php echo base_url();?>style/styles.css" rel="stylesheet">
	<link type="text/css" href="<?php echo base_url();?>style/jquery-confirm.css" rel="stylesheet">
	<link type="text/css" href="<?php echo base_url();?>style/css/font-awesome.min.css" rel="stylesheet">
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.js"></script>
  	<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
  	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.jstepper.js"></script>
  	<script type="text/javascript" src="<?php echo base_url();?>js/jquery-confirm.js"></script>
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
			<div class="left_content">
				<div class="load_sk_towary">
					<div class="global_bar_title">
		  				<i class="fa fa-database" aria-hidden="true"></i> Lista Towarów
		  			</div>
		  			<div class="items_table">
		  				<div class="items_table_tr">
		  					<div class="items_table_td items_head">
		  					Towar
		  					</div>
		  					<div class="items_table_td items_head">
		  					Cena
		  					</div>
		  					<div class="items_table_td items_head">
		  					Kod towaru
		  					</div>
		  					<div class="items_table_td items_head">
		  					Opcje
		  					</div>

		  				</div>
		  				<?php foreach ( $sk_items as $sk_item ): ?>
		  				<div id="item_<?php echo $sk_item->id; ?>" class="items_table_tr">
		  					<div class="items_table_td items_towar">
		  						<?php echo $sk_item->item_name; ?>
		  					</div>
		  					<div class="items_table_td items_cena">
		  						<?php echo $sk_item->item_price; ?> zł
		  					</div>
		  					<div class="items_table_td items_ean">
		  						<?php echo $sk_item->item_ean; ?>
		  					</div>
		  					<div class="items_table_td items_opcje">
		  						<a class="sk_option_link" href="javascript:;" onclick="load_edit_item(<?php echo $sk_item->id; ?>);">Edytuj</a> | <a class="sk_option_link" href="javascript:;" onclick="remove_item(<?php echo $sk_item->id; ?>, '<?php echo $sk_item->item_name; ?>');">Usuń</a>
		  					</div>
		  				</div>
		  				<?php endforeach; ?>
		  			</div>
				</div>

			</div>


			<div class="sk_item_options">
				<div class="sk_item_add">

					<div class="global_bar_item_add">
	  					<i class="fa fa-plus-square-o" aria-hidden="true"></i> Dodaj Towar
	  				</div>
		  			<div class="shop_area">
						<label class="sk_dane_label">Nazwa towaru:</label> <input id="sk_ai_name" type="text" class="sk_input_dane" name="sk_ai_name" placeholder="Towar" value=""><br />
						<label class="sk_dane_label">Cena:</label> <input id="sk_ai_price" type="text" class="sk_input_dane" name="sk_ai_price" placeholder="Cena" value=""><br />
						<label class="sk_dane_label">Kod towaru:</label> <input id="sk_ai_ean" type="text" class="sk_input_dane" name="sk_ai_ean" placeholder="Kod EAN" value=""><br />
						<button type="button" class="look_button" onclick="add_new_item();">Dodaj towar</button><br />
		  			</div>
	  			</div>
				<div class="sk_item_edit" style="display: none;">

					<div class="global_bar_item_add">
	  					<i class="fa fa-pencil" aria-hidden="true"></i> Edytuj Towar
	  				</div>
		  			<div class="shop_area">
						<label class="sk_dane_label">Nazwa towaru:</label> <input id="sk_ei_name" type="text" class="sk_input_dane" name="sk_nazwa" placeholder="Towar" value=""><br />
						<label class="sk_dane_label">Cena:</label> <input id="sk_ei_price" type="text" class="sk_input_dane" name="sk_nazwa" placeholder="Cena" value=""><br />
						<label class="sk_dane_label">Kod towaru:</label> <input id="sk_ei_ean" type="text" class="sk_input_dane" name="sk_nazwa" placeholder="Kod EAN" value=""><br />
						<button id="edit_item_butt" type="button" class="look_button" onclick="edit_item();">Zakończ Edycje</button><br />
		  			</div>
	  			</div>
  			</div>

  		</div>
	</div>

</body>
<script>
var socket = io('http://155.133.46.41:3000');
var sk_id = "<?php get_sk_id(); ?>"

function add_new_item()
{

	var item_name = $( "#sk_ai_name" ).val();
	var item_price = $( "#sk_ai_price" ).val();
	var item_ean = $( "#sk_ai_ean" ).val();

	if(item_name.length === 0 || item_price.length === 0 || item_ean.length === 0)
	{
		$.alert({
	        theme: 'modern',
	        type: 'red',
	        icon: 'fa fa-exclamation-triangle',
	        closeIcon: true,
        	title: 'Błąd!',
        	titleClass: 'error_title',
        	content: 'Nie wprowadzaono wszystkich danych towaru.',
    	});
	}
	else
	{
		socket.emit('s_ai_items', { sklep_id: sk_id, name: item_name, price: item_price, ean: item_ean });
	}


}

function load_edit_item(item_id)
{
	socket.emit('load_edit_item', { sk_item_id: item_id});
}

function edit_item(item_id)
{
	var e_item_name = $( "#sk_ei_name" ).val();
	var e_item_price = $( "#sk_ei_price" ).val();
	var e_item_ean = $( "#sk_ei_ean" ).val();

	if(e_item_name.length === 0 || e_item_price.length === 0 || e_item_ean.length === 0)
	{
		$.alert({
	        theme: 'modern',
	        type: 'red',
	        icon: 'fa fa-exclamation-triangle',
	        closeIcon: true,
        	title: 'Błąd!',
        	titleClass: 'error_title',
        	content: 'Nie wprowadzaono wszystkich danych towaru.',
    	});
	}
	else
	{
		socket.emit('s_edit_item', { sk_item_id: item_id, name: e_item_name, price: e_item_price, ean: e_item_ean });
	}
	
}

function remove_item(item_id, item_name)
{
	$.confirm({
	theme: 'modern',
	icon: 'fa fa-question',
	type: 'orange',
	closeIcon: true,
    title: 'Czy na pewno chcesz usunąc ten towar?',
    content: '<i class="fa fa-cube" aria-hidden="true"></i> ' + item_name,
    buttons: {
        usun: {
            text: 'Usuń Towar',
            btnClass: 'btn-red',
            action: function(){
            	socket.emit('s_ri_items', { sk_item_id: item_id});
            }
        },
        zamknij: {
        	text: 'Anuluj',
        }
    }
	});
}

socket.on('r_load_edit_item', function (data) 
{
	if(data.success == true)
	{
		$('#sk_ei_name').val(data.item_name);
		$('#sk_ei_price').val(data.item_price);
		$('#sk_ei_ean').val(data.item_ean);
		$('#edit_item_butt').attr('onclick', 'edit_item(' + data.id + ');');
		$('.sk_item_edit').show('blind', 1000 );
	}
});

socket.on('r_edit_item', function (data) 
{
	if(data.success == true)
	{
		var sk_item_edit = '<div class="items_table_td items_towar">' + data.item_name + '</div><div class="items_table_td items_cena">' + data.item_price + ' zł</div><div class="items_table_td items_ean">' + data.item_ean + '</div><div class="items_table_td items_opcje"><a class="sk_option_link" href="javascript:;" onclick="load_edit_item(' + data.id + ');">Edytuj</a> | <a class="sk_option_link" href="javascript:;" onclick="remove_item(' + data.id + ', &#39;' + data.item_name + '&#39;);">Usuń</a></div>';
		$('#item_' + data.id).html(sk_item_edit);
		$('.sk_item_edit').hide('blind', 1000 );
		$('#sk_ei_name').val('');
	    $('#sk_ei_price').val('');
	    $('#sk_ei_ean').val('');
	    $.alert({
	        theme: 'modern',
	        type: 'green',
	        icon: 'fa fa-check',
	        closeIcon: true,
        	title: 'Pomyślnie edytowano.',
        	titleClass: 'succ_title',
        	content: '',
    	});
	}
});

socket.on('r_ai_items', function (data) 
{
	if(data.success == true)
	{
		var sk_item_add = '<div id="item_' + data.id + '" class="items_table_tr" style="display:none;"><div class="items_table_td items_towar">' + data.item_name + '</div><div class="items_table_td items_cena">' + data.item_price + ' zł</div><div class="items_table_td items_ean">' + data.item_ean + '</div><div class="items_table_td items_opcje"><a class="sk_option_link" href="javascript:;" onclick="load_edit_item(' + data.id + ');">Edytuj</a> | <a class="sk_option_link" href="javascript:;" onclick="remove_item(' + data.id + ', &#39;' + data.item_name + '&#39;);">Usuń</a></div></div>';
		$(sk_item_add).appendTo($('.items_table'));
		$('#item_' + data.id).show('highlight', 1000);
	    $('#sk_ai_name').val('');
	    $('#sk_ai_price').val('');
	    $('#sk_ai_ean').val('');
	    $.alert({
	        theme: 'modern',
	        type: 'green',
	        icon: 'fa fa-check',
	        closeIcon: true,
        	title: 'Dodano towar do bazy danych.',
        	titleClass: 'succ_title',
        	content: '<i class="fa fa-cube" aria-hidden="true"></i> ' + data.item_name + '<br /><i class="fa fa-money" aria-hidden="true"></i> ' + data.item_price + ' zł<br /><i class="fa fa-barcode" aria-hidden="true"></i> ' + data.item_ean,
    	});
	}
	else
	{
		$.alert({
	        theme: 'modern',
	        type: 'red',
	        icon: 'fa fa-exclamation-triangle',
	        closeIcon: true,
        	title: 'Błąd!',
        	titleClass: 'error_title',
        	content: 'Towar z kodem EAN: ' + data.item_ean + ' istnieje już w bazie danych.',
    	});
	}

});

socket.on('r_ri_items', function (data) 
{
	if(data.success == true)
	{
		$('#item_' + data.id).remove();
		$.alert({
	        theme: 'modern',
	        type: 'green',
	        icon: 'fa fa-check',
	        closeIcon: true,
	    	title: 'Pomyślnie usunięto towar.',
	    	titleClass: 'succ_title',
	    	content: '',
		});
	}
});

$(document).ready(function() 
  {
   $('#sk_ai_price').jStepper({maxDecimals:2,decimalSeparator:".",disableNonNumeric:true});
   $('#sk_ei_price').jStepper({maxDecimals:2,decimalSeparator:".",disableNonNumeric:true});
   
  });
</script>
</html>