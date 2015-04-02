<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_include_path(get_include_path() . PATH_SEPARATOR .  realpath(APPPATH.'third_party/'));

session_start();

//Подключаем Oauth
require_once 'Zend/Oauth/Consumer.php';
require_once 'Zend/Json.php';

/**
 *  Контроллер Одеска
 */
class Odesk {
	
	//Переменная инстанции CI
	private $_ci;
	
	/**
	 *  Singleton
	 */
    public function __construct()
    {
		$this->_ci =& get_instance();
		log_message('debug', 'Odesk class Initialized');
    }
	
	/**
	 * Парсим вакансии из Freelancercom
	 */
	public function parse()
	{
		$consumerKey = 'f555794e0f5cb09df81811ca10a6dd11'; // consumer key, got in console
		$consumerSec = 'beda76600b8d9210'; // consumer secret, got in console with key
		$sigMethod   = 'HMAC-SHA1'; // signature method, e.g. HMAC-SHA1
		$callbackUrl = 'http://fl-work.esy.es/user/projects'; // callback url, full url to your script, e.g. http://localhost/oauth.php
		$url         = 'https://www.odesk.com/api/profiles/v2/search/jobs';
		
		$requestTokenUrl        = 'https://www.odesk.com/api/auth/v1/oauth/token/request';
		$accessTokenUrl         = 'https://www.odesk.com/api/auth/v1/oauth/token/access';
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
		 
		// Get request token
		if (!isset($_SESSION['REQUEST_TOKEN']) && !isset($_SESSION['ACCESS_TOKEN'])) {
			$token = $consumer->getRequestToken();

			$_SESSION['REQUEST_TOKEN'] = serialize($token);

			$consumer->redirect();
		}

		// Get access token
		if (!empty($_GET) && isset($_SESSION['REQUEST_TOKEN'])) {
			$token = $consumer->getAccessToken(
						 $_GET,
						 unserialize($_SESSION['REQUEST_TOKEN'])
					 );

			// Serialize and save token
			$_SESSION['ACCESS_TOKEN'] = serialize($token);
			// Now that we have an Access Token, we can discard the Request Token
			$_SESSION['REQUEST_TOKEN'] = null;
		}

		// Make an example GET request to API
		// We configure parameters and Zend_Http_Client manually,
		// but you can use your own preferred method and logic
		if (!empty($_SESSION['ACCESS_TOKEN'])) {
			$token = unserialize($_SESSION['ACCESS_TOKEN']);
			$t  = $token->getToken();
			$ts = $token->getTokenSecret();

			$params = array(
						'oauth_consumer_key'    => $consumerKey,
						'oauth_signature_method'=> $sigMethod,
						'oauth_timestamp'       => time(),
						'oauth_nonce'           => substr(md5(microtime(true)), 5),
						'oauth_callback'        => $callbackUrl,
						'oauth_token'           => $t,
						'q'=>'php',
						'paging'=>'0;50'
			);

			ksort($params);

			$method = 'GET';
			$secret_key     = $consumerSec . '&' . $ts;
			$params_string  = http_build_query($params);

			$base_string= $method . '&' . urlencode($url) . '&' . urlencode($params_string);
			$signature  = base64_encode(hash_hmac('sha1', $base_string, $secret_key, true));

			$params['oauth_signature'] = $signature;

			$params_string = http_build_query($params);
			$url .= '?' . $params_string;

			$client = new Zend_Http_Client();
			$client->setUri($url);
			$client->setMethod(Zend_Http_Client::GET);
			$response = $client->request();
		 
			$data = Zend_Json::decode($response->getBody());
			return $data;
		}
	}
	
}