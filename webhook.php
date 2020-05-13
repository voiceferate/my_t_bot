<?php


$body = file_get_contents('php://input'); //Получаем в $body json строку
    $arr = json_decode($body, true); //Разбираем json запрос на массив в переменную $arr
     
	// function cir_strrev($stroka){ //Так как функция strrev не умеет нормально переворачивать кириллицу, нужен костыль через массив. Создадим функцию
	// 	preg_match_all('/./us', $stroka, $array); 
	// 	return implode('',array_reverse($array[0]));
	// }

	include_once ('tg.class.php'); //Меж дела подключаем наш tg.class.php
	require __DIR__ . '/vendor/autoload.php';

	 
	//Сразу и создадим этот класс, который будет написан чуть позже
	//Сюда пишем токен, который нам выдал бот
	$tg = new tg('1226348780:AAFUd28Foh_bQEfHOfY9oBZPmu7M9DZqoBY');
	 
	$sms = $arr['message']['text']; //Получаем текст сообщения, которое нам пришло.
	//О структуре этого массива который прилетел нам от телеграмма можно узнать из официальной документации.
	 
	//Сразу и id получим, которому нужно отправлять всё это назад
	$tg_id = $arr['message']['chat']['id'];
	 
	//Перевернём строку задом-наперёд используя функцию cir_strrev
	// $sms_rev = cir_strrev($sms);

	$client = new \Google_Client();
	$client->setApplicationName('Google Sheets PHP');
	$client->setScopes(array(\Google_Service_Sheets::SPREADSHEETS));
	$client->setAccessType('offline');
	$client->setAuthConfig(__DIR__ . '/credentials.json');
	$service = new Google_Service_Sheets($client);
	$spreadsheetId = "1nx5eS4LnlLKGUjwxJBk8Ul7MpELh4JBZVjvYIpm03x4";

	$range = "test!A1:B4";
	// $data = new Google_Service_Sheets_ValueRange(array('item1', 'item2'));

	// $response = $service->spreadsheets_values->get($spreadsheetId, $range);
	// $values = $response->getValues();

	// $query = $service->spreadsheets_values->append($spreadsheetId, $range, $data);

	// if(empty($values)) {
	// 	echo "no data found";
	// } else {
	// 	foreach ($values as $row) {
	// 		// echo sprintf($row[0], $row[1], $row[2]);

	// 		$myObj->row=$row[0];
	// 		$myObj->row1=$row[1];
	// 		$myObj->row2=$row[2];
			
	// 		$myJSON=json_encode($myObj);  
			
	// 	}
	// }


	// ...

// Create the value range Object
$valueRange= new Google_Service_Sheets_ValueRange();

// You need to specify the values you insert
$valueRange->setValues(["values" => ["a", "b"]]); // Add two values

// Then you need to add some configuration
$conf = ["valueInputOption" => "RAW"];

// Update the spreadsheet
$service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $conf);

$message = 'sussess';

	//Используем наш ещё не написанный класс, для отправки сообщения в ответ
	$tg->send($tg_id, $message);
	
    exit('ok'); 
    
    //Обязательно возвращаем "ok", чтобы телеграмм не подумал, что запрос не дошёл

    ?>