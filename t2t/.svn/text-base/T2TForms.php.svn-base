<?php

	/**
	 * @version 0.933
	 	 * @author Sergey Shuruta
	 * @copyright Copyright 2013 Argest Group LLC (email: info@argest.com.ua)
	 */
	class T2TForms
	{

		const SERVER = 'http://v2gui.t2t.in.ua'; // Сервер форм заказа
		const INVOICE_SERVER = 'http://v2invoice.t2t.in.ua'; // Сервер оплаты
		const T2T_FORMS_STYLE = 'http://v2gui.t2t.in.ua/themes/forms/css/t2t.css'; // стили Css
		const T2T_JQUERY_UI_STYLE = 'http://v2gui.t2t.in.ua/themes/forms/css/jquery-ui.css'; // стили Css
		const PS_DEFAULT = 'ec_privat'; // Платежная система по умолчанию
		const TRAIN = 'train'; // Поезда
		const BUS = 'bus'; // Автобусы
		// Локализация
		const LANG_RU = 'ru';
		const LANG_UA = 'ua';
		const LANG_EN = 'en';
		const LANG_DE = 'de';

		// Адрес страницы результатов поиска
		private $action = '#';
		// Стили оформления
		private $t2t_styles = array(
				'forms' => self::T2T_FORMS_STYLE,
				'jquery_ui' => self::T2T_JQUERY_UI_STYLE,
			);
		private $addJQuery = true;
		// Каталог в котором находится класс T2TForms
		private $router = '';
		// Тип транспорта по умолчанию
		private $type = self::TRAIN;
		// Язык интерфейса
		private $lang = self::LANG_RU;
		// Показывать ошибки
		public static $isShowErrors = true;
		private $addFormOnSearch = true;

		protected static $_instance;

		public static function app() {
			if (null === self::$_instance) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		
		private function __clone(){
			
		}
		
		private function __construct()
		{
			if(!isset($_SESSION)) session_start();
			$_SESSION['t2t']['pay_type'] = self::PS_DEFAULT;
		}

		private static function log($mess)
		{
			if(self::$isShowErrors)
				echo '<span style="padding: 2px 5px;color:#fff;background: #ff4500;font-weight:bold;"><u>' . __CLASS__ . '</u>: ' . $mess . '.</span>';
		}
		
		public static function isShowEr($show = true)
		{
			self::$isShowErrors = $show;
		}

		/**
		 * Устанавливает адрес страницы с результатами поиска
		 * @param string $action
		 */
		public function setResultPage($action)
		{
			$this->action = $action;
		}
		
		/**
		 * Возвращает адрес страницы с результатами поиска
		 * @param string
		 */
		public function getResultPage()
		{
			return $this->action;
		}
		
		/**
		 * Устанавливает вайл роутера
		 * @param string $router
		 */
		public function setRouter($router = '/')
		{
			$this->router = $router;
		}
		
		/**
		 * Устанавливает свою ссылку на стили t2t
		 * @param string $router
		 */
		public function setStyle($styleLink)
		{
			$this->t2t_styles['forms'] = $styleLink;
		}

		/**
		 * Устанавливает свою ссылку на стили jQuery UI
		 * @param string $router
		 */
		public function setStyleJQueryUI($styleLink)
		{
			$this->t2t_styles['jquery_ui'] = $styleLink;
		}
		
		/**
		 * Возвращает ссылку на стили t2t
		 * @param string $router
		 */
		public function getStyle()
		{
			return $this->t2t_styles['forms'];
		}
		
		/**
		 * Устанавливает ссылку на стили jQuery UI
		 * @param string $router
		 */
		public function getStyleJQueryUI()
		{
			return $this->t2t_styles['jquery_ui'];
		}
		
		/**
		 * Усанавливает текущую локализацию.
		 * По умолчанию ru
		 * @param string $lang
		 */
		public function setLang($lang = self::LANG_RU)
		{
			if(in_array($lang, array('ru','ua','en','de'))) {
				$this->lang = $lang;
			} else {
				self::log('invalid language "' . $lang . '"');
			}
		}
		
		/**
		 * Устанавливает метку подключать ли jQuery автоматически
		 * @param boolean $isAdd
		 * @return boolean
		 */
		public function isAddJQuery($isAdd = null)
		{
			if(isset($isAdd)) $this->addJQuery = $isAdd;
			return $this->addJQuery;
		}
		
		/**
		 * Устанавливает метку отображения формы 
		 * запроса на странице результатов поиска.
		 * @param boolean $isAdd
		 * @return boolean
		 */
		public function isFormOnSearch($isAdd = null)
		{
			if(isset($isAdd)) $this->addFormOnSearch = $isAdd;
			return $this->addFormOnSearch;
		}

		/**
		 * Усанавливает текущую локализацию.
		 * По умолчанию ru
		 * @param string $lang
		 */
		public function getLang()
		{
			return $this->lang;
		}
		
		/**
		 * Устанавливает рабочий домен
		 * @param string $domain
		 */
		public function setDomain($domain)
		{
			$_SESSION['t2t']['domain'] = $domain;
		}
		
		/**
		 * Возвращает рабочий домен
		 * @param string
		 */
		public static function getDomain()
		{
			return isset($_SESSION['t2t']['domain']) ? $_SESSION['t2t']['domain'] : '';
		}
		
		/**
		 * Возвращает email пользователя
		 * @return string
		 */
		public static function getUEmail()
		{
			return isset($_SESSION['t2t']['uEmail']) ? $_SESSION['t2t']['uEmail'] : '';
		}
		
		/**
		 * Возвращает телефон пользователя
		 * @return string
		 */
		public static function getUPhone()
		{
			return isset($_SESSION['t2t']['uPhone']) ? $_SESSION['t2t']['uPhone'] : '';
		}
		
		/**
		 * Возвращает имя пользователя
		 * @return string
		 */
		private static function getUName()
		{
			return isset($_SESSION['t2t']['uName']) ? $_SESSION['t2t']['uName'] : '';
		}
		
		/**
		 * Возвращает фамилию пользователя
		 * @return string
		 */
		public static function getUSurName()
		{
			return isset($_SESSION['t2t']['uSurName']) ? $_SESSION['t2t']['uSurName'] : '';
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
		 * Возвращает секретный ключь
		 * @param string $secretKey
		 */
		public static function getSecretKey()
		{
			return isset($_SESSION['t2t']['secretKey']) ? $_SESSION['t2t']['secretKey'] : '';
		}
		
		/**
		 * Обработка смены текущей платежной системы
		 */
		public function paySystemSetter()
		{
			if(isset($_GET['pay_type']) && $_GET['pay_type']) {
				$_SESSION['t2t']['pay_type'] = $_GET['pay_type'];
			}
		}
		
		/**
		 * Устанавливает email пользователя
		 * @param string $email
		 */
		public function setUEmail($email)
		{
			if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$_SESSION['t2t']['uEmail'] = $email;
				if(isset($_SESSION['t2t']['t2t_login'])) 
					$_SESSION['t2t']['t2t_login'] = false;
			}
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
		 * Подключает CSS стили.
		 * Принемает не обязательный параметр - булевое значение
		 * указывающий поодключать ли стандартные стили.
		 * По умолчанию подключаются.
		 * @param array $exceptions
		 */
		public function getCss()
		{
			$links = $this->getCssLinks();
			$styles = '';
			foreach ($links as $style)
				$styles .= '<link rel="stylesheet" type="text/css" href="' . $style . '">' . PHP_EOL;
			return $styles;
		}
		
		/**
		 * Подключает JavaScript.
		 * Принемает не обязательный параметр - булевое значение
		 * указывающий поодключать ли jQuery.
		 * По умолчанию подключается.
		 * @param array $exceptions
		 */
		public function getJs()
		{
			return self::sendRequest(self::SERVER . '/get/js', array('addJQuery' => $this->addJQuery));
		}
		
		private static function sendRequest($url, $params = array())
		{
			if(!is_array($params)) return;

			$params['hashCode'] = self::genHashCode($url);
			$params['domain'] = base64_encode(self::getDomain());
			$url = $url . ($params ? ('?' . http_build_query($params)) : '');

			if( $curl = curl_init() ) {
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$request = curl_exec($curl);
		
				if($request) {
					return $request;
				} else {
					self::log('server connection error');
				}
		
				curl_close($curl);
			} else {
				self::log('curl not found.');
			}
		}
		
		private static function genHashCode($url)
		{
			return sha1($url . self::getsecretKey());
		}
		
		/**
		 * Возвращает список доступных платежных систем
		 */
		public function getPaySystems()
		{
			$transport = isset($_GET['transport']) ? $_GET['transport'] : '';
			if(!$transport || !in_array($transport, array('train', 'bus'))) return '';
			$params['form_params'] = base64_encode($_SERVER['QUERY_STRING']);
			$params['form_url'] = base64_encode(substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?')));
			$params['pay_type'] = $_SESSION['t2t']['pay_type'];
			$params['transport'] = $transport;
			return self::sendRequest(self::SERVER  . '/' . $this->getlang() . '/get/paysystems', $params);
		}
		
		/**
		 * Возвращает HTML форму архива.
		 */
		public function getArchive()
		{
				
			$email = self::getUEmail();
			$lang = $this->getlang();
			$date_a = isset($_GET['date_a']) ? $_GET['date_a'] : date("d.m.Y");
			$date_b = isset($_GET['date_b']) ? $_GET['date_b'] : date("d.m.Y");
		
			if(!$email) {
				self::log('Not authorization user');
				return;
			}
			$params = array();
			$params['email'] = $email;
			$params['date_a'] = $date_a;
			$params['date_b'] = $date_b;
			$params['router'] = $this->router;
			$params['params'] = base64_encode($_SERVER['QUERY_STRING']);
			return self::sendRequest(self::SERVER . '/' . $lang . '/get/archive', $params);
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
		public function getForm($type = '')
		{
			if($type)   $this->type = $type;
			$params = array();
			$params['action'] = base64_encode($this->action);
			$params['type']   = $this->type;
			$params['router']   = $this->router;
			return self::sendRequest(self::SERVER  . '/' . $this->getlang() . '/get/form', $params);
		}
		
		/**
		 * Отображает таблицу с результатами поиска
		 */
		public function getTable($vsSearchBox = null)
		{
			$addFormOnSearch = isset($vsSearchBox) ? $vsSearchBox : $this->addFormOnSearch;
			$params = array();
			$params['vs_search_box'] = $addFormOnSearch;
			$params['transport'] = (isset($_GET['transport']) && ($_GET['transport'] == self::TRAIN || $_GET['transport'] == self::BUS)) ? $_GET['transport'] : '';
			$params['src'] = (isset($_GET['src']) && intval($_GET['src']) == $_GET['src']) ? $_GET['src'] : '';
			$params['dst'] = (isset($_GET['dst']) && intval($_GET['dst']) == $_GET['dst']) ? $_GET['dst'] : '';
			$params['dt']  = (isset($_GET['dt']) && date('Y-m-d', strtotime($_GET['dt'])) == $_GET['dt']) ? $_GET['dt'] : '';
			$params['router'] = $this->router;
			$params['inlineLogin'] = isset($_SESSION['t2t']['t2t_login']) ? $_SESSION['t2t']['t2t_login'] : false;
			$params['isLogin'] = (isset($_SESSION['t2t']['uEmail']) && $_SESSION['t2t']['uEmail']) ? $_SESSION['t2t']['uEmail'] : false;
			$params_ = array();
			parse_str($_SERVER['QUERY_STRING'], $params_);
			$params['params'] = array();
			foreach ($params_ as $key => $value) {
				if(!isset($params[$key])) {
					$params['params'][$key] = $value;
				}
			}
			if($params['transport'] && $params['src'] && $params['dst'] && $params['dt'] && $params['router']) {
				return self::sendRequest(self::SERVER  . '/' . $this->getlang() . '/get/table', $params);
			}
		}
		
		/**
		 * Обработка ajax запросов
		 */
		static function ajaxCatcher()
		{
			if(!isset($_SESSION)) session_start();
			$lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : '';
			if(isset($_REQUEST['do'])) {
				$router = '';
				switch ($_REQUEST['do']) {
					case 'autocomplete': $router = '/' . $lang . '/search/autocomplete'; break;
					case 'tripinfo':	  $router = '/' . $lang . '/get/tripinfo'; 		  break;
					case 'loadmap':		  $router = '/' . $lang . '/get/carmap'; 		  break;
					case 'getfio':		  $router = '/' . $lang . '/invoice/getFio'; 	  break;
					case 'passitem':	  $router = '/invoice/passItem.ejs'; 			  break;
					case 'passitemBus':	  $router = '/invoice/passItemBus.ejs';			  break;
					case 'checkemail':   $router = '/' . $lang . '/get/checkemail';		  break;
					case 'tryreg':
						$params = array();
						$params['email'] = isset($_POST['email']) ? $_POST['email'] : '';
						$params['email'] = filter_var($params['email'], FILTER_VALIDATE_EMAIL) ? $params['email'] : false;
						$params['email'] = base64_encode($params['email']);
						$params['router'] = isset($_POST['router']) ? $_POST['router'] : '';
						$captcha = isset($_POST['captcha']) ? $_POST['captcha'] : '';
						$params['captcha'] = (isset($_SESSION['t2t']['captcha']) && strtolower($captcha) === strtolower($_SESSION['t2t']['captcha']));
						$request = self::sendRequest(self::SERVER . '/' . $lang . '/get/reg', $params);
						echo $request;
						$request = json_decode($request);
						if(isset($request->isAuth) && $request->isAuth) {
							$_SESSION['t2t']['uEmail'] = $request->email;
							$_SESSION['t2t']['t2t_login'] = true;
						}
					break;
					case 'trylogin':
						$params = array();
						$params['email'] = isset($_POST['email']) ? $_POST['email'] : '';
						$params['email'] = filter_var($params['email'], FILTER_VALIDATE_EMAIL) ? $params['email'] : '';
						$params['router'] = isset($_POST['router']) ? $_POST['router'] : '';
						$params['passw'] = isset($_POST['passw']) ? $_POST['passw'] : '';
						$request = self::sendRequest(self::SERVER . '/' . $lang . '/get/login', $params);
						echo $request;
						$request = json_decode($request);
						if(isset($request->isAuth) && $request->isAuth) {
							$_SESSION['t2t']['uEmail'] = $params['email'];
							$_SESSION['t2t']['t2t_login'] = true;
						}
					break;
				}

				if($router)
					echo self::sendRequest(self::SERVER . $router, $_REQUEST);
			}
		}
		
		/**
		 * Возвращает ссылки на CSS стили.
		 * Принемает не обязательный параметр - булевое значение
		 * указывающий поодключать ли стандартные стили.
		 * По умолчанию подключаются.
		 * @param array $exceptions
		 * @return array
		 */
		public function getCssLinks()
		{
			$styles = json_decode(self::sendRequest(self::SERVER . '/get/css'));
			foreach ($this->t2t_styles as $style)
				$styles[] = $style;
			return $styles;
		}
		
		/**
		 * Возвращает ссылки на JavaScript.
		 * Принемает не обязательный параметр - булевое значение
		 * указывающий поодключать ли jQuery.
		 * По умолчанию подключается.
		 * @param array $exceptions
		 * @return array
		 */
		public function getJsLinks()
		{
			return json_decode(self::sendRequest(self::SERVER . '/get/js', array('addJQuery' => $this->addJQuery, 'in_json' => true)));
		}
		
		/**
		 * Обработка заказа билета(ов)
		 */
		static public function buyRouter()
		{
			if(!isset($_SESSION)) session_start();
			if(isset($_POST['transport_type'])) {
				$lang = isset($_POST['sys_lang']) ? $_POST['sys_lang'] : self::LANG_RU;
				$url = self::INVOICE_SERVER . '/' . $lang . '/invoice/index';

				$params = array();
				$params['domain']		  = self::getDomain();
				$params['email']		  = self::getUEmail();
				$params['phone']		  = self::getUPhone();
				$params['name']			  = self::getUName();
				$params['surname']		  = self::getUSurName();
				$params['url']			  = $url;
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
				$params['hashCode'] 		  = self::genHashCode($url);
				header('Location: ' . $url .'?' . http_build_query($params));
			}
		}

		/**
		 * Обработка перенаправления на инвойс (из истории)
		 */
		static public function invoiceRouter()
		{
			if(!isset($_SESSION))session_start();
			$ivId = isset($_GET['ivId']) ? $_GET['ivId'] : 0;
			$lang = isset($_GET['lang']) ? $_GET['lang'] : self::LANG_RU;
			if($ivId && self::getUEmail()) {
				$url = self::INVOICE_SERVER . '/' . $lang . '/invoice/index/' . $ivId;
				$params = array();
				$params['domain']  = self::getDomain();
				$params['email']  = self::getUEmail();
				$params['toBack'] = base64_encode($_SERVER['HTTP_REFERER']);
				$params['hashCode'] = self::genHashCode($url);
				header('Location: ' . $url . '?' . http_build_query($params));
			}
		}

		static public function getCaptcha()
		{
			if(!isset($_GET['captcha']))  return;
			
			if(!isset($_SESSION)) session_start();
			$letters = 'ABCDEFGKIJKLMNOPQRSTUVWXYZ';
			 
			  $caplen = 5;
			  $width = 120;
			  $height = 30;
			  $font = 'captcha_font.ttf';
			  $fontsize = 16;
			  header('Content-type: image/png');
			 
			  $im = imagecreatetruecolor($width, $height);
			  imagesavealpha($im, true);
			  $bg = imagecolorallocatealpha($im, 0, 0, 0, 127);
			  imagefill($im, 0, 0, $bg);
			 
			  putenv( 'GDFONTPATH=' . realpath('.') . '/' );

			  $captcha = '';
			  for ($i = 0; $i < $caplen; $i++)
			  {
			    $captcha .= $letters[ rand(0, strlen($letters)-1) ];
			    $x = ($width - 20) / $caplen * $i + 10;
			    $x = rand($x, $x+4);
			    $y = $height - ( ($height - $fontsize) / 2 );
			    $curcolor = imagecolorallocate( $im, rand(0, 100), rand(0, 100), rand(0, 100) );
			    $angle = rand(-25, 25);
			    imagettftext($im, $fontsize, $angle, $x, $y, $curcolor, $font, $captcha[$i]);
			  }
			  
			  $_SESSION['t2t']['captcha'] = $captcha;
			 
			  imagepng($im);
			  imagedestroy($im);
		}
		
		static public function logout($logaut = false)
		{
			if(!isset($_SESSION)) session_start();
			if(isset($_GET['t2t_logout']) || $logaut) {
				$_SESSION['t2t'] = array();
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			}
		}
	}

?>