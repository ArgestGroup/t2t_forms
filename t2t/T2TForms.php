<?php

	/**
	 * @version 0.9
	 * @author Sergey Shuruta
	 * @copyright 2013 Argest Group 
	 */
	class T2TForms
	{
		// Сервер форм заказа
		const SERVER = 'http://v2gui.t2t.in.ua';
		// Сервер оплаты
		const INVOICE_SERVER = 'http://v2invoice.t2t.in.ua';
		// Платежная система по умолчанию
		const PS_DEFAULT = 'ec_privat';
		// Поезда
		const TRAIN = 'train';
		// Автобусы
		const BUS = 'bus';
		// Табличная верстка в линию
		const LINE_FORM = 'line';
		// Табличная верстка
		const NORMAL_FORM = 'normal';
		// Локализация
		const LANG_RU = 'ru';
		const LANG_UA = 'ua';
		const LANG_EN = 'en';
		const LANG_DE = 'de';
		// Сервер запроса
		private $server_url = '';
		// Адрес страницы результатов поиска
		private $action = '#';
		// Язык отображения (ru, ua, en, de) по умолчанию ru
		private $lang = T2TForms::LANG_RU;
		// Каталог в котором находится класс T2TForms
		private $ss = '/';
		// Тип транспорта по умолчанию
		private $type = T2TForms::TRAIN;
		// Тип html верстки формы запроса по умолчанию
		private $kind = T2TForms::NORMAL_FORM;
		// Показывать ошибки
		public $isShowErrors = true;

		protected static $_instance;

		private function __clone(){}

		private function __construct()
		{
			$this->ss = trim($this->ss, '/');
			$this->ss  = '/' . ($this->ss ? ($this->ss . '/') : '') .  __CLASS__ . '.php';
			$this->server_url = trim(T2TForms::SERVER, '/') . '/' . $this->lang . '/';
			if(!isset($_SESSION)) session_start();
			if(!isset($_SESSION['t2t']['pay_type']) || !$_SESSION['t2t']['pay_type'])
				$_SESSION['t2t']['pay_type'] = T2TForms::PS_DEFAULT;
			if(!isset($_SESSION['t2t']['lang']) || !$_SESSION['t2t']['lang'])
				$_SESSION['t2t']['lang'] = $this->lang;
		}

		public static function get() {
			if (null === self::$_instance) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		private function log($mess)
		{
			if(T2TForms::get()->isShowErrors)
				echo '<span style="padding: 2px 5px;color:#fff;background: #ff4500;font-weight:bold;"><u>' . __CLASS__ . '</u>: ' . $mess . '.</span>';
		}

		static function genHashCode($host, $toBack, $email)
		{
			if(!isset($_SESSION['t2t']['secretKey'])) return false;
			$secretKey = $_SESSION['t2t']['secretKey'];
			$hashStr  = $host . ','
					  . $toBack . ','
					  . $email . ','
					  . $secretKey;
			$hashCode = md5(base64_encode($hashStr));
			return $hashCode;
		}
		
		protected function sendRequest($http, $params = array())
		{
			if(!is_array($params)) return;
			$params['secretKey'] = isset($_SESSION['t2t']['secretKey']) ? $_SESSION['t2t']['secretKey'] : '';
			$params['host'] = isset($_SESSION['t2t']['host']) ? $_SESSION['t2t']['host'] : '';
			$url = $http . ($params ? ('?' . http_build_query($params)) : '');
			
			if( $curl = curl_init() ) {
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$request = curl_exec($curl);
				
				if($request) {
					return $request;
				} else {
					T2TForms::log('server connection error');
				}
				
				curl_close($curl);
			} else {
				T2TForms::log('curl not found.');
			}
		}
		
		public function logout()
		{
			if(!isset($_SESSION)) session_start();
			if(isset($_SESSION['t2t'])) unset($_SESSION['t2t']);
		}
		
		/**
		 * Усанавливает текущую локализацию.
		 * По умолчанию ru
		 * @param string $lang
		 */
		public function setLang($lang = T2TForms::LANG_RU)
		{
			if(in_array($lang, array('ru','ua','en','de'))) {
				$this->lang = $lang;
				$this->server_url = T2TForms::SERVER . '/' . $this->lang . '/';
				$_SESSION['t2t']['lang'] = $this->lang;
			} else {
				$this->log('invalid language "' . $lang . '"');
			}
		}
		
		/**
		 * Включает / выключает отображение ошибок.
		 * По умолчанию true
		 * @param bool $isShowErrors
		 */
		public function isShowEr($isShowErrors = true)
		{
			$this->isShowErrors = $isShowErrors ? true : false;
		}

		/**
		 * Устанавливает рабочий домен
		 * @param string $domain
		 */
		public function setDomain($domain)
		{
			$_SESSION['t2t']['host'] = $domain;
		}
		
		/**
		 * Устанавливает секретный ключь
		 * @param string $secretKey
		 */
		public function setSecretKey($secretKey)
		{
			$_SESSION['t2t']['secretKey'] = $secretKey;
		}

		/**
		 * Устанавливает адрес страницы с результатами поиска
		 * @param string $action
		 */
		public function setItemsPage($action)
		{
			$this->action = $action;
		}
		
		/**
		 * Устанавливает вайл роутера
		 * @param string $dir
		 */
		public function setRouter($router = '/')
		{
			$this->ss = $router;
		}
		
		/**
		 * Устанавливает email пользователя
		 * @param string $email
		 */
		public function setUEmail($email)
		{
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
				$_SESSION['t2t']['uEmail'] = $email;
		}

		/**
		 * Устанавливает мобильный телефон пользователя без када страны
		 * десять цыфр без дополнительных символов. (Пример: 0959999999)
		 * @param string $phone
		 */
		public function setUPhone($phone)
		{
			$_SESSION['t2t']['uPhone'] = $phone;
		}

		/**
		 * Устанавливает имя пользователя
		 * @param string $uName
		 */
		public function setUName($uName)
		{
			$_SESSION['t2t']['uName'] = $uName;
		}

		/**
		 * Устанавливает фамилия пользователя
		 * @param string $uSurName
		 */
		public function setUSurName($uSurName)
		{
			$_SESSION['t2t']['uSurName'] = $uSurName;
		}

		/**
		 * Возвращает email пользователя
		 * @return string
		 */
		public function getUEmail()
		{
			return isset($_SESSION['t2t']['uEmail']) ? $_SESSION['t2t']['uEmail'] : '';
		}

		/**
		 * Возвращает рабочий домен
		 * @return string $domain
		 */
		public function getDomain()
		{
			return $_SESSION['t2t']['host'];
		}
		
		/**
		 * Возвращает секретный ключь
		 * @return string $secretKey
		 */
		public function getSecretKey()
		{
			return $_SESSION['t2t']['secretKey'];
		}
		
		/**
		 * Возвращает адрес страницы с результатами поиска
		 * @return string $action
		 */
		public function getItemsPage()
		{
			return $this->action;
		}

		/**
		 * Возвращает текущую локализацию.
		 * По умолчанию ru
		 * @return string $lang
		 */
		public function getLang($lang = T2TForms::LANG_RU)
		{
			return $this->lang;
		}
		
		/**
		 * Возвращает телефон пользователя
		 * @return string
		 */
		public function getUPhone()
		{
			return isset($_SESSION['t2t']['uPhone']) ? $_SESSION['t2t']['uPhone'] : '';
		}

		/**
		 * Возвращает имя пользователя
		 * @return string
		 */
		public function getUName()
		{
			return isset($_SESSION['t2t']['uName']) ? $_SESSION['t2t']['uName'] : '';
		}

		/**
		 * Возвращает фамилию пользователя
		 * @return string
		 */
		public function getUSurName()
		{
			return isset($_SESSION['t2t']['uSurName']) ? $_SESSION['t2t']['uSurName'] : '';
		}
		
		/**
		 * Добавляет скрытые поля,
		 * необходимые для работы JavaScript-ов форм.
		 */
		public function initJs()
		{
			echo '<!-- ' . __CLASS__ . ': init JS -->' . "\n";
			echo '<input type="hidden" id="t2t_server" value="' . $this->server_url . '">' . "\n";
			echo '<input type="hidden" id="t2t_ss" value="' . $this->ss . '">' . "\n";
			echo '<!-- / ' . __CLASS__ . ': init JS -->' . "\n";
		}
		
		/**
		 * Подключает CSS стили.
		 * Принемает не обязательный параметр - булевое значение
		 * указывающий поодключать ли стандартные стили.
		 * По умолчанию подключаются.
		 * @param array $exceptions
		 */
		public function css($addStyle = true)
		{
			echo $this->sendRequest($this->server_url . 'get/css', array('addStyle' => $addStyle));
		}
		
		/**
		 * Возвращает ссылки на CSS стили.
		 * Принемает не обязательный параметр - булевое значение
		 * указывающий поодключать ли стандартные стили.
		 * По умолчанию подключаются.
		 * @param array $exceptions
		 * @return array
		 */
		public function cssLinks($addStyle = true)
		{
			return json_decode($this->sendRequest($this->server_url . 'get/css', array('addStyle' => $addStyle, 'in_json' => true)));
		}

		/**
		 * Подключает JavaScript.
		 * Принемает не обязательный параметр - булевое значение
		 * указывающий поодключать ли jQuery.
		 * По умолчанию подключается.
		 * @param array $exceptions
		 */
		public function js($addJQuery = true)
		{
			echo $this->sendRequest($this->server_url . 'get/js', array('addJQuery' => $addJQuery));
		}
		
		/**
		 * Возвращает ссылки на JavaScript.
		 * Принемает не обязательный параметр - булевое значение
		 * указывающий поодключать ли jQuery.
		 * По умолчанию подключается.
		 * @param array $exceptions
		 * @return array
		 */
		public function jsLinks($addJQuery = true)
		{
			return json_decode($this->sendRequest($this->server_url . 'get/js', array('addJQuery' => $addJQuery, 'in_json' => true)));
		}
		
		/**
		 * Возвращает список доступных платежных систем
		 */
		public function paySystems()
		{
			parse_str($_SERVER['QUERY_STRING'], $params);
			if(!array_key_exists('transport', $params))
				$params['transport'] = $this->type;
			$params['changePsUrl'] = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
			$params['pay_type'] = $_SESSION['t2t']['pay_type'];
			echo $this->sendRequest($this->server_url . 'get/paysystems', $params);
		}

		/**
		 * Возвращает HTML форму поиска рейсов
		 * Принемает не обязательные параметы.
		 * @param string $type - тип верстки.
		 * Константное значение:
		 *   T2TForms::NORMAL_FORM,
		 *   T2TForms::LINE_FORM
		 * @param string $kind - тип транспорта.
		 * Константное значение:
		 *   T2TForms::TRAIN,
		 *   T2TForms::BUS
		 */
		public function form($kind = '', $type = '')
		{
			if(isset($_GET['transport']))
				$type = ($_GET['transport'] == 'train') ? 'train': 'bus';

			if($kind)   $this->kind = $kind;
			if($type)   $this->type = $type;
			$src = (isset($_GET['src']) && intval($_GET['src']) == $_GET['src']) ? $_GET['src'] : '';
			$dst = (isset($_GET['dst']) && intval($_GET['dst']) == $_GET['dst']) ? $_GET['dst'] : '';
			$dt  = (isset($_GET['dt'])  && date('Y-m-d', strtotime($_GET['dt'])) == $_GET['dt']) ? $_GET['dt'] : '';

			$params = array();
			$params['action'] = $this->action;
			$params['type']   = urlencode($type);
			$params['kind']	  = urlencode($kind);
			$params['src']	  = urlencode($src);
			$params['dst']	  = urlencode($dst);
			$params['dt']	  = urlencode($dt);
			$params['ss']	  = urlencode($this->ss);
			echo $this->sendRequest($this->server_url . 'get/fromto', $params);
		}

		/**
		 * Отображает таблицу с результатами поиска
		 */
		public function table()
		{
			$params = array();
			$params['transport'] = (isset($_GET['transport']) && ($_GET['transport'] == T2TForms::TRAIN || $_GET['transport'] == T2TForms::BUS)) ? $_GET['transport'] : '';
			$params['src'] = (isset($_GET['src']) && intval($_GET['src']) == $_GET['src']) ? $_GET['src'] : '';
			$params['dst'] = (isset($_GET['dst']) && intval($_GET['dst']) == $_GET['dst']) ? $_GET['dst'] : '';
			$params['dt']  = (isset($_GET['dt']) && date('Y-m-d', strtotime($_GET['dt'])) == $_GET['dt']) ? $_GET['dt'] : '';
			$params['ss'] = $this->ss;
			if($params['transport'] && $params['src'] && $params['dst'] && $params['dt'] && $params['ss']) {
				echo $this->sendRequest($this->server_url . 'get/table', $params);
			}
		}

		/**
		 * Возвращает HTML форму архива.
		 */
		public function archive()
		{
			
			$email = isset($_SESSION['t2t']['uEmail']) ? $_SESSION['t2t']['uEmail'] : '';
			$date_a = isset($_GET['date_a']) ? $_GET['date_a'] : date("d.m.Y");
			$date_b = isset($_GET['date_b']) ? $_GET['date_b'] : date("d.m.Y");

			if(!$email) {
				$this->log('Not authorization user');
				return;
			}
			$params = array();
			$params['email'] = $email;
			$params['date_a'] = $date_a;
			$params['date_b'] = $date_b;
			$params['invServ'] = $this->ss;
			echo $this->sendRequest($this->server_url . 'get/archive', $params);
		}
		
		/**
		 * Обработка ajax запросов
		 */
		static function ajaxCatcher()
		{
			if(!isset($_SESSION)) session_start();
			if(isset($_REQUEST['do']) && isset($_REQUEST['server'])) {
				$path = '';
				switch ($_REQUEST['do']) {
					case 'autocomplete': $path = '/' . $_SESSION['t2t']['lang'] . '/search/autocomplete';  break;
					case 'tripinfo': 	 $path = '/' . $_SESSION['t2t']['lang'] . '/get/tripinfo'; 		  break;
					case 'loadmap':		 $path = '/' . $_SESSION['t2t']['lang'] . '/get/carmap'; 		  break;
					case 'getfio':		 $path = '/' . $_SESSION['t2t']['lang'] . '/invoice/getFio'; 	  break;
					case 'passitem':	 $path = '/invoice/passItem.ejs'; break;
					case 'passitemBus':	 $path = '/invoice/passItemBus.ejs'; break;
				}
				if($path)
					echo T2TForms::sendRequest(T2TForms::SERVER . $path, $_REQUEST);
			}
		}

		/**
		 * Обработка смены текущей платежной системы
		 */
		static function paySystemSetter()
		{
			if(isset($_GET['pay_type']) && $_GET['pay_type']) {
			
				$_SESSION['t2t']['pay_type'] = $_GET['pay_type'];
			}
		}
		
		/**
		 * Обработка перенаправления на инвойс (из истории)
		 */
		static function invoiceRouter()
		{
			if(!isset($_SESSION)) session_start();
			$ivId = isset($_GET['ivId']) ? $_GET['ivId'] : 0;
			if($ivId && T2TForms::get()->getUEmail()) {
				$params = array();
				$params['host']	  = isset($_SESSION['t2t']['host']) ? $_SERVER['t2t']['host'] : '';
				$params['email']  = T2TForms::get()->getUEmail();
				$params['toBack'] = base64_encode($_SERVER['HTTP_REFERER']);
				if($hashCode = T2TForms::genHashCode($params['host'], $params['toBack'], $params['email']))
					$params['hashCode'] = $hashCode;
				header('Location: ' . T2TForms::INVOICE_SERVER . '/' . $_SESSION['t2t']['lang'] . '/invoice/index/' . $ivId . '?' . http_build_query($params));
			}
		}

		/**
		 * Обработка заказа билета(ов)
		 */
		static function buyRouter()
		{
			if(!isset($_SESSION)) session_start();
			if(isset($_POST['transport_type'])) {

				$params = array();
				$params['t']			  = time();
				$params['host']			  = T2TForms::get()->getDomain();
				$params['secretKey']	  = T2TForms::get()->getSecretKey();
				$params['email']		  = T2TForms::get()->getUEmail();
				$params['phone']		  = T2TForms::get()->getUPhone();
				$params['name']			  = T2TForms::get()->getUName();
				$params['surname']		  = T2TForms::get()->getUSurName();
				if(isset($_POST['transport_type']))
					$params['transport_type'] = $_POST['transport_type'];
				if(isset($_SESSION['t2t']['pay_type']))
					$params['pay_type'] 	  = $_SESSION['t2t']['pay_type'];
				if(isset($_POST['segment_id']))
					$params['segment_id'] 	  =  $_POST['segment_id'];
				if(isset($_POST['name1']))
					$params['name1'] 		  = $_POST['name1'];
				if(isset($_POST['name2']))
					$params['name2'] 		  = $_POST['name2'];
				if(isset($_POST['ticketType']))
					$params['ticketType'] 	  = $_POST['ticketType'];
				if(isset($_POST['birthday']))
					$params['birthday'] 	  = $_POST['birthday'];
				if(isset($_POST['tosId']))
					$params['tosId'] 		  = $_POST['tosId'];
				if(isset($_POST['carId']))
					$params['carId'] 		  = $_POST['carId'];
				if(isset($_POST['sys_place']))
					$params['sys_place'] 	  = $_POST['sys_place'];
				$params['toBack']			  = base64_encode($_SERVER['HTTP_REFERER']);
				if($hashCode = T2TForms::genHashCode($params['host'], $params['toBack'], $params['email']))
					$params['hashCode'] 	  = $hashCode;
				//print_r($params); die();
				header('Location: ' . T2TForms::INVOICE_SERVER . '/' . $_SESSION['t2t']['lang'] . '/invoice/index?' . http_build_query($params));
			}
		}
	}

?>