<?php
class Compounda extends CI_Model
{
	/*
	Determines if a given item_id is an item
	Bổ sung location_id (xác định tại kho nào;)
	*/
	public function exists($item)
	{
	
		if(empty($item)) return FALSE;
		$this->db->from('compounda_orders');
		$this->db->where('compounda_order_no', $item['compounda_order_no']);
		return ($this->db->get()->num_rows() == 1);
	
	}

	/*
	Determines if a given item_number exists
	*/
	public function item_number_exists($item_number, $item_id = '')
	{
		$this->db->from('items');
		$this->db->where('item_number', (string) $item_number);
		if(ctype_digit($item_id))
		{
			$this->db->where('item_id !=', (int) $item_id);
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
		$this->db->from('items');
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
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'compounda_orders.created_at', $order = 'asc')
	{
		$this->db->select('compounda_orders.*');
		$this->db->from('compounda_orders');
		$this->db->where('FROM_UNIXTIME(created_at) BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date'].' 23:59:59'));

		if(!empty($search))
		{
			
			$this->db->group_start();
				$this->db->like('compounda_order_no', $this->db->escape_like_str($search));
			$this->db->group_end();
				
		}
		
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
		$this->db->select('compounda_orders.*');
		$this->db->from('compounda_orders');
		if(strlen($item_id)> 20) // Nêu chuỗi lớn hơn 20 sẽ sử dụng item_uuid
		{
			$this->db->where('compounda_order_uuid', $item_id);
		} else{
			$this->db->where('compounda_order_id', $item_id); // support version cũ
		}

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			$item_obj = $query->row();

			$_list_compound_as = $this->get_list_items_in_order($item_obj->compounda_order_id);

			if(empty($_list_compound_as))
			{
				$item_obj->list_compound_a = [];

			} else {
				foreach($_list_compound_as as $key=>$value)
				{
					$value->list_tasks = $this->get_list_tasks_in_order_item($value->compounda_order_item_id);
					$item_obj->list_compound_a[] = $value;
				}
			}
			return $item_obj;
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
			$item_obj->item_id = 0;
			$item_obj->compounda_order_no = '';
			$item_obj->compounda_order_uuid = '';
			$item_obj->compounda_order_id = 0;
			$item_obj->created_at = 0;
			$item_obj->creator_id = 0;
			$item_obj->creator_name = '';
			$item_obj->creator_account = '';
			$item_obj->order_date  = 0;
			$item_obj->use_date = 0;
			$item_obj->completed_at = 0;
			$item_obj->start_at = 0;
			$item_obj->suppervisor_id = 0;
			$item_obj->suppervisor_name = '';
			$item_obj->suppervisor_account = '';
			$item_obj->area_make_order = '';
			$item_obj->status = 0;
			$item_obj->list_compound_a = [];
			return $item_obj;
		}
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
	Inserts or updates a Compound A Schedule
	*/
	public function save(&$compounda_data,$item_orders)
	{
		$time = time();
		if(empty($item_orders))
		{
			return FALSE;
		}
		if(!$this->exists($compounda_data))
		{
			$compounda_data['created_at'] = $time;

			$this->db->trans_start();
			$this->db->insert('compounda_orders', $compounda_data);
			
			$compounda_data['compounda_order_id'] = $this->db->insert_id();

			//Insert Item_a
			if(!empty($item_orders))
			{
				foreach($item_orders as $key=>$value)
				{
				
					$item = $value['item_order'];
					if(empty($item))
					{
						$this->db->trans_rollback();
						$this->db->trans_complete();
						return FALSE;
					}
					$item['created_at'] = $time;
					$item['compounda_order_id'] = $compounda_data['compounda_order_id'];
					$this->db->insert('compounda_order_item', $item);
					$compounda_order_item_id = $this->db->insert_id();

					$export_data = $value['export_data'];
					if(empty($export_data))
					{
						$this->db->trans_rollback();
						$this->db->trans_complete();
						return FALSE;
					}
					
					foreach($export_data as $k=>$v)
					{
						$_aListItems = $v['list_items'];
						if(empty($_aListItems))
						{
							$this->db->trans_rollback();
							$this->db->trans_complete();
							return FALSE;
						}
						unset($v['list_items']);
						$v['created_at'] = $time;
						$v['compounda_order_id'] = $compounda_data['compounda_order_id'];
						$v['compounda_order_item_id'] = $compounda_order_item_id;
						
						$this->db->insert('export_documents', $v);
						$_iExportDocumentId =  $this->db->insert_id();
						foreach($_aListItems as $_k=>$_item)
						{
							$_item['export_document_id'] = $_iExportDocumentId;
							$_aListItems[$_k] = $_item;
						}
						$this->db->insert_batch('export_document_items', $_aListItems);
					}
					
				}	
			}

			if ($this->db->trans_status() === FALSE) {
				// Có lỗi xảy ra, rollback lại giao dịch
				$this->db->trans_rollback();
			} else {
				// Không có lỗi, commit giao dịch
				$this->db->trans_commit();
			}

			$this->db->trans_complete();

			return $this->db->trans_status();
			
		} else { 
			
			return FALSE;
		// Nếu đã tồn tại Không làm gì
		//$item_data['updated_time'] = $time;
		//$this->db->where('item_id', $item_id);
		//return $this->db->update('items', $item_data);
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

	public function exists_by_encode($encode,$ignore_deleted = FALSE, $deleted = FALSE)
	{
		$this->load->helper('locale_helper');
		$this->db->from('items');
		$this->db->where('encode', $encode);
		if ($ignore_deleted == FALSE)
		{
			$this->db->where('deleted', $deleted);
		}
		
		$query = $this->db->get();
		debug_log($query->num_rows(),'test0');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			debug_log($row,'row');
			return $row->item_id;
		}
		return 0;
	}
	/**
	 * Lấy danh sách item của lệnh sản xuất Compound A.
	 */
	public function get_list_items_in_order($order_id)
	{
		$this->db->select('compounda_order_item.*');
		$this->db->from('compounda_order_item');
		$this->db->where('compounda_order_id', $order_id);
		return $this->db->get()->result();
	}

	public function get_list_tasks_in_order_item($order_item_id)
	{
		$this->db->select('compounda_order_item_completed.*');
		$this->db->from('compounda_order_item_completed');
		$this->db->where('compounda_order_item_id', $order_item_id);
		return $this->db->get()->result();
	}
	/**
	 * Lấy các vật tư theo mẻ
	 */

	public function get_list_items_in_export_document($export_document_id)
	{
		$this->db->select('export_document_items.*, items.name, items.encode as item_encode');
		$this->db->from('export_document_items');
		$this->db->join('items', 'items.item_id=export_document_items.item_id','left');
		$this->db->where('export_document_items.export_document_id', $export_document_id);
		
		return $this->db->get()->result();
	}
	/**
	 * Lấy danh sách các phiếu xuất theo Batch (mẻ) theo  job trong lệnh sản xuất
	 */
	public function get_list_export_document_in_order_item($compounda_order_item_id)
	{
		$this->db->select('export_documents.*');
		$this->db->from('export_documents');
		$this->db->where('export_documents.compounda_order_item_id', $compounda_order_item_id);
		
		return $this->db->get()->result();
	}




	public function get_info_by_no($compounda_order_no)
	{
		$this->db->select('compounda_orders.*');
		$this->db->from('compounda_orders');
		
		$this->db->where('compounda_order_no', $compounda_order_no);
		
		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			$item_obj = $query->row();

			$_list_compound_as = $this->get_list_items_in_order($item_obj->compounda_order_id);

			if(empty($_list_compound_as))
			{
				$item_obj->list_compound_a = [];

			} else {
				foreach($_list_compound_as as $key=>$value)
				{
					$value->list_tasks = $this->get_list_tasks_in_order_item($value->compounda_order_item_id);

					if($value->quantity_batch > 0)
					{
						//$_aExport_documents = [];
						
						$_aExport_documents = $this->get_list_export_document_in_order_item($value->compounda_order_item_id);
						if(empty($_aExport_documents))
						{
							$_aExport_documents = [];
						} else {
							foreach($_aExport_documents as $ex_key=>$ex_document)
							{
								$_aItems = $this->get_list_items_in_export_document($ex_document->export_document_id);
								$ex_document->list_items = $_aItems;
								$_aExport_documents[$ex_key] = $ex_document;
							}
							
						}
						
						$value->export_documents = $_aExport_documents;
					} else {
						$value->export_documents = [];
					}

					$item_obj->list_compound_a[] = $value;
				}
			}

			
			return $item_obj;
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
			$item_obj->item_id = 0;
			$item_obj->compounda_order_no = '';
			$item_obj->compounda_order_uuid = '';
			$item_obj->compounda_order_id = 0;
			$item_obj->created_at = 0;
			$item_obj->creator_id = 0;
			$item_obj->creator_name = '';
			$item_obj->creator_account = '';
			$item_obj->order_date  = 0;
			$item_obj->use_date = 0;
			$item_obj->completed_at = 0;
			$item_obj->start_at = 0;
			$item_obj->suppervisor_id = 0;
			$item_obj->suppervisor_name = '';
			$item_obj->suppervisor_account = '';
			$item_obj->area_make_order = '';
			$item_obj->status = 0;
			$item_obj->list_compound_a = [];
			return $item_obj;
		}
	}

	public function get_info_export_document($export_document_uuid)
	{
		
		$this->db->select('export_documents.*');
		$this->db->from('export_documents');
		if(strlen($export_document_uuid) > 20)
		{
			$this->db->where('export_documents.export_document_uuid', $export_document_uuid);
		} else {
			$this->db->where('export_documents.export_document_id', $export_document_uuid);
		}
		
		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			$item_obj = $query->row();
			return $item_obj;
		}
		 else {
			$item_obj = null;
			return $item_obj;
		}
	}

	public function update_export_document($export_document)
	{
		$this->db->where('export_document_id',$export_document['export_document_id']);
		return $this->db->update('export_documents', $export_document);	
	}

	public function do_export_document($export_document)
	{
		$export_document['status'] = 5;
		return $this->update_export_document($export_document);	
	}

	public function do_confirm_document($export_document)
	{
		$export_document['status'] = 6;
		return $this->update_export_document($export_document);	
	}
	public function do_start_document($export_document)
	{
		$export_document['status'] = 7;
		return $this->update_export_document($export_document);
	}
	public function do_completed_document($export_document)
	{
		$export_document['status'] = 8;
		return $this->update_export_document($export_document);
	}


		
	
}
?>