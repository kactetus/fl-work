<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Контроллер сохраненных проектов
 */
class Favorite extends Frontend_Controller 
{
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
		redirect('user/favorite/page/1');
	}

	
	/**
	 *  Функция вывода сохраненных проектов с пагинацией
	 */
	public function page($page_num = 1)
	{
		//Загружаем модель с проектами
		$this->load->model('Projects_model','projects');
		
		//Определяем пользователя
		$user = $this->session->userdata('is_user_logged');	

		//Кол-во объявлений
		$total_rows = $this->projects->get_user_pr_num_rows($user);
		
		//Настройки пагинации
		$p_config['base_url']   = site_url('user/favorite/page/');
		$p_config['total_rows'] = $total_rows;
		$p_config['per_page']   = 10;
		
		$page_num = ($page_num - 1)*1; //для нормального START (2-ая страница это (2-1)*10 позиция)
		$start     = $page_num * $p_config['per_page']; 
		
		//Результат селекта из таблиц с сохраненными объявлениями
		$favorite_data = $this->projects->get_all_favorite_data($user, $start, $p_config['per_page']); 
		
		//В новый массив динамически добавляем массив проектов по id
		$selected_data = array();
		foreach($favorite_data as $project){
			array_push($selected_data,$this->projects->select_project_by_id($project['site'], $project['project_id']));
		}
		
		//Генерация ссылок пагинации
		$this->pagination->initialize($p_config); 
		$pagination = $this->pagination->create_links();
		
		//Собираем страницу по кускам
		$this->template->title($this->session->userdata('is_user_logged')." | Fl-Work");
		$this->template->set_metadata('description',lang('description2'),'meta');
		$this->template->set_metadata('keywords',lang('keywords2'), 'meta');
		
        $this->template
             ->set_partial('menu_top', 'user/menu_top')
			 ->set_partial('menu_left', 'user/menu_left')
             ->build('user/favorite', array('selected_data'=>$selected_data, 'pagination'=>$pagination, 'per_page'=>$p_config['per_page']));
	}
	
	/**
	 *  Удаление проекта через аякс
	 */
	public function _ajax_del_project()
	{
		//Загружаем модель с проектами
		$this->load->model('Projects_model','projects');
		
		//Собираем данные
		$user = $this->session->userdata('is_user_logged');
		$id   = $_POST['project_id'];
		
		//Определяем биржу
		switch($_POST['project_site']){
			case 'odesk': $site = "odesk";     break;
			case 'elanc': $site = "elance";    break;
			case 'freel': $site = "freelance"; break;
			case 'guru':  $site = "guru";      break;
			default:      $site = "odesk";     break;
		}
		
		//Удаляем фильтр
		$this->projects->delete_project($site, $id, $user);
		
		echo json_encode(array("msg"=>'ok'));
	}
}