<?php
class Batch extends CI_Model
{
	/*
	Kiểm tra xem đã tồn tại đơn pha chế với mã $master_batch
	
	*/
	private $table;
	private $id;
	private $uuid;
	public function __construct()
	{
		$this->table = 'compounda_order_item_completed';
		$this->id = "{$this->table}_id";
		$this->uuid = "{$this->table}_uuid";
	}
	public function exists($code,$ignore_deleted = FALSE, $deleted = FALSE)
	{
		
		$this->db->from($this->table);
		$this->db->where('code', $code);
		if ($ignore_deleted == FALSE)
		{
			$this->db->where('deleted', $deleted);
		}

		return $this->db->get()->num_rows() == 1;
	}



	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from($this->table);
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
		$this->db->select('*');
		$this->db->from($this->table);
		//$this->db->join('suppliers', 'suppliers.person_id = items.supplier_id', 'left');
		//$this->db->join('inventory', 'inventory.trans_items = items.item_id');

		$this->db->where('FROM_UNIXTIME(date_issued) BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']));

		if(!empty($search))
		{
			
			$this->db->group_start();
				$this->db->like('name', $this->db->escape_like_str($search));
				$this->db->or_like('master_batch', $search);
			$this->db->group_end();
				
		}
		$this->db->where("{$this->table}.deleted", $filters['is_deleted']);

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
		$this->db->select('*');
		//$this->db->select('suppliers.company_name');
		$this->db->from($this->table);
		//$this->db->join('suppliers', 'suppliers.person_id = items.supplier_id', 'left');
		if(strlen($item_id)> 20) // Nêu chuỗi lớn hơn 20 sẽ sử dụng item_uuid
		{
			$this->db->where("{$this->table}_uuid", $item_id);
		} else{
			$this->db->where("{$this->table}_id", $item_id); // support version cũ
		}
		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			$item_obj = $query->row();
			$item_obj->qc_cpa_document = $this->Compounda->get_qc_cpa_info_by_batch_id($item_obj->{$this->id});
			$item_obj->recipe = $this->Recipe->get_info_by_ms($item_obj->ms);
			$item_obj->lenh = $this->Compounda->get_info_lenh($item_obj->compounda_order_item_id);
			return $item_obj;
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields($this->table) as $field)
			{
				$item_obj->$field = '';
			}
			$_sFieldID = "{$this->table}_id"; 
			$item_obj->$_sFieldID = 0;
			
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

	

	/**
	 * Lấy các item trong đơn pha chế
	 * $type = "A" hoặc "B";
	 */
	public function get_items_by_recipe_id($recipe_id,$type='A')
	{
		$this->db->select('item_recipes.*,items.name, items.encode, items.dpc_name,items.normal_name');
		$this->db->from('item_recipes');
		$this->db->join('items', 'items.item_id=item_recipes.item_id','left');
		$this->db->where('recipe_id', $recipe_id);
		$this->db->where('item_recipes.type', $type);
		$this->db->order_by('item_group', 'asc');
		return $this->db->get();
	}
	/**
	 * Lấy công thức theo mác nguyên liệu
	 * $type = "A" hoặc "B";
	 */
	public function get_item_by_ms($master_batch,$type='A')
	{
		$this->db->select('item_recipes.*,items.name, items.encode, items.dpc_name,items.normal_name, recipes.*');
		$this->db->from('item_recipes');
		$this->db->join('items', 'items.item_id=item_recipes.item_id','left');
		$this->db->join('recipes', 'recipes.recipe_id=item_recipes.recipe_id','left');
		$this->db->where('master_batch', $master_batch);
		$this->db->where('item_recipes.type', $type);
		$this->db->order_by('item_group', 'asc');
		return $this->db->get();
	}

	public function get_info_by_ms($ms)
	{
		$this->db->select('recipes.*');
		$this->db->from('recipes');		
		$this->db->where('master_batch', $ms);
		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			$item_obj = $query->row();
			$item_obj->list_recipe_a = $this->get_items_by_recipe_id($item_obj->recipe_id,'A')->result();
			$item_obj->list_recipe_b = $this->get_items_by_recipe_id($item_obj->recipe_id,'B')->result();
			return $item_obj;
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields('recipes') as $field)
			{
				$item_obj->$field = '';
			}
			$item_obj->date_issued = 0;
			$item_obj->processing_time_a =0;
			$item_obj->weight_a = 75.00;
			$item_obj->processing_time_b =0;
			$item_obj->weight_b = 25.50;
			$item_obj->status = 0;
			$item_obj->deleted = 0;
			$item_obj->list_recipe_a = [];
			$item_obj->list_recipe_b = [];
			return $item_obj;
		}
	}

	public function completed($batch, $qc_cpa_document, $qc_cpa_document_result)
	{
		$this->db->trans_start();

		// Cập nhật bảng compounda_order_item_completed
		$this->db->where('compounda_order_item_completed_id', $batch['batch_id'])
				->update('compounda_order_item_completed', [
					'updated_at' => $batch['updated_at'],
					'status' => $batch['status']
				]);

		// Cập nhật bảng qc_cpa_document
		$this->db->where('qc_cpa_document_id', $qc_cpa_document['qc_cpa_document_id'])
				->update('qc_cpa_documents', [
					'completed_at' => $qc_cpa_document['completed_at'],
					'status' => $qc_cpa_document['status'],
					'results' => $qc_cpa_document['results']
				]);
			
		
		$this->db->where('qc_cpa_document_result_id', $qc_cpa_document_result['qc_cpa_document_result_id'])
		->update('qc_cpa_document_results', [
			'end_at' => $qc_cpa_document_result['end_at'],
			'qc_status' => $qc_cpa_document_result['qc_status'],
			'results' => $qc_cpa_document_result['results'],
			'qc_id'=>$qc_cpa_document_result['qc_id'],
			'qc_name'=>$qc_cpa_document_result['qc_name'],
		]);
		

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function make_doing_qc($batch,$user)
	{
		$_oQC_cpa_document = $batch->qc_cpa_document;
		$time = time();
		if($batch->status < 4) // Chỉ thực hiện khi < 4;
		{
			$_batch_qc_round = $batch->batch_qc_round + 1;
			//echo $_batch_qc_round;
			$this->db->trans_start();

			$this->db->where('compounda_order_item_completed_id', $batch->compounda_order_item_completed_id)
				->update('compounda_order_item_completed', [
					'updated_at' => $time,
					'status' => 4,
					'batch_qc_round'=>$_batch_qc_round,
				]);


			$this->db->where('qc_cpa_document_id', $_oQC_cpa_document->qc_cpa_document_id)
					->update('qc_cpa_documents', [
						'started_at' => $time,
						'status' => 4,
						'qc_id'=>$user->person_id,
						'qc_name'=>"{$user->last_name} {$user->first_name}",
					]);
			// Khởi tạo bản ghi kết quả QC
			$_aQCResult['qc_cpa_document_id'] = $_oQC_cpa_document->qc_cpa_document_id;
			$_aQCResult['compounda_order_item_completed_id'] = $_oQC_cpa_document->compounda_order_item_completed_id;
			$_aQCResult['compounda_order_id'] = $_oQC_cpa_document->compounda_order_id;
			$_aQCResult['compounda_order_item_id'] = $_oQC_cpa_document->compounda_order_item_id;
			$_aQCResult['qc_round'] = $_batch_qc_round;
			$_aQCResult['qc_name'] = "{$user->last_name} {$user->first_name}";
			$_aQCResult['qc_id'] = $user->person_id;
			$_aQCResult['qc_status'] = 4;
			$_aQCResult['results'] = "";
			$_aQCResult['start_at'] = $time;
			$this->db->insert('qc_cpa_document_results', $_aQCResult);

			$this->db->trans_complete();
			if (!$this->db->trans_status()) {
				echo "Giao dịch thất bại! Lỗi: " . $this->db->error()['message']; die();
			} else {
				return $this->db->trans_status();
			}
		} else {
			return 0; // Không thực hiện gì nếu khác 3
		}
	}

	/**
	 * Đếm số lượng các batch trong một kế hoạch sx với uuid
	 * @return array
	 */
	public function count_by_status($khsx_id) {
        $this->db->select('status, COUNT(*) as count');
		$this->db->where('compounda_order_item_id',$khsx_id);
        $this->db->group_by('status');
        $query = $this->db->get('compounda_order_item_completed'); // Thay 'production_table' bằng tên bảng thực tế

        // Định nghĩa các trạng thái
        $status_map = [
            1 => "choLam",
            2 => "dangLam",
            3 => "choQC",
            4 => "dangQC",
            5 => "qcNotOK",
            6 => "daQCOK",
            7 => "batDauCan",
            8 => "daLam"
        ];

        // Mặc định tất cả trạng thái có số lượng 0
        $result = array_fill_keys(array_values($status_map), 0);

        // Gán số lượng từ dữ liệu truy vấn
        foreach ($query->result() as $row) {
            if (isset($status_map[$row->status])) {
                $result[$status_map[$row->status]] = $row->count;
            }
        }

        return $result;
    }
	/**
	 * Đếm cố mẻ hoàn thành trong lệnh sx
	 * @param mixed $lenh_sx_ids
	 */
	public function get_completed_batches($lenh_sx_ids) {
        if (empty($lenh_sx_ids)) {
            return [];
        }

        if (empty($lenh_sx_ids)) {
            return [];
        }

        $this->db->select([
            'coi.compounda_order_item_id',
            'IFNULL(COUNT(coic.compounda_order_item_completed_id), 0) AS so_me_hoan_thanh'
        ]);
        $this->db->from('compounda_order_item AS coi');
        $this->db->join('compounda_order_item_completed AS coic', 
                        'coic.compounda_order_item_id = coi.compounda_order_item_id 
                         AND coic.status = 8', 
                        'LEFT');
        $this->db->where_in('coi.compounda_order_item_id', $lenh_sx_ids);
        $this->db->group_by('coi.compounda_order_item_id');

        $query = $this->db->get();
		foreach ($query->result() as $row) {
            
                $result[$row->compounda_order_item_id] = $row->so_me_hoan_thanh;
            
        }
        return $result;
    }

	public function make_doing_cpas($batch)
	{
		
		$time = time();
		if($batch['status'] == 6)
		{
			$this->db->trans_start();

			$this->db->where('compounda_order_item_completed_id', $batch['batch_id'])
				->update('compounda_order_item_completed', [
					'updated_at' => $time,
					'status' => 7,
					'thoi_gian_can_luyen_bat_dau'=>$batch['thoi_gian_can_luyen_bat_dau'],
					'nguoi_can_luyen_id' => $batch['nguoi_can_luyen_id'],
					'nguoi_can_luyen_name' => $batch['nguoi_can_luyen_name']
				]);

			$this->db->trans_complete();
			return $this->db->trans_status();
		} else {
			return 0; // không thực hiện gì nếu khác 6
		}
	}

	public function make_completed_cpas($batch)
	{
		
		$time = time();
		if($batch['status'] == 7)
		{
			$this->db->trans_start();

			$this->db->where('compounda_order_item_completed_id', $batch['batch_id'])
				->update('compounda_order_item_completed', [
					'updated_at' => $time,
					'completed_at' => $time,
					'thoi_gian_can_luyen_ket_thuc'=>$time,
					'status' => 8
				]);

			$this->db->trans_complete();
			return $this->db->trans_status();
		} else {
			return 0; // không thực hiện gì nếu khác 6
		}
	}

	public function make_doing_can($batch)
	{
		
		$time = time();
		if($batch['status'] == 1)
		{
			$this->db->trans_start();

			$this->db->where('compounda_order_item_completed_id', $batch['batch_id'])
				->update('compounda_order_item_completed', [
					'updated_at' => $time,
					'started_at' => $time,
					'status' => 2,
					'thoi_gian_can'=>$batch['thoi_gian_can'],
					'nguoi_can_id' => $batch['nguoi_can_id'],
					'nguoi_can_name' => $batch['nguoi_can_name']
				]);

			$this->db->trans_complete();
			return $this->db->trans_status();
		} else {
			return 0; // không thực hiện gì nếu khác 6
		}
	}
	/**
	 * Lấy Bacth theo mã
	 * @param mixed $code
	 */
	public function get_info_by_code($code)
	{
		$this->db->select('*');
		//$this->db->select('suppliers.company_name');
		$this->db->from($this->table);
		//$this->db->join('suppliers', 'suppliers.person_id = items.supplier_id', 'left');
		
		$this->db->where("code", $code); // support version cũ
		
		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			$item_obj = $query->row();
			$item_obj->qc_cpa_document = $this->Compounda->get_qc_cpa_info_by_batch_id($item_obj->{$this->id});
			$item_obj->recipe = $this->Recipe->get_info_by_ms($item_obj->ms);
			$item_obj->lenh = $this->Compounda->get_info_lenh($item_obj->compounda_order_item_id);
			return $item_obj;
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			foreach($this->db->list_fields($this->table) as $field)
			{
				$item_obj->$field = '';
			}
			$_sFieldID = "{$this->table}_id"; 
			$item_obj->$_sFieldID = 0;
			$item_obj->qc_cpa_document = [];
			$item_obj->recipe = [];
			$item_obj->lenh = []; // parent
			return $item_obj;
		}
	}
	/**
	 * Lây danh sách Batch theo status
	 * @param mixed $status array
	 */
	public function get_list_batches_by_status($status)
	{
		if(!is_array($status))
		{
			return [];
		}
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where_in('status', $status);
		
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $item_obj) {
				$item_obj->qc_cpa_document = $this->Compounda->get_qc_cpa_info_by_batch_id($item_obj->{$this->id});
				$item_obj->recipe = $this->Recipe->get_info_by_ms($item_obj->ms);
				$item_obj->lenh = $this->Compounda->get_info_lenh($item_obj->compounda_order_item_id);
			}
			return $query->result();
		}
		
		return [];
	}

	public function make_weighing_count($batch,$weighing_count)
	{
		$time = time();
		$id = $batch->compounda_order_item_completed_id;
		
		$thoi_gian_can = [];
		if($batch->thoi_gian_can == 0)
		{ 
		} else {
			$thoi_gian_can = json_decode($batch->thoi_gian_can,true);
		}
		$thoi_gian_can[] = [
			'started'=>0,
			'ended'=>$time
		];
		return $this->db->where('compounda_order_item_completed_id', $id)
		->update('compounda_order_item_completed', [
			'updated_at' => $time,
			'weighing_count' => $weighing_count,
			'thoi_gian_can'=>json_encode($thoi_gian_can)
		]);
	}

	public function completed_weighing($batch,$weighing_count)
	{
		$time = time();
		$id = $batch->compounda_order_item_completed_id;
		$thoi_gian_can = [];
		if($batch->thoi_gian_can == 0)
		{ 
		} else {
			$thoi_gian_can = json_decode($batch->thoi_gian_can,true);
		}
		$thoi_gian_can[] = [
			'started'=>0,
			'ended'=>$time
		];

		return $this->db->where('compounda_order_item_completed_id', $id)
		->update('compounda_order_item_completed', [
			'updated_at' => $time,
			'weighing_count' => $weighing_count,
			'status'=>3, // cân xong
			'thoi_gian_can'=>json_encode($thoi_gian_can)
		]);
	}

	public function re_completed_weighing($batch)
	{
		$time = time();
		$id = $batch->compounda_order_item_completed_id;
		$thoi_gian_can = [];
		if($batch->thoi_gian_can == 0)
		{ 
		} else {
			$thoi_gian_can = json_decode($batch->thoi_gian_can,true);
		}
		$thoi_gian_can[] = [
			'started'=>0,
			'ended'=>$time
		];

		if($batch->status == 5)
		{

			$this->db->trans_start();	
			$this->db->where('compounda_order_item_completed_id', $id)
			->update('compounda_order_item_completed', [
				'updated_at' => $time,
				'status'=>3, // cân xong
				'thoi_gian_can'=>json_encode($thoi_gian_can)
			]);

			$this->db->where('qc_cpa_document_id', $batch->qc_cpa_document->qc_cpa_document_id)
			->update('qc_cpa_documents', [
				'status'=>3, // cân xong
			]);

			$this->db->trans_complete();
			return $this->db->trans_status();
		}

	}
	
}
?>