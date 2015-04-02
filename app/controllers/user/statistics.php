<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Контроллер статистик
 */
class Statistics extends Frontend_Controller {

	/**
	 *  Конструктор класса
	 */
    function __construct()
    {
    	parent::__construct();
    	$this->load->language('common');
		$this->load->library('Mylib');	
		$this->load->model('Statistics_model','statistics');
    }
	
	/**
	 *  Функция по умолчанию
	 */
	public function index()
	{
		redirect('user/statistics/sites');
	}
	
	/**
	 *  Функция генерации страницы для всех статистик
	 */
	private function building($view, $array)
	{	  	
		//Тайтл и метаданные для head
		$this->template->title($this->session->userdata('is_user_logged')." | Fl-Work");
		$this->template->set_metadata('description',lang('description3'),'meta');
		$this->template->set_metadata('keywords', lang('keywords3'), 'meta');
		
		//Собираем страницу по кускам
        return $this->template
				 ->set_partial('menu_top', 'user/menu_top')
				 ->set_partial('menu_left', 'user/menu_left')
				 ->build($view, $array);
	}

	
	/**
	 *  Статистика по кол-ву проектов за опр. период через аякс
	 */
	public function _ajax_build_sites()
	{
		//Идентифицируем переменные и массивы
		$date_type  = mysql_real_escape_string(trim($_POST['dates']));
		$result     = $this->statistics->select_count_projects($date_type);
		
		//Объявляем динамически формируемые массивы
		$categories = array();
		$series     = array();
		$summary    = array();
		$is_first   = TRUE;
		
		//Выбираем формат даты
		switch($date_type){
			case 'per_day':    $date_format = "Hч. (d)";  break;
			case 'per_week':   $date_format = "l(d)";  break;
			case 'per_month':  $date_format = "F(d)";  break;
			case 'over_month': $date_format = "F(j)";  break;
			default: 		   $date_format = "F(j)";  break;
		}
		
		//Формируем массивы с данными для статистики	
		foreach($result as $key => $site)
		{
		    $tmp = array();
			$sum = 0;
			foreach($site as $value) {
				if($is_first)
					array_push($categories, date($date_format, strtotime($value['interval_start'])));
					
				array_push($tmp, $value['projects_count']*1);
				$sum += $value['projects_count']*1;
			}
			$is_first      = FALSE;
            $series[$key]  = $tmp;
            $summary[$key] = $sum ;			
		}
		
		//Строим статистику и отправляем в json
	    $build = array(
			'title' => array(
					'text'=>'Количество объявлений',
					),
		    'xAxis' => array(
					'categories' => $categories
					),
			'labels' => array(
				'items' => array(
					'html'=>'Всего:',
					'style'=> array(
						'left'=> '-10px',
						'top' => '-50px',
						'color'=> "black"
					)
				)
			),
		    'series'=> array(
				array(
					'type'=> 'column',
					'name'=> 'Odesk',
					'data'=> $series['odesk']
				), array(
					'type'=> 'column',
					'name'=> 'Elance',
					'data'=> $series['elance']
				), array(
					'type'=>'column',
					'name'=>'Guru',
					'data'=> $series['guru']
				), array(
					'type'=> 'column',
					'name'=> 'Freelance',
					'data'=> $series['freelance']
				), array(
					'type'=> 'pie',
					'name'=> 'Всего объявлений',
					'data'=> array(
						   array(
							'name'=> 'Odesk',
							'y'=> $summary['odesk'],
							'color'=> "#7cb5ec"
						), array(
							'name'=> 'Elance',
							'y'=> $summary['elance'],
							'color'=> "#434348"
						), array(
							'name'=> 'Guru',
							'y'=> $summary['guru'],
							'color'=> "#90ed7d"
						), array(
							'name'=> 'Freelance',
							'y'=> $summary['freelance'],
							'color'=> "#f7a35c"
						)
					),
					'center'=> array(20, 10),
					'size'=> 80,
					'showInLegend'=> false,
					'dataLabels'=> array(
						'enabled'=> false
					)
				)
			)
		);
		echo json_encode($build);
	}
	
	
	/**
	 *  Статистика по кол-ву проектов за опр. период
	 */
	public function sites()
	{	  	
         $this->building("user/sites_statistic",array());
	}
	
	
	/**
	 *  Статистика по средней величине ЗП с одеска за опр. период через аякс
	 */
	public function _ajax_build_budget()
	{
		//Идентифицируем переменные и массивы
		$date_type = mysql_real_escape_string(trim($_POST['dates']));
		$result = $this->statistics->select_average_budget($date_type);
		$categories = array();
		$series = array();
		
		//Выбираем формат даты
		switch($date_type){
			case 'per_day':    $date_format = "Hч. (d)";  break;
			case 'per_week':   $date_format = "l(d)";  break;
			case 'per_month':  $date_format = "F(d)";  break;
			case 'over_month': $date_format = "F(j)";  break;
			default: 		   $date_format = "F(j)";  break;
		}
		
		//Формируем массивы с данными для статистики
		foreach($result as $key=>$value){
			array_push($categories, date($date_format, strtotime($value['interval_start'])));
			array_push($series, $value['project_price']*1);
		}
		
		//Строим статистику и отправляем в json
		$build = array(
			'title' => array(
					'text'=>'Бюджет',
					'x'=>-20
					),
			'subtitle'=> array(
					'text'=> '',
					'x'=>-20
					),
			'xAxis' => array(
					'categories' => $categories
					),
			'yAxis' => array(
					'title' =>array(
						'text' => 'Фиксированный ($)'
					),
					'min'=>0,
					'plotLines' => array(array(
						'value'=> 0,
						'width'=> 1,
						'color'=> '#808080'
					))
			),
			'tooltip'=> array(
					'valueSuffix'=> '$'
					),
			'legend'=> array(
					'layout'=> 'vertical',
					'align' => 'right',
					'verticalAlign' => 'middle',
					'borderWidth' => 0
					),
			'series'=> array(array(  
					'name'=>'Odesk', 
					'data'=>$series))
		);
		
		echo json_encode($build);
	}
	
	
	/**
	 *  Статистика по средней величине ЗП с одеска за опр. период
	 */
	public function budget()
	{	
		$this->building("user/budget_statistic",array());	
	}
	
	
	/**
	 *  Статистика по кол-ву проектов по категориям за опр. время через AJAX
	 */
	public function _ajax_build_category()
	{
		//Идентифицируем переменные и массивы
		$date_type  = mysql_real_escape_string(trim($_POST['dates']));
		$result     = $this->statistics->select_categories($date_type);
		
		//Выбираем формат даты
		switch($date_type){
			case 'per_day':    $date_format = "Hч. (d)";  break;
			case 'per_week':   $date_format = "l(d)";  break;
			case 'per_month':  $date_format = "F(d)";  break;
			case 'over_month': $date_format = "F(j)";  break;
			default: 		   $date_format = "F(j)";  break;
		}
		
		//Строим статистику и отправляем в json
	    $build = array(
			'chart'=> array(
				'type'=> 'column'
			),
			'title' => array(
					'text'=>'Распределение по языкам и технологиям программирования',
					),
			'subtitle'=> array(
					'text'=> '',
					),
			'xAxis'=> array(
				'type'=> 'category',
				'labels'=> array(
					'rotation'=> -45,
					'style'=> array(
						'fontSize'=> '13px',
						'fontFamily'=> 'Verdana, sans-serif'
					)
				)
			),
			'yAxis' => array(
				'min'=>0,
				'title' =>array(
					'text' => 'Кол-во объявлений'
				)
			),
			'tooltip'=> array(
					'pointFormat'=> 'Кол-во проектов: <b>{point.y:.1f}</b>',
					),
			'legend'=> array(
					'enabled'=> false,
					),
		    'series'=> array(
				array(
				'name'=> 'Число вакансий',
				'data'=> array(
					array('PHP', $result[0]['PHP']*1),
					array('Ruby', $result[0]['RUBY']*1),
					array('Python', $result[0]['PYTHON']*1),
					array('JavaScript', $result[0]['JAVASCRIPT']*1),
					array('HTML/CSS', $result[0]['HTML']*1),
					array('Databases', $result[0]['DBASE']*1),
					array('Mobile', $result[0]['MOBILE']*1),
					array('Java', $result[0]['JAVA']*1),
					array('C#/.NET', $result[0]['CSHARP']*1),
					array('CMS', $result[0]['CMS']*1),
					array('ActionScript', $result[0]['FLASH']*1),
					array('C/C++', $result[0]['CPLUS']*1),
					array('VBA/VBN/VBS', $result[0]['VBA']*1),
					array('SEO', $result[0]['SEO']*1),
					array('Administration', $result[0]['ADMINISTRATIONS']*1),
				),
				'dataLabels'=> array(
					'enabled'=> true,
					'rotation'=> -90,
					'color'=> '#000000',
					'align'=> 'right',
					'x'=> 4,
					'y'=> -33,
					'style'=>array(
						'fontSize'=> '11px',
						'fontFamily'=> 'Verdana, sans-serif',
						'textShadow'=> '0 0 3px black'
					)
				)
			)
		)
		);
		echo json_encode($build);
	}
	
	
	/**
	 *  Статистика по кол-ву проектов по категориям за опр. время
	 */
	public function categories()
	{	  	
         $this->building("user/category_statistic",array());
	}
	
	
	/**
	 *  Статистика по кол-ву фикс и почас. ЗП за опр. время через аякс
	 */
	public function _ajax_build_b_type()
	{
		//Идентифицируем переменные и массивы
		$date_type  = mysql_real_escape_string(trim($_POST['dates']));
		$fixed_result  = $this->statistics->select_fixed_budget($date_type);
		$hourly_result = $this->statistics->select_hourly_budget($date_type);
		
		//Объявляем динамически формируемые массивы
		$categories = array();
		$fixed  = array();
		$hourly = array();
		
		//Выбираем формат даты
		switch($date_type){
			case 'per_day':    $date_format = "Hч. (d)";  break;
			case 'per_week':   $date_format = "l(d)";  break;
			case 'per_month':  $date_format = "F(d)";  break;
			case 'over_month': $date_format = "F(j)";  break;
			default: 		   $date_format = "F(j)";  break;
		}
		
		//Формируем массивы с данными для статистики
		foreach($fixed_result as $key=>$value){
			array_push($categories, date($date_format, strtotime($value['interval_start'])));
			array_push($fixed, $value['projects_count']*1);
		}


		//Формируем массивы с данными для статистики
		foreach($hourly_result as $key=>$value){
			array_push($hourly, $value['projects_count']*1);
		}
		
		
		//Строим статистику и отправляем в json
	    $build = array(
			'title' => array(
					'text'=>'Тип бюджета',
					'x'=>-20
					),
			'subtitle'=> array(
					'text'=> '',
					'x'=>-20
					),
		    'xAxis' => array(
					'categories' => $categories
					),
			'yAxis' => array(
					'title' =>array(
						'text' => 'Кол-во проектов'
					),
					'min'=>0,
					'plotLines' => array(array(
						'value'=> 0,
						'width'=> 1,
						'color'=> '#909090'
					))
			),
			'tooltip'=> array(
					'valueSuffix'=> ''
					),
			'legend'=> array(
					'layout'=> 'vertical',
					'align' => 'right',
					'verticalAlign' => 'middle',
					'borderWidth' => 0
					),
		    'series'=> array(
				array(  
					'name'=>'Почасовая', 
					'data'=>$hourly
				), array(
					'name'=>'Фиксированная', 
					'data'=>$fixed
				)
			)
		);
		echo json_encode($build);
	}
	
	
	/**
	 *  Статистика по кол-ву фикс и почас. ЗП за опр. время
	 */
	public function budget_types()
	{	  	
         $this->building("user/b_type_statistic",array());
	}
	
}