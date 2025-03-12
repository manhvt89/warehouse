<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Recipes extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('recipes');
		$this->load->library('barcode_lib');
		$this->load->library('item_lib');

		$this->grant_id = 0;
		if($this->Employee->has_grant('recipes_is_action'))
		{
			$this->grant_id = 1;
		}
		if($this->Employee->has_grant('recipes_is_production_order'))
		{
			$this->grant_id = 2;
		}
		if($this->Employee->has_grant('recipes_is_editor'))
		{
			$this->grant_id = 3;
		}
		if($this->Employee->has_grant('recipes_is_approved'))
		{
			$this->grant_id = 5;
		}
	}
	
	public function index()
	{

		$data['table_headers'] = $this->xss_clean(get_recipe_manage_table_headers());

		//$data['table_headers'] = $this->xss_clean(get_items_manage_table_headers());
		
		$data['stock_location'] = $this->xss_clean($this->item_lib->get_item_location());
		$data['stock_locations'] = $this->xss_clean($this->Stock_location->get_allowed_locations());
		// filters that will be loaded in the multiselect dropdown
		$data['filters'] = array('empty_upc' => $this->lang->line('items_empty_upc_items'),
			'low_inventory' => $this->lang->line('items_low_inventory_items'),
			'is_deleted' => $this->lang->line('items_is_deleted'));
		$data['hide_unitprice'] = false;
		$data['grant_id'] = $this->grant_id; //Phân quyền module recipe
		$this->load->view('recipes/manage', $data);
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
		$this->item_lib->set_item_location($this->input->get('stock_location'));

		$filters = array('start_date' => $this->input->get('start_date'),
						'end_date' => $this->input->get('end_date'),
						'stock_location_id' => $this->item_lib->get_item_location(),
						'empty_upc' => FALSE,
						'low_inventory' => FALSE, 
						'is_serialized' => FALSE,
						'no_description' => FALSE,
						'search_custom' => FALSE,
						'is_deleted' => FALSE);
		
		// check if any filter is set in the multiselect dropdown
		$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		$filters = array_merge($filters, $filledup);

		$Recipes = $this->Recipe->search($search, $filters, $limit, $offset, $sort, $order);
		$total_rows = $this->Recipe->get_found_rows($search, $filters);

		$data_rows = array();
		$_index = 0;
		foreach($Recipes->result() as $item)
		{
			debug_log($item,'$item');
			$_index++;
			$data_rows[] = $this->xss_clean(get_recipe_data_row($item, $_index));
		}

		echo json_encode(array('total' => $total_rows, 'rows' => $data_rows));
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
		$person_id = $this->session->userdata('person_id');
		$data['is_approved'] = $this->Employee->has_grant('reipces_is_approved');
		$data['is_editor'] = $this->Employee->has_grant('reipces_is_editor');
		$data['is_action'] = $this->Employee->has_grant('reipces_is_action');
		$data['is_production_order'] = $this->Employee->has_grant('reipces_is_production_order');

		$data['item_tax_info'] = '';
		$data['default_tax_1_rate'] = '';
		$data['default_tax_2_rate'] = '';

		$item_info = $this->Recipe->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}

		$data['item_info'] = $item_info;

		$arrItem_as = $this->Recipe->get_items_by_recipe_id($item_info->recipe_id,'A')->result();
		$arrItem_bs = $this->Recipe->get_items_by_recipe_id($item_info->recipe_id,'B')->result();
		$data['arrItem_as'] = $arrItem_as;
		$data['arrItem_bs'] = $arrItem_bs;
		$data['isApproved'] = 1;
		$item_info->status == 5 ? $data['isApproved'] = 1: $data['isApproved']=0;
		//var_dump($data);
		$this->load->view('recipes/form', $data);
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
	
	
	private function _handle_file_upload()
	{
		$this->load->helper('directory');

		$map = directory_map('./uploads/item_files/', 1);

		// load upload library
		$config = array('upload_path' => './uploads/item_files/',
			'allowed_types' => 'gif|jpg|png|pdf',
			'max_size' => '100',
			'max_width' => '640',
			'max_height' => '480',
			'file_name' => sizeof($map) + 1
		);
		$this->load->library('upload', $config);
		$this->upload->do_upload('item_file');           
		
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
		$recipes_to_delete = $this->input->post('ids');

		if($this->Recipe->delete_list($recipes_to_delete))
		{
			$message = $this->lang->line('recipe_successful_deleted') . ' ' . count($recipes_to_delete) . ' ' . $this->lang->line('recipe_one_or_multiple');
			echo json_encode(array('success' => TRUE, 'message' => $message));
		}
		else
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('recipe_cannot_be_deleted')));
		}
	}

	/*
	Items import from excel spreadsheet
	*/
	public function excel()
	{
		$name = 'import_recipes.xlsx';
		$data = file_get_contents('../' . $name);
		force_download($name, $data);
	}
	
	public function excel_import()
	{
		$this->load->view('recipes/form_excel_import', NULL);
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
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheetapplication/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
		if($_FILES['file_path']['error'] != UPLOAD_ERR_OK)
		{
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_failed')));
		}
		else
		{

			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$file_type = finfo_file($finfo, $_FILES['file_path']['tmp_name']);
			finfo_close($finfo);
			$extension = pathinfo($_FILES['file_path']['name'], PATHINFO_EXTENSION);
		
			if (!in_array($file_type, $file_mimes) || !in_array($extension, ['csv', 'xlsx', 'xls'])) {
				echo json_encode(['success' => FALSE, 'message' => 'File không đúng định dạng']);
				exit();
			}

			
            if('csv' == $extension) {
		
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
			$reader->setReadDataOnly(true); // Xử lý tối ưu giảm bộ nhớ
			$spreadsheet = $reader->load($_FILES['file_path']['tmp_name']);
            //$sheet_data  = $spreadsheet->getActiveSheet(0)->toArray();
			$sheet_data  = $spreadsheet->getActiveSheet(0)->rangeToArray('A1:T100');
			//$worksheet = $spreadsheet->getActiveSheet(0);
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
			//var_dump($sheet_data);
			$i = 8;
			$data = $sheet_data[$i];
			//var_dump($data);
			debug_log(count($data),'data['.$i.']');
			$name = $data['5'];
			$master_batch = $data['5']; //F
			$grade_of_standard = $data['10']; //K
			$i = $i+2;
			$data = $sheet_data[$i];
			$_str_date_issued = trim($data['5']); 
			$certificate_no=$data['10'];
			$_arr_date_issued = explode(' ',$_str_date_issued);
			debug_log($_arr_date_issued,'_arr_date_issued');
			$_str_date = str_replace(" ","", $_arr_date_issued[0]);
			$_str_date = mb_substr($_str_date,0,10);
			$_str_date_issued = $_str_date;
			$_str_date = str_replace('/', '-', $_str_date);
			debug_log($_str_date,'_str_date');
			$_int_date_issued = strtotime($_str_date);
			debug_log($_int_date_issued,'_int_date_issued');
			if($_int_date_issued === false)
			{
				$_int_date_issued = strtotime('17-09-2022'); //dèault
			}

			$master_batch = str_replace('>','',$master_batch);
			$master_batch = str_replace('<','',$master_batch);
			$recipe_data = [
				'name' => $name,
				'master_batch'=>$master_batch,
				'str_date_issued' => $_str_date_issued,
				'grade_of_standard'=>$grade_of_standard,
				'date_issued'=>$_int_date_issued,
				'certificate_no'=>$certificate_no,
				'status'=>5

			];
			$item_as = [];
			$item_bs = [];
			$neader = '';
			//$max = count($sheet_data); //1000
			$max = 101;
			for($i = 14; $i < $max; $i++) {
				//$rowData = $sheet->rangeToArray('A' . $i . ':' . $highestColumn . $i,NULL,TRUE,FALSE);
				debug_log($i,'START FOR: ');
				//echo $i;
				if(!isset($sheet_data[$i]))
				{
					//echo $i;
					break;
				}
				if(isEmptyRow($sheet_data[$i],$highestColumn)) { continue; } // skip empty row
				$data = $sheet_data[$i];
				//var_dump($data);
				debug_log($sheet_data[$i],'$sheet_data['.$i.']');
				
				
				$neader_machine = $data[0] != null ? trim($data[0]):'';
				debug_log($neader_machine,'$neader_machine');
				if(strpos($neader_machine, "A/ Công đoạn máy nhào trộn") !== false)
				{
					
					$recipe_data['kneader_a'] = "A/ Công đoạn máy nhào trộn";
					$processing_time_a = explode(' ',$data[7]);
					$weight_a = explode(' ',$data[10]);
					$recipe_data['processing_time_a'] = $processing_time_a[0];
					$recipe_data['weight_a'] = $weight_a[0];
					$neader = 'A';
					$i = $i+2;
					continue; // Next row
				} 
				elseif(strpos($neader_machine, "B/ Công đoạn máy cán hai trục") !== false)
				{
					$neader = 'B';
					$recipe_data['kneader_b'] = "B/ Công đoạn máy cán hai trục";
					$processing_time_b = explode(' ',$data[7]);
					$weight_b = explode(' ',$data[10]);
					$recipe_data['processing_time_b'] = $processing_time_b[0];
					$recipe_data['weight_b'] = $weight_b[0];
					$i = $i+2;
					continue; // Next row
				}
				debug_log($neader,'$neader');
				$_strTolerace = $data[9];
				//var_dump($_strTolerace);die();
				//$_arrTolerace = explode($_strTolerace);
				//$_dTolerace = 0.000;
				
				if($neader == 'A')
				{ 
					$item_a = [
						'item_group'=>$data[0],
						'item_mix'=>$data[2],
						'uom_code'=>$data[6],
						'uom_name'=>$data[6],
						'weight'=>$data[7],
						'tolerace'=>$_strTolerace,
						'recipe_id'=>'',
						'item_id'=>''
					];
					$item_as[] = $item_a;
				} else {
					
					$item_b = [
						'item_group'=>$data[0],
						'item_mix'=>$data[2],
						'uom_code'=>$data[6],
						'uom_name'=>$data[6],
						'weight'=>$data[7],
						'tolerace'=>$_strTolerace,
						'recipe_id'=>'',
						'item_id'=>''
					];

					$item_bs[] = $item_b;
				}

				
			}

			array_pop($item_bs);
			array_pop($item_as);
			$recipe_data = trimA($recipe_data);
			$item_bs = trimA($item_bs);
			$item_as = trimA($item_as);
			debug_log($recipe_data,'$recipe_data');
			debug_log($item_bs,'$item_bs');
			debug_log($item_as,'$item_as');
			$save_rs = $this->Recipe->save($recipe_data,$item_as,$item_bs);
			

			if($save_rs)
			{
				
			}
			else //insert or update item failure
			{
				$failCodes[] = 1;
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

	public function is_approved()
	{
		/**
		 * Phân quyền cho người xét duyện đơn pha chế
		 * xem được đầy đủ, tên các chất
		 */
		return true;
	}

	public function is_editor()
	{
		/**
		 * Phân quyền cho người tạo đơn pha chế
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
		 * Phân quyền cho người lập kế hoạch
		 **/
		return true;
	}

	public function detail($item_id=-1)
	{
		$person_id = $this->session->userdata('person_id');
		$data['is_approved'] = $this->Employee->has_grant('reipces_is_approved');
		$data['is_editor'] = $this->Employee->has_grant('reipces_is_editor');
		$data['is_action'] = $this->Employee->has_grant('reipces_is_action');
		$data['is_production_order'] = $this->Employee->has_grant('reipces_is_production_order');

		$data['item_tax_info'] = '';
		$data['default_tax_1_rate'] = '';
		$data['default_tax_2_rate'] = '';

		$item_info = $this->Recipe->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			$item_info->$property = $this->xss_clean($value);
		}

		$data['item_info'] = $item_info;

		$arrItem_as = $this->Recipe->get_items_by_recipe_id($item_info->recipe_id,'A')->result();
		$arrItem_bs = $this->Recipe->get_items_by_recipe_id($item_info->recipe_id,'B')->result();
		$data['arrItem_as'] = $arrItem_as;
		$data['arrItem_bs'] = $arrItem_bs;
		//var_dump($data);
		$this->load->view('recipes/detail', $data);
	}

}
?>
