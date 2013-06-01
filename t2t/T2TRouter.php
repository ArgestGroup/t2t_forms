<?php

	// Инициализация компонента
	include('T2TForms.php');
	// Инициализируем статику
	T2TForms::app();  
	// Обработка логаут из режима авторизации форм
	T2TForms::logout();
	// Обработка ajax запросов
	T2TForms::ajaxCatcher();
	// Обработка редиректа на инвойс из истории
	T2TForms::invoiceRouter();
	// Обработка заказа билета(ов)
	T2TForms::buyRouter();
	// Генерация капчи по запросу
	T2TForms::getCaptcha();

?>