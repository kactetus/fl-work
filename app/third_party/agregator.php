<?php
/**
 * THIS IS AN EXAMPLE FOR OAUTH AUTHENTICATION METHOD VIA WEB USING ZEND FRAMEWORK
 */

# See detailed information about Zend_Oauth and 
# Zend Framework at http://framework.zend.com/manual/en/manual.html
# oDesk just provides an example for querying API "as-is"


/**
 * 	Класс агрегации для крона
 */
class Agregator
{
	// Идентификатор подключения к БД
	private $dbh;
	
	/**
	 * Конструктор класса
	 */
	public function __construct()
	{
		$host   = 'localhost'; //хост
		$dbname = 'fl-work';   //имя базы
		$user   = 'root';      //юзер
		$pass   = '';	       //пароль
		
		//Подключаемся к базе
		$this->dbh = mysql_connect($host, $user, $pass) or die("Невозможно подключиться к базе.");
		mysql_select_db($dbname) or die('Не удалось выбрать базу данных');

		
		//Настройка базового пути на third_paty
		set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__));
		
		//Подключаем нужные библиотеки
		require_once 'Zend/Oauth/Consumer.php';
		require_once 'Zend/Json.php';
	}
	
	
	/**
	 * 	Деструктор класса
	 */
	public function __destruct()
	{
		restore_include_path();
		ini_restore('include_path');
	}
	
	/**
	 * 	Запись всех данных в таблицу
	 */
	private function insert_data($table="empty", $title="empty", $description="empty", $price="empty", $requirements="empty", $category="empty", $date="empty", $time="empty", $url="empty", $type="empty")
	{
		$title        = mysql_real_escape_string(trim($title));
		$title        = str_replace("\n",'', $title);
		$description  = mysql_real_escape_string(trim($description));
		$description  = str_replace("\n",'', $description);
		$price        = mysql_real_escape_string(trim($price));
		$price        = str_replace("\n",'', $price);
		$requirements = mysql_real_escape_string(trim($requirements));
		$requirements = str_replace("\n",'', $requirements);
		$category     = mysql_real_escape_string(trim($category));
		$category     = str_replace("\n",'', $category);
		$time = date("H:i:s");
		
		$str2 = "INSERT INTO $table ( title, 
									  description, 
									  price, 
									  requirements, 
									  category, 
									  date,
									  time, 
									  url, 
									  type) 
							VALUES(   '$title',
									  '$description',
									  '$price',
									  '$requirements',
									  '$category',
									  '$date',
									  '$time',
									  '$url',
									  '$type')"; 
		mysql_query($str2);
	}
	
	/**
	 * 	Проверка на существующие объявления
	 */
	private function check_data($table, $title)
	{
		$title = mysql_real_escape_string(trim($title));
		$str  = "SELECT * 
				  FROM   $table
				  WHERE  UPPER(title)=UPPER(TRIM('$title'))
				  AND date > DATE_SUB(CURDATE(), INTERVAL 20 DAY);";
				 
		$is_exist = 0;
		
		$result = mysql_query($str) or die(mysql_error());
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			foreach ($row as $value) {
				$is_exist = $value;
			}
		}
		
		return $is_exist;
	}
	
	/**
	 *  Добавляем все объявления из одеска
	 */
	private function insert_odesk($data)
	{	
		for($i = 0; $i< sizeof($data['jobs']); $i++){
			//-------------массив с необычными символами и str_replace. в title и description---------------
			//заголовок
			$title = $data['jobs'][$i]['title']; 
			//дата и время объявления
			$date  = substr($data['jobs'][$i]['date_created'], 0, 10);
			$time  = substr($data['jobs'][$i]['date_created'], 11, 8);
			//цена
			if(isset($data['jobs'][$i]['budget']))	
				$price = $data['jobs'][$i]['budget']."$";
			else						
				$price = "почасовая";
			//описание
			$description = $data['jobs'][$i]['snippet'];
			//требования
			if(@$data['jobs'][$i]['skills'][0] == "")
				$requirements =  "не указаны";
			else{
				$requirements = " ";
				for($j = 0; $j < sizeof($data['jobs'][$i]['skills']); $j++){
					$requirements .= $data['jobs'][$i]['skills'][$j]." ";
				}
			}
			//категория
			$category = $data['jobs'][$i]['category'];
			//подробнее
			$url 	  = $data['jobs'][$i]['url'];
			//биржа труда
			$table    = "odesk";
			$type     = "odesk";
			
			//Проверяем, есть ли уже такое объявление
			$is_exist = $this->check_data($table, $title);
			
			//Если нет, то добавляем объявление в таблицу
			if(strlen($is_exist) == 1)
				$this->insert_data($table, $title, $description, $price, $requirements, $category, $date, $time, $url, $type); 
		}
	}
	
	
	/**
	 *  Парсим с одеска
	 */
	public function odesk($jobs_category = 'web_development')
	{
		$consumerKey = 'f555794e0f5cb09df81811ca10a6dd11'; // consumer key, got in console для доступа к API одеска
		$consumerSec = 'beda76600b8d9210'; // consumer secret, got in console with key
		$sigMethod   = 'HMAC-SHA1'; // signature method, e.g. HMAC-SHA1
		$callbackUrl = 'http://fl-work.esy.es/user/projects'; // callback url, full url to your script, e.g. http://localhost/oauth.php
		$url         = 'https://www.odesk.com/api/profiles/v2/search/jobs.json'; //путь к API поиска проектов

		$requestTokenUrl        = 'https://www.odesk.com/api/auth/v1/oauth/token/request'; //ссылка на токен авторизации (данные для логина) - открытый ключ
		$accessTokenUrl         = 'https://www.odesk.com/api/auth/v1/oauth/token/access'; // для аутентификации (для подтверждения) - закрытый ключ
		$userAuthorizationUrl   = 'https://www.odesk.com/services/api/auth';

		$config = array(
					'version'               => '1.0',
					'callbackUrl'           => $callbackUrl,
					'signatureMethod'       => $sigMethod,
					'requestTokenUrl'       => $requestTokenUrl,
					'accessTokenUrl'        => $accessTokenUrl,
					'userAuthorizationUrl'  => $userAuthorizationUrl,
					'consumerKey'           => $consumerKey,
					'consumerSecret'        => $consumerSec
		);

		$consumer = new Zend_Oauth_Consumer($config);
		
		//любой запрос на сервер с помощью этих токенов (токены соединения)
		$t = "e11b0a84db941d1661cfe6bb4b1694fa"; //получили аксес токен после авторизации и аутентификации
		$ts = "93cb47ca32d91835"; //секретный

		//ПАРАМЕТРЫ ПОИСКА проектов
		$params = array(
					'oauth_consumer_key'    => $consumerKey,
					'oauth_signature_method'=> $sigMethod,
					'oauth_timestamp'       => time(),
					'oauth_nonce'           => substr(md5(microtime(true)), 5),
					'oauth_callback'        => $callbackUrl,
					'oauth_token'           => $t,
					'q'=>"category:$jobs_category",
					'paging'=>'0;100'
		);

		ksort($params);

		//формируем строку запроса к API odesk
		$method = 'GET';
		$secret_key     = $consumerSec . '&' . $ts;
		$params_string  = http_build_query($params);

		$base_string= $method . '&' . urlencode($url) . '&' . urlencode($params_string); //кодируем
		$signature  = base64_encode(hash_hmac('sha1', $base_string, $secret_key, true)); //сжимаем

		$params['oauth_signature'] = $signature;

		$params_string = http_build_query($params);
		$url .= '?' . $params_string;

		//отправляем запрос
		$client = new Zend_Http_Client();
		$client->setUri($url); 
		$client->setMethod(Zend_Http_Client::GET);
		$response = $client->request();
		if(!$response) {
			die("odesk error");
		}
		//получаем ответ
		$data = Zend_Json::decode($response->getBody());
		
		//Добавляем данные
		$this->insert_odesk($data);
	}

	/**
	 *  Парсим и добавляем все объявления из guru
	 */
	public function guru()
	{
		//Начало работы с XML
		$doc = new DOMDocument();
		$doc->load( 'http://www.guru.com/pro/ProjectResults.aspx?CID=100&TYP=3' );
		
		//Определяем главный тэг объявления
		$projects = $doc->getElementsByTagName( "item" );
		
		//Добавляем все объявления в таблицу
		foreach( $projects as $project )
		{
			//Заголовок
			$titles = $project->getElementsByTagName( "title" );
			$title  = $titles->item(0)->nodeValue;
			
			//Общие сведения объявления
			$infos = $project->getElementsByTagName( "description" );
			$info  = $infos->item(0)->nodeValue;
			$info  = strip_tags($info);
			
			//Подробнее
			$links = $project->getElementsByTagName( "link" );
			$url   = $links->item(0)->nodeValue;
			
			//Дата и время
			$dates    = $project->getElementsByTagName( "pubDate" );
			$datetime = $dates->item(0)->nodeValue;
			$date     = date("Y-m-d", strtotime($datetime));
			$time     = date("h:m:s", strtotime($datetime."-3 hours"));
			
			//Описание объявления
			$desc_num    = strpos($info, "Category");
			$description = str_replace("Description:", "", substr($info, 0, $desc_num));
			
			//Категория
			$cat_num  = strpos($info, "Required skills");
			$category = str_replace("Category:", "", substr($info, $desc_num, $cat_num-$desc_num));
			
			//Требования
			if(strpos($info, "Hourly budget")){
			  $rec_num      = strpos($info, "Hourly budget");
			  $requirements = str_replace("Required skills:", "", substr($info, $cat_num, $rec_num-$cat_num));
			}
			elseif(strpos($info, "Fixed Price budget")){
			  $rec_num = strpos($info, "Fixed Price budget");
			  $requirements = str_replace("Required skills:", "", substr($info, $cat_num, $rec_num-$cat_num));
			}
			else
				$requirements = substr($info, $cat_num);
			
			//Цена
			$price_types = array("Fixed Price budget:", "Hourly budget:");
			$price_num = strpos($info, "Project type");
			$price = str_replace($price_types, "", substr($info, $rec_num, $price_num-$rec_num));
			
			//Тип и таблица
			$table = "guru";
			$type  = "guru";

			//Проверяем, есть ли уже такое объявление
			$is_exist = $this->check_data($table, $title);

			//Если нет, то добавляем объявление в таблицу
			if(strlen($is_exist) == 1)
				$this->insert_data($table, $title, $description, $price, $requirements, $category, $date, $time, $url, $type); 
		}
	}
		
	/**
	 *  Парсим и добавляем все объявления из elance
	 */
	public function elance()
	{
	//Начало работы с XML
	$doc = new DOMDocument();
	$doc->load('https://www.elance.com/r/rss/jobs/cat-it-programming');
	
	//Определяем главный тэг объявления
	$projects = $doc->getElementsByTagName( "item" );
	
	//Добавляем все объявления в таблицу
	foreach( $projects as $project )
	{
		//Заголовок
		$titles = $project->getElementsByTagName( "title" );
		$title = str_replace("| Elance Job", "", $titles->item(0)->nodeValue);
		
		//Подробнее
		$links = $project->getElementsByTagName( "link" );
		$url   = $links->item(0)->nodeValue;
		
		//Дата и время
		$dates    = $project->getElementsByTagName( "pubDate" );
		$datetime = $dates->item(0)->nodeValue;
		$datetime = date('d.m.Y H:i:s', strtotime($datetime));
		$date     = date("Y-m-d", strtotime($datetime));
		$time     = date("h:m:s", strtotime($datetime));
		
		//Общие сведения объявления
		$infos = $project->getElementsByTagName( "description" );
		$info  = $infos->item(0)->nodeValue;
		$info  = mysql_real_escape_string(trim(strip_tags($info)));

		//Описание объявления
		$desc_num    = strpos($info, "Category");
		$description = str_replace("Description:", "", substr($info, 0, $desc_num));
		
		//Категория
		$cat_num  = strpos($info, "Type and Budget");
		$category = str_replace("Category:", "", substr($info, $desc_num, $cat_num-$desc_num));
		
		//Цена
		$price_num = strpos($info, "Time Left:");
		$price = str_replace("Type and Budget:", "", substr($info, $cat_num, $price_num-$cat_num));
		
		if(empty($price))
			$price = "на сайте";
		
		//Требования
		  $rec_num1      = strpos($info, "Desired Skills");
		  $rec_num2      = strpos($info, "Job ID");
		  $requirements = str_replace("Desired Skills:", "", substr($info, $rec_num1, $rec_num2-$rec_num1));

		//Тип и таблица
		$table = "elance";
		$type  = "elance";
			
		//Проверяем, есть ли уже такое объявление
		$is_exist = $this->check_data($table, $title);

		//Если нет, то добавляем объявление в таблицу
		if(strlen($is_exist) == 1)
			$this->insert_data($table, $title, $description, $price, $requirements, $category, $date, $time, $url, $type);
		}
	}
	
	/**
	 *  Парсим и добавляем все объявления из freelance.ru
	 */
	public function freelance()
	{
		//Начало работы с XML
		$doc = new DOMDocument();
		$doc->load('https://freelance.ru/rss/projects.xml');
		
		//Определяем главный тэг объявления
		$projects = $doc->getElementsByTagName( "item" );
		
		//Добавляем все объявления в таблицу
		foreach( $projects as $project )
		{
			//Категория
			$categories = $project->getElementsByTagName( "category" );
			$category   = trim($categories->item(0)->nodeValue);
			
			if($category != "IT и Программирование" && $category != "Веб-дизайн")
				continue;
			
			//Заголовок
			$titles = $project->getElementsByTagName( "title" );
			$title  = $titles->item(0)->nodeValue;
			
			//Подробнее
			$links = $project->getElementsByTagName( "link" );
			$url   = $links->item(0)->nodeValue;
			
			//Дата и время
			$dates    = $project->getElementsByTagName( "pubDate" );
			$datetime = $dates->item(0)->nodeValue;
			$datetime = date('d.m.Y H:i:s', strtotime($datetime));
			$date     = date("Y-m-d", strtotime($datetime));
			$time     = date("h:m:s", strtotime($datetime."-3 hours"));
			
			//Описание объявления
			$descriptions = $project->getElementsByTagName( "description" );
			$description  = $descriptions->item(0)->nodeValue;
			$description  = preg_replace('/([0-2]\d|3[01])\.(0\d|1[012])\.(\d{4})/', " ", $description);
			$del_arr = array("(IT и Программирование)","(Веб-дизайн)");
			$description  = str_replace($del_arr, "", $description);
			
			//Цена
			$price_num = strpos($description, "Бюджет:");

			if($price_num)
				$price = "в описании";
			else
				$price = "-";
			
			//Требования
			$requirements = "-";

			//Тип и таблица
			$table = "freelance";
			$type  = "freelance";
			
			//Проверяем, есть ли уже такое объявление
			$is_exist = $this->check_data($table, $title);

			//Если нет, то добавляем объявление в таблицу
			if(strlen($is_exist) == 1)
				$this->insert_data($table, $title, $description, $price, $requirements, $category, $date, $time, $url, $type);
		}
	}
}

	//Вставляем новые данные
	$agregate = new Agregator();
	$agregate->odesk('web_development');
	$agregate->odesk('software_development');
	$agregate->elance();
	$agregate->freelance();
	$agregate->guru();