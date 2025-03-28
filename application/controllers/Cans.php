<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Cans extends Secure_Controller
{
	private $oTheUser;
	public function __construct()
	{
		parent::__construct('cans');
		$this->load->library('item_lib');
		$this->load->library('barcode_lib');
		$this->load->helper('recipe');
		$this->load->model('Batch');
		$this->load->helper('batch');

		$this->oTheUser = $this->Employee->get_info($this->person_id);
	}
	
	public function index($search='')
	{
		$data['is_approved'] = $this->Employee->has_grant($this->module_id.'_is_approved');
		$data['is_inventory'] = $this->Employee->has_grant($this->module_id.'_is_inventory');
		$data['is_editor'] = $this->Employee->has_grant($this->module_id.'_is_editor');
		$data['is_action'] = $this->Employee->has_grant($this->module_id.'_is_action');
		$data['is_worker'] = $this->Employee->has_grant($this->module_id.'_is_worker');
		$data['is_production_order'] = $this->Employee->has_grant($this->module_id.'_is_production_order');
		$data['is_checker'] = $this->Employee->has_grant($this->module_id.'_is_checker');
		$data['is_monitor'] = $this->Employee->has_grant($this->module_id.'_is_monitor');

		$search = $this->input->get('search');

		if($data['is_inventory']){ //Ưu tiên quyền quản lý kho
			//$person_id = $this->person_id;
			//echo $search; die();
			if($search == '')
			{
				$data['item_info'] = null;
			} else {
				$item_info = $this->Compounda->get_info_by_no($search);
				
				if($item_info->compounda_order_id != 0 )
				{
					foreach(get_object_vars($item_info) as $property => $value)
					{
						if(!is_object($value) && !is_array($value))
						{
							$item_info->$property = $this->xss_clean($value);
						}
					}

					$data['item_info'] = $item_info;
				} else {
					$data['item_info'] = null;
					$data['message'] = 'Chưa tìm thấy lệnh sx theo số lệnh: <b>' .$search.'</b>, hãy thử với lệnh khác';
				}
			}

			//var_dump($data);
			$this->load->view('cans/detail', $data);
			//$this->load->view('recipes/detail', $data);

		} 
		else if($data['is_worker']){ // Tiếp theo Worker// Nhận VT
			if($search == '')
			{
				$data['item_info'] = null;
			} else {
				$item_info = $this->Compounda->get_info_by_no($search);
				
				if($item_info->compounda_order_id != 0 )
				{
					foreach(get_object_vars($item_info) as $property => $value)
					{
						if(!is_object($value) && !is_array($value))
						{
							$item_info->$property = $this->xss_clean($value);
						}
					}

					$data['item_info'] = $item_info;
				} else {
					$data['item_info'] = null;
					$data['message'] = 'Chưa tìm thấy lệnh sx theo số lệnh: <b>' .$search.'</b>, hãy thử với lệnh khác';
				}
			}

			//var_dump($data);
			$this->load->view('cans/detail', $data);
		}
		else {

			$data['table_headers'] = $this->xss_clean(get_compoundas_manage_table_headers());

			//$data['table_headers'] = $this->xss_clean(get_items_manage_table_headers());

			
			$data['stock_location'] = $this->xss_clean($this->item_lib->get_item_location());
			$data['stock_locations'] = $this->xss_clean($this->Stock_location->get_allowed_locations());

			// filters that will be loaded in the multiselect dropdown
			$data['filters'] = array('empty_upc' => $this->lang->line('items_empty_upc_items'),
				'low_inventory' => $this->lang->line('items_low_inventory_items'),
				'is_deleted' => $this->lang->line('items_is_deleted'));

			$data['grant_id'] = $this->grant_id; //Phân quyền module 
			$this->load->view('cans/manage', $data);
		}
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

	public function detail($item_id=-1)
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
		$this->load->view('compoundas/detail', $data);
		//$this->load->view('recipes/detail', $data);
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
		$this->load->view('cans/detail_khcl', $data);
	}

	public function can($lenh_uuid)
	{
		//$person_id = $this->person_id;
		$data['is_approved'] = $this->Employee->has_grant($this->module_id.'_is_approved');
		$data['is_inventory'] = $this->Employee->has_grant($this->module_id.'_is_inventory');
		$data['is_editor'] = $this->Employee->has_grant($this->module_id.'_is_editor');
		$data['is_action'] = $this->Employee->has_grant($this->module_id.'_is_action');
		$data['is_production_order'] = $this->Employee->has_grant($this->module_id.'_is_production_order');

		

		$item_info = $this->Compounda->get_info_lenh($lenh_uuid,'');
		if($item_info->status == 4)
		{
			$this->Compounda->make_running_lenh($item_info);
			//var_dump($item_info->ms);die();
			
			$item_info = $this->Compounda->get_info_lenh($lenh_uuid,''); // Get lại sau update

			$statusOrder = [ // Thứ tự sắp xếp
				1,
				2,
				3,
				4,
				5,
				6,
				7,
				8
			];

			$_aListBatches = sortByCustomOrder($item_info->list_batchs,$statusOrder);
			$_doingBatches = [];
			foreach($_aListBatches as $k=>$item)
			{
				if($item->status == 2)
				{
					$_doingBatches[] = $item;
					unset($_aListBatches[$k]);
				}
			}
			$item_info->list_batchs = $_aListBatches;
			$recipe_info = $this->Recipe->get_info_by_ms($item_info->ms);
			$recipe_ItemA = $this->Recipe->get_item_by_ms($item_info->ms,'A')->result();
			$recipe_ItemB = $this->Recipe->get_item_by_ms($item_info->ms,'B')->result();

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
			
		
			$_aCount_by_status = $this->Batch->count_by_status($item_info->compounda_order_item_id);
			$data['aCount_by_status'] = $_aCount_by_status;
			//$data['statusText'] = $statusText;
			$item_info->list_batchs = transform_data($item_info->list_batchs,2);
			$data['statusClass'] = $statusClass;
			$data['item_info'] = $item_info;
			$data['recipe_info'] = $recipe_info;
			$data['arrItem_as'] = $recipe_ItemA;
			$data['arrItem_bs'] = $recipe_ItemB;
			$data['doingBatches'] = $_doingBatches;
			$data['isApproved'] = 1;
			$data['recipe_body_A'] = recipe_body_A($recipe_info,$recipe_ItemA,5);
			$data['recipe_body_B'] = recipe_body_B($recipe_info,$recipe_ItemB,5);
			$data['recipe_info_'] = recipe_info($recipe_info);
			$item_info->status == 5 ? $data['isApproved'] = 1: $data['isApproved']=0;

			//var_dump($recipe_ItemA);die();
			$this->load->view('cans/listme', $data);
		} else {
			$data['back_url'] = base_url("cans/can{$lenh_uuid}");
			$this->load->view('cans/exist', $data);
		}
	}

	public function searchlenh()
	{
		$data['is_approved'] = $this->Employee->has_grant($this->module_id.'_is_approved');
		$data['is_inventory'] = $this->Employee->has_grant($this->module_id.'_is_inventory');
		$data['is_editor'] = $this->Employee->has_grant($this->module_id.'_is_editor');
		$data['is_action'] = $this->Employee->has_grant($this->module_id.'_is_action');
		$data['is_production_order'] = $this->Employee->has_grant($this->module_id.'_is_production_order');

		$number_order = $this->input->post('compounda_order_uuid_text');
		$uuid = $this->input->post('compounda_order_uuid');

		$item_info = $this->Compounda->get_info($uuid,$number_order);
		//var_dump($item_info);die();
		
		
		$data['item_info'] = $item_info;

		//var_dump($data);
		$this->load->view('cans/detail_khcl', $data);
	}

	public function seachcan()
	{
		$data['is_approved'] = $this->Employee->has_grant($this->module_id.'_is_approved');
		$data['is_inventory'] = $this->Employee->has_grant($this->module_id.'_is_inventory');
		$data['is_editor'] = $this->Employee->has_grant($this->module_id.'_is_editor');
		$data['is_action'] = $this->Employee->has_grant($this->module_id.'_is_action');
		$data['is_production_order'] = $this->Employee->has_grant($this->module_id.'_is_production_order');

		
		$code = $this->input->post('code');
		$uuid = $this->input->post('compounda_order_item_uuid');

		$item_info = $this->Compounda->get_info_lenh($uuid,$code);
		if(!empty($item_info->list_batchs))
		{
			$oBatch = $item_info->list_batchs[0];
			$thoi_gian_can = $oBatch->thoi_gian_can;
			if($thoi_gian_can == '' || $thoi_gian_can == 0)
			{
				$_aThoiGianCan = [
					'started'=>time(),
					'ended'=>0,
					'status'=>'T'
				];

				$thoi_gian_can = json_encode($_aThoiGianCan);

			} else {
				$_aThoiGianCan = json_decode($thoi_gian_can,true);
				//var_dump($_aThoiGianCan); die();
				$_aThoiGianCan[] = [
									'started'=>time(),
									'ended'=>0,
									'status'=>'T'
								];
				$thoi_gian_can = json_encode($_aThoiGianCan);
			}
			$batch = [
				'status' => $oBatch->status,
				'batch_id'   => $oBatch->compounda_order_item_completed_id,
				'nguoi_can_id' => $this->oTheUser->person_id,
				'thoi_gian_can' => $thoi_gian_can,
				'nguoi_can_name' => "{$this->oTheUser->last_name} {$this->oTheUser->first_name}"
			];

			$this->Batch->make_doing_can($batch);
			redirect(base_url("cans/can/{$uuid}"));
			exit();
		
			$_aListBatch2 = $this->Batch->get_list_batches_by_status([2]);
			//var_dump($item_info);die();
			$recipe_info = $this->Recipe->get_info_by_ms($item_info->ms);
			$recipe_ItemA = $this->Recipe->get_item_by_ms($item_info->ms,'A')->result();
			$recipe_ItemB = $this->Recipe->get_item_by_ms($item_info->ms,'B')->result();

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
				3 => "Cân xong",
				4 => "Đang QC",
				5 => "QC chưa đạt",
				6 => "QC đạt",
				7 => "Bắt đầu cán",
				8 => "Hoàn thành cán luyện"
			];

			$_aCount_by_status = $this->Batch->count_by_status($item_info->compounda_order_item_id);
			$data['aCount_by_status'] = $_aCount_by_status;
			$data['statusText'] = $statusText;
			$data['statusClass'] = $statusClass;
			
			$data['aListBatch2'] = $_aListBatch2;
			$data['recipe_info'] = $recipe_info;
			$data['arrItem_as'] = $recipe_ItemA;
			$data['arrItem_bs'] = $recipe_ItemB;
			$data['isApproved'] = 1;
			$item_info->status == 5 ? $data['isApproved'] = 1: $data['isApproved']=0;
			$data['recipe_body_A'] = recipe_body_A($recipe_info,$recipe_ItemA,5);
			$data['recipe_body_B'] = recipe_body_B($recipe_info,$recipe_ItemB,5);
			$data['recipe_info_'] = recipe_info($recipe_info);
			
			$data['item_info'] = $item_info;
			redirect(base_url("cans/can/{$uuid}"));
			//var_dump($data);
			//$this->load->view('cans/listme', $data);
		} else {
			$data['back_url'] = base_url("cans/can/{$uuid}");
			$this->load->view('cans/exist', $data);
		}
	}

	
	public function ajax_update_weighing()
	{
		
		$uuid = $this->input->post("uuid");
		$weighing_count = $this->input->post("weighing_count");
		$max_weighing_count = 5;

		$_oBatchInfo = $this->Batch->get_info($uuid);
		//var_dump($weighing_count);
		if($_oBatchInfo->compounda_order_item_completed_id == '' || $_oBatchInfo->compounda_order_item_completed_id == 0)
		{
			echo json_encode(["success" => false]);
			exit();
		} else {
			// Lấy trạng thái hiện tại
			$currentWeighing = $_oBatchInfo->weighing_count;
			//var_dump($currentWeighing);
			// Kiểm tra điều kiện chỉ nhấn theo thứ tự
			if ($weighing_count != $currentWeighing+1) {
				echo json_encode(["success" => false, "message" => "Không thể cập nhật sai thứ tự!"]);
				exit;
			}

			if ($weighing_count > $max_weighing_count+1) {
				echo json_encode(["success" => false, "message" => "Đã hoàn thành!"]);
				exit;
			}

			if($weighing_count < $max_weighing_count+1)
			{

				if($this->Batch->make_weighing_count($_oBatchInfo,$weighing_count))
				{
					echo json_encode(["success" => true]);
				} else {
					echo json_encode(["success" => false]);
				}
			} else {
				if($this->Batch->completed_weighing($_oBatchInfo,$weighing_count))
				{
					echo json_encode(["success" => true]);
				} else {
					echo json_encode(["success" => false]);
				}
			}
		}
		
	}

	public function recan($uuid)
	{
		$item_info = $this->Batch->get_info($uuid);
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
		$this->load->view('cans/detail_rs', $data);
	}

	public function re_completed()
	{
		$uuid = $this->input->post("batch_uuid");
		$batch = $this->Batch->get_info($uuid);
		if($batch->compounda_order_item_completed_id != 0)
		{
			$this->Batch->re_completed_weighing($batch);
			redirect(base_url("cans/can/{$batch->lenh->compounda_order_item_uuid}"));
		}
	}

}
?>
