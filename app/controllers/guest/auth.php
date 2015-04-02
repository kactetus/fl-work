<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Контроллер авторизации пользователей
 */
class Auth extends MY_Controller 
{
	/**
	 *  Конструктор класса
	 */
    function __construct()
    {
    	parent::__construct();
    	$this->load->language('common');
		$this->load->library('Mylib');
    }


	/**
	 *  Авторизация через аякс
	 */
	public function _ajax_login()
	{		
		//Подключаем библиотеку валидации
		$this->load->library('form_validation');
		
		//Проверяем поля на валидность
		$this->form_validation->set_rules('login_email', 'Email','required|xss|trim');
		$this->form_validation->set_rules('login_pass',  'Password', 'required|xss|trim');

		//Если данные указаны неверно, выдаём ошибку 
        if ($this->form_validation->run() == FALSE){
		    echo json_encode(array("msg"=>$this->mylib->alerts("error",validation_errors())));
            return;
		}
		
		//Присваиваем переменным данные с формы
		$email = mysql_real_escape_string($this->input->post('login_email'));
		$pass  = sha1( mysql_real_escape_string($this->input->post('login_pass')));

		//Подключаем модель с авторизацией
		$this->load->model('Auth_model', 'auth'); 
		
		//Проверяем, есть ли такой юзер
		$is_user = $this->auth->auth_user($email, $pass);
        if (!$is_user){
            echo json_encode(array("msg"=>$this->mylib->alerts("error",lang('user_doesnt_exist')))); 
            return;
		}
	
		//Записываем в сессию
		$this->session->set_userdata('is_user_logged',$email);
		
		//Добавляем куку
		if($this->input->post('remember')){
			//Готовим переменную к куке
			$remember= sha1($email."mykey");
			
			//Создаём куку
			setcookie("remember", $remember, time()+86400*14,"/");

			//Добавляем куку
			$data = array('COOKIES'=>$remember);
			$this->auth->add_cookie($data, $email);
		}	
		echo json_encode(array("msg"=>'ok')); 
	}
	
	
	/**
	 * Напоминание пароля
	 */
    public function _ajax_remind_pass()
    {
		//Новая сессия для каптчи
		session_start();
		
		//Подключаем библиотеку валидации
		$this->load->library('form_validation');
		
		//Проверяем поля на валидность
		$this->form_validation->set_rules('rec_email', 'Email', 'required|valid_email|xss|trim');

		//Если данные указаны неверно, выдаём ошибку 
        if ($this->form_validation->run() == FALSE)
        {
			echo json_encode(array("msg"=>$this->mylib->alerts("error",validation_errors())));
            return;
		}
		
		//Присваиваем переменным
		$email = $this->input->post('rec_email');
		$pass  = $this->mylib->generate_pass(10);
		$data  = array('password'=>sha1($pass));

		//Подключаем модель с регистрацией и авторизацией
		$this->load->model('Auth_model', 'auth'); 
		
		//Проверяем, есть ли такая почта и нет ли там social_id
		$is_mail = $this->auth->get_by_email_social_id($email);
        if (empty($is_mail)){
            echo json_encode(array("msg"=>$this->mylib->alerts("error",lang('email_doesnt_exist')))); 
            return;
		}
		
		//Проверяем, правильно ли введена каптча	
		if($_SESSION['randStr'] != $this->input->post('rec_captcha')){
			echo json_encode(array("msg"=>$this->mylib->alerts("error",lang('captcha_error'))));
			return;  
		}
		
		//Восстанавливаем пароль
		$this->auth->remind_pass($email, $data);
		
		//Отправка данных на почту и редирект
		$to= "Dear <$email>";
		$subject = "Fl-Work | ".lang("recover");
		$message = "<p style='color:green;'>".lang("your_new_pass")." : $pass</p>";
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: Password reminder <admin@fl-work.com>\r\n";
		mail($to, $subject, $message, $headers);

		echo json_encode(array("msg"=>$this->mylib->alerts("success",lang('password_recover'))));
		
		return true;
	}
	
	/**
	 *  Выход из профиля
	 */
	public function logout()
	{
		//Удаляем сессию
		$this->session->unset_userdata('is_user_logged');
		$this->session->sess_destroy();
		
		//Удаляем куку
		if(isset($_COOKIE['remember'])){
			//Подключаем модель с регистрацией и авторизацией
			$this->load->model('Auth_model', 'auth'); 
			$data = array("cookies" => "0");
			$this->auth->delete_cookie($_COOKIE['remember'], $data);
			setcookie('remember',"",time()-3600, "/");
		}
		redirect("home");
	}
}