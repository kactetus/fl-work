<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Контроллер домашней страницы для гостя
 */
class Home extends MY_Controller 
{
	/**
	 *  Конструктор класса
	 */
    function __construct()
    {
    	parent::__construct();
    	$this->load->language('common'); 

		//Если сессия открыта, редиректим на страницу проектов
		if($this->session->userdata('is_user_logged'))
			redirect('user/projects');		
    }
	
	/**
	 *  Функция по умолчанию
	 */
	public function index()
	{

		//Если в браузере найдена кука и при этом нет сессии, то создаём её и перенаправляем
		 if(!empty($_COOKIE['remember'])){
			$this->load->model("Auth_model","auth");
			$result = $this->auth->check_cookie($_COOKIE['remember']);
			 foreach($result as $row){
				 if(!empty($row['EMAIL'])){
					 $this->session->set_userdata('is_user_logged',$row['EMAIL']);
					 redirect("user/projects");
				 }   
			}			
		 }
 
		//Капча от гугла для регистрации
		require_once('app/libraries/Recaptchalib.php');	
		$publickey = "6LfU8e8SAAAAANaGiyKttK7b18WRT1OJKxUk7vsJ";
		$captcha = recaptcha_get_html($publickey);
		
		//Собираем страницу по кускам
		$this->template->title(lang('home_site_header'));
		$this->template->set_metadata('description',lang('description1'),'meta');
		$this->template->set_metadata('keywords',lang('keywords1'), 'meta');
        $this->template
             ->set_partial('menu', 'menu')
             ->set_partial('container', 'container')
			 ->set_partial('footer', 'footer')
             ->build('home', array('captcha'=>$captcha));
	}
}