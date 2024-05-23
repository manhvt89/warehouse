<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open('locations/save/'.$location_info->location_uuid, array('id'=>'location_form', 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal')); ?>
	<fieldset id="item_basic_info">
		

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('location_name'), 'location_name', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'location_name',
						'id'=>'location_name',
						'class'=>'form-control input-sm',
						'value'=>$location_info->location_name)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('location_code'), 'location_code', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'location_code',
						'id'=>'location_code',
						'class'=>'form-control input-sm',
						'value'=>$location_info->location_code)
						);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('location_phone'), 'location_phone', array('class'=>'required control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array(
						'name'=>'location_phone',
						'id'=>'location_phone',
						'class'=>'form-control input-sm',
						'value'=>$location_info->location_phone)
						);?>
			</div>
		</div>


		
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('items_is_deleted'), 'is_deleted', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-1'>
				<?php echo form_checkbox(array(
						'name'=>'is_deleted',
						'id'=>'is_deleted',
						'value'=>1,
						'checked'=>($location_info->deleted) ? 1 : 0)
						);?>
			</div>
		</div>
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

		$("#new").click(function() {
			stay_open = true;
			$("#location_form").submit();
		});

		$("#submit").click(function() {
			stay_open = false;
		});

		var no_op = function(event, data, formatted){};

		$('#location_form').validate($.extend({
			submitHandler: function(form, event) {
				$(form).ajaxSubmit({
					success: function(response) {
						var stay_open = dialog_support.clicked_id() != 'submit';
						if (stay_open)
						{
							// set action of item_form to url without item id, so a new one can be created
							$("#location_form").attr("action", "<?php echo site_url("locations/save/")?>");
							// use a whitelist of fields to minimize unintended side effects
							$(':text, :password, :file, #description, #location_form').not('.quantity, #reorder_level, #tax_name_1,' +
								'#tax_percent_name_1, #reference_number, #name, #cost_price, #unit_price, #taxed_cost_price, #taxed_unit_price').val('');
							// de-select any checkboxes, radios and drop-down menus
							$(':input', '#location_form').not('#item_category_id').removeAttr('checked').removeAttr('selected');
						}
						else
						{
							dialog_support.hide();
						}
						table_support.handle_submit('<?php echo site_url('locations'); ?>', response, stay_open);
					},
					dataType: 'json'
				});
			},

			rules:
			{
				location_name:"required",
			},

			messages:
			{
				location_name:"<?php echo $this->lang->line('items_name_required'); ?>"
	
			}
		}, form_support.error));
	})(jQuery);
</script>

