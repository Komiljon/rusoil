<?php
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

$arResult["PARAMS_HASH"] = md5(serialize($arParams).$this->GetTemplateName());

$arParams["USE_CAPTCHA"] = (($arParams["USE_CAPTCHA"] != "N" && !$USER->IsAuthorized()) ? "Y" : "N");
$arParams["EVENT_NAME"] = trim($arParams["EVENT_NAME"]);
if($arParams["EVENT_NAME"] == ''){
	$arParams["EVENT_NAME"] = "AKA_APP_ORDER_FORM";
}
$arParams["EMAIL_TO"] = trim($arParams["EMAIL_TO"]);
if($arParams["EMAIL_TO"] == '')
	$arParams["EMAIL_TO"] = COption::GetOptionString("main", "email_from");
$arParams["OK_TEXT"] = trim($arParams["OK_TEXT"]);
if($arParams["OK_TEXT"] == '')
	$arParams["OK_TEXT"] = GetMessage("MF_OK_MESSAGE");

if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] <> '' && (!isset($_POST["PARAMS_HASH"]) || $arResult["PARAMS_HASH"] === $_POST["PARAMS_HASH"]))
{
	$arResult["ERROR_MESSAGE"] = array();
	if(check_bitrix_sessid())
	{		
		if(empty($arParams["REQUIRED_FIELDS"]) || !in_array("NONE", $arParams["REQUIRED_FIELDS"]))
		{
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("TITLE", $arParams["REQUIRED_FIELDS"])) && mb_strlen($_POST["orderApplication_title"]) <= 1){
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_TITLE");
			}
		}
		
		if(empty($arResult["ERROR_MESSAGE"]))
		{
			$order_list_count = 0;
			$order_list_с = [];
			$order_list_count = intVal($_POST['order_list_count']);
			if($order_list_count>0){
				$order_list_с = range(0, $order_list_count);
			}else{
				$order_list_с = [0=>'0'];
			}			
			$order_list_body = '';
			if(count($order_list_с)>1){
				foreach ($order_list_с as $key => $value) {

					$order_list_body .= 'Бренд: ' . $_POST['brend_' . $key] 
					. ', Наименование: ' . $_POST['prodname_' . $key] 
					. ', Количество: ' . $_POST['count_' . $key] 
					. ', Фасофка: ' . $_POST['fasovka_' . $key] 
					. ', Клиент: ' . $_POST['customer_' . $key] 
					. '<br>\n';
				}	
			}else{
				$order_list_body .= 'Бренд: ' . $_POST['brend_0'] 
					. ', Наименование: ' . $_POST['prodname_0'] 
					. ', Количество: ' . $_POST['count_0'] 
					. ', Фасофка: ' . $_POST['fasovka_0'] 
					. ', Клиент: ' . $_POST['customer_0'] 
					. '<br>\n';
			}

			// Название <input type="file">
			$input_name = 'orderFile'; 
			// Разрешенные расширения файлов.
			$allow = array(); 
			// Запрещенные расширения файлов.
			$deny = array(
				'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'cgi', 'pl', 'asp', 
				'aspx', 'shtml', 'shtm', 'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'js', 'html', 
				'htm', 'css', 'sql', 'spl', 'scgi', 'fcgi', 'exe', 'py', 'sh'
			);

			$path = $_SERVER["DOCUMENT_ROOT"].'/upload/';
			
			if (isset($_FILES[$input_name]) && !empty($_FILES[$input_name])) {
				$file = $_FILES[$input_name];			 
				// Проверим на ошибки загрузки.
				if (!empty($file['error']) || empty($file['tmp_name'])) {
					//$arResult["ERROR_MESSAGE"][] = 'Не удалось загрузить файл.';
				} elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
					//$arResult["ERROR_MESSAGE"][] = 'Не удалось загрузить файл.';
				} else {
					// Оставляем в имени файла только буквы, цифры и некоторые символы.
					$pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
					$name = mb_eregi_replace($pattern, '-', $file['name']);
					$name = mb_ereg_replace('[-]+', '-', $name);
					$parts = pathinfo($name);
			 
					if (empty($name) || empty($parts['extension'])) {
						$arResult["ERROR_MESSAGE"][] = 'Недопустимый тип файла';
					} elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
						$arResult["ERROR_MESSAGE"][] = 'Недопустимый тип файла';
					} elseif (!empty($deny) && in_array(strtolower($parts['extension']), $deny)) {
						$arResult["ERROR_MESSAGE"][] = 'Недопустимый тип файла';
					} else {
						// Перемещаем файл в директорию.
						if (move_uploaded_file($file['tmp_name'], $path . $name)) {
							// Далее можно сохранить название файла
						} else {
							$arResult["ERROR_MESSAGE"][] = 'Не удалось загрузить файл.';
						}
					}
				}
			}
			

			$arFields = Array(
				"TITLE" => $_POST["orderApplication_title"],
				"CATEGORY" => $_POST["catRadios"],
				"ORDER_TYPE" => $_POST["typeRadios"],
				"STORES" => $_POST["selected_store"],
				"ORDER_LIST" => $order_list_body,				
				"TEXT" => $_POST["MESSAGE"],
				"EMAIL_TO" => $arParams["EMAIL_TO"],				
			);	

			// echo '<pre>';
			// print_r($arFields);
			// echo '</pre>';

			if(strlen($name)){
				$arFields['FILE'] = '<a href="' . $_SERVER['HTTP_HOST'] . '/upload/' . $name . '">Файл ' . $name . '</a>';
			}		

			if(!empty($arParams["EVENT_MESSAGE_ID"]))
			{
				foreach($arParams["EVENT_MESSAGE_ID"] as $v){
					if(intval($v) > 0){
						CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields, "N", intval($v));
					}
				}

			}
			else{				
				CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields);
			}

			/*
			$subject = $_POST["orderApplication_title"]; 
			$message .= 
			'Категория: ' . $arFields["CATEGORY"]
			. ', ' . "\r\n" . 'Вид заявки: ' . $arFields["ORDER_TYPE"]
			. ', ' . "\r\n" . 'Склад поставки: ' . $arFields["STORES"]
			. ', ' . "\r\n" . 'Состав заказа: ' . $arFields["ORDER_LIST"]
			. ', ' . "\r\n" . 'Комментарий: ' . $arFields["TEXT"] . "\r\n";

			if(strlen($arFields['FILE'])){
				$message .= $arFields['FILE'];
			}

			$headers  = "Content-type: text/html; charset=UTF-8 \r\n"; 
			$headers .= "From: От кого письмо " . $arParams["EMAIL_TO"] . "\r\n"; 
			$headers .= "Reply-To: " . $arParams["EMAIL_TO"] . "\r\n"; 

			@mail($arParams["EMAIL_TO"], $subject, $message, $headers);		
			*/
			
			LocalRedirect($APPLICATION->GetCurPageParam("success=".$arResult["PARAMS_HASH"], Array("success")));
		}

		$arResult["MESSAGE"] = htmlspecialcharsbx($_POST["MESSAGE"]);
	}
	else{
		$arResult["ERROR_MESSAGE"][] = GetMessage("MF_SESS_EXP");
	}
}
elseif($_REQUEST["success"] == $arResult["PARAMS_HASH"])
{
	$arResult["OK_MESSAGE"] = $arParams["OK_TEXT"];
}


$this->IncludeComponentTemplate();
