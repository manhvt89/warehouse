<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Qccans extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('qccans');
		$this->load->library('item_lib');
		$this->load->library('barcode_lib');
		$this->load->model('Batch');
		$this->load->helper('batch');
	}
	
	public function index()
	{
		//$person_id = $this->person_id;
		
		$data['is_qc'] = $this->Employee->has_grant("{$this->module_id}_is_qc");
	
		//var_dump($item_info->ms);die();
		$_aStatus = [
			3, // Cân xong
			4, // Bắt đầu QC
			5, // QC Ko đạt
			6, // QC đạt
			7, // bắt đầu cán
			8 // hoàn thành
		];

		$statusClass = [
			1 => "choLam",
			2 => "dangLam",
            3 => "choQC",
            4 => "dangQC",
            5 => "qcNotOK",
            6 => "daQCOK",
            7 => "batDauCan",
            8 => "daLam"
        ];
		
		$statusText = [
			1 => "Chờ cân",
			2 => "Đang cân",
            3 => "Chờ Q",
            4 => "Đang QC",
            5 => "QC chưa đạt",
            6 => "QC đạt",
            7 => "Bắt đầu cán",
            8 => "Hoàn thành cán luyện"
        ];

		$data['statusText'] = $statusText;
		$data['statusClass'] = $statusClass;
		// Lấy các batch đã hoàn thành cân
		$_aoListBatchs = $this->Compounda->get_list_tasks_by_status($_aStatus);
		
		$data['aoListBatchs'] = transform_data($_aoListBatchs);
		
		$data['isApproved'] = 1;
		
		//$item_info->status == 5 ? $data['isApproved'] = 1: $data['isApproved']=0;

		//var_dump($recipe_ItemA);die();
		$this->load->view('qccans/listme', $data);
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

		$filters = [
			'start_date' => $this->input->get('start_date'),
			'end_date' => $this->input->get('end_date'),
			//'stock_location_id' => $this->item_lib->get_item_location(),
			'empty_upc' => FALSE,
			'low_inventory' => FALSE,
			'is_serialized' => FALSE,
			'no_description' => FALSE,
			'search_custom' => FALSE,
			'is_deleted' => FALSE
		];
		
		// check if any filter is set in the multiselect dropdown
		$filledup = array_fill_keys($this->input->get('filters'), TRUE);
		$filters = array_merge($filters, $filledup);

		$items = $this->Compounda->search($search, $filters, $limit, $offset, $sort, $order);
		$total_rows = $this->Compounda->get_found_rows($search, $filters);

		$data_rows = [];
		$index = 1;
		foreach($items->result() as $item)
		{
			debug_log($item,'$item');
			$data_rows[] = $this->xss_clean(get_compounda_data_row($item, $index));
			$index++;
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
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_nodata_wrongformat')));
				exit();
			}
			//$array_file = explode('.', $_FILES['file_path']['name']);
            //$extension  = end($array_file);
           
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            
			try {
				$reader->setReadDataOnly(true); // Xử lý tối ưu giảm bộ nhớ
				$spreadsheet = $reader->load($_FILES['file_path']['tmp_name']);
			} catch(Exception $e) { // File upload không đúng định dạng
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_nodata_wrongformat')));
                //$reader = new Csv();
				exit();
			}
            $sheet_data  = $spreadsheet->getActiveSheet(0)->toArray(); // Lây sheet đầu tiên và chuyển thành mảng; rangeToArray('A1:T100');
			//$worksheet = $spreadsheet->getActiveSheet(0); // Lấy sheet đầu tiên
			//var_dump($sheet_data);
            
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
			$i = 4;
			$data = $sheet_data[$i];
			$creator_account = trim($data['10']); //
			$executor_account=trim($data['14']); //
			$approver_account=trim($data['12']); //

			
			$thangNam = date('mY'); // Lấy tháng và năm hiện tại
			$maDinhDanh = "KHCL {$thangNam}";
			$compounda_order_no = $maDinhDanh;
			$code = "KHCL{$maDinhDanh}";
			
			//$area_make_order=$data['9']; //J
			$area_make_order = 'KV CÁN LUYỆN';
			

			// Begin Thông tin người lập kế hoạch
			//Get creator by account// sử dụng account upload file excel;
			// Sau này cần thay thế bằng tài khoản đăng nhập;
			$_oCreator = $this->Employee->get_info_by_account($creator_account);
			$creator_id = 0; 
			$creator_name = '';
			if(empty($_oCreator->person_id))
			{
				$failCodes[] = 'TK người lập chưa tồn tại';
			} else {
				$creator_id = $_oCreator->person_id; //C
				$creator_name = "{$_oCreator->last_name} {$_oCreator->first_name}"; //C
			} 

			// End thông tin người lập kế hoạch


			//Begin Thông tin người giám sát 
			// Mặc định khi được phân quyền giám sát (kiểm tra) is_check (có quyền kiểm tra)
			//Get Suppervisor by account
			$_oExecutor = $this->Employee->get_info_by_account($executor_account);
			$executor_id = 0; //C
			$executor_name = ''; //C
			if(empty($_oExecutor->person_id))
			{
				$failCodes[] = 'TK người phụ trách chưa tồn tại';
			} else {
				$executor_id = $_oExecutor->person_id;//C
				$executor_name = "{$_oExecutor->last_name} {$_oExecutor->first_name}"; //C;
			}

			$_oApprover = $this->Employee->get_info_by_account($approver_account);
			$approver_id = 0; //C
			$approver_name = ''; //C
			if(empty($_oApprover->person_id))
			{
				$failCodes[] = 'TK người phụ phê duyệt không tồn tại';
			} else {
				$approver_id = $_oApprover->person_id;//C
				$approver_name = $_oApprover->last_name . ' '. $_oApprover->first_name; //C;
			}
			// Thông tin người giám sát sẽ được cập nhật vào khi click "Đạt", chuyển sang trạng thái "Đã xem xét"
			// End thông tin người giám sát;

			// Thông tin về kế hoạch cán luyện

			$compounda_data = [
				'compounda_order_no'=>$compounda_order_no,
				'creator_account'=>$creator_account,
				'created_at'=>time(),
				'updated_date' => time(),
				'creator_id'=>$creator_id,
				'creator_name'=>$creator_name,
				'code'=>$code,
				'executor_id'=>$executor_id,
				'executor_name'=>$executor_name,
				'executor_account'=>$executor_account,
				'approver_id'=>$approver_id,
				'approver_name'=>$approver_name,
				'approver_account'=>$approver_account,
				'area_make_order'=>$area_make_order,
				'status'=>4 //Đã chập nhận
			];
			//var_dump($compounda_data); die();
			debug_log($compounda_data,'$compounda_data');
			//var_dump($compounda_data);

			$_istart_index = 10; // Bắt đầu đọc từ dòng thứ 14, // Lấy chi tiết về kế hoạch
			$item_orders = [];
			for($i = $_istart_index; $i < count($sheet_data); $i++) {
				//echo $i;
				//$rowData = $sheet->rangeToArray('A' . $i . ':' . $highestColumn . $i,NULL,TRUE,FALSE);
				//debug_log($sheet_data[$i],'$sheet_data[$i]');
				if(isEmptyRow($sheet_data[$i],$highestColumn)) { continue; } // skip empty row
				
				$data = $sheet_data[$i];
				//var_dump($data); die();
				$_sTmp = explode(' ',$data[0] ?? '');
				if(trim($_sTmp[0]) == "KHCL")
				{
					//echo $i;
					break; // đến dòng này thì dừng
				}
				
				debug_log($sheet_data[$i],'$sheet_data[$i]');
				$ms =  trim($data[3] ?? '');
				$item_code = trim($data[1] ?? '');
				$order_number = trim($data[0] ?? '');
				$quantity = is_numeric(str_replace(',','',$data[2])) == true ? (float) str_replace(',','',$data[2]):0;
				$kl_phoi = is_numeric(str_replace(',','',$data[4])) == true ? (float) str_replace(',','',$data[4]):0;
				$kl_su_dung = ($quantity*$kl_phoi)/1000; //Kg
				
				$kl_batch = is_numeric(str_replace(',','',$data[6])) == true ? (int) str_replace(',','',$data[6]):1;
				$so_luong_batch = ceil($kl_su_dung/$kl_batch);
				$quantity_schedule = $so_luong_batch * $kl_batch;

				$phan_cong = trim($data[12] ?? '');
				$phan_cong ??= trim($data[13] ?? '');

				$kl_cuoi_ky = $quantity_schedule - $kl_su_dung;
				$created_at = time();
				$start_at = 0;
				$end_at = 0;
				$note = trim($data[14] ?? '');
				$uom_code = '';
				$uom_name = '';

				
				$item_code = preg_replace('/[<>]/', '', $item_code); // remove <>
				$_oProduct = $this->Item->get_info_by_code($item_code);
				$item_id = $_oProduct->item_id;
				$item_name = $_oProduct->name;

				if($_oProduct->item_id == 0) // lỗi
				{
					$failCodes[] = "Không tìm thấy Mã SP tại dòng $i";
					continue;
				} 

				//$_item_orders = [];
				$item_data = [
					'ms' => $ms,
					'status'=>4, //chú ý
					'item_code' => $item_code,
					'order_number' =>$order_number,
					'quantity' => $quantity,
					'kl_phoi' => $kl_phoi,
					'kl_su_dung'=>$kl_su_dung,
					'kl_batch' => $kl_batch,
					'so_luong_batch' =>$so_luong_batch,
					'quantity_schedule' =>$quantity_schedule,
					'phan_cong' => $phan_cong,
					'kl_cuoi_ky' =>$kl_cuoi_ky,
					'created_at' =>$created_at,
					'start_at' => $start_at,
					'end_at'=>$end_at,
					'note' => $note,
					'uom_code' => $uom_code,
					'uom_name' =>$uom_name,
					'item_id' => $item_id,
					'item_name'=>$item_name,
					'code'=>"CLA{$created_at}"
				];
				
				$_oItem = $this->Item->get_info_by_code($ms,'CA');

				$_aItemAs = $this->Recipe->get_item_by_ms($ms,'A')->result_array(); // Nguyên liệu và Vật tư để cán luyện ra compound A này;
				$_aItemBs = $this->Recipe->get_item_by_ms($ms,'B')->result_array(); // Nguyên liệu và Vật tư để cán luyện ra compound A này;
				$_oRecipes = $this->Recipe->get_info_by_ms($ms); // Nguyên liệu và Vật tư để cán luyện ra compound A này;


				//var_dump($_aItemAs);
				//var_dump($_oItem);
				
				if(empty($_aItemAs))
				{
					$failCodes[] = "{$i} Chưa tồn tại công thức với MÁC nguyên liệu này: {$ms}";
					continue;
				}
				if(empty($_aItemBs))
				{
					$failCodes[] = "{$i} Chưa tồn tại công thức với MÁC nguyên liệu này: {$ms}";
					continue;
				}
				if($_oItem->item_id == 0) // Nếu không tìm thấy item với mác nguyên liệu // V
				{
					$failCodes[] = "{$i} Chưa tồn tại Nguyên Liệu (Compound A) với mác nguyên liệu này: {$ms}";
					continue;
				} 
				if($_oRecipes->recipe_id == 0) // Nếu không tìm thấy item với mác nguyên liệu // 
				{
					$failCodes[] = "{$i} Chưa tồn tại Nguyên Liệu (Compound A) với mác nguyên liệu này: {$ms}";
					continue;
				}

				$results = json_encode([
					"A"=>$_aItemAs,
					"B" => $_aItemBs,
					"R" => (array) $_oRecipes
				]);

				$_detail_batch = [];
				// Chi tiết mẻ, có bao nhiêu mẻ nhập từng đó bản ghi và bản QC
				for($j = 1; $j <= $item_data['so_luong_batch']; $j++)
				{
					$time = time();
					$_detail = [
					'compounda_order_item_id' => 0,
					'created_at' => $time,
					'ms' => $ms,
					'code'=>"BAT{$time}",
					'item_name' => '',
					'uom_code' => '',
					'uom_name' => '',
					'creator_name' => 'System',
					'creator_id' => '1',
					'updated_at' => 0,
					'status' => 1,
					'completed_at' => 0,
					'started_at' => 0
					];
					$_detail_batch[] = $_detail;
				}
				//var_dump($_aNvlItems);
				$_qc_data = [];


				
				
				$item_orders[$i]['detail_batch'] = $_detail_batch;
				$item_orders[$i]['result_qc'] = $results;
				$item_orders[$i]['item_data'] = $item_data;
				$i++;
				
			}
			
			//var_dump($item_orders); die();
			if(!empty($failCodes)){ // Nếu xuất hiện lỗi, không làm gì cả, hiển thị thông báo lỗi tại dòng nào;
				$message = $this->lang->line('items_excel_import_partially_failed') . ' (' . count($failCodes) . '): ' . implode(', ', $failCodes);
				echo json_encode(['success' => FALSE, 'message' => $message]);

			} else {
				//var_dump(json_decode($item_orders[10]['result_qc'])); die();
				$save_rs = $this->Compounda->save($compounda_data,$item_orders);

				if($save_rs)
				{
					echo json_encode(array('success' => TRUE, 'message' => $this->lang->line('items_excel_import_success')));
				} else {
					echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('khcl_excel_import_partially_failed')));
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
		 * Phân quyền cho người xét duyệt lệnh sx
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

	public function is_worker()
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

	public function is_qc()
	{
		/**
		 * Xem được đã được mã hóa
		 * Thực hiện cân
		 * Phân quyền cho thủ kho; với vai trò thủ kho; scan barcode  lệnh sx sẽ view chi tiết lệnh sx, để xuất kho theo từng mục; (mác nguyên liệu --> ra nguyên liệu)
		 **/
		return true;
	}

	public function is_checker()
	{
		/**
		 * Xem được đã được mã hóa
		 * Phân quyền cho thủ kho; với vai trò thủ kho; scan barcode  lệnh sx sẽ view chi tiết lệnh sx, để xuất kho theo từng mục; (mác nguyên liệu --> ra nguyên liệu)
		 **/
		return true;
	}
	public function is_monitor()
	{
		/**
		 * Xem được đã được mã hóa
		 * Phân quyền cho thủ kho; với vai trò thủ kho; scan barcode  lệnh sx sẽ view chi tiết lệnh sx, để xuất kho theo từng mục; (mác nguyên liệu --> ra nguyên liệu)
		 **/
		return true;
	}

	public function detail($item_id=-1)
	{
		//$person_id = $this->person_id;
		
		$data['is_qc'] = $this->Employee->has_grant($this->module_id.'_is_qc');
		

		$item_info = $this->Batch->get_info($item_id);
		$this->Batch->make_doing_qc($item_info); // update thời gian bắt đầu QC và stauts đang làm
		//var_dump($item_info->qc_cpa_document);die();
		$item_info = $this->Batch->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			if(!is_object($value) && !is_array($value))
			{
				$item_info->$property = $this->xss_clean($value);
			}
		}

		$data['item_info'] = transform_batch_info($item_info);
		//$_aRecipeItemA = $data['item_info']->qc_cpa_document->tieu_chi['A'];
		//$_aRecipeItemB = $data['item_info']->qc_cpa_document->tieu_chi['B'];

		$started_date = $data['item_info']->qc_cpa_document->started_at;
		$ended_date = $data['item_info']->qc_cpa_document->completed_at;

		if($ended_date == '' || $ended_date == 0)
		{
			$ended_date = 'Đang QC ...';
		} else {
			$ended_date = date('d/m/Y H:i',$ended_date);
		}

		if($started_date == '')
		{
			$started_date = 'Chưa bắt đầu ...';
		} else {
			$started_date = date('d/m/Y H:i',$started_date);
		}

		$data['started_date'] = $started_date;
		$data['completed_date'] = $ended_date;
		$data['form_qc_cpa'] = form_qc_cpa($data['item_info']->qc_cpa_document->tieu_chi);
		//var_dump($data['item_info']->qc_cpa_document->tieu_chi['A']);die();
		$this->load->view('qccans/detail', $data);
		//$this->load->view('recipes/detail', $data);
	}

	public function ajax_export_document()
	{
		$uuid = $this->input->post('uuid');
		
		
		$_oDocument = $this->Compounda->get_info_export_document($uuid);
		if($_oDocument != null)
		{
			$_aDocument = (array) $_oDocument;

			$rs = $this->Compounda->do_export_document($_aDocument);
			if($rs)
			{
				echo json_encode(array('success' => TRUE,'status'=>$this->lang->line('export_document_waiting_confirm_status') ,'message' => $this->lang->line('items_excel_import_success')));
			} else {
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_partially_failed')));
			}
		} else {
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_partially_failed')));
		}
	}

	public function ajax_confirm_document()
	{
		$uuid = $this->input->post('uuid');
		
		
		$_oDocument = $this->Compounda->get_info_export_document($uuid);
		if($_oDocument != null)
		{
			$_aDocument = (array) $_oDocument;

			$rs = $this->Compounda->do_confirm_document($_aDocument);
			if($rs)
			{
				echo json_encode(
						array('success' => TRUE,
								'status'=>$this->lang->line('export_document_do_confirmed_status'),
								'text'=>$this->lang->line('export_document_ready_to_do_btn'),
								'message' => $this->lang->line('items_excel_import_success')));
			} else {
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_partially_failed')));
			}
		} else {
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_partially_failed')));
		}
	}

	public function ajax_ready_document()
	{
		$uuid = $this->input->post('uuid');
		
		
		$_oDocument = $this->Compounda->get_info_export_document($uuid);
		if($_oDocument != null)
		{
			$_aDocument = (array) $_oDocument;

			$rs = $this->Compounda->do_start_document($_aDocument);
			if($rs)
			{
				echo json_encode(
						array('success' => TRUE,
								'status'=>$this->lang->line('export_document_doing_status'),
								'text'=>$this->lang->line('export_document_completed_btn'),
								'message' => $this->lang->line('items_excel_import_success')));
			} else {
				echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_partially_failed')));
			}
		} else {
			echo json_encode(array('success' => FALSE, 'message' => $this->lang->line('items_excel_import_partially_failed')));
		}
	}


	public function detail_rs($item_id = -1)
	{
		//$person_id = $this->person_id;
		$item_info = $this->Batch->get_info($item_id);
		foreach(get_object_vars($item_info) as $property => $value)
		{
			if(!is_object($value) && !is_array($value))
			{
				$item_info->$property = $this->xss_clean($value);
			}
		}

		$data['item_info'] = transform_batch_info($item_info); 
		//$_aRecipeItemA = $data['item_info']->qc_cpa_document->tieu_chi['A'];
		//$_aRecipeItemB = $data['item_info']->qc_cpa_document->tieu_chi['B'];
		$data['form_qc_cpa'] = form_qc_cpa_rs($data['item_info']);

		$started_date = $data['item_info']->qc_cpa_document->started_at;
		$ended_date = $data['item_info']->qc_cpa_document->completed_at;

		if($ended_date == '' || $ended_date == 0)
		{
			$ended_date = 'Đang QC ...';
		} else {
			$ended_date = date('d/m/Y H:i',$ended_date);
		}

		if($started_date == '')
		{
			$started_date = 'Chưa bắt đầu ...';
		} else {
			$started_date = date('d/m/Y H:i',$started_date);
		}
		$data['started_date'] = $started_date;
		$data['completed_date'] = $ended_date;
		//var_dump($data);
		$this->load->view('qccans/detail_rs', $data);
	}

	public function printBarcode($lenh_uuid)
	{
		//$person_id = $this->person_id;
		$data['is_approved'] = $this->Employee->has_grant($this->module_id.'_is_approved');
		$data['is_inventory'] = $this->Employee->has_grant($this->module_id.'_is_inventory');
		$data['is_editor'] = $this->Employee->has_grant($this->module_id.'_is_editor');
		$data['is_action'] = $this->Employee->has_grant($this->module_id.'_is_action');
		$data['is_production_order'] = $this->Employee->has_grant($this->module_id.'_is_production_order');

		

		$item_info = $this->Compounda->get_info_lenh($lenh_uuid);
		
		$data['item_info'] = $item_info;

		//var_dump($data);
		$this->load->view('compoundas/printbarcode', $data);
	}
	
	public function completed()
	{
		$batch_uuid = $this->input->post('batch_uuid');
		$batch_info = $this->Batch->get_info($batch_uuid);
		
		if (!$batch_info || !$batch_info->qc_cpa_document) {
			show_error('Dữ liệu batch không hợp lệ.', 400);
			return;
		}

		$names_a = $this->input->post('name_A[]') ?? [];
		$names_b = $this->input->post('name_B[]') ?? [];
		$time = time();

		// Trạng thái QC
		$status = (empty($names_a) && empty($names_b)) ? 6 : 5;

		$batch = [
			'batch_id'   => $batch_info->compounda_order_item_completed_id,
			'updated_at' => $time,
			'status'     => $status
		];

		$qc_cpa_document = [
			'qc_cpa_document_id' => $batch_info->qc_cpa_document->qc_cpa_document_id,
			'completed_at'       => $time,
			'status'             => $status,
			'results'            => ($status === 5) ? result_json($batch_info, $names_a, $names_b) : json_encode([])
		];
		//var_dump($names_b);die();
		// Gọi hàm xử lý cập nhật
		$this->Batch->completed($batch, $qc_cpa_document);
		redirect(base_url('qccans/index'));
	}

	public function back()
	{
		redirect(base_url('qccans/index'));
	}

	public function seachbatch()
	{
		$code = $this->input->post('code');
		$data['is_qc'] = $this->Employee->has_grant("{$this->module_id}_is_qc");
	
		//var_dump($item_info->ms);die();
		$_aStatus = [
			3, // Cân xong
			4, // Bắt đầu QC
			5, // QC Ko đạt
			6, // QC đạt
			7, // bắt đầu cán
			8 // hoàn thành
		];

		$statusClass = [
			1 => "choLam",
			2 => "dangLam",
            3 => "choQC",
            4 => "dangQC",
            5 => "qcNotOK",
            6 => "daQCOK",
            7 => "batDauCan",
            8 => "daLam"
        ];
		
		$statusText = [
			1 => "Chờ cân",
			2 => "Đang cân",
            3 => "Chờ Q",
            4 => "Đang QC",
            5 => "QC chưa đạt",
            6 => "QC đạt",
            7 => "Bắt đầu cán",
            8 => "Hoàn thành cán luyện"
        ];

		$data['statusText'] = $statusText;
		$data['statusClass'] = $statusClass;
		// Lấy các batch đã hoàn thành cân
		$_aoListBatchs = $this->Compounda->get_list_tasks_by_code($code);
		
		$data['aoListBatchs'] = transform_data($_aoListBatchs);
		
		$data['isApproved'] = 1;
		
		//$item_info->status == 5 ? $data['isApproved'] = 1: $data['isApproved']=0;

		//var_dump($recipe_ItemA);die();
		$this->load->view('qccans/listme', $data);
	}

}
?>
