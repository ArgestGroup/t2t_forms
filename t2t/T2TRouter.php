<?php

	// Инициализация компонента
	include('T2TForms.php');
	// Обработка ajax запросов
	T2TForms::ajaxCatcher();
	// Обработка редиректа на инвойс из истории
	T2TForms::invoiceRouter();
	// Обработка заказа билета(ов)
	T2TForms::buyRouter();

?>