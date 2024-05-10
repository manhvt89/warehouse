<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Persons.php");

class Employees extends Persons
{
	public function __construct()
	{
		parent::__construct('employees');
	}
	
	public function index()
	{
		$data['table_headers'] = $this->xss_clean(get_people_manage_table_headers());

		$this->load->view('people/manage', $data);
	}
	
	/*
	Returns employee table data rows. This will be called with AJAX.
	*/
	public function search()
	{
		$search = $this->input->get('search');
		$limit  = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort   = $this->input->get('sort');
		$order  = $this->input->get('order');

		$employees = $this->Employee->search($search, $limit, $offset, $sort, $order);
		$total_rows = $this->Employee->get_found_rows($search);

		$data_rows = array();
		foreach($employees->result() as $person)
		{
			if(!$this->Employee->has_grant('employees_is_super_admin'))
			{
				if($person->person_id > 1)
				{
					$data_rows[] = get_employee_data_row($person);
				}
			} else {
				$data_rows[] = get_employee_data_row($person);
			}
		}

		$data_rows = $this->xss_clean($data_rows);

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}
	
	/*
	Gives search suggestions based on what is being searched for
	*/
	public function suggest_search()
	{
		$suggestions = $this->xss_clean($this->Employee->get_search_suggestions($this->input->post('term')));

		echo json_encode($suggestions);
	}
	
	/*
	Loads the employee edit form
	*/
	public function view($employee_id = -1)
	{
		$person_info = $this->Employee->get_info($employee_id);
		foreach(get_object_vars($person_info) as $property => $value)
		{
			$person_info->$property = $this->xss_clean($value);
		}
		$person_info->first_name = get_fullname($person_info->first_name, $person_info->last_name);

		$data['person_info'] = $person_info;
        $city_ = get_cities_list();
        $cities = array();
        foreach ($city_ as $key=>$value)
        {
            $cities[$value] = $value;
        }
        $data['city'] = $person_info->city;
        if($data['city'] == '' || $data['city'] == 'HN')
        {
            $data['city'] = $this->config->item('default_city');
        }
        $data['cities'] = $cities;

		/* $modules = array();
		foreach($this->Module->get_all_modules()->result() as $module)
		{
			$module->module_key = $this->xss_clean($module->module_key);
			$module->grant = $this->xss_clean($this->Employee->has_grant($module->module_key, $person_info->person_id));
			$modules[] = $module;
		}
		$data['all_modules'] = $modules; */

		$_aRoles = array();
		$_aRoles = $this->Module->get_roles_of_the_user($person_info->person_id)->result();
		$_aAllRoles = array();
		$_aAllRoles = $this->Module->get_roles()->result();
		//var_dump($_aAllRoles); die();
		//var_dump($_aRoles);
		if(!empty($_aAllRoles))
		{
			foreach($_aAllRoles as $key=>$value)
			{
				$value->flag = 0;
				$_aAllRoles[$key] = $value;
			}
			//var_dump($_aAllRoles);
			foreach ($_aAllRoles as $key=>$value) {
				if(!empty($_aRoles))
				{
					foreach ($_aRoles as $k=>$v) {
						if($value->role_uuid == $v->role_uuid)
						{
							$value->flag = 1;
							$_aAllRoles[$key] = $value;
						}
					}
				} 
			}

		}
		//var_dump($_aAllRoles);
		$data['allroles'] = $_aAllRoles;

		/* $permissions = array();
		foreach($this->Module->get_all_subpermissions()->result() as $permission)
		{
			$permission->module_id = $this->xss_clean($permission->module_id);
			$permission->permission_id = $this->xss_clean($permission->permission_id);
			$permission->grant = $this->xss_clean($this->Employee->has_grant($permission->permission_id, $person_info->person_id));
			
			$permissions[] = $permission;
		}
		$data['all_subpermissions'] = $permissions; */

		$this->load->view("employees/form", $data);
	}
	
	/*
	Inserts/updates an employee
	*/
	public function save($employee_id = -1)
	{

		$_firstname = $this->input->post('first_name');
		$_aName = extract_fullname($_firstname);
		$person_data = array(
			'first_name' => mb_convert_case($_aName['firstname'], MB_CASE_TITLE, "UTF-8"),
			'last_name' => mb_convert_case($_aName['lastname'], MB_CASE_TITLE, "UTF-8"),
			'gender' => $this->input->post('gender'),
			'email' => $this->input->post('email'),
			'phone_number' => $this->input->post('phone_number'),
			'address_1' => $this->input->post('address_1'),
			'address_2' => $this->input->post('address_2'),
			'city' => $this->input->post('city'),
			'state' => $this->input->post('state'),
			'zip' => $this->input->post('zip'),
			'country' => $this->input->post('country'),
			'comments' => $this->input->post('comments')
			
		);
		//$grants_data = $this->input->post('grants') != NULL ? $this->input->post('grants') : array();
		$roles_data = $this->input->post('role') != NULL ? $this->input->post('role') : array();
		
		//Password has been changed OR first time password set
		if($this->input->post('password') != '')
		{
			$employee_data = array(
				'username' => $this->input->post('username'),
				'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
				'hash_version' => 2,
				'code' => $this->input->post('code') == ''? 'CTV'.time() : $this->input->post('code'),
				'comission_rate' => $this->input->post('comission_rate')
			);
		}
		else //Password not changed
		{
			$employee_data = [
				'username' => $this->input->post('username'),
				'code' => $this->input->post('code') == ''? 'CTV'.time() : $this->input->post('code'),
				'comission_rate' => $this->input->post('comission_rate')
			];
		}
		
		if($this->Employee->save_employee($person_data, $employee_data, $roles_data, $employee_id))
		{
			$person_data = $this->xss_clean($person_data);
			$employee_data = $this->xss_clean($employee_data);

			//New employee
			if($employee_id == -1)
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('employees_successful_adding').' '.
								$person_data['first_name'].' '.$person_data['last_name'], 'id' => $employee_data['person_id']));
			}
			else //Existing employee
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('employees_successful_updating').' '.
								$person_data['first_name'].' '.$person_data['last_name'], 'id' => $employee_id));
			}
		}
		else//failure
		{
			$person_data = $this->xss_clean($person_data);

			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('employees_error_adding_updating').' '.
							$person_data['first_name'].' '.$person_data['last_name'], 'id' => -1));
		}
	}
	
	/*
	This deletes employees from the employees table
	*/
	public function delete()
	{
		$employees_to_delete = $this->xss_clean($this->input->post('ids'));

		if($this->Employee->delete_list($employees_to_delete))
		{
			echo json_encode(array('success' => TRUE,'message' => $this->lang->line('employees_successful_deleted').' '.
							count($employees_to_delete).' '.$this->lang->line('employees_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success' => FALSE,'message' => $this->lang->line('employees_cannot_be_deleted')));
		}
	}

	public function is_super_admin()
	{

	}
}
?>