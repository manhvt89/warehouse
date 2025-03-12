<style type="text/css">
	#recipe_basic_info {
		width : 100%;
	}

	#recipe_basic_info table {
		width : 100%;
		border-collapse: collapse;
	}

	#recipe_basic_info table, th, td {
		border: 1px solid;
	}
	#recipe-info td {
		width: 20%;
	}
	#recipe-header-kneader-a, #recipe-header-kneader-b {
		height: 40px;
	}
	#recipe-header-kneader-a td {
		width: 20%;
	}
	#recipe-header-kneader-a td:first-child,#recipe-header-kneader-b td:first-child {
		width: 20%;
		font-weight: bold;
	}

	#recipe_basic_info table td{
		padding: 5px;
	}

	.compounda-order-header-body-kneader-a td:first-child {
		max-width: 35px;
		text-align: center;
	}
	.compounda-order-header-body-kneader-a td:nth-child(2) {
		
		text-align: center;
	}
	.compounda-order-header-body-kneader-a td:nth-child(3) {
		max-width: 45px;
		text-align: center;
	}
	.compounda-order-header-body-kneader-a td:nth-child(4) {
		max-width: 75px;
		text-align: center;
	}
	.compounda-order-header-body-kneader-a td:nth-child(5) {
		max-width: 75px;
		text-align: center;
	}
	.compounda-order-header-body-kneader-a td:nth-child(6) {
		max-width: 95px;
		text-align: center;
	}
	.compounda-order-header-body-kneader-a td:nth-child(8) {
		max-width: 95px;
		text-align: center;
	}
	.compounda-order-header-body-kneader-a td:nth-child(9) {
		max-width: 95px;
		text-align: center;
	}

	.compounda-order-item-body-kneader-a td:first-child {
		max-width: 35px;
		text-align: center;
	}
	.compounda-order-item-body-kneader-a td:nth-child(2) {
		width: 20%;
	}
	.compounda-order-item-body-kneader-a td:nth-child(3) {
		max-width: 45px;
		text-align: center;
	}
	.compounda-order-item-body-kneader-a td:nth-child(4) {
		max-width: 75px;
		text-align: right;
	}
	.compounda-order-item-body-kneader-a td:nth-child(5) {
		max-width: 75px;
		text-align: right;
	}
	.compounda-order-item-body-kneader-a td:nth-child(6) {
		max-width: 95px;
		text-align: center;
	}
	.compounda-order-item-body-kneader-a td:nth-child(7) {
		max-width: 95px;
		text-align: right;
	}
	.compounda-order-item-body-kneader-a td:nth-child(8) {
		max-width: 95px;
		text-align: right;
	}
	.compounda-order-item-body-kneader-a td:nth-child(9) {
		max-width: 95px;
		text-align: center;
	}

	.compounda-order-footer-body-kneader-a td:nth-child(1){
		text-align: center;
		font-weight: bold;
	}

	.compounda-order-footer-body-kneader-a td:nth-child(2){
		text-align: right;
		font-weight: bold;
	}
	.compounda-order-footer-body-kneader-a td:nth-child(3){
		text-align: right;
		font-weight: bold;
	}
	.compounda-order-footer-body-kneader-a td:nth-child(4){
		text-align: right;
		font-weight: bold;
	}
	.compounda-order-footer-body-kneader-a td:nth-child(5){
		text-align: right;
		font-weight: bold;
	}
	.compounda-order-footer-body-kneader-a td:nth-child(6){
		text-align: right;
		font-weight: bold;
	}

	

	.compounda-order-title {
		text-align: center;
		font-size: 25px;
		font-weight: bold;
		height: 50px;
	}

	@media print {
		body * {
            visibility: hidden;
        }
        #recipe_basic_info * {
            visibility: visible;
        }
        #recipe_basic_info {
            /*position: absolute;
            left: 0;
            top: 0;
            */
            /*width: 210mm;
            height: 297mm;*/
            width: 297mm;  /* Width of A4 in Landscape */
        	height: 210mm; /* Height of A4 in Landscape */
            padding: 5mm;
            box-sizing: border-box;
            page-break-after: always;
        }
		#recipe_basic_info table {
			width : 95%;
			border-collapse: collapse;
		}
		#recipe_basic_info #recipe-header, #recipe_basic_info #compounda-order-title {
			border: 0px solid;
		}
	}

	/*
	.name {
        font-size: 20px;
    }
    .time {
        font-size: 15px;
    }
    .customer_number,
    .phone {
        font-size: 16px;
    }
    #receipt_items {
        font-size: 16px;
    }
    #receipt_items thead th:not(:first-child) {
        display: none;
    }
    #receipt_items tbody th {
        font-weight: normal;
    }
    #receipt_items td:not(:last-child) {
        display: none;
    }
   
    td[data-th]:before {
        content: attr(data-th);
    }
	*/
</style>
<div id="recipe_basic_info" width="100%">
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
	<!-- #endregion recipe-header -->
	<!-- #region recipe-title-->
	<table id="compounda-order-title">
		<tr>
			<td>
				<div class="compounda-order-title">
						<?=$this->lang->line('compounda-order_title')?>
					</div>
			</td>
		</tr>

	</table>
	<!-- #endregion -->
	<!-- #region recipe-info-->
	<table id="compounda-order-info">
		<tr>
			<td rowspan="3">
				<?php $barcode = $this->barcode_lib->generate_receipt_barcode($item_info->compounda_order_no); ?>
				<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br/>
			</td>
			<td><?=$this->lang->line('compounda_order_order_date')?>:</td>
			<td><b><?=date('d/m/Y',$item_info->order_date)?></b></td>
			<td><?=$this->lang->line('compounda_order_no')?>:</td>
			<td><b><?=$item_info->compounda_order_no?></b></td>
		</tr>
		<tr>
			
			<td><?=$this->lang->line('compounda_order_use_date')?>:</td>
			<td><b><?=date('d/m/Y',$item_info->use_date)?></b></td>
			<td><?=$this->lang->line('compounda_order_area_make_order')?>:</td>
			<td><b><?=$item_info->area_make_order?></b></td>
		</tr>
		<tr>
			
			<td><?=$this->lang->line('compounda_order_creator_name')?>:</td>
			<td><b><?=$item_info->creator_name?></b></td>
			<td><?=$this->lang->line('compounda_order_suppervisor_name')?>:</td>
			<td><b><?=$item_info->suppervisor_name?></b></td>
			
		</tr>
	</table>
	<!-- #endregion -->
	<!-- #region recipe-body-kneader-a-->
	<table id="compounda-order-body-kneader-a">
				<tr class="compounda-order-header-body-kneader-a">
					<td rowspan="2">
						<?=$this->lang->line('compounda_order_num')?>
					</td>
					<td rowspan="2">
					<?=$this->lang->line('compounda_order_ms')?>
					</td>
					<td rowspan="2">
					<?=$this->lang->line('compounda_order_batch_per_one')?>
					</td>

					<td colspan="2">
					<?=$this->lang->line('compounda_order_schedule')?>
					</td>
					<td colspan="3">
					<?=$this->lang->line('compounda_order_weight')?>
					</td>
					<td rowspan="2">
						<?=$this->lang->line('compounda_order_input_quantity')?>
					</td>
					<td rowspan="2">
					<?=$this->lang->line('compounda_order_signal')?>
					</td>
					<td rowspan="2">
					<?=$this->lang->line('compounda_order_note')?>
					</td>
				</tr>
				<tr class="compounda-order-header-body-kneader-a">
					<td>
						<?=$this->lang->line('compounda_order_batch')?>
						</td>
						<td>
						<?=$this->lang->line('compounda_order_total')?>
						</td>
						<td>
						<?=$this->lang->line('compounda_order_start_weight')?>
						</td>
						<td>
						<?=$this->lang->line('compounda_order_use_weight')?>
						</td>
						<td>
						<?=$this->lang->line('compounda_order_end_weight')?>
					</td>
				</tr>
				<?php if(!empty($item_info->list_compound_a)): ?>
					<?php 
						$_i = 0; 
						$_total_batch = 0;
						$_total_schedule = 0;
						$_total_use = 0;
						$_total_end = 0;
					?>
					<?php foreach($item_info->list_compound_a as $item_a): ?>
					<?php 
						$_i++; 
						$_total_batch = $_total_batch + $item_a->quantity_batch;
						$_total_schedule = $_total_schedule + 75 * $item_a->quantity_batch;
						$_total_use = $_total_use + $item_a->quantity_use;
						
					?>
					<tr class="compounda-order-item-body-kneader-a">
						<td>
							<?=$_i?>
						</td>
						<td>
						<?=$item_a->item_name?>
						</td>
						<td>
						<?='75'?>
						</td>
						<td>
						<?=number_format($item_a->quantity_batch,0)?>
						</td>
						<td>
						<?=number_format($item_a->quantity_batch*75,3)?>
						</td>
						<td>
						<?php echo '-'?>
						</td>
						<td>
						<?=$item_a->quantity_use?>
						</td>
						<td>
						<?=number_format($item_a->quantity_batch*75 - $item_a->quantity_use,3)?>
						</td>
						<td>
						
						</td>
						<td>
						
						</td>
						<td>
						<?=$item_a->note?>
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				<!-- #region Tổng cộng-->
				<tr class="compounda-order-footer-body-kneader-a">
					<?php $_total_end = $_total_schedule - $_total_use; ?>
					<td colspan="3">
					<?=$this->lang->line('compounda_order_sum')?>
					
					</td>
					<td>
					<?=number_format($_total_batch ,0)?>
					</td>
					<td>
					<?=number_format($_total_schedule,3)?>
					</td>
					<td>
					<?php echo '-'?>
					</td>
					<td>
					<?=number_format($_total_use,3)?>
					</td>
					<td>
					<?=number_format($_total_end,3)?>
					</td>
					<td>
					
					</td>
					<td>
					
					</td>
					<td>
					
					</td>
					
				</tr>

				<!-- #endregion -->
			</table>
	<!-- #endregion -->
	
	
</div>
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
	
	})(jQuery);
</script>

