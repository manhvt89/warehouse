
<?php $this->load->view("partial/header"); ?>
<script src="/dist/jquery.number.min.js"></script>
<style type="text/css">
	.number{
		text-align: right;
	}
	.code {
		text-align: center;
	}
	.one{

	}
	.two{
		
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
	
	<!-- #region recipe-title-->
	<table id="compounda-order-title">
		<tr>
			<td>
				<div class="compounda-order-title">
						Danh sách các mẻ
					</div>
			</td>
		</tr>

	</table>
	<table id="recipe-header">
		<tr>
			<td><div class="recipe-header-company-name">
			<?php echo form_open($controller_name."/seachcan", array('id'=>'seachcan', 'class'=>'form-horizontal panel panel-default')); ?>
				<input type="text" name="code" value="" id="code" class="form-control input-sm ui-autocomplete-input" size="50" tabindex="1" autocomplete="off">
				<?php echo form_hidden('compounda_order_item_uuid',$item_info->compounda_order_item_uuid) ?>
			<?php echo form_close(); ?>
			</div></td>
		</tr>

	</table>
	<!-- #endregion recipe-header -->
	<!-- #endregion -->
	<!-- #region recipe-info-->
	<?php if($item_info->compounda_order_id > 0):?>
	<?php $_oList_batchs = $item_info->list_batchs;?>
	<?php //$_oList_lenh_can = $item_info->list_compound_a;?>
	<!-- #endregion -->
	<!-- #region recipe-body-kneader-a-->
	<table id="compounda-order-body-kneader-a">
				<tr class="compounda-order-header-body-kneader-a">
					<td >
						Mẻ
					</td>
					
					<td >
					Trạng thái
					</td>
					<td >
					<?=$this->lang->line('compounda_order_note')?>
					</td>
				</tr>
				
				<?php
					if(!empty($_oList_batchs))
					{
						foreach($_oList_batchs as $batch)
						{ //var_dump($batch);die();
				?>

						<tr class="one">
							<td>
								<?=$batch->code ?>
							</td>
							
							<td rowspan="2">
								<?php // Hiển thị thông tin recipe với mác nguyên liệu
								
								?>
								<table id="recipe-info">
		<tr>
			<td rowspan="3">
				<?php $barcode = $this->barcode_lib->generate_receipt_barcode($recipe_info->name); ?>
				<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br/>
				<?php echo form_hidden('uuid',$recipe_info->recipe_uuid) ?>
			</td>
			<td><?=$this->lang->line('recipes_master_batch')?>:</td>
			<td><b><?=$recipe_info->name?></b></td>
			<td><?=$this->lang->line('recipes_grade_of_standard')?>:</td>
			<td><b><?=$recipe_info->grade_of_standard?></b></td>
		</tr>
		<tr>
			
			<td><?=$this->lang->line('recipes_date_issued')?>:</td>
			<td><b><?=date('d/m/Y',$recipe_info->date_issued)?></b></td>
			<td><?=$this->lang->line('recipes_certificate_no')?>:</td>
			<td><b><?=$recipe_info->certificate_no?></b></td>
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
						<?=$recipe_info->kneader_a?>
					</td>
					<td>
						<?=$this->lang->line('recipe_processing_time')?>:
					</td>
					<td>
						<?=$recipe_info->processing_time_a?> phút
					</td>
					<td>
						<?=$this->lang->line('recipe_weight')?>:
					</td>
					<td>
						<?=$recipe_info->weight_a?> Kg
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
				<?=$recipe_info->kneader_b?>
			</td>
			<td>
				<?=$this->lang->line('recipe_processing_time')?>:
			</td>
			<td>
				<?=$recipe_info->processing_time_b?> phút
			</td>
			<td>
				<?=$this->lang->line('recipe_weight')?>:
			</td>
			<td>
				<?=$recipe_info->weight_b?> Kg
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
							</td>
							<td rowspan="2">
								<?//=$lenh->note ?>
							</td>
						</tr>
						<tr class="two">
							<td>
							<?php $barcode_code = $this->barcode_lib->generate_receipt_barcode($batch->code); ?>
									<img src='data:image/png;base64,<?php echo $barcode_code; ?>' /><br/>
							</td>
							
							
							
						</tr>
				<?php
						}
						

					}
				
				?>
				<!-- #region Tổng cộng-->
				
				<!-- #endregion -->
			</table>
	<!-- #endregion -->
	<?php else: ?>
		<table id="compounda-order-info">
			<tr>
				<td class="code">
					Kế hoạch cán luyện không tồn tại!
				</td>

			</tr>
		</table>
	<?php endif; ?>
	
</div>
<script type="text/javascript">

	/*$("#compounda_order_uuid_text").autocomplete(
	{
		source: '<?php echo site_url($controller_name."/item_search"); ?>',
    	minChars: 0,
    	autoFocus: false,
       	delay: 600,
		select: function (a, ui) {
			$(this).val(ui.item.value);
			$("#add_item_form").submit();
			return false;
		}
    });
	*/

	$('#order_number').keypress(function (e) {
		if (e.which == 13) {
			$('#seachcan').submit();
			return false;
		}
	});

   
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

<?php $this->load->view("partial/footer"); ?>