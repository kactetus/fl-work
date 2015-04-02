<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Контроллер спарсенных проектов
 */
class Projects extends Frontend_Controller {

	/**
	 *  Конструктор класса
	 */
    function __construct()
    {
    	parent::__construct();
    	$this->load->language('common');
		$this->load->library('Mylib');	
		$this->load->library('pagination');
    }
	
	/**
	 *  Функция по умолчанию
	 */
	public function index()
	{
		redirect('user/projects/page/1');
	}
	
	
	/**
	 *  Получаем параметры с фильтра (is_save - для "сохранить фильтр")
	 */
	private function get_filter_params($is_save = 0)
	{
		//Подключаем библиотеку валидации
		$this->load->library('form_validation');
		
		//Проверяем поля на валидность
		$this->form_validation->set_rules('keywords', 'Keywords', 'xss|trim');
		$this->form_validation->set_rules('sites', lang('is_sites'), 'required');
		$this->form_validation->set_rules('requirements', lang('is_requirements'), 'required');
		if($is_save)
			$this->form_validation->set_rules('filter_name', lang('filter_name'), 'required|alpha_dash|min_length[5]|max_length[20]|xss|trim');
			
		//Если данные указаны неверно, выдаём ошибку 
        if ($this->form_validation->run() == FALSE){
			echo json_encode(array("msg"=>$this->mylib->alerts("error",validation_errors())));
            exit;
		}
		
		//Ключевые слова
		if(empty($_POST['keywords']))
			$this->session->set_userdata('keywords','|none|');
		elseif(!strpos($_POST['keywords']," "))
			$this->session->set_userdata('keywords',$_POST['keywords']);
		else{
			$keywords = mysql_real_escape_string(trim($_POST['keywords']));
			$keywords = explode(" ", $keywords); 
			$this->session->set_userdata('keywords',$keywords);
		}
			
		//Сайты
		$this->session->set_userdata('sites',$_POST['sites']);
		
		
		//Требования
		$req_arr = array();
		foreach($_POST['requirements'] as $key1){
			if(!strpos($key1, ","))
				array_push($req_arr, mysql_real_escape_string(trim($key1)));
			else{
				$key1 = explode(",",$key1);
				foreach($key1 as $key2)
					array_push($req_arr, mysql_real_escape_string(trim($key2)));
			}
		}
		$this->session->set_userdata('requirements', $req_arr);
			
		//Бюджет
		if(!isset($_POST['budget']))
			$this->session->set_userdata('budget','any_budget');
		else
			$this->session->set_userdata('budget',$_POST['budget']);
		
		//Период
		if(!isset($_POST['dates']))
			$this->session->set_userdata('dates','per_day');
		else
			$this->session->set_userdata('dates',$_POST['dates']);
		
		//Имя фильтра
		if($is_save){
			$this->session->set_userdata('filter_name',$_POST['filter_name']);
		}
	}
	
	
	/**
	 *  Функция "применить фильтр" через аякс
	 */
	public function _ajax_filter_agree()
	{ 
		$this->get_filter_params(0);
		echo json_encode(array("msg"=>'ok'));
	}
	
	/**
	 *  Функция "сохранить фильтр" через аякс
	 */
	public function _ajax_filter_save()
	{	
		$this->get_filter_params(1);

		//Загружаем модель с проектами
		$this->load->model('Projects_model','projects');
		
		//Юзер
		$user = $this->session->userdata('is_user_logged');
		
		//Биржи
	    $sites_arr = $this->session->userdata('sites');
		$sites = "";
		foreach($sites_arr as $key1)
			$sites .= $key1." ";
		$sites = trim($sites);
		
		//Ключевые слова
		$keywords_arr = $this->session->userdata('keywords');
		$keywords = "";
		if(is_array($keywords_arr)){
			foreach($keywords_arr as $key2){
				$keywords .= $key2." ";
			}
		}
		else
			$keywords = $this->session->userdata('keywords');
		$keywords = trim($keywords);
				
		//Имя фильтра
		$filter_name  = $this->session->userdata('filter_name');
		
		//Требования
		$requirements_arr = $this->session->userdata('requirements');
		$requirements = "";
		for ($i = 0; $i < sizeof($requirements_arr); $i++){
			if($i == sizeof($requirements_arr)-1){
				$requirements .= $requirements_arr[$i];
				break;
			}
			$requirements .= $requirements_arr[$i].",";
		}
		$requirements = trim($requirements);

		
		//Бюджет и дата
		$budget       = mysql_real_escape_string(trim($this->session->userdata('budget')));
		$dates        = mysql_real_escape_string(trim($this->session->userdata('dates')));
		
		//Сохраняем фильтр
		$this->projects->save_filter($user, $sites, $keywords, $requirements, $budget, $dates, $filter_name);
		
		echo json_encode(array("msg"=>'ok'));
	}

	
	/**
	 *  Функция вывода проектов с пагинацией и фильтрами
	 */
	public function page($page_num = 1)
	{	
		//Загружаем модель с проектами
		$this->load->model('Projects_model','projects');
		
		//Переприсваивание
		if(!$this->session->userdata('sites'))
			$sites = array("odesk","elance","freelance","guru");
		else 
			$sites = $this->session->userdata('sites');
		$user         = $this->session->userdata('is_user_logged');	
		$keywords     = $this->session->userdata('keywords');
		$requirements = $this->session->userdata('requirements');
		$budget       = mysql_real_escape_string(trim($this->session->userdata('budget')));
		$dates        = mysql_real_escape_string(trim($this->session->userdata('dates')));

		//Длины массивов
		$sites_len    = sizeof($sites);
		$keywords_len = sizeof($keywords);
		$requirements_len = sizeof($requirements);
		
		
		//Кол-во объявлений
		$total_rows = $this->projects->get_num_rows($sites, $sites_len, $keywords, $keywords_len, $requirements, $requirements_len, $dates, $budget);
		$total_rows = $total_rows[0]->ROW_NUMS;
		
		//Лимит объявлений на странице
		if(!$this->session->userdata('per_page'))
			$this->session->set_userdata('per_page', "10");

		//Настройки пагинации
		$p_config['base_url']   = site_url('user/projects/page/');
		$p_config['total_rows'] = $total_rows;
		$p_config['per_page']   = $this->session->userdata('per_page');
		
		$page_num = ($page_num - 1)*1; //для нормального START (2-ая страница это (2-1)*10 позиция) 
		$start     = $page_num * $p_config['per_page']; 
		
		//Результат селекта из таблиц с объявлениями
		$selected_data = $this->projects->select_all_data($sites, $sites_len, $keywords, $keywords_len, $requirements, $requirements_len, $dates, $budget, $start, $p_config['per_page']); 
		
		//Динамически добавляем ассоциативный индекс со значением Y/N в массив каждой строки
		foreach($selected_data as $key=>&$value)
			$value->is_favorite = $this->projects->is_favorite_project($user, $value->id);
		
		//Генерация ссылок пагинации
		$this->pagination->initialize($p_config); 
		$pagination = $this->pagination->create_links();
		
		//Выводим фильтры
		$selected_filters = $this->projects->select_filters_by_user($user);
		
		//Собираем страницу по кускам
		$this->template->title($this->session->userdata('is_user_logged')." | Fl-Work");
		$this->template->set_metadata('description',lang('description4'),'meta');
		$this->template->set_metadata('keywords',lang('keywords4'), 'meta');
		
        $this->template
             ->set_partial('menu_top', 'user/menu_top')
			 ->set_partial('menu_left', 'user/menu_left')
			 ->set_partial('filters', 'user/filters', array('selected_filters'=>$selected_filters))
             ->build('user/projects', array('selected_data'=>$selected_data, 'pagination'=>$pagination, 'per_page'=>$p_config['per_page']));
	}
	
	/**
	 *  Определение кол-ва объявлений на странице
	 */
	public function set_per_page($per_page)
	{
		$this->session->set_userdata('per_page', $per_page);
		redirect('user/projects/page/1');
	}
	
	/**
	 *  Использование сохранённого фильтра
	 */
	public function go_filter($id)
	{
		//Загружаем модель с проектами
		$this->load->model('Projects_model','projects');
		
		//Получаем данные фильтра
		$filter_arr = $this->projects->select_filters_by_id($id);
		
		//Выбираем все данные из массива с фильтром
		foreach($filter_arr as $filter){
			//Ключевые слова
			if(!strpos($filter['keys']," "))
				$this->session->set_userdata('keywords',$filter['keys']);
			else{
				$keywords = mysql_real_escape_string(trim($filter['keys']));
				$keywords = explode(" ", $keywords); 
				$this->session->set_userdata('keywords',$keywords);
			}
				
			//Сайты
			$sites_arr = array();
			if(!strpos($filter['sites']," "))
				array_push($sites_arr, mysql_real_escape_string(trim($filter['sites'])));
			else
				$sites_arr = explode(" ",$filter['sites']);
			$this->session->set_userdata('sites', $sites_arr);
			
			//Требования
			$req_arr = array();
			if(!strpos($filter['category'],","))
				array_push($req_arr, mysql_real_escape_string(trim($filter['category'])));
			else
				$req_arr = explode(",",$filter['category']);
			$this->session->set_userdata('requirements', $req_arr);
				
			//Бюджет
			$this->session->set_userdata('budget',$filter['price']);
			
			//Период
			$this->session->set_userdata('dates',$filter['dates']);
		}
			
		redirect("user/projects");
	}
	
	/**
	 *  Удаление фильтра по ссылке через аякс
	 */
	public function _ajax_del_filter()
	{
		//Загружаем модель с проектами
		$this->load->model('Projects_model','projects');
		
		//Определяем id
		$id = $_POST['filter_id'];
		
		//Удаляем фильтр
		$this->projects->delete_filter($id, $this->session->userdata('is_user_logged'));
		
		echo json_encode(array("msg"=>'ok'));
	}
	
	/**
	 *  Сохранение проекта через аякс
	 */
	public function _ajax_save_project()
	{
		//Загружаем модель с проектами
		$this->load->model('Projects_model','projects');
		
		//Определяем id
		$id = $_POST['project_id'];
		
		//Определяем биржу
		switch($_POST['project_site']){
			case 'odesk': $site = "odesk";     break;
			case 'elanc': $site = "elance";    break;
			case 'freel': $site = "freelance"; break;
			case 'guru':  $site = "guru";      break;
			default:      $site = "odesk";     break;
		}
		
		//Определяем юзера, дату и время проекта
		$username = $this->session->userdata('is_user_logged');
		$date = date('Y-m-d');
		$time = date("H:i:s");
		
		//Сохранить проект
		$this->projects->save_project($username, $site, $id, $date, $time);
		
		echo json_encode(array("msg"=>'ok'));
	}
}