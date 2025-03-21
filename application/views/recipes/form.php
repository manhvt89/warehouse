<style type="text/css">
	.approved {
		position: relative;
	}

	.approved::before {
		content: "ĐÃ PHÊ DUYỆT";
		position: absolute;
		top: 150px;
		left: 90px;
		transform: translate(-50%, -50%);
		font-size: 50px;
		color: rgba(200, 0, 0, 0.3);
		font-weight: bold;
		text-transform: uppercase;
		pointer-events: none;
		z-index: 10;
		transform: rotate(-45deg);
	}
	.approved-footer {
    position: absolute;
    top: 120px;
    right: 90px;
    font-size: 18px;
    color: rgba(200, 0, 0, 0.7);
    font-weight: bold;
    text-transform: uppercase;
    pointer-events: none;
    transform: rotate(-45deg);
	}
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

	.recipe-header-body-kneader-a td:first-child {
		width: 10%;
		text-align: center;
	}
	.recipe-header-body-kneader-a td:nth-child(2) {
		width: 20%;
		text-align: center;
	}
	.recipe-header-body-kneader-a td:nth-child(3) {
		width: 15%;
		text-align: center;
	}
	.recipe-header-body-kneader-a td:nth-child(4) {
		width: 15%;
		text-align: center;
	}
	.recipe-header-body-kneader-a td:nth-child(5) {
		width: 15%;
		text-align: center;
	}
	.recipe-header-body-kneader-a td:nth-child(6) {
		width: 25%;
		text-align: center;
	}

	.recipe-item-body-kneader-a td:first-child {
		width: 10%;
		text-align: center;
	}
	.recipe-item-body-kneader-a td:nth-child(2) {
		width: 20%;
	}
	.recipe-item-body-kneader-a td:nth-child(3) {
		width: 15%;
		text-align: center;
	}
	.recipe-item-body-kneader-a td:nth-child(4) {
		width: 15%;
		text-align: right;
	}
	.recipe-item-body-kneader-a td:nth-child(5) {
		width: 15%;
		text-align: right;
	}
	.recipe-item-body-kneader-a td:nth-child(6) {
		width: 25%;
		text-align: center;
	}

	#recipe-header-kneader-b td {
		width: 20%;
	}

	.recipe-header-body-kneader-b td:first-child {
		width: 10%;
		text-align: center;
	}
	.recipe-header-body-kneader-b td:nth-child(2) {
		width: 20%;
		text-align: center;
	}
	.recipe-header-body-kneader-b td:nth-child(3) {
		width: 15%;
		text-align: center;
		
	}
	.recipe-header-body-kneader-b td:nth-child(4) {
		width: 15%;
		text-align: center;
		
	}
	.recipe-header-body-kneader-b td:nth-child(5) {
		width: 15%;
		text-align: center;
		
	}
	.recipe-header-body-kneader-b td:nth-child(6) {
		width: 25%;
		text-align: center;
	}

	.recipe-item-body-kneader-b td:first-child {
		width: 10%;
		text-align: center;
	}
	.recipe-item-body-kneader-b td:nth-child(2) {
		width: 20%;
	}
	.recipe-item-body-kneader-b td:nth-child(3) {
		width: 15%;
		text-align: center;
	}
	.recipe-item-body-kneader-b td:nth-child(4) {
		width: 15%;
		text-align: right;
	}
	.recipe-item-body-kneader-b td:nth-child(5) {
		width: 15%;
		text-align: right;
	}
	.recipe-item-body-kneader-b td:nth-child(6) {
		width: 25%;
		text-align: center;
	}

	.recipe-title {
		text-align: center;
		font-size: 25px;
		font-weight: bold;
		height: 50px;
	}

	@media print {
		#table_holder {
			display: none;
		}
		.modal-header, .modal-footer, .bootstrap-dialog-footer{
			display: none;
		}
		.modal-content{
			border: 0px solid rgba(0,0,0,0.2);
		}
		.modal-footer{
			border: 0px solid rgba(0,0,0,0.2);
		}
		.approved-footer, .approved {
			opacity: 0.2; /* Giảm độ đậm khi in */
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
<?php
	$_sApproved = '';
	if($isApproved == 1)
	{
		$_sApproved = 'approved';
	}
?>
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
	<table id="recipe-title">
		<tr>
			<td>
				<div class="recipe-title <?=$_sApproved ?>">
						<?=$this->lang->line('recipe_title')?>
					</div>
			</td>
		</tr>

	</table>
	<!-- #endregion -->
	<!-- #region recipe-info-->
	<table id="recipe-info">
		<tr>
			<td rowspan="3">
				<?php $barcode = $this->barcode_lib->generate_receipt_barcode($item_info->name); ?>
				<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br/>
				<?php echo form_hidden('uuid',$item_info->recipe_uuid) ?>
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
	<!-- #endregion -->
	<!-- #region recipe-header-kneader-a-->
	<table id="recipe-header-kneader-a">
				<tr>
					<td colspan="2">
						<?=$item_info->kneader_a?>
					</td>
					<td>
						<?=$this->lang->line('recipe_processing_time')?>:
					</td>
					<td>
						<?=$item_info->processing_time_a?> phút
					</td>
					<td>
						<?=$this->lang->line('recipe_weight')?>:
					</td>
					<td>
						<?=$item_info->weight_a?> Kg
					</td>	
				<tr>
			</table>
	<!-- #endregion -->
	<!-- #region recipe-body-kneader-a-->
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
	<!-- #endregion -->
	<!-- #region -->
	<table id="recipe-header-kneader-b">
		<tr>
			<td colspan="2">
				<?=$item_info->kneader_b?>
			</td>
			<td>
				<?=$this->lang->line('recipe_processing_time')?>:
			</td>
			<td>
				<?=$item_info->processing_time_b?> phút
			</td>
			<td>
				<?=$this->lang->line('recipe_weight')?>:
			</td>
			<td>
				<?=$item_info->weight_b?> Kg
			</td>	
		<tr>
	</table>
	<!-- #endregion -->
	<!-- #region -->
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
	<!-- #endregion -->
	<?php if ($isApproved): ?>
        <div class="approved-footer">ĐÃ PHÊ DUYỆT</div>
    <?php endif; ?>
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

