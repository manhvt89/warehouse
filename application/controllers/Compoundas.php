<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Compoundas extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('compoundas');
		$this->load->library('item_lib');
		$this->load->library('barcode_lib');
	}
	
	public function index()
	{

		$data['table_headers'] = $this->xss_clean(get_compoundas_manage_table_headers());

		//$data['table_headers'] = $this->xss_clean(get_items_manage_table_headers());

		
		$data['stock_location'] = $this->xss_clean($this->item_lib->get_item_location());
		$data['stock_locations'] = $this->xss_clean($this->Stock_location->get_allowed_locations());

		// filters that will be loaded in the multiselect dropdown
		$data['filters'] = array('empty_upc' => $this->lang->line('items_empty_upc_items'),
			'low_inventory' => $this->lang->line('items_low_inventory_items'),
			'is_deleted' => $this->lang->line('items_is_deleted'));

		$data['grant_id'] = $this->grant_id; //Phân quyền module 
		$this->load->view('compoundas/manage', $data);
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

        //$search = str_replace(' ','%',$search);
		//$this->item_lib->set_item_location($this->input->get('stock_location'));

		$filters = array('start_date' => $this->input->get('start_date'),
						'end_date' => $this->input->get('end_date'),
						//'stock_location_id' => $this->item_lib->get_item_location(),
						'empty_upc' => FALSE,
						'low_inventory' => FALSE, 
						'is_serialized' => FALSE,
						'no_description' => FALSE,
						'search_custom' => FALSE,
						'is_deleted' => FALSE);
		
		// check if any filter is set in the multiselect dropdown
		$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		$filters = array_merge($filters, $filledup);

		$items = $this->Compounda->search($search, $filters, $limit, $offset, $sort, $order);
		$total_rows = $this->Compounda->get_found_rows($search, $filters);

		$data_rows = array();
		$index = 1;
		foreach($items->result() as $item)
		{
			debug_log($item,'$item');
			$data_rows[] = $this->xss_clean(get_compounda_data_row($item, $index));
			$index++;
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
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
	public function suggest_search()
	{
		$suggestions = $this->xss_clean($this->Item->get_search_suggestions($this->input->post_get('term'),
			array('search_custom' => $this->input->post('search_custom'), 'is_deleted' => $this->input->post('is_deleted') != NULL), FALSE));

		echo json_encode($suggestions);
	}

	public function suggest()
	{
		$suggestions = $this->xss_clean($this->Item->get_search_suggestions($this->input->post_get('term'),
			array('search_custom' => FALSE, 'is_deleted' => FALSE), TRUE));

		echo json_encode($suggestions);
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	public function suggest_category()
	{
		$suggestions = $this->xss_clean($this->Item->get_category_suggestions($this->input->get('term')));

		echo json_encode($suggestions);
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

	public function view($item_id = -1)
	{
		//$person_id = $this->person_id;
		$data['is_approved'] = $this->Employee->has_grant($this->module_id.'_is_approved');
		$data['is_inventory'] = $this->Employee->has_grant($this->module_id.'_is_inventory');
		$data['is_editor'] = $this->Employee->has_grant($this->module_id.'_is_editor');
		$data['is_action'] = $this->Employee->has_grant($this->module_id.'_is_action');
		$data['is_production_order'] = $this->Employee->has_grant($this->module_id.'_is_production_order');

		$data['item_tax_info'] = '';
		$data['default_tax_1_rate'] = '';
		$data['default_tax_2_rate'] = '';

		$item_info = $this->Compounda->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			if(!is_object($value) && !is_array($value))
			{
				$item_info->$property = $this->xss_clean($value);
			}
		}

		$data['item_info'] = $item_info;

		//var_dump($data);
		$this->load->view('compoundas/form', $data);
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

	public function save($item_id = -1)
	{
		$upload_success = $this->_handle_image_upload();
		$upload_data = $this->upload->data();

		//Save item data
		$person_id = $this->session->userdata('person_id');
		$has_grant = $this->Employee->has_grant('items_accounting', $person_id);
		$item_data = array(
			'name' => $this->input->post('name'),
			'description' => $this->input->post('description'),
			'category' => $this->input->post('category'),
			'supplier_id' => $this->input->post('supplier_id') == '' ? NULL : $this->input->post('supplier_id'),
			'item_number' => $this->input->post('item_number') == '' ? NULL : $this->input->post('item_number'),
			'unit_price' => parse_decimals($this->input->post('unit_price')),
			'reorder_level' => parse_decimals($this->input->post('reorder_level')),
			'receiving_quantity' => parse_decimals($this->input->post('receiving_quantity')),
			'standard_amount' => parse_decimals($this->input->post('standard_amount')),
			'allow_alt_description' => $this->input->post('allow_alt_description') != NULL,
			'is_serialized' => $this->input->post('is_serialized') != NULL,
			'deleted' => $this->input->post('is_deleted') != NULL,
			'custom1' => $this->input->post('custom1') == NULL ? '' : $this->input->post('custom1'),
			'custom2' => $this->input->post('custom2') == NULL ? '' : $this->input->post('custom2'),
			'custom3' => $this->input->post('custom3') == NULL ? '' : $this->input->post('custom3'),
			'custom4' => $this->input->post('custom4') == NULL ? '' : $this->input->post('custom4'),
			'custom5' => $this->input->post('custom5') == NULL ? '' : $this->input->post('custom5'),
			'custom6' => $this->input->post('custom6') == NULL ? '' : $this->input->post('custom6'),
			'custom7' => $this->input->post('custom7') == NULL ? '' : $this->input->post('custom7'),
			'custom8' => $this->input->post('custom8') == NULL ? '' : $this->input->post('custom8'),
			'custom9' => $this->input->post('custom9') == NULL ? '' : $this->input->post('custom9'),
			'custom10' => $this->input->post('custom10') == NULL ? '' : $this->input->post('custom10')
		);
		if($has_grant) {
			$item_data['cost_price'] = parse_decimals($this->input->post('cost_price'));
		}
		
		if(!empty($upload_data['orig_name']))
		{
			// XSS file image sanity check
			if($this->xss_clean($upload_data['raw_name'], TRUE) === TRUE)
			{
				$item_data['pic_id'] = $upload_data['raw_name'];
			}
		}
		
		$employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
		//$cur_item_info = $this->Item->get_info($item_id);
		
		if($this->Item->save($item_data, $item_id))
		{
			$success = TRUE;
			$new_item = FALSE;
			//New item
			if($item_id == -1)
			{
				$item_id = $item_data['item_id'];
				$new_item = TRUE;
			}
			
			$items_taxes_data = array();
			$tax_names = $this->input->post('tax_names');
			$tax_percents = $this->input->post('tax_percents');
			$count = count($tax_percents);
			for ($k = 0; $k < $count; ++$k)
			{
				$tax_percentage = parse_decimals($tax_percents[$k]);
				if(is_numeric($tax_percentage))
				{
					$items_taxes_data[] = array('name' => $tax_names[$k], 'percent' => $tax_percentage);
				}
			}
			$success &= $this->Item_taxes->save($items_taxes_data, $item_id);
            
            //Save item quantity
            $stock_locations = $this->Stock_location->get_undeleted_all()->result_array();
            foreach($stock_locations as $location)
            {
                $updated_quantity = parse_decimals($this->input->post('quantity_' . $location['location_id']));
                $location_detail = array('item_id' => $item_id,
                                        'location_id' => $location['location_id'],
                                        'quantity' => $updated_quantity);  
                $item_quantity = $this->Item_quantity->get_item_quantity($item_id, $location['location_id']);
                if($item_quantity->quantity != $updated_quantity || $new_item) 
                {              
	                $success &= $this->Item_quantity->save($location_detail, $item_id, $location['location_id']);
	                
	                $inv_data = array(
	                    'trans_date' => date('Y-m-d H:i:s'),
	                    'trans_items' => $item_id,
	                    'trans_user' => $employee_id,
	                    'trans_location' => $location['location_id'],
	                    'trans_comment' => $this->lang->line('items_manually_editing_of_quantity'),
	                    'trans_inventory' => $updated_quantity - $item_quantity->quantity
	                );

	                $success &= $this->Inventory->insert($inv_data);       
                }                                            
            }

			if($success && $upload_success)
            {
            	$message = $this->xss_clean($this->lang->line('items_successful_' . ($new_item ? 'adding' : 'updating')) . ' ' . $item_data['name']);

            	echo json_encode(array('success' => TRUE, 'message' => $message, 'id' => $item_id));
            }
            else
            {
            	$message = $this->xss_clean($upload_success ? $this->lang->line('items_error_adding_updating') . ' ' . $item_data['name'] : strip_tags($this->upload->display_errors())); 

            	echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => $item_id));
            }
		}
		else//failure
		{
			$message = $this->xss_clean($this->lang->line('items_error_adding_updating') . ' ' . $item_data['name']);

			echo json_encode(array('success' => FALSE, 'message' => $message, 'id' => -1));
		}
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
		$name = 'import_compounda.xlsx';
		$data = file_get_contents('../' . $name);
		force_download($name, $data);
	}
	
	public function excel_import()
	{
		$this->load->view('compoundas/form_excel_import', NULL);
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
            //$extension  = end($array_file);
           
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            
			try {
				$spreadsheet = $reader->load($_FILES['file_path']['tmp_name']);
			} catch(Exception $e) {
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_nodata_wrongformat')));
                //$reader = new Csv();
				exit();
			}
            $sheet_data  = $spreadsheet->getActiveSheet(0)->toArray();
			$worksheet = $spreadsheet->getActiveSheet(0);
			//var_dump($worksheet);
            
			$highestColumn = 5;
			
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
			debug_log(count($sheet_data),'count($sheet_data)');
			$i = 7;
			$data = $sheet_data[$i];
			//var_dump($data);

			debug_log(count($data),'data['.$i.']');
			$_str_order_date = trim($data['2']); //C
			$_str_order_date = str_replace('/', '-', $_str_order_date);
			$order_date = strtotime($_str_order_date);

			$compounda_order_no = trim($data['9']); //J

			$i = $i+1;
			$data = $sheet_data[$i];
			$_str_use_date = trim($data['2']); //C
			$_str_use_date = str_replace('/', '-', $_str_use_date);
			$use_date = strtotime($_str_use_date);
			$area_make_order=$data['9']; //J

			$i = $i+1;
			$data = $sheet_data[$i];
			$creator_account = trim($data['2']); //C
			$suppervisor_account=trim($data['9']); //J

			//Get creator by account
			$_oCreator = $this->Employee->get_info_by_account($creator_account);
			$creator_id = 0; //C
			$creator_name = ''; //C
			if(empty($_oCreator))
			{
				$failCodes[] = 'TK người lập chưa tồn tại';
			} else {
				$creator_id = $_oCreator->person_id; //C
				$creator_name = $_oCreator->last_name . ' '. $_oCreator->first_name; //C
			} 




			//Get Suppervisor by account
			$_oSuppervisor = $this->Employee->get_info_by_account($suppervisor_account);
			$suppervisor_id = 0; //C
			$suppervisor_name = ''; //C
			if(empty($_oSuppervisor))
			{
				$failCodes[] = 'TK người phụ trách chưa tồn tại';
			} else {
				$suppervisor_id = $_oSuppervisor->person_id;//C
				$suppervisor_name = $_oSuppervisor->last_name . ' '. $_oSuppervisor->first_name; //C; //C
			}

			if($use_date === false)
			{
				$use_date = strtotime('17-09-2022'); //dèault
			}

			if($order_date === false)
			{
				$order_date = strtotime('17-09-2022'); //dèault
			}


			$compounda_data = [
				'order_date' => $order_date,
				'compounda_order_no'=>$compounda_order_no,
				'use_date' => $use_date,
				'area_make_order'=>$area_make_order,
				'creator_account'=>$creator_account,
				'creator_id'=>$creator_id,
				'creator_name'=>$creator_name,
				'suppervisor_account'=>$suppervisor_account,
				'suppervisor_id'=>$suppervisor_id,
				'suppervisor_name'=>$suppervisor_name,
				'status'=>4 //Đã chập nhận
			];

			debug_log($compounda_data,'$compounda_data');
			//var_dump($compounda_data);

			$j = 13; // Bắt đầu đọc từ dòng thứ 14,
			$item_orders = [];
			for($i = $j; $i < count($sheet_data); $i++) {
				//$rowData = $sheet->rangeToArray('A' . $i . ':' . $highestColumn . $i,NULL,TRUE,FALSE);
				//debug_log($sheet_data[$i],'$sheet_data[$i]');
				if(isEmptyRow($sheet_data[$i],$highestColumn)) { continue; } // skip empty row
				
				$data = $sheet_data[$i];
				//var_dump($data);
				if(trim($data[0]) == "Tổng cộng:")
				{
					break;
				}
				debug_log($sheet_data[$i],'$sheet_data[$i]');
				$ms = trim($data[12]) != null ? trim($data[12]):'';
				$item_data = [
					'ms'					=> trim($data[12]) != null ? trim($data[12]):'',
					'quantity_batch'			=> is_numeric(str_replace(',','',$data[3])) == true ? (float) str_replace(',','',$data[3]):0,
					'quantity_use'			=> is_numeric(str_replace(',','',trim($data[6]))) == true ? (float) str_replace(',','',trim($data[6])):0,
					'note'=>trim($data[10])
				];
				
				$_oItem = $this->Item->get_info_by_ms($ms);

				

				if($_oItem->item_id == 0) // Nếu không tìm thấy item với mác nguyên liệu
				{
					$failCodes[] = $i;
					continue;
				} else {
					$item_data['item_id'] = $_oItem->item_id;
					$item_data['item_name'] = $_oItem->name;
					$item_data['uom_code'] = $_oItem->inventory_uom_name;
					$item_data['uom_name'] = $_oItem->inventory_uom_name;
					$item_data['quantity_schedule'] = 75 * $item_data['quantity_batch'];
					$item_data['created_at'] = time();

					$item_orders[] = $item_data;
				}
			}

			if(!empty($failCodes)){ // Nếu xuất hiện lỗi, không làm gì cả, hiển thị thông báo lỗi tại dòng nào;
				$message = $this->lang->line('items_excel_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);
				echo json_encode(array('success' => FALSE, 'message' => $message));

			} else {
	
				$save_rs = $this->Compounda->save($compounda_data,$item_orders);

				if($save_rs)
				{
					echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_excel_import_success')));
				} else {
					echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_partially_failed')));
				}
			}
		}
	}

	// Added by ManhVT to support field permissions
	public function is_view()
	{
		/**
		 * Phân quyền cho người xét duyện lệnh sx
		 * xem được đầy đủ, tên các chất
		 */
		return true;
	}
	public function is_approved()
	{
		/**
		 * Phân quyền cho người xét duyện lệnh sx
		 * xem được đầy đủ, tên các chất
		 */
		return true;
	}

	public function is_editor()
	{
		/**
		 * Phân quyền cho người tạo lệnh sản xuất
		 * Xem được đầy đủ tên các chất
		 */
		return true;
	}

	public function is_action()
	{
		/**
		 * Xem được đã được mã hóa
		 * Phân quyền dành cho cán bộ công nhân thực hiện
		 **/
		return true;
	}

	public function is_production_order()
	{
		/**
		 * Xem được đã được mã hóa
		 * Phân quyền cho ...
		 **/
		return true;
	}

	public function is_inventory()
	{
		/**
		 * Xem được đã được mã hóa
		 * Phân quyền cho thủ kho; với vai trò thủ kho; scan barcode  lệnh sx sẽ view chi tiết lệnh sx, để xuất kho theo từng mục; (mác nguyên liệu --> ra nguyên liệu)
		 **/
		return true;
	}
	

}
?>
