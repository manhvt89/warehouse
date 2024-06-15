<?php
class SmsSale extends CI_Model
{

	//added to support reminder clients retest

	/*
	 Get the sales data for the takings (sales/manage) view
	*/

	public function get_sms_sales()
	{
		// NOTE: temporary tables are created to speed up searches due to the fact that are ortogonal to the main query
		// create a temporary table to contain all the payments per sale item
		$this->db->from("sms_sale");
		$this->db->where("is_sms", 0 ); //Get all records
		//$this->db->group_end();
		return $this->db->get();
	}


	//added by ManhVT
	public function get_info($id)
	{
		$this->db->from('sms_sale');
		$this->db->where('id',$id);
		$query = $this->db->get();
		if($query->result()) {
			return $query->result()[0];
		}
		return null;
	}

	/*
	 Get number of rows for the takings (sales/manage) view
	*/
	public function get_found_rows($search, $filters)
	{
		return $this->search($search, $filters)->num_rows();
	}

	/*
	 Get the sales data for the takings (sales/manage) view
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'created_date', $order = 'desc')
	{
		// NOTE: temporary tables are created to speed up searches due to the fact that are ortogonal to the main query
		// create a temporary table to contain all the payments per sale item
		$this->db->select('reminders.*');

		$this->db->from('reminders');

		//$this->db->where('DATE(FROM_UNIXTIME(reminders.created_date)) BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']));

		if(!empty($search))
		{
			$this->db->group_start();
			// customer last name
			$this->db->like('reminders.name', $search);
			$this->db->group_end();
		}
		//$this->db->group_by('test.sale_id');
		$this->db->order_by('created_date', 'desc');
		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}
		return $this->db->get();
	}

	public function get_search_suggestions($search, $unique = TRUE, $limit = 25)
	{
		$suggestions = array();

		$this->db->from('reminders');

		$this->db->group_start();
		$this->db->like('name', $search);
		$this->db->or_like('phone',$search);
		$this->db->group_end();
		$this->db->where('deleted', 0);
		$this->db->order_by('created_date', 'desc');
		foreach($this->db->get()->result() as $row)
		{
			$suggestions[] = array('value' => $row->person_id, 'label' => $row->name.' | '.$row->phone );
		}

		//only return $limit suggestions
		if($suggestions > $limit)
		{
			$suggestions = array_slice($suggestions, 0, $limit);
		}

		return $suggestions;
	}
	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('sms_sale');

		return $this->db->count_all_results();
	}


	public function exists($id)
	{
		$this->db->from('sms_sale');
		$this->db->where('id', $id);

		return ($this->db->get()->num_rows()==1);
	}

	public function update($id, $data)
	{
		$this->db->where('id', $id);
		$success = $this->db->update('sms_sale', $data);

		return $success;
	}

	public function save($items)
	{
		if(count($items) == 0)
		{
			return -1;
		}
		if(!isset($items['saled_date'])) {
			$items['saled_date'] = time();
		}

		// Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->insert('sms_sale', $items);
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		
		if($this->db->trans_status() === FALSE)
		{
			return -1;
		}
		
		return $id;
	}

	public function delete_list($sale_ids, $employee_id, $update_inventory = TRUE) 
	{
		$result = TRUE;

		foreach($sale_ids as $sale_id)
		{
			$result &= $this->delete($sale_id, $employee_id, $update_inventory);
		}

		return $result;
	}

	public function delete($sale_id, $employee_id, $update_inventory = TRUE) 
	{
		// start a transaction to assure data integrity
		$this->db->trans_start();

		// first delete all payments
		$this->db->delete('sales_payments', array('sale_id' => $sale_id));
		// then delete all taxes on items
		$this->db->delete('sales_items_taxes', array('sale_id' => $sale_id));

		if($update_inventory)
		{
			// defect, not all item deletions will be undone??
			// get array with all the items involved in the sale to update the inventory tracking
			$items = $this->get_sale_items($sale_id)->result_array();
			foreach($items as $item)
			{
				// create query to update inventory tracking
				$inv_data = array(
					'trans_date'      => date('Y-m-d H:i:s'),
					'trans_items'     => $item['item_id'],
					'trans_user'      => $employee_id,
					'trans_comment'   => 'Deleting sale ' . $sale_id,
					'trans_location'  => $item['item_location'],
					'trans_inventory' => $item['quantity_purchased']
				);
				// update inventory
				$this->Inventory->insert($inv_data);

				// update quantities
				$this->Item_quantity->change_quantity($item['item_id'], $item['item_location'], $item['quantity_purchased']);
			}
		}

		// delete all items
		$this->db->delete('sales_items', array('sale_id' => $sale_id));
		// delete sale itself
		$this->db->delete('sales', array('sale_id' => $sale_id));

		// execute transaction
		$this->db->trans_complete();
	
		return $this->db->trans_status();
	}

}
?>
