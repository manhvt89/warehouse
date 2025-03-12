<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('items/save/'.$item_info->item_uuid, array('id'=>'item_form', 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal')); ?>
	<fieldset id="item_basic_info">
		
		<?php 
			/** Chuẩn bị dữ liệu để hiện thị Hiển thị các thuộc tính của NVL, Vật tư được mã hóa trong công thức*/ 
			$_aVTFrame = [
						'dpc_name'=>isset($item_info->dpc_name) ? $item_info->dpc_name : "",
						'encode'=>$item_info->encode ?? "",
						'group'=>$item_info->group ?? "",
						'group_category'=>$item_info->group_category ?? "",
						'cas_no'=>$item_info->cas_no ?? "",
						'country'=>isset($item_info->country) ? $item_info->country : "",
						'brand'=>$item_info->brand ?? "",
						'manufactory'=>$item_info->manufactory ?? "",
							];  
		
			$input_data = [];
			switch ($item_info->type) {
				case 'VT':
					echo form_input_item_before('item_number',$item_info->category, 'glyphicon glyphicon-barcode',['class'=>'required']);

					echo form_input_item('name',$item_info->name,true,['class'=>'required']); 

					echo form_input_item_before('category',$item_info->category, 'glyphicon glyphicon-tag',['class'=>'required']);
					
				
					echo form_dropdown_item(
						'supplier_id',      // Tên dropdown
						$suppliers,         // Danh sách options
						$selected_supplier, // Giá trị được chọn
						$this->lang->line('items_supplier') // Nhãn hiển thị
					);
					if($has_grant):
				
						echo form_input_item_price('cost_price',$item_info->cost_price,['class'=>'required']);
					endif; 
					
					echo form_input_item_price('unit_price',$item_info->unit_price,['class'=>'required']);
					echo form_input_item_tax($item_tax_info,0);
					echo form_input_item_tax($item_tax_info,1);

					echo form_input_locations($stock_locations,['class'=>'required hidden','hidden'=>'hidden']);

				
					echo form_input_item('receiving_quantity',to_quantity_decimals($item_info->receiving_quantity) ?? 0,true,['class'=>'required']);
					echo form_input_item('reorder_level', to_quantity_decimals($item_info->reorder_level) ?? 0,true,['class'=>'required']);
					// Hiển thị
					echo form_input_item(
						'standard_amount',
						isset($item_info->standard_amount) ? to_quantity_decimals($item_info->standard_amount) : to_quantity_decimals(0),
						true,
						['class'=>'required']
					);
					// Hiển thị description
					echo form_textarea_item([
						'name'=>'description',
						'id'=>'description',
						'class'=>'form-control input-sm',
						'value'=>$item_info->description
					],$this->lang->line('items_description'));
					
					$_aVTFrame = [
								'normal_name'=>isset($item_info->normal_name) ? $item_info->normal_name : "",
								'short_name'=> isset($item_info->short_name) ? $item_info->short_name : "",
								
									];  

					/** Hiển thị các thuộc tính của NVL, Vật tư được mã hóa trong công thức*/ 
					echo form_input_item($_aVTFrame); 
					$input_data = $_aVTFrame; /** Hiển thị các thuộc tính của NVL, Vật tư được mã hóa trong công thức*/
					echo form_input_item($input_data);
					break;
				case 'SP':
					echo form_input_item_before('item_number',$item_info->item_number, 'glyphicon glyphicon-barcode',['class'=>'required']);

					echo form_input_item('name',$item_info->name,true,['class'=>'required']); 

					echo form_input_item_before('customer_code',$item_info->customer_code, 'glyphicon glyphicon-user',['class'=>'']);

					echo form_input_item_before('category',$item_info->category, 'glyphicon glyphicon-tag',['class'=>'required']);
					
					echo form_input_locations($stock_locations,['class'=>'required hidden','hidden'=>'hidden']);
					
					
					echo form_input_item_tax($item_tax_info,0,['class'=>'required hidden','hidden'=>'hidden']);
					echo form_input_item_tax($item_tax_info,1, ['class'=>'required hidden','hidden'=>'hidden']);
					//echo form_input_item_tax($item_tax_info,0);
					//echo form_input_item_tax($item_tax_info,1);
					
					// Hiển thị description
					echo form_textarea_item([
						'name'=>'description',
						'id'=>'description',
						'class'=>'form-control input-sm',
						'value'=>$item_info->description
					],$this->lang->line('items_description'));
					
					
				
					$_aVTFrame = [
								'normal_name'=>isset($item_info->normal_name) ? $item_info->normal_name : "",
								'short_name'=> isset($item_info->short_name) ? $item_info->short_name : "",
								'machine_code' => $item_info->machine_code ?? "",
								'so_sp_tren_khuan' => $item_info->so_sp_tren_khuan ?? "",
								'tg_cu' => $item_info->tg_cu ?? "",
								'tg_luu_hoa' => $item_info->tg_luu_hoa ?? "",
								'tg_thao_tac' => $item_info->tg_thao_tac ?? "",
								'tg_thay_khuan' => $item_info->tg_thay_khuan ?? "",
								'tl_tho' => $item_info->tl_tho ?? "",
								'tl_tinh' => $item_info->tl_tinh ?? "",
								'part_no' => $item_info->part_no ?? "",
								
									];  

					/** Hiển thị các thuộc tính của NVL, Vật tư được mã hóa trong công thức*/ 
					echo form_input_item($_aVTFrame); 
					$input_data = [
						'nl_ten_thuong_mai'=>isset($item_info->nl_ten_thuong_mai) ? $item_info->nl_ten_thuong_mai : "",
						'nl_mac_tieu_chuan'=> isset($item_info->nl_mac_tieu_chuan) ? $item_info->nl_mac_tieu_chuan : "",
						'ms' => $item_info->ms ?? "", // Hiển thị mác nguyên liệu
						'unit_name'=> $item_info->unit_name ?? ''
					];
					echo form_input_item($input_data);
					//Hiển thị nut delete
					
					break;
				default:
					// Nếu có trường hợp mặc định, có thể xử lý ở đây
					break;
			}
			/** Hiển thị */
			//echo form_input_item($input_data);
			
			/** Hiển thi thuộc tính chung*/
			$_aFrame = [
						'kind'=>isset($item_info->kind) ? $item_info->kind : "",
						//'unit_meansure'=> isset($item_info->unit_meansure) ? $item_info->unit_meansure : "",
						//'packing'=>$item_info->packing ?? "",
						//'ms'=>$item_info->ms ?? "",
						'type'=>$item_info->type ?? "",
							];
			
			/** Hiển thi thuộc tính chung*/
			echo form_input_item($_aFrame);
			
			$item_arr = (array) $item_info;

			for ($i = 1; $i <= 10; ++$i) {
				$custom_name = $this->config->item("custom{$i}_name");

				if (!empty($custom_name)) {
					$value = $item_arr["custom{$i}"] ?? "";
					echo form_input_item($custom_name, $value,false);
				}
			}
			echo form_checkbox_item([
				'name'=>'is_deleted',
				'id'=>'is_deleted',
				'value'=>1,
				'checked'=>$item_info->deleted
			],
			$this->lang->line('items_is_deleted')
		);
			$_aHiddenInput = ['item_id'=> $item_info->item_id];
			echo form_hidden($_aHiddenInput); 
		?>
	</fieldset>
<?php echo form_close(); ?>

<script type="text/javascript">
	//validation and submit handling
	//(function($) {
        // You pass-in jQuery and then alias it with the $-sign
        // So your internal code doesn't change
    //})(jQuery);
	//$(document).ready(function()
	(function($)
	{
		$('#unit_price').number(true,0,',','.');
		$('#cost_price').number(true,0,',','.');
		$("#new").click(function() {
			stay_open = true;
			$("#item_form").submit();
		});

		$("#submit").click(function() {
			stay_open = false;
		});

		var no_op = function(event, data, formatted){};
		$("#category").autocomplete({
			source: "<?php echo site_url('items/suggest_category');?>",
			delay:10,
			appendTo: '.modal-content'});

		<?php for ($i = 1; $i <= 10; ++$i)
		{
		?>
			$("#custom"+<?php echo $i; ?>).autocomplete({
				source:function (request, response) {
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('items/suggest_custom');?>",
						dataType: "json",
						data: $.extend(request, $extend(csrf_form_base(), {field_no: <?php echo $i; ?>})),
						success: function(data) {
							response($.map(data, function(item) {
								return {
									value: item.label
								};
							}))
						}
					});
				},
				delay:10,
				appendTo: '.modal-content'});
		<?php
		}
		?>

		$("a.fileinput-exists").click(function() {
			$.ajax({
				type: "GET",
				url: "<?php echo site_url("$controller_name/remove_logo/$item_info->item_id"); ?>",
				dataType: "json"
			})
		});

		$('#item_form').validate($.extend({
			submitHandler: function(form, event) {
				$(form).ajaxSubmit({
					success: function(response) {
						var stay_open = dialog_support.clicked_id() != 'submit';
						if (stay_open)
						{
							// set action of item_form to url without item id, so a new one can be created
							$("#item_form").attr("action", "<?php echo site_url("items/save/")?>");
							// use a whitelist of fields to minimize unintended side effects
							$(':text, :password, :file, #description, #item_form').not('.quantity, #reorder_level, #tax_name_1,' +
								'#tax_percent_name_1, #reference_number, #name, #cost_price, #unit_price, #taxed_cost_price, #taxed_unit_price').val('');
							// de-select any checkboxes, radios and drop-down menus
							$(':input', '#item_form').not('#item_category_id').removeAttr('checked').removeAttr('selected');
						}
						else
						{
							dialog_support.hide();
						}
						table_support.handle_submit('<?php echo site_url('items'); ?>', response, stay_open);
					},
					dataType: 'json'
				});
			},

			rules:
			{
				name:"required",
				category:"required",
				item_number:
				{
					required: false,
					remote:
					{
						url: "<?php echo site_url($controller_name . '/check_item_number')?>",
						type: "post",
						data: $.extend(csrf_form_base(),
						{
							"item_id" : "<?php echo $item_info->item_id; ?>",
							"item_number" : function()
							{
								return $("#item_number").val();
							},
						})
					}
				},
				cost_price:
				{
					required: true,
					number: true
					//remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
				},
				unit_price:
				{
					required:true,
					number: true
					//remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
				},
				<?php
				foreach($stock_locations as $key=>$location_detail)
				{
				?>
					<?php echo 'quantity_' . $key ?>:
					{
						required:true,
						number: true
						//remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
					},
				<?php
				}
				?>
				receiving_quantity:
				{
					required:true,
					number: true
					//remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
				},
				reorder_level:
				{
					required:true,
					number: true
					//remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
				},
				tax_percent:
				{
					required:true,
					number: true
					//remote: "<?php echo site_url($controller_name . '/check_numeric')?>"
				}
			},

			messages:
			{
				name:"<?php echo $this->lang->line('items_name_required'); ?>",
				item_number: "<?php echo $this->lang->line('items_item_number_duplicate'); ?>",
				category:"<?php echo $this->lang->line('items_category_required'); ?>",
				cost_price:
				{
					required:"<?php echo $this->lang->line('items_cost_price_required'); ?>",
					number:"<?php echo $this->lang->line('items_cost_price_number'); ?>"
				},
				unit_price:
				{
					required:"<?php echo $this->lang->line('items_unit_price_required'); ?>",
					number:"<?php echo $this->lang->line('items_unit_price_number'); ?>"
				},
				<?php
				foreach($stock_locations as $key=>$location_detail)
				{
				?>
					<?php echo 'quantity_' . $key ?>:
					{
						required:"<?php echo $this->lang->line('items_quantity_required'); ?>",
						number:"<?php echo $this->lang->line('items_quantity_number'); ?>"
					},
				<?php
				}
				?>
				receiving_quantity:
				{
					required:"<?php echo $this->lang->line('items_quantity_required'); ?>",
					number:"<?php echo $this->lang->line('items_quantity_number'); ?>"
				},
				reorder_level:
				{
					required:"<?php echo $this->lang->line('items_reorder_level_required'); ?>",
					number:"<?php echo $this->lang->line('items_reorder_level_number'); ?>"
				},
				tax_percent:
				{
					required:"<?php echo $this->lang->line('items_tax_percent_required'); ?>",
					number:"<?php echo $this->lang->line('items_tax_percent_number'); ?>"
				}
			}
		}, form_support.error));
	})(jQuery);
</script>

