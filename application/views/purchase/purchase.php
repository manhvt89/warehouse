<?php $this->load->view("partial/header"); ?>
<script src="/dist/jquery.number.min.js"></script>
<?php
if (isset($error))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error."</div>";
}

if (!empty($warning))
{
	echo "<div class='alert alert-dismissible alert-warning'>".$warning."</div>";
}

if (isset($success))
{
	echo "<div class='alert alert-dismissible alert-success'>".$success."</div>";
}
?>

<div id="po_wrapper">

<!-- Top register controls -->

	<?php echo form_open($controller_name."/change_mode", array('id'=>'mode_form', 'class'=>'form-horizontal panel panel-default')); ?>
		<div class="panel-body form-group">
			<ul>
				<?php if(has_grant('purchases_lens')): ?>
					<li class="pull-right">
						<div class='btn btn-sm btn-success pull-right' id='lens_receiving_button'><span class="glyphicon">&nbsp</span><?php echo 'Nhập mắt từ bảng'; ?></div>
					</li>
				<?php endif; ?>
				<?php if(has_grant('purchases_excel')): ?>
					<li class="pull-right">
					<button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/excel_import"); ?>'
            			title='<?php echo $this->lang->line('items_import_items_excel'); ?>'>
        				<span class="glyphicon glyphicon-import">&nbsp</span><?php echo $this->lang->line('common_import_excel'); ?>
    				</button>
					</li>

				<?php
					if (has_grant('purchases_manage')) // Hiển thị danh sách đơn đặt hàng PO;
					{
				?>
					<li class="pull-right">
						<?php echo anchor($controller_name."/manage", '<span class="glyphicon glyphicon-list-alt">&nbsp</span>' . 'Danh sách PO',
							array('class'=>'btn btn-primary btn-sm', 'id'=>'sales_takings_button', 'title'=>'Danh sách PO')); ?>
					</li>
				<?php
					}
				?>
				<?php endif; ?>
			</ul>
		</div>
	<?php echo form_close(); ?>

	<?php echo form_open($controller_name."/add", array('id'=>'add_item_form', 'class'=>'form-horizontal panel panel-default')); ?>
		<div class="panel-body form-group">
			<ul>
				<li class="pull-left first_li">
					<label for="item", class='control-label'>
						<?php echo $this->lang->line('receivings_find_or_scan_item'); ?>
					</label>
				</li>
				<li class="pull-left">
					<?php echo form_input(array('name'=>'item', 'id'=>'item', 'class'=>'form-control input-sm', 'size'=>'50', 'tabindex'=>'1')); ?>

				<li class="pull-left" style="font-size: large; font-weight: bold">
					<?php echo $this->lang->line('receivings_quantity').':'.$quantity; ?>
				</li>				
			</ul>
		</div>
	<?php echo form_close(); ?>
	
<!-- Receiving Items List -->

	<table class="sales_table_100" id="register">
		<thead>
			<tr>
				<th style="width:5%;"><?php echo $this->lang->line('common_delete'); ?></th>
				<th style="width:12%;">Barcode</th>
				<th style="width:25%;"><?php echo $this->lang->line('receivings_item_name'); ?></th>
				<th style="width:18%;">Danh mục</th>
				<th style="width:5%;"><?php echo $this->lang->line('receivings_quantity'); ?></th>
				<th style="width:10%;">Giá nhập</th>
				<th style="width:10%;">Giá bán</th>
				<th style="width:5%;"><?php echo $this->lang->line('receivings_update'); ?></th>
			</tr>
		</thead>

		<tbody id="cart_contents">
		<?php
			if(count($cart) == 0)
			{
			?>
				<tr>
					<td colspan='8'>
						<div class='alert alert-dismissible alert-info'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
					</td>
				</tr>
			<?php
			}
			else
			{
				foreach(array_reverse($cart, true) as $line=>$item) {
					//var_dump($item);
			?>	
					<?php echo form_open($controller_name."/edit_item/$line", array('class'=>'form-horizontal', 'id'=>'cart_'.$line)); ?>
						<?php if($item['status'] == 7){?>
							<tr id="line_<?=$line?>" class="po-error07">
						<?php } elseif($item['status'] == 6){
							?>
							<tr id="line_<?=$line?>" class="po-error06">
							<?php
						} else { ?>
							<tr id="line_<?=$line?>">
						<?php } ?>
							<td><?php echo anchor($controller_name."/delete_item/$line", '<span class="glyphicon glyphicon-trash"></span>');?></td>
							<td style="align:center;">
							<?php if ($item['status'] == 9) { ?>
								<?php echo form_input(array('name' => 'item_number', 'readonly' => 'readonly', 'class' => 'form-control input-sm', 'value' => $item['item_number'])); ?>
							<?php } else { ?>	
								<?php echo form_input(array('name' => 'item_number', 'class' => 'form-control input-sm', 'value' => $item['item_number'])); ?>
							<?php }?>
								<input type="hidden" name="item_status" value="<?=$item['status']?>" />
								<input type="hidden" name="old_item_number" value="<?=$item['item_number']?>" />
							</td>
							<td style="align:center;">
							<?php if ($item['status'] == 9) { ?>
								<?php echo form_input(array('name'=>'item_name', 'readonly' => 'readonly','class'=>'form-control input-sm', 'value'=>$item['item_name'])); ?>
							<?php } else { ?>
								<?php echo form_input(array('name'=>'item_name', 'class'=>'form-control input-sm', 'value'=>$item['item_name'])); ?>
							<?php }?>
							</td>
							<td style="align:center;">
							<?php if ($item['status'] == 9) { ?>
								<?php echo form_input(array('name'=>'item_category', 'readonly' => 'readonly','class'=>'form-control input-sm', 'value'=>$item['item_category'])); ?>
							<?php } else { ?>
								<?php echo form_input(array('name'=>'item_category', 'class'=>'form-control input-sm', 'value'=>$item['item_category'])); ?>
							<?php }?>
							</td>
							<td><?php echo form_input(array('name'=>'item_quantity', 'class'=>'form-control input-sm', 'value'=>to_quantity_decimals($item['item_quantity']))); ?></td>
							<td><?php echo form_input(array('name'=>'item_price', 'class'=>'form-control input-sm price', 'value'=>to_currency_no_money($item['item_price'])));?></td>
							
							<td><?php echo form_input(array('name'=>'item_u_price', 'class'=>'form-control input-sm price', 'value'=>to_currency_no_money($item['item_u_price'])));?></td>
							<td><a href="javascript:document.getElementById('<?php echo 'cart_'.$line ?>').submit();" title=<?php echo $this->lang->line('receivings_update')?> ><span class="glyphicon glyphicon-refresh"></span></a></td>
						</tr>
						
					<?php echo form_close(); ?>
			<?php
				}
			}
			?>
		</tbody>
	</table>
</div>

<!-- Overall Receiving -->

<div id="overall_po" class="panel panel-default">
	<div class="panel-body">
		<?php
		if(isset($supplier))
		{
		?>
			<table class="sales_table_100">
				<tr>
					<th style='width: 55%;'><?php echo $this->lang->line("receivings_supplier"); ?></th>
					<th style="width: 45%; text-align: right;"><?php echo $supplier; ?></th>
				</tr>
				<?php
				if(!empty($supplier_email))
				{
				?>
					<tr>
						<th style='width: 55%;'><?php echo $this->lang->line("receivings_supplier_email"); ?></th>
						<th style="width: 45%; text-align: right;"><?php echo $supplier_email; ?></th>
					</tr>
				<?php
				}
				?>
				<?php
				if(!empty($supplier_address))
				{
				?>
					<tr>
						<th style='width: 55%;'><?php echo $this->lang->line("receivings_supplier_address"); ?></th>
						<th style="width: 45%; text-align: right;"><?php echo $supplier_address; ?></th>
					</tr>
				<?php
				}
				?>
			</table>
			
			<?php echo anchor($controller_name."/remove_supplier", '<span class="glyphicon glyphicon-remove">&nbsp</span>' . $this->lang->line('common_remove').' '.$this->lang->line('suppliers_supplier'),
								array('class'=>'btn btn-danger btn-sm', 'id'=>'remove_supplier_button', 'title'=>$this->lang->line('common_remove').' '.$this->lang->line('suppliers_supplier'))); ?>
		<?php
		}
		else
		{
		?>
			<?php echo form_open($controller_name."/select_supplier", array('id'=>'select_supplier_form', 'class'=>'form-horizontal')); ?>
				<div class="form-group" id="select_customer">
					<label id="supplier_label" for="supplier" class="control-label" style="margin-bottom: 1em; margin-top: -1em;"><?php echo $this->lang->line('receivings_select_supplier'); ?></label>
					<?php echo form_input(array('name'=>'supplier', 'id'=>'supplier', 'class'=>'form-control input-sm', 'value'=>$this->lang->line('receivings_start_typing_supplier_name'))); ?>

					<button id='new_supplier_button' class='btn btn-info btn-sm modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url("suppliers/view"); ?>'
							title='<?php echo $this->lang->line('receivings_new_supplier'); ?>'>
						<span class="glyphicon glyphicon-user">&nbsp</span><?php echo $this->lang->line('receivings_new_supplier'); ?>
					</button>

				</div>
			<?php echo form_close(); ?>
		<?php
		}
		?>

		<table class="sales_table_100" id="sale_totals">
			<tr>
				
					<th style="width: 55%;">Tổng tiền</th>
					<th style="width: 45%; text-align: right;"><?php echo to_currency($total); ?></th>
				
			</tr>
		</table>

		<?php
		if(count($cart) > 0 && isset($supplier))
		{
		?>
			<div id="finish_sale">
				
					<?php echo form_open($controller_name."/complete", array('id'=>'finish_receiving_form', 'class'=>'form-horizontal')); ?>
						<div class="form-group form-group-sm">
							<label id="comment_label" for="comment"><?php echo $this->lang->line('common_comments'); ?></label>
							<?php //echo form_textarea(array('name'=>'comment', 'id'=>'comment', 'class'=>'form-control input-sm', 'value'=>$comment, 'rows'=>'4'));?>
							<?php echo form_hidden('purchase_id',$purchase_id);?>
							<table class="sales_table_100" id="payment_details">
								<tr>
									<td><?php //echo $this->lang->line('receivings_print_after_sale'); ?></td>
									<td>
										<?php //echo form_checkbox(array('name'=>'recv_print_after_sale', 'id'=>'recv_print_after_sale', 'class'=>'checkbox', 'value'=>1, 'checked'=>$print_after_sale)); ?>
									</td>
								</tr>
							</table>

							<div class='btn btn-sm btn-danger pull-left' id='cancel_receiving_button'><span class="glyphicon glyphicon-remove">&nbsp</span><?php echo $this->lang->line('receivings_cancel_receiving') ?></div>
							<?php if($check == 1) { ?>
								<div class='btn btn-sm btn-success pull-right' id='finish_receiving_button'><span class="glyphicon glyphicon-ok">&nbsp</span><?php echo $this->lang->line('receivings_complete_receiving') ?></div>
							<?php } else { ?>
								<div class='btn btn-sm btn-success pull-right' id='check_button'><span class="glyphicon glyphicon-ok">&nbsp</span>Kiểm tra hợp lệ</div>
							
							<?php } ?>	
						</div>
					<?php echo form_close(); ?>
				
			</div>
		<?php
		}
		?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
	$("#lens_receiving_button").click(function()
    {
		var url = '<?php echo site_url("/receivings/lens"); ?>';
		window.location.replace(url);
    });
	
    $('.price').number(true,0,',','.');
	//$('#amount_tendered').number(true,0,',','.');
	$("#item").autocomplete(
    {
		source: '<?php echo site_url($controller_name."/item_search"); ?>',
    	minChars:0,
       	delay:600,
       	autoFocus: false,
		select:	function (a, ui) {
			$(this).val(ui.item.value);
			$("#add_item_form").submit();
		}
    });

    $('#item').focus();

	$('#item').keypress(function (e) {
		if (e.which == 13) {
			$('#add_item_form').submit();
			return false;
		}
	});

	$('#item').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
    });

	$('#comment').keyup(function() 
	{
		$.post('<?php echo site_url($controller_name."/set_comment");?>', {comment: $('#comment').val()});
	});

	$('#recv_reference').keyup(function() 
	{
		$.post('<?php echo site_url($controller_name."/set_reference");?>', {recv_reference: $('#recv_reference').val()});
	});

	$("#recv_print_after_sale").change(function()
	{
		$.post('<?php echo site_url($controller_name."/set_print_after_sale");?>', {recv_print_after_sale: $(this).is(":checked")});
	});

	$('#item,#supplier').click(function()
    {
    	$(this).attr('value','');
    });

    $("#supplier").autocomplete(
    {
		source: '<?php echo site_url("suppliers/suggest"); ?>',
    	minChars:0,
    	delay:10,
		select: function (a, ui) {
			$(this).val(ui.item.value);
			$("#select_supplier_form").submit();
		}
    });

	dialog_support.init("a.modal-dlg, button.modal-dlg");

	$('#supplier').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('receivings_start_typing_supplier_name'); ?>");
    });

    $("#finish_receiving_button").click(function()
    {
   		$('#finish_receiving_form').submit();
    });

    $("#cancel_receiving_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("receivings_confirm_cancel_receiving"); ?>'))
    	{
			$('#finish_receiving_form').attr('action', '<?php echo site_url($controller_name."/cancel_receiving"); ?>');
    		$('#finish_receiving_form').submit();
    	}
    });
	$("#check_button").click(function()
    {
			$.post('<?php echo site_url("purchases/check_purchase");?>', {'csrf_token':csrf_token}, function(ketqua) {
                window.location.replace("<?php echo site_url("purchases");?>");
            });
    });

	$("#cart_contents input").keypress(function(event)
	{
		if (event.which == 13)
		{
			$(this).parents("tr").prevAll("form:first").submit();
			//console.log($(this).parents("tr"));
			//console.log($("p"));
			//$(this).parents("tr").removeClass("po-error07");
			//$(this).parents("tr").removeClass("po-error06");

		}
	});

	table_support.handle_submit = function(resource, response, stay_open)
	{
		//alert(resource);
		if(response.success)
		{
			if (resource.match(/suppliers$/))
			{
				$("#supplier").attr("value",response.id);
				$("#select_supplier_form").submit();
			}
			else if(resource.match(/purchases$/))
			{
				window.location.replace("<?php echo base_url("purchases") ?>");
			}
			else
			{
				$("#item").attr("value",response.id);
				if (stay_open)
				{
					$("#add_item_form").ajaxSubmit();
				}
				else
				{
					$("#add_item_form").submit();
				}
			}
		}
	}
});

</script>

<?php $this->load->view("partial/footer"); ?>
