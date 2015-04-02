<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Модель статистики
 */
class Statistics_model extends CI_Model
{

	/**
	 * 	 Динамически создаём временну таблицу
	 *	 $startdate - начало интервала
	 *   $enddate   - конец интервала
	 *   $step      - шаг
	 *   $time_unit - тип отнимаемой даты
	 *   $date_type - пост переменная типа даты
	 */ 
	private function creating_table($date_type) 
	{
		//Удаляем временную таблицу, если существует
		$this->db->query("DROP TEMPORARY TABLE IF EXISTS time_intervals");
		
		//Создаём временную таблицу, если не существует
		$this->db->query("CREATE TEMPORARY TABLE IF NOT EXISTS time_intervals(interval_start timestamp, interval_end timestamp)");		

		switch($date_type){
			case 'per_day':    { $time_unit = "day";   $step = "hour"; }  break;
			case 'per_week':   { $time_unit = "week";  $step = "day"; }   break;
			case 'per_month':  { $time_unit = "month"; $step = "day"; }   break;
			case 'over_month': { $time_unit = "year";  $step = "month"; } break;
			default: 		   { $time_unit = "year";  $step = "month"; } break;
		}
		
		//Текущая дата
		$endDate  = time();
		
		//Изначальная дата
		$startDate = strtotime("-1 $time_unit", $endDate);
		
		//Вставляем в базу интервалы, пока не окончится диапазон дат
		while($startDate <= $endDate){	
			$nextDate = strtotime("+ 1 $step", $startDate);		
			$this->db->set('interval_start', date("Y-m-d H:i:s", $startDate));
			$this->db->set('interval_end', date("Y-m-d H:i:s", strtotime('-1 second', $nextDate)));
			$this->db->insert('time_intervals'); 
			$startDate  = $nextDate;
		}
		
		return true;
	}
	
	
	/**
	 * 	 Посчитать среднюю стоимость проектов по датам
	 */ 
	public function select_average_budget($date_type)
	{ 	
		$this->creating_table($date_type);
		
		$str = "SELECT i.interval_start,      
				ROUND(AVG(o.price),2) as project_price
				FROM   odesk as o
				RIGHT  OUTER JOIN time_intervals as i  
				ON     (concat(o.date,' ',o.time) between i.interval_start and i.interval_end)
				WHERE  o.price NOT LIKE 'почасовая'
				GROUP BY i.interval_start ";
		
		return $this->db->query($str)->result_array();	
	}
	
	
	/**
	 * 	 Посчитать кол-во проектов в биржах
	 */
	public function select_count_projects($date_type)
	{ 	
		$this->creating_table($date_type);
		
		$str1 = "SELECT i.interval_start, COUNT(o.id) as projects_count
				FROM   odesk as o
				RIGHT  OUTER JOIN time_intervals as i  
				ON     (CONCAT(o.date,' ',o.time) between i.interval_start and i.interval_end)
				GROUP BY i.interval_start";
		
		$return_odesk = $this->db->query($str1)->result_array();	
		
		$str2 = "SELECT i.interval_start, COUNT(e.id) as projects_count
				 FROM   elance as e
				 RIGHT  OUTER JOIN time_intervals as i  
				 ON     (CONCAT(e.date,' ',e.time) between i.interval_start and i.interval_end)
				 GROUP BY i.interval_start";
				 
		$return_elance = $this->db->query($str2)->result_array();
		
		$str3 = "SELECT i.interval_start, COUNT(g.id) as projects_count
				 FROM   guru as g
				 RIGHT  OUTER JOIN time_intervals as i  
				 ON     (CONCAT(g.date,' ',g.time) between i.interval_start and i.interval_end)
				 GROUP BY i.interval_start";
				 
		$return_guru = $this->db->query($str3)->result_array();
		
		$str4 = "SELECT i.interval_start, COUNT(f.id) as projects_count
				 FROM   freelance as f
				 RIGHT  OUTER JOIN time_intervals as i  
				 ON     (concat(f.date,' ',f.time) between i.interval_start and i.interval_end)
				 GROUP BY i.interval_start";
				 
		$return_freelance = $this->db->query($str4)->result_array();
	
		return array("odesk"=>$return_odesk, "elance"=>$return_elance, "guru"=>$return_guru, "freelance"=>$return_freelance);
	}
	
	
	/**
	 * 	 Посчитать число категорий
	 */
	public function select_categories($date_type)
	{ 	
		$this->creating_table($date_type);
						
		$str = "SELECT  SUM(IF(INSTR(UPPER(requirements),'PHP'),1,0)) as PHP,
						SUM(IF(INSTR(UPPER(requirements),'RUBY'),1,0)) as RUBY,
						SUM(IF(INSTR(UPPER(requirements),'PYTHON'),1,0)) as PYTHON,
						SUM(IF(INSTR(UPPER(requirements),'JAVASCRIPT'),1,0)) as JAVASCRIPT,
						SUM(IF(INSTR(UPPER(requirements),'HTML'),1,0)) +
						SUM(IF(INSTR(UPPER(requirements),'CSS'),1,0)) as HTML,
						SUM(IF(INSTR(UPPER(requirements),'SQL'),1,0)) +
						SUM(IF(INSTR(UPPER(requirements),'ORACLE'),1,0)) + 
						SUM(IF(INSTR(UPPER(requirements),'MONGO'),1,0)) +
						SUM(IF(INSTR(UPPER(requirements),'FOXPRO'),1,0)) +
						SUM(IF(INSTR(UPPER(requirements),'POSTGRE'),1,0)) as DBASE,
						SUM(IF(INSTR(UPPER(requirements),'IOS'),1,0))+
						SUM(IF(INSTR(UPPER(requirements),'ANDROID'),1,0))+
						SUM(IF(INSTR(UPPER(requirements),'OBJECTIVE C'),1,0))+
						SUM(IF(INSTR(UPPER(requirements),'MOBILE'),1,0)) as MOBILE,
						SUM(IF(INSTR(UPPER(requirements),'JAVA'),1,0)) as JAVA,
						SUM(IF(INSTR(UPPER(requirements),'C#'),1,0))+
						SUM(IF(INSTR(UPPER(requirements),'.NET'),1,0)) as CSHARP,
						SUM(IF(INSTR(UPPER(requirements),'WORDPRESS'),1,0))+ 
						SUM(IF(INSTR(UPPER(requirements),'JOOMLA'),1,0))+
						SUM(IF(INSTR(UPPER(requirements),'DRUPAL'),1,0))+
						SUM(IF(INSTR(UPPER(requirements),'CMS'),1,0)) as CMS,
						SUM(IF(INSTR(UPPER(requirements),'FLASH'),1,0))+
						SUM(IF(INSTR(UPPER(requirements),'ACTIONSCRIPT'),1,0)) as FLASH,
						SUM(IF(INSTR(UPPER(requirements),'C++'),1,0)) as CPLUS,
						SUM(IF(INSTR(UPPER(requirements),'SEO'),1,0)) as SEO,
						SUM(IF(INSTR(UPPER(requirements),'LINUX'),1,0)) +
						SUM(IF(INSTR(UPPER(requirements),'XAAMP'),1,0)) +
						SUM(IF(INSTR(UPPER(requirements),'APACHE'),1,0)) +
						SUM(IF(INSTR(UPPER(requirements),'ADMINISTRATION'),1,0)) as ADMINISTRATIONS,
						SUM(IF(INSTR(UPPER(requirements),'VB Script'),1,0)) +
						SUM(IF(INSTR(UPPER(requirements),'VBA'),1,0)) +
						SUM(IF(INSTR(UPPER(requirements),'VB.NET'),1,0)) as VBA
				FROM   (
				         SELECT requirements, CONCAT(o.date,' ',o.time) as x_date
						 ,      o.id
						 FROM   odesk as o
						 WHERE  o.price NOT LIKE 'почасовая'
						 UNION ALL
						 SELECT requirements, CONCAT(e.date,' ',e.time) as x_date
						 ,      e.id
						 FROM   elance as e
						 WHERE  e.price LIKE '%Fixed price%' AND  e.price NOT LIKE '%not sure%'
						 UNION ALL
						 SELECT requirements, CONCAT(g.date,' ',g.time) as x_date
						 ,      g.id
						 FROM   guru as g
						 WHERE  g.price NOT LIKE '%Hours%' AND g.price NOT LIKE '%Not Sure%'
				       ) as t
				RIGHT  OUTER JOIN time_intervals as i  
				ON     (t.x_date between i.interval_start and i.interval_end)";
				 
		return $this->db->query($str)->result_array();
	}
	
	
	/**
	 * 	 Посчитать число проектов с фикс. ЗП
	 */
	public function select_fixed_budget($date_type)
	{ 	
		$this->creating_table($date_type);
		
		$str = "SELECT i.interval_start, COUNT(id) as projects_count
				FROM   (
				         SELECT CONCAT(o.date,' ',o.time) as x_date
						 ,      o.id
						 FROM   odesk as o
						 WHERE  o.price NOT LIKE 'почасовая'
						 UNION ALL
						 SELECT CONCAT(e.date,' ',e.time) as x_date
						 ,      e.id
						 FROM   elance as e
						 WHERE  e.price LIKE '%Fixed price%' AND  e.price NOT LIKE '%not sure%'
						 UNION ALL
						 SELECT CONCAT(g.date,' ',g.time) as x_date
						 ,      g.id
						 FROM   guru as g
						 WHERE  g.price NOT LIKE '%Hours%' AND g.price NOT LIKE '%Not Sure%'
				       ) as t
				RIGHT  OUTER JOIN time_intervals as i  
				ON     (t.x_date between i.interval_start and i.interval_end)
				GROUP BY i.interval_start";
				 
		return $this->db->query($str)->result_array();
	}
	
	
	/**
	 * 	 Посчитать число проектов с почасовой ЗП
	 */
	public function select_hourly_budget($date_type)
	{ 	
		$this->creating_table($date_type);
		
		$str = "SELECT i.interval_start, COUNT(id) as projects_count
				FROM   (
				         SELECT CONCAT(o.date,' ',o.time) as x_date
						 ,      o.id
						 FROM   odesk as o
						 WHERE  o.price LIKE 'почасовая'
						 UNION ALL
						 SELECT CONCAT(e.date,' ',e.time) as x_date
						 ,      e.id
						 FROM   elance as e
						 WHERE  e.price LIKE '%Hourly%' AND e.price NOT LIKE '%not sure%'
						 UNION ALL
						 SELECT CONCAT(g.date,' ',g.time) as x_date
						 ,      g.id
						 FROM   guru as g
						 WHERE  g.price LIKE '%Hours%' AND g.price NOT LIKE '%Not Sure%'
				       ) as t
				RIGHT  OUTER JOIN time_intervals as i  
				ON     (t.x_date between i.interval_start and i.interval_end)
				GROUP BY i.interval_start";
				 
		return $this->db->query($str)->result_array();
	}
}