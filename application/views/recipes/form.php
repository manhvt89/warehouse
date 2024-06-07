<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<table id="recipe_basic_info">
	<tr>
		<td>
			<table id="recipe-header">
				<tr>
					<td><div class="recipe-header-company-name"><?=$this->config->item('company')?></div></td>
					<td>
					<div class="recipe-header-company-info">
						<p><?=$this->config->item('address')?></p>
						<p>Tel : (251) 352 5199 / 352 5200  _ Fax:(251) 352 5222</p>
					</div>
					</td>
				</tr>

			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div class="recipe-title">
					<?=$this->lang->line('recipe_title')?>
				</div>
		</td>
	</tr>
	<tr>
		<td>
			<table id="recipe-info">
				<tr>
					<td rowspan="3">
						QRCODE
					</td>
					<td><?=$this->lang->line('recipes_master_batch')?>:</td>
					<td><b><?=$item_info->name?></b></td>
					<td><?=$this->lang->line('recipes_grade_of_standard')?>:</td>
					<td><b><?=$item_info->grade_of_standard?></b></td>
				</tr>
				<tr>
					
					<td><?=$this->lang->line('recipes_date_issued')?>:</td>
					<td><b><?=date('d/m/Y',$item_info->date_issued)?></b></td>
					<td><?=$this->lang->line('recipes_certificate_no')?>:</td>
					<td><b><?=$item_info->certificate_no?></b></td>
				</tr>
				<tr>
					
					<td><?=$this->lang->line('recipe_product_code')?>:</td>
					<td colspan="3"><b>N/A</b></td>
					
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="recipe-header-kneader-a">
				<tr>
					<td colspan="2">
						<?=$item_info->kneader_a?>
					</td>
					<td>
						<?=$this->lang->line('recipe_processing_time')?>:
					</td>
					<td>
						<?=$item_info->processing_time_a?>
					</td>
					<td>
						<?=$this->lang->line('recipe_weight')?>:
					</td>
					<td>
						<?=$item_info->weight_a?>
					</td>	
				<tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="recipe-body-kneader-a">
				<tr class="recipe-header-body-kneader-a">
					<td>
						<?=$this->lang->line('recipe_group')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_component_mix')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_unit')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_weight')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_tolerance')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_contains_percentage')?>
					</td>
				</tr>
				<?php if(!empty($arrItem_as)): ?>
					<?php foreach($arrItem_as as $item_a): ?>
					<tr class="recipe-item-body-kneader-a">
						<td>
							<?=$item_a->item_group?>
						</td>
						<td>
						<?=$item_a->item_mix?>
						</td>
						<td>
						<?=$item_a->uom_name?>
						</td>
						<td>
						<?=$item_a->weight?>
						</td>
						<td>
						<?php echo $item_a->tolerace;?>
						</td>
						<td>
						N/A
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="recipe-header-kneader-b">
				<tr>
					<td colspan="2">
						<?=$item_info->kneader_b?>
					</td>
					<td>
						<?=$this->lang->line('recipe_processing_time')?>:
					</td>
					<td>
						<?=$item_info->processing_time_b?>
					</td>
					<td>
						<?=$this->lang->line('recipe_weight')?>:
					</td>
					<td>
						<?=$item_info->weight_b?>
					</td>	
				<tr>
			</table>
		</td>
	</tr>

	<tr>
		<td>
			<table id="recipe-body-kneader-b">
				<tr class="recipe-header-body-kneader-b">
					<td>
						<?=$this->lang->line('recipe_group')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_component_mix')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_unit')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_weight')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_tolerance')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_contains_percentage')?>
					</td>
				</tr>
				<?php if(!empty($arrItem_bs)): ?>
					<?php foreach($arrItem_bs as $item_b): ?>
					<tr class="recipe-item-body-kneader-b">
						<td>
							<?=$item_b->item_group?>
						</td>
						<td>
						<?=$item_b->item_mix?>
						</td>
						<td>
						<?=$item_b->uom_name?>
						</td>
						<td>
						<?=$item_b->weight?>
						</td>
						<td>
						<?php echo $item_a->tolerace;?>
						</td>
						<td>
						N/A
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</td>
	</tr>
</table>

<script type="text/javascript">
	//validation and submit handling
	//(function($) {
        // You pass-in jQuery and then alias it with the $-sign
        // So your internal code doesn't change
    //})(jQuery);
	//$(document).ready(function()
	(function($)
	{
		

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

