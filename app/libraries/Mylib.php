<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Пользовательская библиотека
 */
class Mylib {
	
	//Переменная инстанции CI
	private $_ci;
	
	/**
	 *  Singleton Pattern
	 */
    public function __construct()
    {
		$this->_ci =& get_instance();
		log_message('debug', 'Mylib class Initialized');
    }
	
	/**
	 * Генерация пароля
	 */
	public function generate_pass($nchars)
	{
		//Выходная строка
		$str = "";
		
		//Массив с символами
		$arr = array("a","b","c","d","e","f","g","h","i","j",
					 "k","l","m","n","o","p","q","r","s","t",
					 "u","v","w","x","y","z","1","2","3","4",
					 "5","6","7","8","9","0");

		//Генерируем строку в каптче
		for($i = 0; $i <= $nchars; $i++){
			$str .= $arr[rand(0,35)];
		}	
		
		return $str;
	}
	
	
	/**
	 *  Функция оповещения
	 */
	public function alerts($type, $msg)
	{
		switch($type){
			case 'error':    $result = "<div class='alert alert-danger'>".$msg."</div>"; break;
			case 'success':  $result = "<div class='alert alert-success'>".$msg."</div>";break;
			case 'warning':  $result = "<div class='alert alert-warning'>".$msg."</div>";break;
			case 'info':     $result = "<div class='alert alert-info'>".$msg."</div>";   break;
		}

		return $result;
	}	
	
}