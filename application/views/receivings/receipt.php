<?php $this->load->view("partial/header"); ?>

<?php
if (isset($error_message)) {
    echo "<div class='alert alert-dismissible alert-danger'>".$error_message."</div>";
    exit;
}
?>

<?php $this->load->view('partial/print_receipt', array('print_after_sale', $print_after_sale, 'selected_printer'=>'receipt_printer')); ?>
<?php echo form_open($controller_name."/act", array('id'=>'action_form', 'class'=>'form-horizontal panel panel-default')); ?>
<div class="print_hide" id="control_buttons" style="text-align:right">
	<a href="javascript:printdoc();"><div class="btn btn-info btn-sm", id="show_print_button"><?php echo '<span class="glyphicon glyphicon-print">&nbsp</span>' . $this->lang->line('common_print'); ?></div></a>
	<?php echo anchor("receivings", '<span class="glyphicon glyphicon-save">&nbsp</span>' . $this->lang->line('receivings_register'), array('class'=>'btn btn-info btn-sm', 'id'=>'show_sales_button')); ?>
	<div class='btn btn-sm btn-info pull-right' id='excel_export_button'><span class="glyphicon glyphicon-import">&nbsp</span>Xuất Excel</div>
</div>
<?php echo form_hidden('receive_id', $_receive_id);?>
<?php echo form_close(); ?>
<div id="receipt_wrapper">
	
		<div id="receipt_header">
			<?php
			if ($this->config->item('company_logo') == '') 
			{ 
			?>
				<div id="company_name"><?php echo $this->config->item('company'); ?></div>
			<?php 
			}
			else 
			{ 
			?>
				<div id="company_name"><img id="image" src="<?php echo base_url('uploads/' . $this->config->item('company_logo')); ?>" alt="company_logo" /></div>			
			<?php
			}
			?>
			<div id="company_address"><?php echo nl2br($this->config->item('address')); ?></div>
			<div id="company_phone"><?php echo $this->config->item('phone'); ?></div>
			<div id="sale_receipt"><?php echo $receipt_title; ?></div>
			<div id="sale_time"><?php echo $transaction_time ?></div>
		</div>
	

	<div id="receipt_general_info">
		<?php
		if(isset($supplier))
		{
		?>
			<div id="customer"><?php echo $this->lang->line('suppliers_supplier').": ".$supplier; ?></div>
		<?php
		}
		?>
		<div id="sale_id"><?php echo 'Mã phiếu'.": ".$code; ?></div>
		<?php 
		if (!empty($reference))
		{
		?>
			<div id="reference"><?php echo $this->lang->line('receivings_reference').": ".$reference; ?></div>	
		<?php 
		}
		?>
		
	</div>
	<table id="receipt_items">
		<tr>
			<th style="width:40%;"><?php echo $this->lang->line('items_item'); ?></th>
			<th style="width:20%;text-align:center;"><?php echo $this->lang->line('common_price'); ?></th>
			<th style="width:10%;text-align:center;"><?php echo $this->lang->line('sales_quantity'); ?></th>
			<th style="width:25%;text-align:center;"><?php echo $this->lang->line('sales_total'); ?></th>
		</tr>

		<?php
		foreach(array_reverse($cart, true) as $line=>$item)
		{
		?>
			<tr>
				<td><?php echo $item['name']; ?></td>
				<td style="text-align:right;"><?php echo to_currency($item['price']); ?></td>
				<td style="text-align:right;"><?php echo to_quantity_decimals($item['quantity']) . " " . ($show_stock_locations ? " [" . $item['stock_name'] . "]" : ""); 
				?></td>
				<td style="text-align:right;"><div class="total-value"><?php echo to_currency($item['total']); ?></div></td>
			</tr>
			<tr>
				<td ><?php echo $item['serialnumber']; ?></td>
			</tr>
			<?php
			if ($item['discount'] > 0 )
			{
			?>
				<tr>
					<td colspan="3" style="font-weight: bold;"> <?php echo number_format($item['discount'], 0) . " " . $this->lang->line("sales_discount_included")?> </td>
				</tr>
			<?php
			}
			?>
		<?php
		}
		?>	
		<tr>
			<td style='text-align:right;border-top:2px solid #000000;'><?php echo $this->lang->line('receiving_total'); ?></td>
			<td style='border-top:2px solid #000000;'></td>
			<td style='border-top:2px solid #000000;'><div class="total-value"><?php echo number_format($amount); ?></div></td>
			<td style='border-top:2px solid #000000;'><div class="total-value"><?php echo to_currency($total); ?></div></td>
		</tr>
		<?php 
		if($mode!='requisition')
		{
		?>
			<tr>
				<td colspan="4" style='text-align:right;'><?php echo $this->lang->line('history_payment'); ?></td>
				
			</tr>
			<?php foreach($payments as $payment): ?>
				<tr>
					<td style='text-align:left;'></td>
					<td style=''><?=$payment['payment_time']?></td>
					<td style=''><div class="total-value"><?=$payment['payment_type']?></div></td>
					<td style='text-align:right '><div class="total-value"><?php echo to_currency($payment['payment_amount']); ?></div></td>
				</tr>
			<?php endforeach; ?>
			<tr>
				<td style='text-align:right;border-top:2px solid #000000;'><?php echo $this->lang->line('paid_total'); ?></td>
				<td style='border-top:2px solid #000000;'></td>
				<td style='border-top:2px solid #000000;'><div class="total-value"></div></td>
				<td style='border-top:2px solid #000000;'><div class="total-value"><?php echo to_currency($paid_total); ?></div></td>
			</tr>
			<tr>
				<td style='text-align:right;border-top:2px solid #000000;'><?php echo $this->lang->line('remain_amount'); ?></td>
				<td style='border-top:2px solid #000000;'></td>
				<td style='border-top:2px solid #000000;'><div class="total-value"></div></td>
				<td style='border-top:2px solid #000000;'><div class="total-value"><?php echo to_currency($remain_amount); ?></div></td>
			</tr>
			<?php if(isset($amount_change))
			{
			?>
				<tr>
					<td colspan="3" style='text-align:right;'>Đã thanh toán</td>
					<td><div class="total-value"><?php echo to_currency($amount_tendered); ?></div></td>
				</tr>

				<tr>
					<td colspan="3" style='text-align:right;'>Còn lại</td>
					<td><div class="total-value"><?php echo $amount_change; ?></div></td>
				</tr>
			<?php
			}
			?>
		<?php 
		}
		?>
	</table>

	<div id="sale_return_policy">
		<?php //echo nl2br($this->config->item('return_policy')); ?>
		<div id="employee"><?php echo 'Người lập phiếu: '.$employee; ?></div>
	</div>

	<div id='barcode'>
		<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br>
		<?php echo $code; ?>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{

    
	$("#excel_export_button").click(function()
    {	
		$('#action_form').attr('action', '<?php echo site_url($controller_name . "/export"); ?>');
		$('#action_form').attr('method', 'get');
		$('#action_form').submit();
		
    });
	

	
	
});



</script>
<?php $this->load->view("partial/footer"); ?>
