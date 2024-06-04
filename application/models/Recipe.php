<?php
class Recipe extends CI_Model
{
	/*
	Kiểm tra xem đã tồn tại đơn pha chế với mã $master_batch
	
	*/
	public function exists($master_batch,$ignore_deleted = FALSE, $deleted = FALSE)
	{
		
		$this->db->from('recipes');
		$this->db->where('master_batch', $master_batch);
		if ($ignore_deleted == FALSE)
		{
			$this->db->where('deleted', $deleted);
		}

		return ($this->db->get()->num_rows() == 1);
	}


	/*
	get item in cart to check
	*/
	public function get_items_in_cart($_aItemNUmber)
	{
		$this->db->from('items');
		$this->db->where_in('item_number', $_aItemNUmber);
		return $this->db->get()->result();

	}

	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('recipes');
		$this->db->where('deleted', 0);

		return $this->db->count_all_results();
	}

	/*
	Get number of rows
	*/
	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters)->num_rows();
	}

	/*
	Perform a search on items
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'recipes.name', $order = 'asc')
	{
		$this->db->select('
							recipes.*');
		$this->db->from('recipes');
		//$this->db->join('suppliers', 'suppliers.person_id = items.supplier_id', 'left');
		//$this->db->join('inventory', 'inventory.trans_items = items.item_id');

		$this->db->where('DATE_FORMAT(date_issued, "%Y-%m-%d") BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']));

		if(!empty($search))
		{
			
			$this->db->group_start();
				$this->db->like('name', $this->db->escape_like_str($search));
				$this->db->or_like('master_batch', $search);
			$this->db->group_end();
				
		}
		$this->db->where('recipes.deleted', $filters['is_deleted']);

		// avoid duplicated entries with same name because of inventory reporting multiple changes on the same item in the same date range
		//$this->db->group_by('items.item_id');
		
		// order by name of item
		$this->db->order_by($sort, $order);

		if($rows > 0) 
		{	
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}
	
	/*
	Returns all the items
	*/
	public function get_all($stock_location_id = -1, $rows = 0, $limit_from = 0)
	{
		$this->db->from('items');
		$this->db->join('suppliers', 'suppliers.person_id = items.supplier_id', 'left');

		if($stock_location_id > -1)
		{
			$this->db->join('item_quantities', 'item_quantities.item_id = items.item_id');
			$this->db->where('location_id', $stock_location_id);
		}

		$this->db->where('items.deleted', 0);

		// order by name of item
		$this->db->order_by('items.name', 'asc');

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}

		return $this->db->get();
	}

	/*
	Gets information about a particular item
	*/
	public function get_info($item_id)
	{
		$this->db->select('items.*');
		$this->db->select('suppliers.company_name');
		$this->db->from('items');
		$this->db->join('suppliers', 'suppliers.person_id = items.supplier_id', 'left');
		if(strlen($item_id)> 20) // Nêu chuỗi lớn hơn 20 sẽ sử dụng item_uuid
		{
			$this->db->where('item_uuid', $item_id);
		} else{
			$this->db->where('item_id', $item_id); // support version cũ
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
			foreach($this->db->list_fields('items') as $field)
			{
				$item_obj->$field = '';
			}
			$item_obj->unit_price = 0;
			$item_obj->item_id = 0;
            $item_obj->cost_price = 0;
			$item_obj->supplier_id = 0;
			$item_obj->reorder_level = 0.000;
			$item_obj->receiving_quantity = 1.000;
			$item_obj->pic_id = 0;
			$item_obj->allow_alt_description = 0;
			$item_obj->is_serialized = 0;
			$item_obj->deleted = 0;
			$item_obj->standard_amount = 0.000;
			$item_obj->status = 0;
			$item_obj->purchase_item_per_purchase_unit = 1.00;
			$item_obj->purchase_quality_per_packge     = 1.00;
			$item_obj->purchase_packing_length         = 1.00;
			$item_obj->purchase_packing_height         = 1.00;
			$item_obj->purchase_packing_width          = 1.00;
			$item_obj->purchase_packing_volume         = 1.00;
			$item_obj->purchase_packing_weigth         = 1.00;
			$item_obj->sale_item_per_sale_unit         = 1.00;
			$item_obj->sale_quality_per_packge         = 1.00;
			$item_obj->sale_packing_length             = 1.00;
			$item_obj->sale_packing_height             = 1.00;
			$item_obj->sale_packing_width              = 1.00;
			$item_obj->sale_packing_volume             = 1.00;
			$item_obj->sale_packing_weigth             = 1.00;
			$item_obj->set_default_warehouse_id        = 0;
			$item_obj->inventory_weigth_per_unit       = 1.00;
			$item_obj->uom_group_id                    = 0;

			return $item_obj;
		}
	}
	
	/*
	Gets information about a particular item by item id or number
	*/
	public function get_info_by_id_or_number($item_id)
	{
		$this->db->from('items');

        if (ctype_digit($item_id))
        {
            $this->db->group_start();
                $this->db->where('item_id', (int) $item_id);
                $this->db->or_where('items.item_number', $item_id);
                $this->db->or_where('items.item_number_new',$item_id);
				$this->db->or_where('items.code',$item_id);
            $this->db->group_end();
        }
        else
        {
            $this->db->where('item_number', $item_id);
            $this->db->or_where('items.item_number_new',$item_id);
			$this->db->or_where('items.code',$item_id);
        }

		$this->db->where('items.deleted', 0);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row();
		}

		return '';
	}

	/*
	Get an item id given an item number
	*/
	public function get_item_id($item_number, $ignore_deleted = FALSE, $deleted = FALSE)
	{
		$this->db->from('items');
		$this->db->join('suppliers', 'suppliers.person_id = items.supplier_id', 'left');
		$this->db->where('item_number', $item_number);
		$this->db->or_where('items.code',$item_number);
		if($ignore_deleted == FALSE)
		{
			$this->db->where('items.deleted', $deleted);
		}
        
		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row()->item_id;
		}

		return FALSE;
	}

	/*
	Gets information about multiple items
	*/
	public function get_multiple_info($item_ids, $location_id)
	{
		$this->db->from('items');
		$this->db->join('suppliers', 'suppliers.person_id = items.supplier_id', 'left');
		$this->db->join('item_quantities', 'item_quantities.item_id = items.item_id', 'left');
		$this->db->where('location_id', $location_id);
		$this->db->where_in('items.item_id', $item_ids);

		return $this->db->get();
	}

	/*
	Inserts or updates a item
	
	*/
	public function save(&$recipe_data, $item_as, $item_bs)
	{
		//echo 'SAve';
		$master_batch = $recipe_data['master_batch'];
		//echo $master_batch;
		//$exist = $this->exists($master_batch, TRUE);
		if(!$this->exists($master_batch, TRUE))
		{
			//echo '1';
			$this->db->trans_start();
			$this->db->insert('recipes', $recipe_data);
			
			$recipe_data['recipe_id'] = $this->db->insert_id();
			
			//Insert Item_a
			if(!empty($item_as))
			{
				foreach($item_as as $item)
				{
					$item_id = $this->Item->exists_by_encode($item['item_mix']);
					
					//debug_log($item_id,'$item_id');
					$item['item_id'] = $item_id;
					$item['type'] = 'A';
					$item['recipe_id'] = $recipe_data['recipe_id'];
					$this->db->insert('item_recipes', $item);
				}	
			}

			//Insert Item_b
			if(!empty($item_bs))
			{
				foreach($item_bs as $item)
				{
					$item_id = $this->Item->exists_by_encode($item['item_mix']);
					
					$item['item_id'] = $item_id;
					$item['type'] = 'B';
					$item['recipe_id'] = $recipe_data['recipe_id'];
					$this->db->insert('item_recipes', $item);
				}
			}
			
			$this->db->trans_complete();

			return $this->db->trans_status();
			
			
		} else {
			//echo '0';
			//$item_data['updated_time'] = $time;
			//$this->db->where('item_id', $item_id);
			
			//return $this->db->update('items', $item_data);
			return false;
		}
	}

	/*
	Updates multiple items at once
	*/
	public function update_multiple($item_data, $item_ids)
	{
		$this->db->where_in('item_id', explode(':', $item_ids));

		return $this->db->update('items', $item_data);
	}

	/*
	Deletes one item
	*/
	public function delete($item_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		// set to 0 quantities
		$this->Item_quantity->reset_quantity($item_id);
		$this->db->where('item_id', $item_id);
		$success = $this->db->update('items', array('deleted'=>1));
		
		$this->db->trans_complete();
		
		$success &= $this->db->trans_status();

		return $success;
	}
	
	/*
	Undeletes one item
	*/
	public function undelete($item_id)
	{
		$this->db->where('item_id', $item_id);

		return $this->db->update('items', array('deleted'=>0));
	}

	/*
	Deletes a list of items
	*/
	public function delete_list($item_ids)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		// set to 0 quantities
		$this->Item_quantity->reset_quantity_list($item_ids);
		$this->db->where_in('item_id', $item_ids);
		$success = $this->db->update('items', array('deleted'=>1));
		
		$this->db->trans_complete();
		
		$success &= $this->db->trans_status();

		return $success;
 	}

	public function get_search_suggestions($search, $filters = array('is_deleted' => FALSE, 'search_custom' => FALSE), $unique = FALSE, $limit = 25)
	{
		$suggestions = array();
		$this->db->select('item_id, name,unit_price');
		$this->db->from('items');
		$this->db->where('deleted', $filters['is_deleted']);
		$this->db->like('name', $search);
        $this->db->or_like('unit_price',$search);
		$this->db->or_like('code',$search); //add by ManhVT 16.12.2022
		$this->db->or_like('item_number_new',$search); //add by ManhVT 22.04.2023
		$this->db->order_by('name', 'asc');
		foreach($this->db->get()->result() as $row)
		{
			$suggestions[] = array('value' => $row->item_id, 'label' => $row->name . ' | '.$row->unit_price);
		}

		$this->db->select('item_id, item_number');
		$this->db->from('items');
		$this->db->where('deleted', $filters['is_deleted']);
		$this->db->like('item_number', $search);
		$this->db->order_by('item_number', 'asc');
		foreach($this->db->get()->result() as $row)
		{
			$suggestions[] = array('value' => $row->item_id, 'label' => $row->item_number);
		}

		if(!$unique)
		{
			//Search by category
			$this->db->select('category');
			$this->db->from('items');
			$this->db->where('deleted', $filters['is_deleted']);
			$this->db->distinct();
			$this->db->like('category', $search);
			$this->db->order_by('category', 'asc');
			foreach($this->db->get()->result() as $row)
			{
				$suggestions[] = array('label' => $row->category);
			}

			//Search by supplier
			$this->db->select('company_name');
			$this->db->from('suppliers');
			$this->db->like('company_name', $search);
			// restrict to non deleted companies only if is_deleted is FALSE
			$this->db->where('deleted', $filters['is_deleted']);
			$this->db->distinct();
			$this->db->order_by('company_name', 'asc');
			foreach($this->db->get()->result() as $row)
			{
				$suggestions[] = array('label' => $row->company_name);
			}

			//Search by description
			$this->db->select('item_id, name, description');
			$this->db->from('items');
			$this->db->where('deleted', $filters['is_deleted']);
			$this->db->like('description', $search);
			$this->db->order_by('description', 'asc');
			foreach($this->db->get()->result() as $row)
			{
				$entry = array('value' => $row->item_id, 'label' => $row->name);
				if(!array_walk($suggestions, function($value, $label) use ($entry) { return $entry['label'] != $label; } ))
				{
					$suggestions[] = $entry;
				}
			}

			//Search by custom fields
			if($filters['search_custom'] != FALSE)
			{
				$this->db->from('items');
				$this->db->group_start();
					$this->db->like('custom1', $search);
					$this->db->or_like('custom2', $search);
					$this->db->or_like('custom3', $search);
					$this->db->or_like('custom4', $search);
					$this->db->or_like('custom5', $search);
					$this->db->or_like('custom6', $search);
					$this->db->or_like('custom7', $search);
					$this->db->or_like('custom8', $search);
					$this->db->or_like('custom9', $search);
					$this->db->or_like('custom10', $search);
				$this->db->group_end();
				$this->db->where('deleted', $filters['is_deleted']);
				foreach($this->db->get()->result() as $row)
				{
					$suggestions[] = array('value' => $row->item_id, 'label' => $row->name);
				}
			}
		}

		//only return $limit suggestions
		//if(count($suggestions > $limit))
		if($suggestions > $limit)
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}

		return $suggestions;
	}

	public function get_category_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('category');
		$this->db->from('items');
		$this->db->like('category', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by('category', 'asc');
		foreach($this->db->get()->result() as $row)
		{
			$suggestions[] = array('label' => $row->category);
		}

		return $suggestions;
	}
	
	public function get_location_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('location');
		$this->db->from('items');
		$this->db->like('location', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by('location', 'asc');
		foreach($this->db->get()->result() as $row)
		{
			$suggestions[] = array('label' => $row->location);
		}
	
		return $suggestions;
	}

	public function get_custom_suggestions($search, $field_no)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('custom'.$field_no);
		$this->db->from('items');
		$this->db->like('custom'.$field_no, $search);
		$this->db->where('deleted', 0);
		$this->db->order_by('custom'.$field_no, 'asc');
		foreach($this->db->get()->result() as $row)
		{
			$row_array = (array) $row;
			$suggestions[] = array('label' => $row_array['custom'.$field_no]);
		}
	
		return $suggestions;
	}

	public function get_categories()
	{
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted', 0);
		$this->db->distinct();
		$this->db->order_by('category', 'asc');

		return $this->db->get();
	}

	/*
	 * changes the cost price of a given item
	 * calculates the average price between received items and items on stock
	 * $item_id : the item which price should be changed
	 * $items_received : the amount of new items received
	 * $new_price : the cost-price for the newly received items
	 * $old_price (optional) : the current-cost-price
	 *
	 * used in receiving-process to update cost-price if changed
	 * caution: must be used before item_quantities gets updated, otherwise the average price is wrong!
	 *
	 */
	public function change_cost_price($item_id, $items_received, $new_price, $old_price = null)
	{
		if($old_price === null)
		{
			$item_info = $this->get_info($item_id);
			$old_price = $item_info->cost_price;
		}

		$this->db->from('item_quantities');
		$this->db->select_sum('quantity');
		$this->db->where('item_id', $item_id);
		$this->db->join('stock_locations', 'stock_locations.location_id=item_quantities.location_id');
		$this->db->where('stock_locations.deleted', 0);
		$old_total_quantity = $this->db->get()->row()->quantity;

		$total_quantity = $old_total_quantity + $items_received;
		$average_price = bcdiv(bcadd(bcmul($items_received, $new_price), bcmul($old_total_quantity, $old_price)), $total_quantity);

		$data = array('cost_price' => $average_price);

		return $this->save($data, $item_id);
	}
}
?>