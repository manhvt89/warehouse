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
