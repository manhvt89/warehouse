<?php
class Stock_location extends CI_Model
{
    public function exists($location_code = '',$location_uuid='')
    {
        $this->db->from('stock_locations');  
        $this->db->where('location_code', $location_code);

		$row = $this->db->get()->row();
		if(empty($row)) // nếu chưa tồn tại
		{
			return false;
		} else { // nếu đã có bản ghi
			if($row->location_uuid == $location_uuid) //nếu là chính nó thì bỏ qua
			{
				return false;
			} else { // nếu trùng với bản khi khác thì đã tồn tại;
				return true;
			}
		}

    }
    
    public function get_all($limit = 10000, $offset = 0)
    {
        $this->db->from('stock_locations');
        $this->db->limit($limit);
        $this->db->offset($offset);
	
        return $this->db->get();
    }
    
    public function get_undeleted_all($module_id = 'items')
    {
        $this->db->from('stock_locations');
        $this->db->where('deleted', 0);
        return $this->db->get();
    }

	public function show_locations($module_id = 'items')
	{
		$stock_locations = $this->get_allowed_locations($module_id);

		return count($stock_locations) > 1;
	}

	public function multiple_locations()
	{
		return $this->get_all()->num_rows() > 1;
	}

    public function get_allowed_locations($module_id = 'items')
    {
    	$stock = $this->get_undeleted_all($module_id)->result_array();
    	$stock_locations = array();
    	foreach($stock as $location_data)
    	{
    		$stock_locations[$location_data['location_id']] = $location_data['location_name'];
    	}

    	return $stock_locations;
    }

	public function is_allowed_location($location_id, $module_id = 'items')
	{
		return true;
		$this->db->from('stock_locations');
		$this->db->join('permissions', 'permissions.location_id = stock_locations.location_id');
		$this->db->join('grants', 'grants.permission_id = permissions.permission_id');
		// --> add by ManhVT 23/10/2022
		$this->db->join('roles', 'roles.id = grants.role_id');
		$this->db->join('user_roles', 'user_roles.role_id = roles.id');
    	$this->db->where('user_id', $this->session->userdata('person_id'));
		//$this->db->where('person_id', $this->session->userdata('person_id'));
		// <-- end 
		$this->db->like('permissions.id', $module_id, 'after');
		$this->db->where('deleted', 0);
		$this->db->where('stock_locations.location_id', $location_id);

		return ($this->db->get()->num_rows() == 1);
	}
    
    public function get_default_location_id()
    {
    	$this->db->from('stock_locations');
		/*
    	$this->db->join('permissions', 'permissions.location_id = stock_locations.location_id');
		$this->db->join('grants', 'grants.permission_id = permissions.id');
		// --> add by ManhVT 23/10/2022
		$this->db->join('roles', 'roles.id = grants.role_id');
		$this->db->join('user_roles', 'user_roles.role_id = roles.id');
    	$this->db->where('user_id', $this->session->userdata('person_id'));
		//$this->db->where('person_id', $this->session->userdata('person_id'));
		// <-- end 
		*/
    	$this->db->where('deleted', 0);
		$this->db->limit(1);
		$rs = $this->db->get()->row();
		if($rs != null)
		{
			return $rs->location_id;
		}
		return 0;
    }
    
    public function get_location_name($location_id) 
    {
    	$this->db->from('stock_locations');
    	$this->db->where('location_id', $location_id);

    	return $this->db->get()->row()->location_name;
    }
    
    public function save(&$location_data, $location_uuid) 
    {
		$location_code = $location_data['location_code'];

		if($this->get_info($location_uuid)->location_id) // update
		{
			if(!$this->exists($location_code,$location_uuid))
			{
				$this->db->where('location_uuid', $location_uuid);

				return $this->db->update('stock_locations', $location_data);
			} else{
				return false;	
			}
		} else { // tạo mới

			if(!$this->exists($location_code,$location_uuid))
			{
				$this->db->trans_start();
				$this->db->insert('stock_locations', $location_data);
				$location_id = $this->db->insert_id();
				// insert quantities for existing items
				$items = $this->Item->get_all(); //Lấy tất cả sản phẩm hiện hữu trong phần mềm, thêm vào kho mới với số lượng = 0;
				foreach($items->result_array() as $item)
				{
					$quantity_data = [
						'item_id' => $item['item_id'], 
						'location_id' => $location_id, 
						'quantity' => 0.00,
						'inventory_uom_name'=>$item['inventory_uom_name'],
						'inventory_uom_code'=>$item['inventory_uom_code'],
						'cost_price'=>$item['cost_price'],
						'unit_price'=>$item['unit_price']
					];
					$this->db->insert('item_quantities', $quantity_data);
				}
				$this->db->trans_complete();
				return $this->db->trans_status();
			}
		}
    	
    }
    	
    private function _insert_new_permission($module, $location_id, $location_name)
    {
    	// insert new permission for stock location
    	/* $permission_id = $module . '_' . $location_name;
    	$permission_data = array('permission_key' => $permission_id, 'module_id' => $module, 'module_key' => $module,,'location_id' => $location_id);
    	$this->db->insert('permissions', $permission_data);
    	
    	// insert grants for new permission
    	$employees = $this->Employee->get_all();
    	foreach($employees->result_array() as $employee)
    	{
    		$grants_data = array('permission_id' => $permission_id, 'person_id' => $employee['person_id']);
    		$this->db->insert('grants', $grants_data);
    	}
		*/
    } 
    
    /*
     Deletes one item
    */
    public function delete($location_id)
    {
    	$this->db->trans_start();

    	$this->db->where('location_id', $location_id);
    	$this->db->update('stock_locations', array('deleted' => 1));
    	
    	$this->db->where('location_id', $location_id);
    	$this->db->delete('permissions');

    	$this->db->trans_complete();
		
		return $this->db->trans_status();
    }

	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'stock_locations.location_name', $order = 'asc')
	{
		$this->db->from('stock_locations');
	
		// order by name of item
		$this->db->order_by($sort, $order);

		if($rows > 0) 
		{	
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}

	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters)->num_rows();
	}

	public function get_info($location_id)
	{
		$this->db->select('stock_locations.*');
		$this->db->from('stock_locations');
		if(strlen($location_id) > 20)
		{
			$this->db->where('location_uuid', $location_id);
		} else {
			$this->db->where('location_id', $location_id);
		}

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields('stock_locations') as $field)
			{
				$item_obj->$field = '';
			}
			
			return $item_obj;
		}
	}
}
?>