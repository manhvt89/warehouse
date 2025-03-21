<?php
class Testex extends CI_Model
{

	//added to support reminder clients retest
	/*
	 Get number of rows for the takings (sales/manage) view
	*/
	public function get_found_reminder_rows($search, $filters)
	{
		return $this->search($search, $filters)->num_rows();
	}

	/*
	 Get the sales data for the takings (sales/manage) view
	*/
	public function get_reminders()
	{
		// NOTE: temporary tables are created to speed up searches due to the fact that are ortogonal to the main query
		// create a temporary table to contain all the payments per sale item
		$this->db->select("
				max(test.test_id) as test_id,
				test.test_time as test_time,
				test.note as note,
				test.code as code,
				test.*,
				customers_p.*,
				people.*
		");

		$this->db->from('customers AS customers_p');
		$this->db->join('test AS test', 'test.customer_id = customers_p.person_id', 'left');
		$this->db->join('people AS people', 'test.customer_id = people.person_id', 'left');

		$this->db->where("DATE(FROM_UNIXTIME(expired_date)) = CURDATE()");
		$this->db->where("reminder",1);
		//AND test_time + duration * con >= $pre7day ");
		$this->db->group_by('customer_id');
		return $this->db->get();
	}


	//added by ManhVT

	public function exists_by_code($code)
	{
		$this->db->from('test');
		$this->db->where('code', $code);
		return ($this->db->get()->num_rows()==1);
	}

	public function get_info($id)
	{
		$this->db->from('test');
		$this->db->where('test_id',$id);
		$query = $this->db->get();
		if($query->result('array')) {
			return $query->result('array')[0];
		}
		return null;
	}

	public function get_tests_by_customer($customer_id,$limit =0)
	{
		$this->db->from('test');
		$this->db->where('customer_id',$customer_id);
		if($limit > 0) {
			$this->db->limit($limit);
		}else{
			$this->db->limit(50);
		}
		$this->db->order_by('test_id','desc');
		$query = $this->db->get();
		return $query->result_array();
	}


	/*
	 Get number of rows for the takings (sales/manage) view
	*/
	public function get_found_rows($search, $filters, $bUser_Type=0, $iEmployeer_Id=0)
	{
		return $this->search($search, $filters,0,0,'test_time','desc',$bUser_Type, $iEmployeer_Id)->num_rows();
	}

	/*
	 Get the sales data for the takings (sales/manage) view
	*/
	public function search($search, $filters, $rows = 0, $limit_from = 0, $sort = 'test_time', $order = 'desc',$bUser_Type=0, $iEmployeer_Id=0)
	{
		// NOTE: temporary tables are created to speed up searches due to the fact that are ortogonal to the main query
		// create a temporary table to contain all the payments per sale item


		$this->db->select('
				test.test_id as test_id,
				test.test_time as test_time,
				test.note as note,
				test.code as code,
				test.*,
				customers_p.*,
				people.*
		');

		$this->db->from('customers AS customers_p');
		$this->db->join('test AS test', 'test.customer_id = customers_p.person_id', 'left');
		$this->db->join('people AS people', 'test.customer_id = people.person_id', 'left');

		$this->db->where('DATE(FROM_UNIXTIME(test.test_time)) BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']));

		if(!empty($search))
		{
			if($filters['is_valid_receipt'] != FALSE)
			{
				$pieces = explode(' ', $search);
				$this->db->where('test.test_id', $pieces[1]);
			}
			else
			{			
				$this->db->group_start();
					// customer last name
					$this->db->like('customer_p.last_name', $search);
					// customer first name
					$this->db->or_like('customer_p.first_name', $search);
					// customer first and last name
					$this->db->or_like('CONCAT(customer_p.first_name, " ", customer_p.last_name)', $search);
					// customer company name
				$this->db->group_end();
			}
		}

		if($filters['location_id'] != 'all')
		{
			$this->db->where('test.location_id', $filters['location_id']);
		}
		if($bUser_Type == 2) // Nếu là bác sĩ
		{
			$this->db->where('employeer_id', $iEmployeer_Id);
		}
/*
		if($filters['sale_type'] == 'sales')
        {
            $this->db->where('sales_items.quantity_purchased > 0');
        }
        elseif($filters['sale_type'] == 'returns')
        {
            $this->db->where('sales_items.quantity_purchased < 0');
        }

*/
		//$this->db->group_by('test.sale_id');
		$this->db->order_by($sort, $order);

		if($rows > 0)
		{
			$this->db->limit($rows, $limit_from);
		}
		return $this->db->get();
	}

	/*
	 Get the payment summary for the takings (sales/manage) view
	*/
	public function get_payments_summary($search, $filters)
	{
		// get payment summary
		$this->db->select('payment_type, count(*) AS count, SUM(payment_amount) AS payment_amount');
		$this->db->from('sales');
		$this->db->join('sales_payments', 'sales_payments.sale_id = sales.sale_id');
		$this->db->join('people AS customer_p', 'sales.customer_id = customer_p.person_id', 'left');
		$this->db->join('customers AS customer', 'sales.customer_id = customer.person_id', 'left');

		$this->db->where('DATE(sale_time) BETWEEN ' . $this->db->escape($filters['start_date']) . ' AND ' . $this->db->escape($filters['end_date']));

		if(!empty($search))
		{
			if($filters['is_valid_receipt'] != FALSE)
			{
				$pieces = explode(' ',$search);
				$this->db->where('sales.sale_id', $pieces[1]);
			}
			else
			{
				$this->db->group_start();
					// customer last name
					$this->db->like('customer_p.last_name', $search);
					// customer first name
					$this->db->or_like('customer_p.first_name', $search);
					// customer first and last name
					$this->db->or_like('CONCAT(customer_p.first_name, " ", customer_p.last_name)', $search);
					// customer company name
					$this->db->or_like('customer.company_name', $search);
				$this->db->group_end();
			}
		}

		if($filters['sale_type'] == 'sales')
		{
			$this->db->where('payment_amount > 0');
		}
		elseif($filters['sale_type'] == 'returns')
		{
			$this->db->where('payment_amount < 0');
		}

		if($filters['only_invoices'] != FALSE)
		{
			$this->db->where('invoice_number IS NOT NULL');
		}
		
		if($filters['only_cash'] != FALSE)
		{
			$this->db->like('payment_type', $this->lang->line('sales_cash'), 'after');
		}

		$this->db->group_by('payment_type');

		$payments = $this->db->get()->result_array();

		// consider Gift Card as only one type of payment and do not show "Gift Card: 1, Gift Card: 2, etc." in the total
		$gift_card_count = 0;
		$gift_card_amount = 0;
		foreach($payments as $key=>$payment)
		{
			if( strstr($payment['payment_type'], $this->lang->line('sales_giftcard')) != FALSE )
			{
				$gift_card_count  += $payment['count'];
				$gift_card_amount += $payment['payment_amount'];

				// remove the "Gift Card: 1", "Gift Card: 2", etc. payment string
				unset($payments[$key]);
			}
		}

		if($gift_card_count > 0)
		{
			$payments[] = array('payment_type' => $this->lang->line('sales_giftcard'), 'count' => $gift_card_count, 'payment_amount' => $gift_card_amount);
		}

		return $payments;
	}

	/*
	Gets total of rows
	*/
	public function get_total_rows()
	{
		$this->db->from('test');

		return $this->db->count_all_results();
	}

	public function get_search_suggestions($search, $limit = 25)
	{
		$suggestions = array();

		if(!$this->is_valid_receipt($search))
		{
			$this->db->distinct();
			$this->db->select('first_name, last_name');
			$this->db->from('sales');
			$this->db->join('people', 'people.person_id = sales.customer_id');
			$this->db->like('last_name', $search);
			$this->db->or_like('first_name', $search);
			$this->db->or_like('CONCAT(first_name, " ", last_name)', $search);
			$this->db->or_like('company_name', $search);
			$this->db->order_by('last_name', 'asc');

			foreach($this->db->get()->result_array() as $result)
			{
				$suggestions[] = array('label' => $result['first_name'] . ' ' . $result['last_name']);
			}
		}
		else
		{
			$suggestions[] = array('label' => $search);
		}

		return $suggestions;
	}

	/*
	Gets total of invoice rows
	*/
	public function get_invoice_count()
	{
		$this->db->from('sales');
		$this->db->where('invoice_number IS NOT NULL');

		return $this->db->count_all_results();
	}

	public function get_sale_by_invoice_number($invoice_number)
	{
		$this->db->from('sales');
		$this->db->where('invoice_number', $invoice_number);

		return $this->db->get();
	}

	public function get_invoice_number_for_year($year = '', $start_from = 0) 
	{
		$year = $year == '' ? date('Y') : $year;
		$this->db->select('COUNT( 1 ) AS invoice_number_year');
		$this->db->from('sales');
		$this->db->where('DATE_FORMAT(sale_time, "%Y" ) = ', $year);
		$this->db->where('invoice_number IS NOT NULL');
		$result = $this->db->get()->row_array();

		return ($start_from + $result['invoice_number_year']);
	}
	
	public function is_valid_receipt(&$receipt_sale_id)
	{
		if(!empty($receipt_sale_id))
		{
			//POS #
			$pieces = explode(' ', $receipt_sale_id);

			if(count($pieces) == 2 && preg_match('/(POS)/', $pieces[0]))
			{
				return $this->exists($pieces[1]);
			}
			elseif($this->config->item('invoice_enable') == TRUE)
			{
				$sale_info = $this->get_sale_by_invoice_number($receipt_sale_id);
				if($sale_info->num_rows() > 0)
				{
					$receipt_sale_id = 'POS ' . $sale_info->row()->sale_id;

					return TRUE;
				}
			}
		}

		return FALSE;
	}

	public function exists($test_id)
	{
		$this->db->from('test');
		$this->db->where('test_id', $test_id);

		return ($this->db->get()->num_rows()==1);
	}

	public function update($sale_id, $sale_data)
	{
		$this->db->where('test_id', $sale_id);
		$success = $this->db->update('test', $sale_data);

		return $success;
	}

	public function save($items)
	{
		if(count($items) == 0)
		{
			return -1;
		}
		if(!isset($items['test_time'])) {
			$items['test_time'] = time();
		}
		if(is_numeric($items['duration']))
		{
			if($items['duration_dvt'] == 'Tháng')
			{
				$items['expired_date'] = $items['test_time'] + $items['duration']*24*30*3600;
			} elseif($items['duration_dvt'] == 'Tuần'){
				$items['expired_date'] = $items['test_time'] + $items['duration']*24*7*3600;
			} else {
				$items['expired_date'] = $items['test_time'] + $items['duration']*24*3600;
			}
			
		} else {
			$items['expired_date'] = $items['test_time'];
		}
		// Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->insert('test', $items);
		$sale_id = $this->db->insert_id();
		$this->db->trans_complete();
		
		if($this->db->trans_status() === FALSE)
		{
			return -1;
		}
		
		return $sale_id;
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

	public function get_sale_items($sale_id)
	{
		$this->db->from('sales_items');
		$this->db->where('sale_id', $sale_id);

		return $this->db->get();
	}

	public function get_sale_payments($sale_id)
	{
		$this->db->from('sales_payments');
		$this->db->where('sale_id', $sale_id);

		return $this->db->get();
	}

	public function get_payment_options($giftcard = TRUE)
	{
		$payments = array();
		
		if($this->config->item('payment_options_order') == 'debitcreditcash')
		{
			$payments[$this->lang->line('sales_debit')] = $this->lang->line('sales_debit');
			$payments[$this->lang->line('sales_credit')] = $this->lang->line('sales_credit');
			$payments[$this->lang->line('sales_cash')] = $this->lang->line('sales_cash');
		}
		elseif($this->config->item('payment_options_order') == 'debitcashcredit')
		{
			$payments[$this->lang->line('sales_debit')] = $this->lang->line('sales_debit');
			$payments[$this->lang->line('sales_cash')] = $this->lang->line('sales_cash');
			$payments[$this->lang->line('sales_credit')] = $this->lang->line('sales_credit');
		}
		else // default: if($this->config->item('payment_options_order') == 'cashdebitcredit')
		{
			$payments[$this->lang->line('sales_cash')] = $this->lang->line('sales_cash');
			$payments[$this->lang->line('sales_debit')] = $this->lang->line('sales_debit');
			$payments[$this->lang->line('sales_credit')] = $this->lang->line('sales_credit');
		}

		$payments[$this->lang->line('sales_check')] = $this->lang->line('sales_check');

		if($giftcard)
		{
			$payments[$this->lang->line('sales_giftcard')] = $this->lang->line('sales_giftcard');
		}

		return $payments;
	}

	public function get_customer($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id', $sale_id);

		return $this->Customer->get_info($this->db->get()->row()->customer_id);
	}

	public function get_employee($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id', $sale_id);

		return $this->Employee->get_info($this->db->get()->row()->employee_id);
	}

	public function check_invoice_number_exists($invoice_number, $sale_id = '')
	{
		$this->db->from('sales');
		$this->db->where('invoice_number', $invoice_number);
		if(!empty($sale_id))
		{
			$this->db->where('sale_id !=', $sale_id);
		}
		
		return ($this->db->get()->num_rows() == 1);
	}

	public function get_giftcard_value($giftcardNumber)
	{
		if(!$this->Giftcard->exists($this->Giftcard->get_giftcard_id($giftcardNumber)))
		{
			return 0;
		}
		
		$this->db->from('giftcards');
		$this->db->where('giftcard_number', $giftcardNumber);

		return $this->db->get()->row()->value;
	}

	//We create a temp table that allows us to do easy report/sales queries
	public function create_temp_table(array $inputs)
	{
		if($this->config->item('tax_included'))
		{
			$sale_total = '(sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount_percent / 100))';
			$sale_subtotal = '(sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount_percent / 100) * (100 / (100 + SUM(sales_items_taxes.percent))))';
			$sale_tax = '(sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount_percent / 100) * (1 - 100 / (100 + SUM(sales_items_taxes.percent))))';
		}
		else
		{
			$sale_total = '(sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount_percent / 100) * (1 + (SUM(sales_items_taxes.percent) / 100)))';
			$sale_subtotal = '(sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount_percent / 100))';
			$sale_tax = '(sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount_percent / 100) * (SUM(sales_items_taxes.percent) / 100))';
		}

		$sale_cost  = '(sales_items.item_cost_price * sales_items.quantity_purchased)';

		$decimals = totals_decimals();

		if(empty($inputs['sale_id']))
		{
			$where = 'WHERE DATE(sales.sale_time) BETWEEN ' . $this->db->escape($inputs['start_date']) . ' AND ' . $this->db->escape($inputs['end_date']);
		}
		else
		{
			$where = 'WHERE sales.sale_id = ' . $this->db->escape($inputs['sale_id']);
		}

		// create a temporary table to contain all the payment types and amount
		$this->db->query('CREATE TEMPORARY TABLE IF NOT EXISTS ' . $this->db->dbprefix('sales_payments_temp') . 
			' (PRIMARY KEY(sale_id), INDEX(sale_id))
			(
				SELECT payments.sale_id AS sale_id, 
					IFNULL(SUM(payments.payment_amount), 0) AS sale_payment_amount,
					GROUP_CONCAT(CONCAT(payments.payment_type, " ", payments.payment_amount) SEPARATOR ", ") AS payment_type
				FROM ' . $this->db->dbprefix('sales_payments') . ' AS payments
				INNER JOIN ' . $this->db->dbprefix('sales') . ' AS sales
					ON sales.sale_id = payments.sale_id
				' . "
				$where
				" . '
				GROUP BY payments.sale_id
			)'
		);

		$this->db->query('CREATE TEMPORARY TABLE IF NOT EXISTS ' . $this->db->dbprefix('sales_items_temp') . 
			' (INDEX(sale_date), INDEX(sale_id))
			(
				SELECT
					DATE(sales.sale_time) AS sale_date,
					sales.sale_time,
					sales.sale_id,
					sales.comment,
					sales.invoice_number,
					sales.customer_id,
					CONCAT(customer_p.first_name, " ", customer_p.last_name) AS customer_name,
					customer_p.first_name AS customer_first_name,
					customer_p.last_name AS customer_last_name,
					customer_p.email AS customer_email,
					customer_p.comments AS customer_comments, 
					customer.company_name AS customer_company_name,
					sales.employee_id,
					CONCAT(employee.first_name, " ", employee.last_name) AS employee_name,
					items.item_id,
					items.name,
					items.category,
					items.supplier_id,
					sales_items.quantity_purchased,
					sales_items.item_cost_price,
					sales_items.item_unit_price,
					sales_items.discount_percent,
					sales_items.line,
					sales_items.serialnumber,
					sales_items.item_location,
					sales_items.description,
					payments.payment_type,
					payments.sale_payment_amount,
					IFNULL(SUM(sales_items_taxes.percent), 0) AS item_tax_percent,
					' . "
					ROUND($sale_subtotal, $decimals) AS subtotal,
					IFNULL(ROUND($sale_tax, $decimals), 0) AS tax,
					IFNULL(ROUND($sale_total, $decimals), ROUND($sale_subtotal, $decimals)) AS total,
					ROUND($sale_cost, $decimals) AS cost,
					ROUND($sale_total - IFNULL($sale_tax, 0) - $sale_cost, $decimals) AS profit
					" . '
				FROM ' . $this->db->dbprefix('sales_items') . ' AS sales_items
				INNER JOIN ' . $this->db->dbprefix('sales') . ' AS sales
					ON sales_items.sale_id = sales.sale_id
				INNER JOIN ' . $this->db->dbprefix('items') . ' AS items
					ON sales_items.item_id = items.item_id
				LEFT OUTER JOIN ' . $this->db->dbprefix('sales_payments_temp') . ' AS payments
					ON sales_items.sale_id = payments.sale_id		
				LEFT OUTER JOIN ' . $this->db->dbprefix('suppliers') . ' AS supplier
					ON items.supplier_id = supplier.person_id
				LEFT OUTER JOIN ' . $this->db->dbprefix('people') . ' AS customer_p
					ON sales.customer_id = customer_p.person_id
				LEFT OUTER JOIN ' . $this->db->dbprefix('customers') . ' AS customer
					ON sales.customer_id = customer.person_id
				LEFT OUTER JOIN ' . $this->db->dbprefix('people') . ' AS employee
					ON sales.employee_id = employee.person_id
				LEFT OUTER JOIN ' . $this->db->dbprefix('sales_items_taxes') . ' AS sales_items_taxes
					ON sales_items.sale_id = sales_items_taxes.sale_id AND sales_items.item_id = sales_items_taxes.item_id AND sales_items.line = sales_items_taxes.line
				' . "
				$where
				" . '
				GROUP BY sales.sale_id, items.item_id, sales_items.line
			)'
		);

		// drop the temporary table to contain memory consumption as it's no longer required
		$this->db->query('DROP TEMPORARY TABLE IF EXISTS ' . $this->db->dbprefix('sales_payments_temp'));
	}

	public function get_info_tests_yeserday()
	{
		// NOTE: temporary tables are created to speed up searches due to the fact that are ortogonal to the main query
		// create a temporary table to contain all the payments per sale item
		$this->db->select("
				max(test.test_id) as test_id,
				test.test_time as test_time,
				test.note as note,
				test.code as code,
				test.*,
				customers_p.*,
				people.*
		");

		$this->db->from('customers AS customers_p');
		$this->db->join('test AS test', 'test.customer_id = customers_p.person_id', 'left');
		$this->db->join('people AS people', 'test.customer_id = people.person_id', 'left');
		//$this->db->where("DATE(FROM_UNIXTIME(test.test_time)) = CURDATE()");
		//$this->db->where('DATE(FROM_UNIXTIME(test.expired_date)) BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() - INTERVAL 5 DAY');
		$this->db->where('test.expired_date <=', date('Y-m-d', strtotime('+10 days')));

		//$this->db->where("reminder",1);
		//AND test_time + duration * con >= $pre7day ");
		$this->db->group_by('customer_id');
		return $this->db->get();
	}
	public function get_info_tests()
	{
		// NOTE: temporary tables are created to speed up searches due to the fact that are ortogonal to the main query
		// create a temporary table to contain all the payments per sale item
		$this->db->select("
				max(test.test_id) as test_id,
				test.test_time as test_time,
				test.note as note,
				test.code as code,
				test.*,
				customers_p.*,
				people.*
		");

		$this->db->from('customers AS customers_p');
		$this->db->join('test AS test', 'test.customer_id = customers_p.person_id', 'left');
		$this->db->join('people AS people', 'test.customer_id = people.person_id', 'left');
		//$this->db->where("DATE(FROM_UNIXTIME(test.test_time)) = CURDATE()");
		//$this->db->where('DATE(FROM_UNIXTIME(test.test_time)) BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND');

		//$this->db->where("reminder",1);
		//AND test_time + duration * con >= $pre7day ");
		$this->db->group_by('customer_id');
		return $this->db->get();
	}

	public function get_tests_by_uuid($uuid,$limit =0)
	{
		$this->db->from('test');
		$this->db->join('customers','customers.person_id = test.customer_id');
		$this->db->where('customer_uuid',$uuid);
		if($limit > 0) {
			$this->db->limit($limit);
		}else{
			$this->db->limit(50);
		}
		$this->db->order_by('test_id','desc');
		$query = $this->db->get();
		return $query->result_array();
	}
}
?>
