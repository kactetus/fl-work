<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Контроллер регистрации пользователей
 */
class Registration extends MY_Controller
{
	/**
	 * Конструктор класса
	 */
    function __construct()
    {
    	parent::__construct();
    	$this->load->language('common'); 
		$this->load->library('Mylib');		
    }
	
	/**
	 * Результат проверки каптчи
	 */
	private function captcha()
    {
		//Подключаем библиотеку капчи
		require_once('app/libraries/Recaptchalib.php');

		$privatekey = "6LfU8e8SAAAAAIVKZW9gJcLQ7VPZ0QSEWx17rANR";
		$resp = recaptcha_check_answer ($privatekey,
									$_SERVER["REMOTE_ADDR"],
									$_POST["recaptcha_challenge_field"],
									$_POST["recaptcha_response_field"]);
		
		//Если невалидная, то ошибка		
		if (!$resp->is_valid){
			echo json_encode(array("msg"=>$this->mylib->alerts("error",lang('captcha_error'))));
			exit;
		}
		
		return true;
	}
	
	/**
	 *  Проверка social_id на уникальность
	 */
	private function check_social_id($social_id, $fname, $lname)
	{	
		//проверяем не занят ли такой social_id адрес в USERS
		$this->load->model('Registration_model','registration');
        $data2 = $this->registration->get_by_social($social_id);
        
        //проверяем если массив не пустой, значит такой social_id существует в базе
        if (!empty($data2)){
			//Записываем в сессию
			$this->session->set_userdata('is_user_logged',$fname." ".$lname);
			redirect('user/projects');
		}
		
		return true;
	}
	
	
	/**
	 *  Регистрация с помощью соц. сетей
	 */
	public function reg_by_social()
	{
		//Получение данных с авторизации в соц сетях
		$s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
		$user = json_decode($s, true);
		
		//Присваиваем переменным внешние данные
		$email     = $user['email'];
		$social_id = $user['identity'];
		$fname     = $user['first_name'];
		$lname     = $user['last_name'];
		$pass      = md5($this->mylib->generate_pass(20)."pass");
		$ip        = $_SERVER['REMOTE_ADDR'];
		$date      = date("Y-m-d");
		$cookies   = 0;
		
		//Проверяем social_id на существование и логинимся, если есть
		$this->check_social_id($social_id, $fname, $lname);
		
		//Проверяем почту на существование
		$this->load->model('Registration_model','registration');
        $data2 = $this->registration->get_by_email($email);
		
		if(empty($data2)){
			//Подключаем модель с регистрацией
			$this->load->model('Registration_model', 'registration'); 
			//Регистрируем юзера и редиректим
			$this->registration->register($email, $pass, $ip, $date, $cookies, $social_id);
			
			//Записываем в сессию
			$this->session->set_userdata('is_user_logged',$fname." ".$lname);
		}
		else
			$this->session->set_userdata('is_user_logged',$fname." ".$lname);
		
		redirect('user/projects');
	}
	
	
	/**
	 *  Проверка Email на уникальность
	 */
	private function check_mail($email)
	{	
		//проверяем не занят ли такой email адрес в USERS
		$this->load->model('Registration_model','registration');
        $data2 = $this->registration->get_by_email($email);
        
        //проверяем если массив не пустой, значит такой email существует в базе и выводим сообщение о том что он занят
        if (!empty($data2)){ 
			echo json_encode(array("msg"=>$this->mylib->alerts("error",lang('email_is_exist'))));
            exit;
        }	
		return true;
	}
	
	
	/**
	 *  Регистрация юзера через аякс
	 */
	public function _ajax_do_registration()
	{
		//Подключаем библиотеку валидации
		$this->load->library('form_validation');
		
		//Проверяем поля на валидность
		$this->form_validation->set_rules('reg_email', 'Email','required|valid_email|xss|trim');
		$this->form_validation->set_rules('reg_pass1',  lang('password'), 'required|min_length[5]|xss|trim|matches[reg_pass2]');
		$this->form_validation->set_rules('reg_pass2', 'Password Confirmation', '');

		//Если данные указаны неверно, выдаём ошибку 
        if ($this->form_validation->run() == FALSE){
		    echo json_encode(array("msg"=>$this->mylib->alerts("error",validation_errors())));
            return;
		}
		
		//Присваиваем переменным внешние данные
		$email   = $this->input->post('reg_email');
		$pass    = $this->input->post('reg_pass1');
		$ip      = $_SERVER['REMOTE_ADDR'];
		$date    = date("Y-m-d");
		$cookies = 0;
		
		//Проверяем почту на существование
		$this->check_mail($email);
		
		//Результат проверки капчи
		$resp = $this->captcha();
		
		//Подключаем модель с регистрацией
		$this->load->model('Registration_model', 'registration'); 
		
		//Регистрируем юзера и редиректим
		$this->registration->register($email, sha1($pass), $ip, $date, $cookies);
		
		//Отправка данных на почту и редирект
		$to= "Dear <$email>";
		$subject = "Fl-Work | ".lang("registration");
		$message = "<p style='color:green;'>".lang('registration_success')."<br>".lang("your_pass").": $pass</p>";
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: Password reminder <admin@fl-work.com>\r\n";
		mail($to, $subject, $message, $headers);
		
		//Записываем в сессию
		$this->session->set_userdata('is_user_logged',$email);
		
		//Вывод сообщения
		echo json_encode(array("msg"=>'ok')); 
	}
}