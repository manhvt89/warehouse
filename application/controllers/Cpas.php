<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Cpas extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('cpas');
		$this->load->library('item_lib');
		$this->load->library('barcode_lib');
		$this->load->helper('batch');
		$this->load->model('Batch');
	}
	
	public function index($search='')
	{
		$data['is_cpa'] = $this->Employee->has_grant("{$this->module_id}_is_cpa");
	
		//var_dump($item_info->ms);die();
		$_aStatus = [
			6, // QC đạt
			7, // bắt đầu cán
			8 // hoàn thành
		];
		$step = 6;

		$statusClass = [
			1 => "choLam",
			2 => "dangLam",
            6 => "choQC",
            4 => "dangQC",
            5 => "qcNotOK",
            3 => "daQCOK",
            7 => "batDauCan",
            8 => "daLam"
        ];
		
		$statusText = [
			1 => "Chờ cân",
			2 => "Đang cân",
            3 => "Chờ QC",
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
		
		$data['aoListBatchs'] = transform_data($_aoListBatchs,$step);
		
		$data['isApproved'] = 1;
		
		//$item_info->status == 5 ? $data['isApproved'] = 1: $data['isApproved']=0;

		//var_dump($recipe_ItemA);die();
		$this->load->view('cpas/listme', $data);
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

	public function is_inventory()
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

	public function detail_khcl($item_id = -1)
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
		$this->load->view('compoundas/detail_khcl', $data);
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
		$started_date = $data['item_info']->thoi_gian_can_luyen_bat_dau;
		$ended_date = $data['item_info']->thoi_gian_can_luyen_ket_thuc;
		if($ended_date == '' || $ended_date == 0)
		{
			$ended_date = 'Đang cán ...';
		} else {
			$ended_date = date('d/m/Y H:i',$ended_date);
		}
		if($started_date == '' || $started_date == 0)
		{
			$started_date = 'Chưa bắt đầu ...';
		} else {
			$started_date = date('d/m/Y H:i',$started_date);
		}
		$data['started_date'] = $started_date;
		$data['completed_date'] = $ended_date;
		//var_dump($data);
		$this->load->view('cpas/detail_rs', $data);
	}
	public function detail_next($item_id = -1)
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

		$started_date = $data['item_info']->thoi_gian_can_luyen_bat_dau;
		$ended_date = $data['item_info']->thoi_gian_can_luyen_ket_thuc;

		if($ended_date == '' || $ended_date == 0)
		{
			$ended_date = 'Đang Cán ...';
		} else {
			$ended_date = date('d/m/Y H:i',$ended_date);
		}

		if($started_date == '' || $started_date == 0)
		{
			$started_date = 'Chưa bắt đầu ...';
		} else {
			$started_date = date('d/m/Y H:i',$started_date);
		}
		$data['started_date'] = $started_date;
		$data['completed_date'] = $ended_date;
		//var_dump($data);
		$this->load->view('cpas/detail_next', $data);
	}
	
	public function detail($item_id=-1)
	{
		//$person_id = $this->person_id;
		
		$data['is_qc'] = $this->Employee->has_grant($this->module_id.'_is_qc');
		$_oTheUser = $this->Employee->get_info($this->person_id);
		

		$item_info = $this->Batch->get_info($item_id);

		// Kiểm tra chỉ có 1 bản ghi trạng thái 7.
		// Tìm xem hệ thống có bản ghi nào 7 chưa?
		$_aStatus = [
						7 // trạng thai 7
					];
		$_aoListBatchs = $this->Compounda->get_list_tasks_by_status($_aStatus);

		if(!empty($_aoListBatchs) && ($_aoListBatchs[0]->compounda_order_item_completed_uuid != $item_id))
		{
			$this->load->view('cpas/exist', $data);
		} else {

			$time = time();
			$batch = [
				'batch_id'   => $item_info->compounda_order_item_completed_id,
				'updated_at' => $time,
				'status'     => $item_info->status,
				'thoi_gian_can_luyen_bat_dau'=>$time,
				'nguoi_can_luyen_id' => $_oTheUser->person_id,
				'nguoi_can_luyen_name' => "{$_oTheUser->last_name} {$_oTheUser->first_name}"
			];

			$this->Batch->make_doing_cpas($batch); // update thời gian bắt đầu Cán và status của mẻ đang làm
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

			$started_date = $data['item_info']->started_at;
			$ended_date = $data['item_info']->completed_at;

			if($ended_date == '' || $ended_date == 0)
			{
				$ended_date = 'Đang cán ...';
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
			
			$this->load->view('cpas/detail', $data);
		}
		
	}

	public function completed()
	{
		$batch_uuid = $this->input->post('batch_uuid');
		$batch_info = $this->Batch->get_info($batch_uuid);
		
		if (!$batch_info) {
			show_error('Dữ liệu batch không hợp lệ.', 400);
			return;
		}

		
		$time = time();
		$batch = [
			'batch_id'   => $batch_info->compounda_order_item_completed_id,
			'updated_at' => $time,
			'status'     => $batch_info->status
		];

		$this->Batch->make_completed_cpas($batch);
		redirect(base_url('cpas/index'));
	}

	public function back()
	{
		redirect(base_url('cpas/index'));
	}

	public function seachbatch()
	{
		$code = $this->input->post('code');
		$step = 6;

		$statusClass = [
			1 => "choLam",
			2 => "dangLam",
            6 => "choQC",
            4 => "dangQC",
            5 => "qcNotOK",
            3 => "daQCOK",
            7 => "batDauCan",
            8 => "daLam"
        ];
		
		$statusText = [
			1 => "Chờ cân",
			2 => "Đang cân",
            3 => "Chờ QC",
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
		
		$data['aoListBatchs'] = transform_data($_aoListBatchs,$step);
		
		$data['isApproved'] = 1;
		
		//$item_info->status == 5 ? $data['isApproved'] = 1: $data['isApproved']=0;

		//var_dump($recipe_ItemA);die();
		$this->load->view('cpas/listme', $data);
	}

}
?>
