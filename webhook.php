<?php


	$body = file_get_contents('php://input'); //Получаем в $body json строку
	$arr = json_decode($body, true); //Разбираем json запрос на массив в переменную $arr
     
	include_once ('tg.class.php'); //Меж дела подключаем наш tg.class.php
	require __DIR__ . '/vendor/autoload.php';

	//Сразу и создадим этот класс, который будет написан чуть позже
	//Сюда пишем токен, который нам выдал бот
	$tg = new tg('1226348780:AAFUd28Foh_bQEfHOfY9oBZPmu7M9DZqoBY');

	//Сразу и id получим, которому нужно отправлять всё это назад
	$tg_id = $arr['message']['chat']['id'];
	 
	//О структуре этого массива который прилетел нам от телеграмма можно узнать из официальной документации.
	$sms = $arr['message']['text']; //Получаем текст сообщения, которое нам пришло.
	$from = $arr['message']['from']['first_name'] . ' ' . $arr['message']['from']['last_name'];
	$date = date("F j, Y, g:i a", $arr['message']['date']); 

	$messageArrStr = explode("*", $sms);

	$summ = trim ($messageArrStr[0]);
	$contragent = trim ($messageArrStr[1]);
	$purpose = trim ($messageArrStr[2]);
	
	if (count($messageArrStr) !== 3) {
		$tg->send($tg_id, 'wrong message format');
		exit('ok'); 
	}

	// if (!is_int ( $summ )) {
	// 	$tg->send($tg_id, 'wrong number format');
	// 	exit('ok'); 
	// }


	$client = new \Google_Client();
	$client->setApplicationName('Google Sheets PHP');
	$client->setScopes(array(\Google_Service_Sheets::SPREADSHEETS));
	$client->setAccessType('offline');
	$client->setAuthConfig(__DIR__ . '/credentials.json');
	$service = new Google_Service_Sheets($client);
	$spreadsheetId = "1nx5eS4LnlLKGUjwxJBk8Ul7MpELh4JBZVjvYIpm03x4";

	// я не їбу що воно робить
	$range = "test!A1:B4";


	$valueRange= new Google_Service_Sheets_ValueRange();
	$valueRange->setValues(["values" => [$date, $from, $summ, $contragent, $purpose]]); // Add two values
	$conf = ["valueInputOption" => "RAW"];
	// append the values the spreadsheet
	$service->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $conf);

	$message = 'sussess';

	//Используем наш ещё не написанный класс, для отправки сообщения в ответ
	$tg->send($tg_id, $message);
	
	exit('ok'); 
    
    //Обязательно возвращаем "ok", чтобы телеграмм не подумал, что запрос не дошёл
