var express = require('express');
var app = express();
var mysql = require('mysql')
var server = require('http').createServer(app);
var io = require('socket.io').listen(server);

app.set('port', process.env.PORT || 3000);

var db_config =
{
    host: '*********',
    user: '*********',
    database: '*********',
	password: '*********'
};

var db;

function handleDisconnect() {
  db = mysql.createConnection(db_config);

  db.connect(function(err) {
    if(err) {
      console.log('error when connecting to db:', err);
      setTimeout(handleDisconnect, 2000);
    }
  });

  db.on('error', function(err) {
    console.log('db error', err);
    if(err.code === 'PROTOCOL_CONNECTION_LOST') {
      handleDisconnect();
    } else {
      throw err;                            
    }
  });
}

handleDisconnect(); // Potrzebne aby nie przerywalo polączonia z mysql

var clientsid = {};
var clientssid = {};

function GenerateSessionKey() 
{
    var length = 13,
        charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    return retVal;
}

function addZero(i) 
{
	if (i < 10) 
	{
		i = "0" + i;
	}
	return i;
}

var removeByAttr = function(arr, attr, value){
    var i = arr.length;
    while(i--){
       if( arr[i] 
           && arr[i].hasOwnProperty(attr) 
           && (arguments.length > 2 && arr[i][attr] === value ) ){ 

           arr.splice(i,1);

       }
    }
    return arr;
}

io.on("connection", function(socket)
{
	console.info('Connected (sid=' + socket.id + ').');
	var currentUser;
	var currentUsersid;
	var itemInfo = {};
	var clientInfo = {};
	var items = [];
	var items_p = [];
	var item_arr;
	var kosz_item_id = 0;

	var koszyk = {firstName:"John", lastName:"Doe", age:50, eyeColor:"blue"};

 	socket.emit('login', { hello: 'world' });

////------------------------------------------------------------
////******* LADOWANIE KLIENTA *******////
 	socket.on("s_load_client", function(data)
	{
		db.query('SELECT * FROM `kl_users` WHERE `card_id` = ?',data.idkarty , function(err, rows, fields)
		{
			if(rows.length != 0)
			{
				clientInfo["id_karty"] = rows[0].card_id;
				clientInfo["imie"] = rows[0].imie;
				clientInfo["nazwisko"] = rows[0].nazwisko;
				clientInfo["punkty"] = rows[0].punkty;
				clientInfo["mail"] = rows[0].email;
				socket.emit("r_load_client", clientInfo);
			}
		});
	});

////******* KONIEC LADOWANIE KLIENTA *******////
////------------------------------------------------------------
////******* KOSZYK *******////
 	socket.on("s_load_item", function(data)
	{
		 //// !!! spawdzenie czy jest zaladowany klient
		db.query('SELECT * FROM `sk_items` WHERE `sk_id` = ? AND `item_ean` = ?',[data.sklep_id, data.item_ean] , function(err, rows, fields)
		{
			if(rows.length != 0)
			{
				kosz_item_id++;
				itemInfo["item_div_id"] = kosz_item_id;
				itemInfo["total_price"] = 0;
				itemInfo["item_name"] = rows[0].item_name;
				itemInfo["id"] = rows[0].id;
				itemInfo["item_price"] = rows[0].item_price;
				itemInfo["item_points"] = itemInfo["item_price"];

				if(parseFloat(clientInfo["punkty"]) >= parseFloat(itemInfo["item_points"]))
				{
					itemInfo["item_p_unable"] = "1";
				}
				else
				{
					itemInfo["item_p_unable"] = "0";
				}

				item_arr = {item_id: itemInfo["id"], item_div: itemInfo["item_div_id"], item_name: itemInfo["item_name"], item_price: itemInfo["item_price"], item_points: itemInfo["item_points"], item_p_unable: itemInfo["item_p_unable"]};

				items.push(item_arr);
				console.log(items);
				console.log(clientInfo["punkty"]);

				socket.emit("r_load_item", itemInfo);
			}
		});
	});

 	socket.on("s_remove_item", function(data)
	{			
				var item_success = {};
				item_success["total_price"] = 0;
				removeByAttr(items, 'item_div', data.list_item); 

				item_success["item_list_id"] = data.list_item;
				console.log(items);

				for  (i = 0; i < items.length; i++)
				{
					if(items.length > 0)
					{   
						item_success["total_price"] += parseFloat(items[i]["item_price"]);
					}
					else
					{
						item_success["total_price"] = 0;
					}
					
				}
				console.log(item_success["total_price"]);

				socket.emit("r_remove_item", item_success);
	});

////******* KONIEC KOSZYK *******////
////------------------------------------------------------------
////******* EDYCJA TOWARU *******////
 	socket.on("s_ai_items", function(data)
	{			
		var sk_items_add = {};
		db.query('SELECT * FROM `sk_items` WHERE `item_ean` = ?',data.ean , function(err, rows, fields)
		{
			if(rows.length != 0)
			{
				sk_items_add["success"] = false;
	    		sk_items_add["item_ean"] = rows[0].item_ean;
	    		socket.emit("r_ai_items", sk_items_add);
    		}
    		else
    		{
				db.query('INSERT INTO `ShopConnect`.`sk_items` (`sk_id`, `item_name`, `item_price`, `item_ean`) VALUES (?, ?, ?, ?)',[data.sklep_id, data.name, data.price, data.ean] , function(err, result)
				{
					if (err) throw err;
						sk_items_add["success"] = true;
						sk_items_add["id"] = result.insertId;
		    			sk_items_add["sk_id"] = data.sklep_id;
		    			sk_items_add["item_name"] = data.name;
		    			sk_items_add["item_price"] = data.price;
		    			sk_items_add["item_ean"] = data.ean;
		    			socket.emit("r_ai_items", sk_items_add);
				});
    		}
		});
	});

 	socket.on("load_edit_item", function(data)
	{			
		db.query('SELECT * FROM `sk_items` WHERE `id` = ?',data.sk_item_id , function(err, rows, fields)
		{
			if(rows.length != 0)
			{
				var sk_items_eload = {};
					sk_items_eload["success"] = true;
					sk_items_eload["id"] = rows[0].id;
	    			sk_items_eload["item_name"] = rows[0].item_name;
	    			sk_items_eload["item_price"] = rows[0].item_price;
	    			sk_items_eload["item_ean"] = rows[0].item_ean;
	    			socket.emit("r_load_edit_item", sk_items_eload);
    		}
		});
	});

 	socket.on("s_edit_item", function(data)
	{			
		db.query('UPDATE `sk_items` SET `item_name` = ?, `item_price` = ?, `item_ean` = ? WHERE `id` = ?',[data.name, data.price, data.ean, data.sk_item_id] , function(err, result)
		{
			if (err) throw err;
			var sk_items_edit = {};
				sk_items_edit["success"] = true;
				sk_items_edit["id"] = data.sk_item_id;
    			sk_items_edit["item_name"] = data.name;
    			sk_items_edit["item_price"] = data.price;
    			sk_items_edit["item_ean"] = data.ean;
    			socket.emit("r_edit_item", sk_items_edit);
		});
	});

 	socket.on("s_ri_items", function(data)
	{			
		db.query('DELETE FROM `sk_items` WHERE `id` = ?',data.sk_item_id , function(err, result)
		{
			if (err) throw err;
			{
				var sk_remove_item = {};
					sk_remove_item["success"] = true;
					sk_remove_item["id"] = data.sk_item_id;
	    			socket.emit("r_ri_items", sk_remove_item);
    		}
		});
	});

////******* KONIEC EDYCJA TOWARU *******////
////------------------------------------------------------------

	socket.on("PLAY", function(data)
	{
		db.query('SELECT * FROM users WHERE username = ?',data.name , function(err, rows, fields)
		{
			if(rows.length != 0)
			{
				if(rows[0].password == data.password)
				{
					console.log("Zalogowany Poprawnie");	

					playerInfo["id"] = rows[0].id;
					playerInfo["username"] = rows[0].username;
					playerInfo["machine_name"] = data.machine_name;
					playerInfo["active_session"] = GenerateSessionKey();

					socket.nickname = rows[0].username;
					socket.iduser = rows[0].id;
					socket.machine_name = data.machine_name;
					socket.active_session = playerInfo["active_session"];

					currentUser = {sid: socket.id, username: socket.nickname, active_session: socket.active_session};
					clientsid[socket.iduser] = currentUser;

					db.query('UPDATE users SET machine_name = ?, active_session = ?, online = 1 WHERE id = ?', [playerInfo.machine_name, playerInfo.active_session, playerInfo.id]);

					socket.emit("PLAY", playerInfo);
					socket.join('Global_Chat');
					socket.to('Global_Chat').emit('PLAYER_JOINED', playerInfo);
				}
				else
				{
					console.log("Błędne hasło");
				}
			}
			else
			{
				var errorx = ["zly login"];
				socket.emit("PLAY", errorx);
			}		
		});

		

	});

	socket.on("LOLEK", function(data)
	{
		db.query('SELECT tb1.*, tb2.username, tb2.online FROM friends_list tb1, users tb2 WHERE tb1.local_p_id = ? and tb2.id = tb1.friend_p_id', socket.iduser, function(err, rows, fields)
		{
			var friendlist = {};
			for  (i = 0; i < rows.length; i++)
			{
				friendlist[i] = {username: rows[i].username, id: rows[i].friend_p_id, online: rows[i].online};
			}
			
			socket.emit("FLISTXXXXXX", friendlist);
		});
	
					
	});


    socket.on('CHAT_MSG', function(data) {
 
    	var time = new Date();
    	var today = time.getHours() + ":" + addZero(time.getMinutes());
    	var msg = {username: socket.nickname, message: data.msg, time: today, active_session: socket.active_session};
    	io.in('Global_Chat').emit('LOAD_MSG_TO_CHAT', msg);
		console.log(msg);        
    })

    socket.on('disconnect', function() {
        console.info('Client gone (sid=' + socket.id + ').');
    })










});

server.listen(app.get('port'), function()
{
	console.log("======== SERVER IS RUNNING ========");
});
