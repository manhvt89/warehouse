<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Locations extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('locations');

		//$this->load->library('item_lib');
		//$this->load->model('cron/Product');
		//$this->url = 'https://apicuongdat.thiluc2020.com';
	}
	
	public function index()
	{

		$data['table_headers'] = $this->xss_clean(get_locations_manage_table_headers());

		//$data['table_headers'] = $this->xss_clean(get_items_manage_table_headers());

		
		//$data['stock_location'] = $this->xss_clean($this->item_lib->get_item_location());
		//$data['stock_locations'] = $this->xss_clean($this->Stock_location->get_allowed_locations());

		// filters that will be loaded in the multiselect dropdown
		$data['filters'] = array('empty_upc' => $this->lang->line('items_empty_upc_items'),
			'low_inventory' => $this->lang->line('items_low_inventory_items'),
			'is_deleted' => $this->lang->line('items_is_deleted'));

		$data['hide_unitprice'] = $this->Employee->has_grant('items_unitprice_hide');
		debug_log($data,'data');
		$this->load->view('location/manage', $data);
	}

	/*
	Returns Items table data rows. This will be called with AJAX.
	*/
	public function search()
	{
		$search = $this->input->get('search');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		$sort = $this->input->get('sort');
		$order = $this->input->get('order');
		$filters = [];
		$items = $this->Stock_location->search($search, $filters, $limit, $offset, $sort, $order);
		$total_rows = $this->Stock_location->get_found_rows($search, $filters);

		$data_rows = array();
		foreach($items->result() as $item)
		{
			$data_rows[] = $this->xss_clean(get_location_data_row($item, $this));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
	}

	public function view($location_id = -1)
	{
		$person_id = $this->session->userdata('person_id');
		$controller_name = strtolower(get_class($this));
		
		//$data['has_grant'] = $this->Employee->has_grant($controller_name.'_view', $person_id);
		$location_info = $this->Stock_location->get_info($location_id);
		foreach(get_object_vars($location_info) as $property => $value)
		{
			$location_info->$property = $this->xss_clean($value);
		}

		if($location_id == -1)
		{
			
			
			$location_info->location_name = '';
			$location_info->location_code = '';
			$location_info->location_phone = '';
			$location_info->location_address = '';
		}

		$data['location_info'] = $location_info;

		$this->load->view('location/form', $data);
	}

	public function save($location_uuid = -1)
	{
		//Save item data
		
		$has_grant = true;
		$item_data = array(
			'location_name' => $this->input->post('location_name'),
			'location_code' => $this->input->post('location_code'),
			'location_phone' => $this->input->post('location_phone'),
			'location_address' => $this->input->post('location_address'),
			'deleted'=>$this->input->post('is_deleted'),
		);
		
		
		
		
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		//$cur_item_info = $this->Item->get_info($item_id);
		
		if($this->Stock_location->save($item_data, $location_uuid))
		{
			$success = TRUE;
			$new_item = FALSE;
			//New item
			if($location_uuid == -1)
			{
				$item_id = $item_data['$location_uuid'];
				$new_item = TRUE;
			}
			
			
            
            //Save item quantity
            
			if($success)
            {
            	$message = $this->xss_clean($this->lang->line('items_successful_' . ($new_item ? 'adding' : 'updating')) . ' ' . $item_data['location_name']);

            	echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $location_uuid));
            }
            else
            {
            	$message = $this->xss_clean($upload_success ? $this->lang->line('items_error_adding_updating') . ' ' . $item_data['location_name'] : strip_tags($this->upload->display_errors())); 

            	echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => $location_uuid));
            }
		}
		else//failure
		{
			$message = $this->xss_clean($this->lang->line('items_error_adding_updating') . ' ' . $item_data['location_name']);

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
	}
	
	public function pic_thumb($pic_id=null)
	{
		if($pic_id == null)
		{
			echo 'Invalid Data';
			exit();
		}
		$this->load->helper('file');
		$this->load->library('image_lib');
		$base_path = './uploads/item_pics/' . $pic_id;
		$images = glob($base_path . '.*');
		if(sizeof($images) > 0)
		{
			$image_path = $images[0];
			$ext = pathinfo($image_path, PATHINFO_EXTENSION);
			$thumb_path = $base_path . $this->image_lib->thumb_marker . '.' . $ext;
			if(sizeof($images) < 2)
			{
				$config['image_library'] = 'gd2';
				$config['source_image']  = $image_path;
				$config['maintain_ratio'] = TRUE;
				$config['create_thumb'] = TRUE;
				$config['width'] = 52;
				$config['height'] = 32;
 				$this->image_lib->initialize($config);
 				$image = $this->image_lib->resize();
				$thumb_path = $this->image_lib->full_dst_path;
			}
			$this->output->set_content_type(get_mime_by_extension($thumb_path));
			$this->output->set_output(file_get_contents($thumb_path));
		}
	}

	

	/*
	 Gives search suggestions based on what is being searched for
	*/
	public function suggest_location()
	{
		$suggestions = $this->xss_clean($this->Item->get_location_suggestions($this->input->get('term')));

		echo json_encode($suggestions);
	}
	
	/*
	 Gives search suggestions based on what is being searched for
	*/
	public function suggest_custom()
	{
		$suggestions = $this->xss_clean($this->Item->get_custom_suggestions($this->input->post('term'), $this->input->post('field_no')));

		echo json_encode($suggestions);
	}

	public function get_row($item_ids='')
	{
		if($item_ids == '')
		{
			echo 'Invalid Data';
			exit();
		}
		$item_infos = $this->Item->get_multiple_info(explode(":", $item_ids), $this->item_lib->get_item_location());

		$result = array();
		foreach($item_infos->result() as $item_info)
		{
			$result[$item_info->item_id] = $this->xss_clean(get_item_data_row($item_info, $this));
		}

		echo json_encode($result);
	}

	
    
	public function inventory($item_id = -1)
	{
		$item_info = $this->Item->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;

        $data['stock_locations'] = array();
        $stock_locations = $this->Stock_location->get_undeleted_all()->result_array();
        foreach($stock_locations as $location)
        {
			$location = $this->xss_clean($location);
			$quantity = $this->xss_clean($this->Item_quantity->get_item_quantity($item_id, $location['location_id'])->quantity);
		
            $data['stock_locations'][$location['location_id']] = $location['location_name'];
            $data['item_quantities'][$location['location_id']] = $quantity;
        }

		$this->load->view('items/form_inventory', $data);
	}
	
	public function count_details($item_id = -1)
	{
		$item_info = $this->Item->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}
		$data['item_info'] = $item_info;

        $data['stock_locations'] = array();
        $stock_locations = $this->Stock_location->get_undeleted_all()->result_array();
        foreach($stock_locations as $location)
        {
			$location = $this->xss_clean($location);
			$quantity = $this->xss_clean($this->Item_quantity->get_item_quantity($item_id, $location['location_id'])->quantity);
		
            $data['stock_locations'][$location['location_id']] = $location['location_name'];
            $data['item_quantities'][$location['location_id']] = $quantity;
        }

		$this->load->view('items/form_count_details', $data);
	}

	public function generate_barcodes($item_ids)
	{
		$this->load->library('barcode_lib');

		$item_ids = explode(':', $item_ids);
		$result = $this->Item->get_multiple_info($item_ids, $this->item_lib->get_item_location())->result_array();
		$config = $this->barcode_lib->get_barcode_config();

		$data['barcode_config'] = $config;

		// check the list of items to see if any item_number field is empty
		foreach($result as &$item)
		{
			$item = $this->xss_clean($item);
			
			// update the UPC/EAN/ISBN field if empty / NULL with the newly generated barcode
			if(empty($item['item_number']) && $this->config->item('barcode_generate_if_empty'))
			{
				// get the newly generated barcode
				$barcode_instance = Barcode_lib::barcode_instance($item, $config);
				$item['item_number'] = $barcode_instance->getData();
				
				$save_item = array('item_number' => $item['item_number']);

				// update the item in the database in order to save the UPC/EAN/ISBN field
				$this->Item->save($save_item, $item['item_id']);
			}
		}
		$data['items'] = $result;

		// display barcodes
		$this->load->view('barcodes/barcode_sheet', $data);
	}

	public function generate_barcodes_lens($item_ids)
	{
		$this->load->library('barcode_lib');

		$item_ids = explode(':', $item_ids);
		$result = $this->Item->get_multiple_info($item_ids, $this->item_lib->get_item_location())->result_array();
		$config = $this->barcode_lib->get_barcode_config();

		$data['barcode_config'] = $config;

		// check the list of items to see if any item_number field is empty
		foreach($result as &$item)
		{
			$item = $this->xss_clean($item);
			
			// update the UPC/EAN/ISBN field if empty / NULL with the newly generated barcode
			if(empty($item['item_number']) && $this->config->item('barcode_generate_if_empty'))
			{
				// get the newly generated barcode
				$barcode_instance = Barcode_lib::barcode_instance($item, $config);
				$item['item_number'] = $barcode_instance->getData();
				
				$save_item = array('item_number' => $item['item_number']);

				// update the item in the database in order to save the UPC/EAN/ISBN field
				$this->Item->save($save_item, $item['item_id']);
			}
		}
		$data['items'] = $result;

		// display barcodes
		$this->load->view('barcodes/barcode_sheet_lens', $data);
	}

	public function add_barcodes($item_ids=0)
	{
		if($item_ids == 0)
		{
			redirect(base_url('items/'));
		}
		$this->load->library('printbarcode_lib');

		$item_ids = explode(':', $item_ids);
		//$result = $this->Item->get_multiple_info($item_ids, $this->item_lib->get_item_location())->result_array();
		$quantity = 1;
		// check the list of items to see if any item_number field is empty
		foreach($item_ids as $item)
		{
			$item = $this->xss_clean($item);

			$this->printbarcode_lib->add_item($item, $quantity);
			
		}
		redirect(base_url('items/'));
	}
	public function bulk_edit()
	{
		$suppliers = array('' => $this->lang->line('items_none'));
		foreach($this->Supplier->get_all()->result_array() as $row)
		{
			$row = $this->xss_clean($row);

			$suppliers[$row['person_id']] = $row['company_name'];
		}
		$person_id = $this->session->userdata('person_id');
		$data['has_grant'] = $this->Employee->has_grant('items_accounting', $person_id);
		$data['suppliers'] = $suppliers;
		$data['allow_alt_description_choices'] = array(
			'' => $this->lang->line('items_do_nothing'), 
			1  => $this->lang->line('items_change_all_to_allow_alt_desc'),
			0  => $this->lang->line('items_change_all_to_not_allow_allow_desc'));

		$data['serialization_choices'] = array(
			'' => $this->lang->line('items_do_nothing'), 
			1  => $this->lang->line('items_change_all_to_serialized'),
			0  => $this->lang->line('items_change_all_to_unserialized'));

		$this->load->view('items/form_bulk', $data);
	}

	
	
	public function check_item_number()
	{
		$exists = $this->Item->item_number_exists($this->input->post('item_number'), $this->input->post('item_id'));
		echo !$exists ? 'true' : 'false';
	}
	
	private function _handle_image_upload()
	{
		$this->load->helper('directory');

		$map = directory_map('./uploads/item_pics/', 1);

		// load upload library
		$config = array('upload_path' => './uploads/item_pics/',
			'allowed_types' => 'gif|jpg|png',
			'max_size' => '100',
			'max_width' => '640',
			'max_height' => '480',
			'file_name' => sizeof($map) + 1
		);
		$this->load->library('upload', $config);
		$this->upload->do_upload('item_image');           
		
		return strlen($this->upload->display_errors()) == 0 || !strcmp($this->upload->display_errors(), '<p>'.$this->lang->line('upload_no_file_selected').'</p>');
	}

	public function remove_logo($item_id)
	{
		$item_data = array('pic_id' => NULL);
		$result = $this->Item->save($item_data, $item_id);

		echo json_encode(array('success' => $result));
	}

	public function save_inventory($item_id = -1)
	{	
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);
        $location_id = $this->input->post('stock_location');
		$inv_data = array(
			'trans_date' => date('Y-m-d H:i:s'),
			'trans_items' => $item_id,
			'trans_user' => $employee_id,
			'trans_location' => $location_id,
			'trans_comment' => $this->input->post('trans_comment'),
			'trans_inventory' => parse_decimals($this->input->post('newquantity'))
		);
		
		$this->Inventory->insert($inv_data);
		
		//Update stock quantity
		$item_quantity = $this->Item_quantity->get_item_quantity($item_id, $location_id);
		$item_quantity_data = array(
			'item_id' => $item_id,
			'location_id' => $location_id,
			'quantity' => $item_quantity->quantity + parse_decimals($this->input->post('newquantity'))
		);

		if($this->Item_quantity->save($item_quantity_data, $item_id, $location_id))
		{
			$message = $this->xss_clean($this->lang->line('items_successful_updating') . ' ' . $cur_item_info->name);
			
			echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
		}
		else//failure
		{
			$message = $this->xss_clean($this->lang->line('items_error_adding_updating') . ' ' . $cur_item_info->name);
			
			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
	}

	public function bulk_update()
	{
		$items_to_update = $this->input->post('item_ids');
		$item_data = array();

		foreach($_POST as $key => $value)
		{		
			//This field is nullable, so treat it differently
			if($key == 'supplier_id' && $value != '')
			{	
				$item_data["$key"] = $value;
			}
			elseif($value != '' && !(in_array($key, array('item_ids', 'tax_names', 'tax_percents'))))
			{
				$item_data["$key"] = $value;
			}
		}

		//Item data could be empty if tax information is being updated
		if(empty($item_data) || $this->Item->update_multiple($item_data, $items_to_update))
		{
			$items_taxes_data = array();
			$tax_names = $this->input->post('tax_names');
			$tax_percents = $this->input->post('tax_percents');
			$tax_updated = FALSE;
			$count = count($tax_percents);
			for ($k = 0; $k < $count; ++$k)
			{		
				if(!empty($tax_names[$k]) && is_numeric($tax_percents[$k]))
				{
					$tax_updated = TRUE;
					
					$items_taxes_data[] = array('name' => $tax_names[$k], 'percent' => $tax_percents[$k]);
				}
			}
			
			if($tax_updated)
			{
				$this->Item_taxes->save_multiple($items_taxes_data, $items_to_update);
			}

			echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_successful_bulk_edit'), 'id' => $this->xss_clean($items_to_update)));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_error_updating_multiple')));
		}
	}

	public function delete()
	{
		$items_to_delete = $this->input->post('ids');

		if($this->Item->delete_list($items_to_delete))
		{
			$message = $this->lang->line('items_successful_deleted') . ' ' . count($items_to_delete) . ' ' . $this->lang->line('items_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_cannot_be_deleted')));
		}
	}

	/*
	Items import from excel spreadsheet
	*/
	public function excel()
	{
		$name = 'import_items.xlsx';
		$data = file_get_contents('../' . $name);
		force_download($name, $data);
	}
	
	public function excel_import()
	{
		$this->load->view('items/form_excel_import', NULL);
	}


	//import don hang

	public function do_excel_import_dh()
	{
		//$this->load->library('sale_lib');
		if($_FILES['file_path']['error'] != UPLOAD_ERR_OK)
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_failed')));
		}
		else
		{
			if(($handle = fopen($_FILES['file_path']['tmp_name'], 'r')) !== FALSE)
			{
				// Skip the first row as it's the table description
				fgetcsv($handle);
				$i = 1;

				$failCodes = array();
				$arrxns = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					// XSS file data sanity check
					$data = $this->xss_clean($data);
					//$item_data = array();
					if(sizeof($data) >= 14)
					{
						$invalidated = FALSE;

						$item = $this->Item->get_info_by_id_or_number($data[2]);

						if(is_object($item))
						{
							$arrxns[$data[0]]['items'][$data[2]] = array(
								'barcode'=>$data[2],
								'quantity_purchased'=>$data[3],
								'item_cost_price'=>$data[4],
								'item_unit_price'=>$data[5],
								'discount_percent'=>$data[6],
								'item_location' => 1,
								'item_id'=>$item->item_id
							);
						}
						$arrxns[$data[0]]['sale_time'] = strtotime($data[10]);
						$arrxns[$data[0]]['employee_id'] = 28;
						$arrxns[$data[0]]['comment'] = '';
						$arrxns[$data[0]]['test_id'] = 0;
						$arrxns[$data[0]]['status'] = 0;
						$arrxns[$data[0]]['code'] = $data[0];
						$arrxns[$data[0]]['tienhang'] = $data[13];
						$arrxns[$data[0]]['tienck'] = $data[15];
						$arrxns[$data[0]]['tongtien'] = $data[16];
						$customer = $this->Customer->get_info_by_account_number($data[20]);
						if($customer)
						{
							$arrxns[$data[0]]['customer_id'] = $customer->person_id;
						}else{
							$invalidated = TRUE;
						}
					}
					else
					{
						$invalidated = TRUE;
					}

					//var_dump($obj);
					//Kiểm tra xem đã tồn tại đơn kính chưa?
				}


				foreach($arrxns as $item)
				{
					$this->Sale->import_sale($item);
				}

				if(count($failCodes) > 0)
				{
					$message = $this->lang->line('items_excel_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);

					echo json_encode(array('success' => FALSE, 'message' => $message));
				}
				else
				{
					echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_excel_import_success')));
				}
			}
			else
			{
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_nodata_wrongformat')));
			}
		}
	}

	//Import don kinh
	public function do_excel_import_dk()
	{
		if($_FILES['file_path']['error'] != UPLOAD_ERR_OK)
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_failed')));
		}
		else
		{
			if(($handle = fopen($_FILES['file_path']['tmp_name'], 'r')) !== FALSE)
			{
				// Skip the first row as it's the table description
				fgetcsv($handle);
				$i = 1;

				$failCodes = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					// XSS file data sanity check
					$data = $this->xss_clean($data);
					//$item_data = array();
					if(sizeof($data) >= 14)
					{

						$reArray = array(); // right eye information
						$leArray = array(); // left eye information

						$leArray['ADD'] = $data[9];
						$leArray['AX'] = $data[8];
						$leArray['CYL'] = $data[7];
						$leArray['PD'] = $data[11];
						//$leArray['ADD'] = $this->input->post('l_add') ? $this->input->post('l_add');
						$leArray['SPH'] = $data[6];
						$leArray['VA'] = $data[10];

						$reArray['ADD'] = $data[16];
						$reArray['AX'] = $data[15];
						$reArray['CYL'] = $data[14];
						$reArray['PD'] = $data[18];
						//$reArray['ADD'] = $this->input->post('r_add');
						$reArray['SPH'] = $data[13];
						$reArray['VA'] = $data[17];

						$obj['note'] = '';
						$obj['right_e'] = json_encode($reArray);
						$obj['left_e'] = json_encode($leArray);
						if($data[19]==1)
						{
							$obj['toltal'] = 'Nhìn xa';
						}else{
							$obj['toltal'] = '';
						}
						if($data[12]==1){
							$obj['toltal'] = $obj['toltal'] . ';' . 'Nhìn gần';
						}else{
							$obj['toltal'] = $obj['toltal'] . ';' . '';
						}

						if($data[20]==1){
							$obj['lens_type']= 'Đơn tròng';
						}else{
							$obj['lens_type']= '';
						}
						if($data[22]==1){
							$obj['lens_type'] = $obj['lens_type'] . ';Hai tròng';
						}else{
							$obj['lens_type'] = $obj['lens_type'] . ';';
						}
						if($data[23]==1){
							$obj['lens_type'] = $obj['lens_type'] . ';Đa tròng';
						}else{
							$obj['lens_type'] = $obj['lens_type'] . ';';
						}
						if($data[24]==1){
							$obj['lens_type'] = $obj['lens_type'] . ';Mắt đặt';
						}else{
							$obj['lens_type'] = $obj['lens_type'] . ';';
						}

						$obj['type'] =  0;
						$obj['duration'] = $data[32];
						$obj['employeer_id'] = 1;
						$obj['contact_lens_type'] = '';

						//get customer_id via account_number
						$customer = $this->Customer->get_info_by_account_number($data[4]);

						$invalidated = FALSE;
						if(!$customer)
						{
							$invalidated = TRUE;
						}else {

							$obj['customer_id'] = $customer->person_id;
							$obj['code'] = $data[1]; // just only create new
							$obj['test_time'] = strtotime($data[2]);
						}
					}
					else
					{
						$invalidated = TRUE;
					}

					//var_dump($obj);
					//Kiểm tra xem đã tồn tại đơn kính chưa?
					if($this->Testex->exists_by_code($data[1]))
					{
						$invalidated = TRUE; //do Nothing
					}

					if(!$invalidated && $this->Testex->save($obj))
					//if(!$invalidated)
					{

					}
					else //insert or update item failure
					{
						$failCodes[] = $i;
					}

					++$i;
				}

				if(count($failCodes) > 0)
				{
					$message = $this->lang->line('items_excel_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);

					echo json_encode(array('success' => FALSE, 'message' => $message));
				}
				else
				{
					echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_excel_import_success')));
				}
			}
			else
			{
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_nodata_wrongformat')));
			}
		}
	}

	public function  _do_excel_imp()
	{
		$reArray = array(); // right eye information
		$leArray = array(); // left eye information

		$leArray['ADD'] = $this->input->post('l_add') ? $this->input->post('l_add') : '';
		$leArray['AX'] = $this->input->post('l_ax') ? $this->input->post('l_ax') : '';
		$leArray['CYL'] = $this->input->post('l_cyl') ? $this->input->post('l_cyl') : '';
		$leArray['PD'] = $this->input->post('l_pd') ? $this->input->post('l_pd') : '';
		//$leArray['ADD'] = $this->input->post('l_add') ? $this->input->post('l_add');
		$leArray['SPH'] = $this->input->post('l_sph') ? $this->input->post('l_sph') : '';
		$leArray['VA'] = $this->input->post('l_va') ? $this->input->post('l_va') : '';

		$reArray['ADD'] = $this->input->post('r_add') ? $this->input->post('r_add') : '';
		$reArray['AX'] = $this->input->post('r_ax') ? $this->input->post('r_ax') : '';
		$reArray['CYL'] = $this->input->post('r_cyl') ? $this->input->post('r_cyl') : '';
		$reArray['PD'] = $this->input->post('r_pd') ? $this->input->post('r_pd') : '';
		//$reArray['ADD'] = $this->input->post('r_add');
		$reArray['SPH'] = $this->input->post('r_sph') ? $this->input->post('r_sph') : '';
		$reArray['VA'] = $this->input->post('r_va') ? $this->input->post('r_va') : '';


		$obj['note'] = $this->input->post('note') ? $this->input->post('note') : '';
		$obj['right_e'] = json_encode($reArray);
		$obj['left_e'] = json_encode($leArray);
		$obj['toltal'] = $this->input->post('distance') . ';' . $this->input->post('reading');
		$obj['lens_type'] = $this->input->post('single') . ';' .
			$this->input->post('bifocal') . ';' .
			$this->input->post('progressive') . ';' .
			$this->input->post('rx');
		$obj['type'] = $this->input->post('type') ? $this->input->post('type') : 0;
		$obj['duration'] = $this->input->post('duration') ? $this->input->post('duration') : 6;
		$obj['employeer_id'] = $employee_id;
		$obj['customer_id'] = $customer_id;
		$obj['contact_lens_type'] = '';

		$new = 0;
		if ($this->input->post('hidden_test_id') == 0) {
			$new = 1;
		}

		if ($new == 0) {
			$data['test_id_num'] = $this->Testex->update($test_id, $obj);
		} else {
			$obj['code'] = 'TD' . time(); // just only create new
			$data['test_id_num'] = $this->Testex->save($obj);
			$this->test_lib->set_cart($data['test_id_num']);
			$this->test_lib->set_test_id($data['test_id_num']);
		}
	}
	//Import sản phẩm từ hệ thống quản lý cũ
	public function do_excel_import_bk3()
	{
		if($_FILES['file_path']['error'] != UPLOAD_ERR_OK)
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_failed')));
		}
		else
		{
			if(($handle = fopen($_FILES['file_path']['tmp_name'], 'r')) !== FALSE)
			{
				// Skip the first row as it's the table description
				fgetcsv($handle);
				$i = 1;

				$failCodes = array();

				while(($data = fgetcsv($handle)) !== FALSE)
				{
					// XSS file data sanity check
					$data = $this->xss_clean($data);
					//$item_data = array();
					if(sizeof($data) >= 14)
					{
						$item_data = array(
							'name'					=> $data[2],
							'description'			=> '',
							'category'				=> 'Cũ',
							'cost_price'			=> $data[6],
							'unit_price'			=> $data[7],
							'reorder_level'			=> 0,
							'supplier_id'			=> 3,
							'allow_alt_description'	=> '0',
							'is_serialized'			=> '0',
							'custom1'				=> '',
							'custom2'				=> '',
							'custom3'				=> '',
							'custom4'				=> '',
							'custom5'				=> '',
							'custom6'				=> '',
							'custom7'				=> '',
							'custom8'				=> '',
							'custom9'				=> '',
							'custom10'				=> ''
						);
						$item_number = $data[3];
						$invalidated = FALSE;
						if($item_number != '')
						{
							$item_data['item_number'] = $item_number;
							$invalidated = $this->Item->item_number_exists($item_number);
						}
					}
					else
					{
						$invalidated = TRUE;
					}

					if(!$invalidated && $this->Item->save($item_data))
					{
						$items_taxes_data = NULL;
						//tax 1

						$items_taxes_data[] = array('name' => 'Tax', 'percent' => '10' );



						// save tax values
						if(count($items_taxes_data) > 0)
						{
							$this->Item_taxes->save($items_taxes_data, $item_data['item_id']);
						}

						// quantities & inventory Info
						$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
						$emp_info = $this->Employee->get_info($employee_id);
						$comment =$this->lang->line('items_qty_file_import');



						// array to store information if location got a quantity


								$item_quantity_data = array(
									'item_id' => $item_data['item_id'],
									'location_id' => 1,
									'quantity' => 0,
								);
								$this->Item_quantity->save($item_quantity_data, $item_data['item_id'], 1);

								$excel_data = array(
									'trans_items' => $item_data['item_id'],
									'trans_user' => $employee_id,
									'trans_comment' => $comment,
									'trans_location' => 1,
									'trans_inventory' => 0
								);

								$this->Inventory->insert($excel_data);

					}
					else //insert or update item failure
					{
						$failCodes[] = $i;
					}

					++$i;
				}

				if(count($failCodes) > 0)
				{
					$message = $this->lang->line('items_excel_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);

					echo json_encode(array('success' => FALSE, 'message' => $message));
				}
				else
				{
					echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_excel_import_success')));
				}
			}
			else
			{
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_nodata_wrongformat')));
			}
		}
	}

	// import sản phẩm file .txt
    public function do_excel_import_bk2()
    {
        if($_FILES['file_path']['error'] != UPLOAD_ERR_OK)
        {
            echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_failed')));
        }
        else
		{
            if(($handle = fopen($_FILES['file_path']['tmp_name'], 'r')) !== FALSE)
            {
                // Skip the first row as it's the table description
                //fgetcsv($handle);
                $i = 1;
				
				$failCodes = array();
		
                while(($data = fgetcsv($handle)) !== FALSE)
                {
					// XSS file data sanity check
					$data = $this->xss_clean($data);
					
					if(sizeof($data) >= 23)
					{
	                    $item_data = array(
	                        'name'					=> $data[0],
	                        'description'			=> $data[4],
	                        'category'				=> $data[1],
	                        'cost_price'			=> $data[5],
	                        'unit_price'			=> $data[6],
	                        'reorder_level'			=> $data[7],
	                        'supplier_id'			=> $this->Supplier->exists($data[2]) ? $data[2] : NULL,
	                        'allow_alt_description'	=> $data[11] != '' ? '1' : '0',
	                        'is_serialized'			=> $data[12] != '' ? '1' : '0',
	                        'custom1'				=> $data[14],
	                        'custom2'				=> $data[15],
	                        'custom3'				=> $data[16],
	                        'custom4'				=> $data[17],
	                        'custom5'				=> $data[18],
	                        'custom6'				=> $data[19],
	                        'custom7'				=> $data[20],
	                        'custom8'				=> $data[21],
	                        'custom9'				=> $data[22],
	                        'custom10'				=> $data[23]
	                    );
	                    $item_number = $data[3];
	                    $invalidated = FALSE;
	                    if($item_number != '')
	                    {
	                    	$item_data['item_number'] = $item_number;
		                    $invalidated = $this->Item->item_number_exists($item_number);
	                    }
					}
					else 
					{
						$invalidated = TRUE;
					}

                    if(!$invalidated && $this->Item->save($item_data)) 
                    {
                        $items_taxes_data = NULL;
                        //tax 1
                        if(is_numeric($data[25]) && $data[24] != '')
                        {
                            $items_taxes_data[] = array('name' => $data[24], 'percent' => $data[25] );
                        }


                        // save tax values
                        if(count($items_taxes_data) > 0)
                        {
                            $this->Item_taxes->save($items_taxes_data, $item_data['item_id']);
                        }

                        // quantities & inventory Info
                        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
                        $emp_info = $this->Employee->get_info($employee_id);
                        $comment =$this->lang->line('items_qty_file_import');

                        $cols = count($data);

                        // array to store information if location got a quantity
                        $allowed_locations = $this->Stock_location->get_allowed_locations();
                        for ($col = 26; $col < $cols; $col = $col + 2)
                        {
                            $location_id = $data[$col];
                            if(array_key_exists($location_id, $allowed_locations))
                            {
                                $item_quantity_data = array(
                                    'item_id' => $item_data['item_id'],
                                    'location_id' => $location_id,
                                    'quantity' => $data[$col + 1],
                                );
                                $this->Item_quantity->save($item_quantity_data, $item_data['item_id'], $location_id);

                                $excel_data = array(
                                    'trans_items' => $item_data['item_id'],
                                    'trans_user' => $employee_id,
                                    'trans_comment' => $comment,
                                    'trans_location' => $data[$col],
                                    'trans_inventory' => $data[$col + 1]
                                );
								
                                $this->Inventory->insert($excel_data);
                                unset($allowed_locations[$location_id]);
                            }
                        }

                        /*
                         * now iterate through the array and check for which location_id no entry into item_quantities was made yet
                         * those get an entry with quantity as 0.
                         * unfortunately a bit duplicate code from above...
                         */
                        foreach($allowed_locations as $location_id => $location_name)
                        {
                            $item_quantity_data = array(
                                'item_id' => $item_data['item_id'],
                                'location_id' => $location_id,
                                'quantity' => 0,
                            );
                            $this->Item_quantity->save($item_quantity_data, $item_data['item_id'], $data[$col]);

                            $excel_data = array(
								'trans_items' => $item_data['item_id'],
								'trans_user' => $employee_id,
								'trans_comment' => $comment,
								'trans_location' => $location_id,
								'trans_inventory' => 0
							);

                            $this->Inventory->insert($excel_data);
                        }
                    }
                    else //insert or update item failure
                    {
                        $failCodes[] = $i;
                    }

					++$i;
                }

				if(count($failCodes) > 0)
				{
					$message = $this->lang->line('items_excel_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);
					
					echo json_encode(array('success' => FALSE, 'message' => $message));
				}
				else
				{
					echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_excel_import_success')));
				}
			}
			else 
			{
                echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_nodata_wrongformat')));
			}
        }
	}
	// Import sản phẩm của hệ thống mới
	public function do_excel_import()
	{
		$this->load->helper('file');

        /* Allowed MIME(s) File */
        $file_mimes = array(
            'application/octet-stream', 
            'application/vnd.ms-excel', 
            'application/x-csv', 
            'text/x-csv', 
            'text/csv', 
            'application/csv', 
            'application/excel', 
            'application/vnd.msexcel', 
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
		if($_FILES['file_path']['error'] != UPLOAD_ERR_OK)
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_failed')));
		}
		else
		{
			$array_file = explode('.', $_FILES['file_path']['name']);
            $extension  = end($array_file);
            if('csv' == $extension) {
				if(($handle = fopen($_FILES['file_path']['tmp_name'], 'r')) !== FALSE)
				{
					// Skip the first row as it's the table description
					fgetcsv($handle);
					$i = 1;

					$failCodes = array();

					while(($data = fgetcsv($handle)) !== FALSE)
					{
						// XSS file data sanity check
						$data = $this->xss_clean($data);

						if(sizeof($data) >= 23)
						{
							$item_data = array(
								'name'					=> $data[1],
								'description'			=> $data[11],
								'category'				=> $data[2],
								'cost_price'			=> $data[4],
								'unit_price'			=> $data[5],
								'reorder_level'			=> $data[10],
								'supplier_id'			=> $this->Supplier->exists($data[3]) ? $data[3] : NULL,
								'allow_alt_description'	=> $data[12] != '' ? '1' : '0',
								'is_serialized'			=> $data[13] != '' ? '1' : '0',
								'custom1'				=> $data[14],
								'custom2'				=> $data[15],
								'custom3'				=> $data[16],
								'custom4'				=> $data[17],
								'custom5'				=> $data[18],
								'custom6'				=> $data[19],
								'custom7'				=> $data[20],
								'custom8'				=> $data[21],
								'custom9'				=> $data[22],
								'custom10'				=> $data[23]
							);
							$item_number = $data[0];
							$invalidated = FALSE;
							if($item_number != '')
							{
								$item_data['item_number'] = $item_number;
								$invalidated = $this->Item->item_number_exists($item_number);
							}
						}
						else
						{
							$invalidated = TRUE;
						}

						if(!$invalidated && $this->Item->save($item_data))
						{
							$items_taxes_data = NULL;
							//tax 1
							if(is_numeric($data[7]) && $data[6] != '')
							{
								$items_taxes_data[] = array('name' => $data[6], 'percent' => $data[7] );
							}

							//tax 2
							if(is_numeric($data[9]) && $data[8] != '')
							{
								$items_taxes_data[] = array('name' => $data[8], 'percent' => $data[9] );
							}

							// save tax values
							if(count($items_taxes_data) > 0)
							{
								$this->Item_taxes->save($items_taxes_data, $item_data['item_id']);
							}

							// quantities & inventory Info
							$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
							$emp_info = $this->Employee->get_info($employee_id);
							$comment =$this->lang->line('items_qty_file_import');

							$cols = count($data);

							// array to store information if location got a quantity
							$allowed_locations = $this->Stock_location->get_allowed_locations();
							for ($col = 24; $col < $cols; $col = $col + 2)
							{
								$location_id = $data[$col];
								if(array_key_exists($location_id, $allowed_locations))
								{
									$item_quantity_data = array(
										'item_id' => $item_data['item_id'],
										'location_id' => $location_id,
										'quantity' => $data[$col + 1],
									);
									$this->Item_quantity->save($item_quantity_data, $item_data['item_id'], $location_id);

									$excel_data = array(
										'trans_items' => $item_data['item_id'],
										'trans_user' => $employee_id,
										'trans_comment' => $comment,
										'trans_location' => $data[$col],
										'trans_inventory' => $data[$col + 1]
									);

									$this->Inventory->insert($excel_data);
									unset($allowed_locations[$location_id]);
								}
							}

							/*
							* now iterate through the array and check for which location_id no entry into item_quantities was made yet
							* those get an entry with quantity as 0.
							* unfortunately a bit duplicate code from above...
							*/
							foreach($allowed_locations as $location_id => $location_name)
							{
								$item_quantity_data = array(
									'item_id' => $item_data['item_id'],
									'location_id' => $location_id,
									'quantity' => 0,
								);
								$this->Item_quantity->save($item_quantity_data, $item_data['item_id'], $data[$col]);

								$excel_data = array(
									'trans_items' => $item_data['item_id'],
									'trans_user' => $employee_id,
									'trans_comment' => $comment,
									'trans_location' => $location_id,
									'trans_inventory' => 0
								);

								$this->Inventory->insert($excel_data);
							}
						}
						else //insert or update item failure
						{
							$failCodes[] = $i;
						}

						++$i;
					}

					if(count($failCodes) > 0)
					{
						$message = $this->lang->line('items_excel_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);

						echo json_encode(array('success' => FALSE, 'message' => $message));
					}
					else
					{
						echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_excel_import_success')));
					}
				}
				else
				{
					echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_nodata_wrongformat')));
				}
                //$reader = new Csv();
				exit();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

			$spreadsheet = $reader->load($_FILES['file_path']['tmp_name']);
            $sheet_data  = $spreadsheet->getActiveSheet(0)->toArray();
			$worksheet = $spreadsheet->getActiveSheet(0);
			//var_dump($worksheet);
            
			$highestColumn = 6;
			
			$_iMaxColumn = 0;

			foreach($sheet_data[0] as $item)
			{
				if($item != null)
				{
					$_iMaxColumn++;

				} else {
					break;
				}
			}
			$failCodes = [];
			// Bỏ qua dòng đầu tiên, start với i=1
			for($i = 1; $i < count($sheet_data); $i++) {
				//$rowData = $sheet->rangeToArray('A' . $i . ':' . $highestColumn . $i,NULL,TRUE,FALSE);
				
				if(isEmptyRow($sheet_data[$i],$highestColumn)) { continue; } // skip empty row
				$data = $sheet_data[$i];
				//var_dump($data);
				$item_data = array(
					'name'					=> $data[1] != null ? $data[1]:'',
					'description'			=> $data[11] != null ? $data[11]:0,
					'category'				=> $data[2] != null ? $data[2]:'',
					'cost_price'			=> is_numeric($data[4]) == true ? $data[4]:0,
					'unit_price'			=> is_numeric($data[5]) == true ? $data[5]:0,
					'reorder_level'			=> is_numeric($data[10]) == true ? $data[10]:0,
					'supplier_id'			=> $this->Supplier->exists($data[3]) ? $data[3] : NULL,
					'allow_alt_description'	=> $data[12] != null ? $data[12] : '',
					'is_serialized'			=> $data[13] != null ? '1' : '0',
					'custom1'				=> $data[14] != null ? $data[14]:'',
					'custom2'				=> $data[15] != null ? $data[15]:'',
					'custom3'				=> $data[16] != null ? $data[16]:'',
					'custom4'				=> $data[17] != null ? $data[17]:'',
					'custom5'				=> $data[18] != null ? $data[18]:'',
					'custom6'				=> $data[19] != null ? $data[19]:'',
					'custom7'				=> $data[20] != null ? $data[20]:'',
					'custom8'				=> $data[21] != null ? $data[21]:'',
					'custom9'				=> $data[22] != null ? $data[22]:'',
					'custom10'				=> $data[23] != null ? $data[23]:''
				);
				$item_number = $data[0] != null ? $data[0]:'';
				$invalidated = FALSE;
				if($item_number != '')
				{
					$item_data['item_number'] = $item_number;
					$invalidated = $this->Item->item_number_exists($item_number);
				}
				//var_dump($item_data);die();
				if(!$invalidated && $this->Item->save($item_data))
						{
							$items_taxes_data = NULL;
							//tax 1
							if(is_numeric($data[7]) && $data[6] != '')
							{
								$items_taxes_data[] = array('name' => $data[6], 'percent' => $data[7] );
							}

							//tax 2
							if(is_numeric($data[9]) && $data[8] != '')
							{
								$items_taxes_data[] = array('name' => $data[8], 'percent' => $data[9] );
							}

							// save tax values
							if(count($items_taxes_data) > 0)
							{
								$this->Item_taxes->save($items_taxes_data, $item_data['item_id']);
							}

							// quantities & inventory Info
							$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
							$emp_info = $this->Employee->get_info($employee_id);
							$comment =$this->lang->line('items_qty_file_import');

							$cols = count($data);

							// array to store information if location got a quantity
							$allowed_locations = $this->Stock_location->get_allowed_locations();
							for ($col = 24; $col < $cols; $col = $col + 2)
							{
								$location_id = $data[$col];
								if(array_key_exists($location_id, $allowed_locations))
								{
									$item_quantity_data = array(
										'item_id' => $item_data['item_id'],
										'location_id' => $location_id,
										'quantity' => $data[$col + 1],
									);
									$this->Item_quantity->save($item_quantity_data, $item_data['item_id'], $location_id);

									$excel_data = array(
										'trans_items' => $item_data['item_id'],
										'trans_user' => $employee_id,
										'trans_comment' => $comment,
										'trans_location' => $data[$col],
										'trans_inventory' => $data[$col + 1]
									);

									$this->Inventory->insert($excel_data);
									unset($allowed_locations[$location_id]);
								}
							}

							/*
							* now iterate through the array and check for which location_id no entry into item_quantities was made yet
							* those get an entry with quantity as 0.
							* unfortunately a bit duplicate code from above...
							*/
							foreach($allowed_locations as $location_id => $location_name)
							{
								$item_quantity_data = array(
									'item_id' => $item_data['item_id'],
									'location_id' => $location_id,
									'quantity' => 0,
								);
								$this->Item_quantity->save($item_quantity_data, $item_data['item_id'], $data[$col]);

								$excel_data = array(
									'trans_items' => $item_data['item_id'],
									'trans_user' => $employee_id,
									'trans_comment' => $comment,
									'trans_location' => $location_id,
									'trans_inventory' => 0
								);

								$this->Inventory->insert($excel_data);
							}
						}
						else //insert or update item failure
						{
							$failCodes[] = $i;
						}
				
				
			}
			if(count($failCodes) > 0)
			{
				$message = $this->lang->line('items_excel_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);

				echo json_encode(array('success' => FALSE, 'message' => $message));
			}
			else
			{
				echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_excel_import_success')));
			}
		}
	}

	// Added by ManhVT to support field permissions
	public function unitprice_hide()
	{
		exit();
	}

	public function run_synchro()
	{
		$time = time();
		$bCanUpdate = true;
        $lfile =  str_replace('/public/','/',FCPATH).'log-lens.txt';
        //echo $lfile;exit();
        $_flog=fopen($lfile, 'a');
        fwrite($_flog, 'Bat dau dong bo theo SP'.PHP_EOL);
        //echo 'Bat dau dong bo';
        $input = $this->Product->get_max_synched_time();
        echo "INPUT ".$input;
        //var_dump($input);die();
        //$id = 15294;
        $_aProducts = $this->get_last_products($input);
        //echo 'manhvt';
        //var_dump($_aProducts);
        $i = 0;
        foreach($_aProducts as $_oProduct)
        {   //echo '.';
            $i++;
            $item_number = $_oProduct->item_number;
            $invalidated = $this->Item->item_number_exists($item_number);
            if($invalidated == true) // update
            {
                    if($bCanUpdate) {
                        //echo 'update:' . $item_number . ' \n';
                        fwrite($_flog, 'SP.Update' . PHP_EOL);
                        $_oItem = array();
                        $_oItem['unit_price'] = $_oProduct->unit_price;
                        $_oItem['name'] = $_oProduct->name;
                        $_oItem['cost_price'] = $_oProduct->cost_price;
                    }
                    $_oItem['ref_item_id'] = $_oProduct->item_id;
					$_oItem['updated_time'] = $time;
                    $_oItem['synched_time'] = $time;
                    //var_dump($_oItem);
                    $this->Product->update_product($_oItem,$item_number);
                

            } else{ // create mới
                if($_oProduct->name != '')
                {
                    //echo 'Create: ' .$item_number.' \n';
                    $item_data = array(
                        'name'					=> $_oProduct->name,
                        'description'			=> $_oProduct->description,
                        'category'				=> $_oProduct->category,
                        'cost_price'			=> $_oProduct->cost_price,
                        'unit_price'			=> $_oProduct->unit_price,
                        'reorder_level'			=> $_oProduct->reorder_level,
                        'supplier_id'			=> $_oProduct->supplier_id,
                        'allow_alt_description'	=> $_oProduct->allow_alt_description,
                        'is_serialized'			=> $_oProduct->is_serialized,
                        'item_number'           => $_oProduct->item_number,
                        'ref_item_id'   =>$_oProduct->item_id,
                        'custom1'				=> '',
                        'custom2'				=> '',
                        'custom3'				=> '',
                        'custom4'				=> '',
                        'custom5'				=> '',
                        'custom6'				=> '',
                        'custom7'				=> '',
                        'custom8'				=> '',
                        'custom9'				=> '',
                        'custom10'				=> ''
                    );
					$_oItem['updated_time'] = $time;
                    $_oItem['created_time'] = $time;
                    $_oItem['synched_time'] = $time;
                    if( $this->Product->save_item($item_data))
                    {
                        fwrite($_flog, 'SP.Add Thanh cong'.PHP_EOL);
                    } 
                    else //insert or update item failure
                    {
                            $failCodes[$i] = $item_data['item_number'];
                            $message = "". $item_data['item_number'];
                            fwrite($_flog, $message.PHP_EOL);
                            //echo 	$message .PHP_EOL;
                    }
                }else {
                    $message = "Dữ liệu trống";
                    fwrite($_flog, $message.PHP_EOL);
                    //echo 	$message .PHP_EOL;
                }

            }
        }
        //echo 'Toal:'.$i;
		// Phản hồi cho Ajax
		echo json_encode(['success' => true]);
	}

	/**
	 * Lấy các sản phẩm chưa update từ thời điểm đồng bộ cuối cùng
	 */
	private function get_last_products($time)
    {
        //insert data
        $url = $this->url."/api/item/last_products/$time";
        
        //create a new cURL resource
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, 'admin:123456789');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $userData);
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);

        // Then, after your curl_exec call:
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        $result = json_decode($body);
        //var_dump($result);
        curl_close($ch);
        if(empty($result))
        {
            return array();
        }
        if($result->status == TRUE)
        {
            return $result->data;
        } else {
            return array();
        }
        
    }

}
?>
