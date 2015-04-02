<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *  Модель для работы с проектами
 */
class Projects_model extends CI_Model
{
	/**
	 *  Подготовка строки к запросу
	 */
	private function prepare_search_query($sites, $sites_len, $keywords="", $keywords_len, $requirements, $requirements_len, $dates="per_day", $budget="any_budget")
	{
		$str = "";
		for($i = 0; $i < $sites_len; $i++){
			$str .= "SELECT *
					 FROM  ".$sites[$i].
					" WHERE (title LIKE 'fgfdgsgs|g16q' ";
			
			
			//Keywords
			if($keywords == "|none|" || empty($keywords))
						$str .= " ) OR";
			else{
				if(is_array($keywords)){
					for($j = 0; $j < $keywords_len; $j++){
						$str .= " OR UPPER(description) LIKE UPPER('%".$keywords[$j]."%') ";
						$str .= " OR UPPER(title) LIKE UPPER('%".$keywords[$j]."%') ";
					}
				}
				else{
					$str .= " OR UPPER(description) LIKE UPPER('%".$keywords."%') ";
					$str .= " OR UPPER(title) LIKE UPPER('%".$keywords."%') ";
				}
				$str .= ") AND";
			}

			//Requirements
			for($k = 0; $k < $requirements_len; $k++){
				if($k == 0){
					if($requirements[$k] == "anye" || empty($requirements))
						$str .= " (requirements LIKE '%' ";
					else
						$str .= " (UPPER(requirements) LIKE UPPER('%".$requirements[$k]."%') ";
				}
				else 
					$str .= " OR UPPER(requirements) LIKE UPPER('%".$requirements[$k]."%') ";
			}
			$str .= ") ";
			
			//Dates
			switch($dates){
				case 'per_day':    $interval = "STR_TO_DATE(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d')";   break;
				case 'per_week':   $interval = "STR_TO_DATE(DATE_SUB(CURDATE(), INTERVAL 1 WEEK), '%Y-%m-%d')";  break;
				case 'per_month':  $interval = "STR_TO_DATE(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m-%d')"; break;
				case 'over_month': $interval = "2014-04-01"; break;
				default: $interval = "STR_TO_DATE(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '%Y-%m-%d')"; break;
			}
			$str .= " AND (STR_TO_DATE(date,'%Y-%m-%d') BETWEEN $interval AND CURDATE())";
			
			//Budget
			switch($budget){
				case 'fixed_budget': {
					switch($sites[$i]){
						case 'odesk':     $b_val = "price NOT LIKE 'почасовая'"; break;
						case 'Guru':      $b_val = "price NOT LIKE '%Hours%' AND price NOT LIKE '%Not Sure%'"; break;
						case 'Elance':    $b_val = "price LIKE '%Fixed price%' AND price NOT LIKE '%not sure%'"; break;
						case 'Freelance': $b_val = "price LIKE '%в описании%'"; break;
					}
				} break;
				case 'hourly_rate_budget': {
					switch($sites[$i]){
						case 'odesk':     $b_val = "price LIKE 'почасовая'"; break;
						case 'Guru':      $b_val = "price LIKE '%Hours%' AND price NOT LIKE '%Not Sure%'"; break;
						case 'Elance':    $b_val = "price LIKE '%Hourly%' AND price NOT LIKE '%not sure%'"; break;
						case 'Freelance': $b_val = "price LIKE '%-%'"; break;
					}
				} break;
				case 'any_budget':         $b_val = "price LIKE '%'"; break;
				default: $b_val = "price LIKE '%'"; break;
			}
			$str .= " AND ($b_val) ";
			
			// Если последний период цикла, то ничего не добавляем, иначе UNION
			if($i == $sites_len-1){
				$str .= "";
				break;
			}
			else 
				$str .= " UNION ";
		}
		return $str;
	}
	
	/**
	 *  Подсчёт строк результатов поиска
	 */
	public function get_num_rows($sites, $sites_len, $keywords, $keywords_len, $requirements, $requirements_len, $dates, $budget)
	{
		$sub_query = $this->prepare_search_query($sites, $sites_len, $keywords, $keywords_len, $requirements, $requirements_len, $dates, $budget);
		$str = "SELECT COUNT(*) AS ROW_NUMS
				FROM ($sub_query) AS T";
				
		$query = $this->db->query($str);
		return $query->result();
	}
	
	
	/**
	 *  Выборка всех проектов из таблиц с биржами с учётом пагинации
	 */
	public function select_all_data($sites, $sites_len, $keywords, $keywords_len, $requirements, $requirements_len, $dates, $budget,  $start, $per_page)
	{
		$sub_query = $this->prepare_search_query($sites, $sites_len, $keywords, $keywords_len, $requirements, $requirements_len, $dates, $budget);
		$str = "SELECT *
				FROM ($sub_query) AS T
				ORDER BY date DESC, time DESC
				LIMIT $start, $per_page";
				
		$query = $this->db->query($str);
		return $query->result();
	}
	
	
	/**
	 *  Сохранение фильтров
	 */
    public function save_filter($user, $sites, $keywords, $requirements, $budget, $dates, $filter_name)
	{
		$this->db->set('USER',     $user);
		$this->db->set('NAME',     $filter_name);
		$this->db->set('KEYS',     $keywords);
		$this->db->set('PRICE',    $budget);
		$this->db->set('CATEGORY', $requirements);
		$this->db->set('SITES',    $sites);
		$this->db->set('DATES',    $dates);

		$result = $this->db->insert('user_filters');
		
		return true;
	}
	
	/**
	 *  Выборка всех фильтров пользователя
	 */
	public function select_filters_by_user($user)
	{
        $this->db->select('*');
        $this->db->from('user_filters');
        $this->db->where('user', $user); 
        $query = $this->db->get();
        
        return $query->result_array();
	}
	
	/**
	 *  Выборка фильтра по его id
	 */
	public function select_filters_by_id($id)
	{
        $this->db->select('*');
        $this->db->from('user_filters');
        $this->db->where('id', $id); 
        $query = $this->db->get();
        
        return $query->result_array();
	}
	
	/**
	 * 	 Удаление фильтра
	 */
	public function delete_filter($id, $user)
	{
		$this->db->where('id', $id);
		$this->db->where('user', $user);
		$this->db->delete('user_filters');
		
		return true;
	}
	
	/**
	 * 	 Выборка проекта по его id и бирже
	 */
	public function select_project_by_id($site, $id)
	{
        $this->db->select('*');
		$this->db->from($site);
        $this->db->where('id', $id); 
        $query = $this->db->get();
        
        return $query->result_array();
	}

	/**
	 * 	 Проверка на существование проекта в избранном
	 */
	public function is_favorite_project($user, $project_id)
	{
        $this->db->select('id');
		$this->db->from('user_favorite');
        $this->db->where('username', $user); 
		$this->db->where('project_id', $project_id); 
        $result = $this->db->get()->result_array();
        
        return (!empty($result));
	}
	
	/**
	 * 	 Сохранение проекта
	 */
	public function save_project($username, $site, $id, $date, $time)
	{
		$this->db->set('USERNAME',     $username);
		$this->db->set('SITE',         $site);
		$this->db->set('PROJECT_ID',   $id);
		$this->db->set('PROJECT_DATE', $date);
		$this->db->set('PROJECT_TIME', $time);

		$result = $this->db->insert('user_favorite');
		
		return $result;
	}
	
	
	/**
	 * 	 Определение кол-ва избранных объявлений
	 */
	public function get_user_pr_num_rows($user)
	{
        $this->db->where('username', $user); 
		$count = $this->db->count_all_results('user_favorite');
        
        return $count;
	}
	
	
	/**
	 * 	 Выборка всех сохраненных проектов юзера с учётом пагинации 
	 */
	public function get_all_favorite_data($user, $start, $step)
	{
        $this->db->select('*');
		$this->db->from('user_favorite'); 
		$this->db->where('username', $user);
		$this->db->order_by('project_date DESC, project_time DESC');
		$this->db->limit($step, $start);		
        $query = $this->db->get();
        
        return $query->result_array();
	}
	
	/**
	 * 	 Удаляем проекты
	 */
	public function delete_project($site, $id, $user)
	{
		$this->db->where('username', $user);
		$this->db->where('project_id', $id);
		$this->db->where('site', $site);
		$this->db->delete('user_favorite');
		
		return true;
	}
}