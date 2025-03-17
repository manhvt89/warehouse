
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
						<?=$this->lang->line('compounda-order_title')?>
					</div>
			</td>
		</tr>

	</table>
	<table id="recipe-header">
		<tr>
			<td><div class="recipe-header-company-name">
			<?php echo form_open($controller_name."/searchlenh", array('id'=>'seachlenh', 'class'=>'form-horizontal panel panel-default')); ?>
				<input type="text" name="compounda_order_uuid_text" value="" id="compounda_order_uuid_text" class="form-control input-sm ui-autocomplete-input" size="50" tabindex="1" autocomplete="off">
				<?php echo form_hidden('compounda_order_uuid',$item_info->compounda_order_uuid) ?>
			<?php echo form_close(); ?>
			</div></td>
		</tr>

	</table>
	<!-- #endregion recipe-header -->
	<!-- #endregion -->
	<!-- #region recipe-info-->
	<?php if($item_info->compounda_order_id > 0):?>
	<?php $_oList_lenh_can = $item_info->list_compound_a;?>
	<!-- #endregion -->
	<!-- #region recipe-body-kneader-a-->
	<table id="compounda-order-body-kneader-a">
				<tr class="compounda-order-header-body-kneader-a">
					<td rowspan="2">
						Lệnh sản xuất
					</td>
					<td rowspan="2">
						Mã định danh
					<td rowspan="2">
						Số lượng sản xuất
					</td>
					<td rowspan="2">
						Vật liệu
					</td>
					<td colspan="5">
					Khối lượng nguyên liệu (Kg)
Raw material volumes
					</td>
					<td colspan="3">
					Thời gian thực
Real time
					</td>
					<td colspan="1">
					Man
					</td>
					<td rowspan="2">
					Trạng thái
					</td>
					<td rowspan="2">
					<?=$this->lang->line('compounda_order_note')?>
					</td>
				</tr>
				<tr class="compounda-order-header-body-kneader-a">
					<td>
					Phôi 
					GW(g)
						</td>
						<td>
						Sử dụng
Used 

						</td>
						<td>
						TLg' mẽ Batch

						</td>
						<td>
						Thực tế Actual	
						</td>
						<td>
						Tồn cuối Balance
					</td>
					<td>
					Bắt đầu Begin 	 			

						</td>
						<td>
						Kết thúc Deadline 
						</td>
						<td>
						Giờ Cán Work time
						</td>
						<td>
						Ca Worker
					</td>
					
					
				</tr>
				<?php
					if(!empty($_oList_lenh_can))
					{
						foreach($_oList_lenh_can as $lenh)
						{ 
				?>

						<tr class="one">
							<td>
								<?=$lenh->order_number ?>
							</td>
							<td class="code">
								<?=$lenh->item_code ?>
							</td>
							<td class="number">
								<?=number_format($lenh->quantity) ?>
							</td>
							<td>
								<?=$lenh->ms ?>
							</td>
							<td class="number">
								<?=number_format($lenh->kl_phoi,0) ?>
							</td>
							<td class="number">
								<?=number_format($lenh->kl_su_dung,0) ?>
							</td>
							<td class="number">
								<?=number_format($lenh->kl_batch,0) ?>
							</td>
							<td class="number">
								<?=number_format($lenh->quantity_schedule) ?>
							</td>
							<td class="number">
								<?=number_format($lenh->kl_cuoi_ky) ?>
							</td>
							<td>
								<?=$lenh->start_at == 0 ? '': $lenh->start_at?>
							</td>
							<td>
								<?=$lenh->end_at == 0 ? '': $lenh->end_at?>
							</td>
							<td>
								<?=($lenh->end_at -  $lenh->start_at) == 0 ? '': ($lenh->end_at -  $lenh->start_at)?>
							</td>
							<td>
								<?=$lenh->phan_cong ?>
							</td>
							<td>
								<?php 
								$status = '';
								if($lenh->status == 4)
								{
									$status = ($lenh->running == 1) ? 'Đang cán' : 'Chờ cán';
								} else {
									$status = 'Chờ phê duyệt';
								}
								echo $status;
								?>
							</td>
							<td>
								<?=$lenh->note ?>
							</td>
						</tr>
						<tr class="two">
							<td>
							<?php $barcode = $this->barcode_lib->generate_receipt_barcode($lenh->order_number); ?>
									<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br/>
							</td>
							<td>
								
							</td>
							<td>
								
							</td>
							<td class="code">
								<?=" 0/{$lenh->so_luong_batch} Mẻ" ?>
							</td>
							<td>
								
							</td>
							<td>
								
							</td>
							<td class="number">
								<?="$lenh->so_luong_batch" ?>
							</td>
							<td>
								
							</td>
							<td>
								
							</td>
							<td>
								
							</td>
							<td>
								
							</td>
							<td>
													</td>
							<td>
								
							</td>
							<td>
								<?php echo "<a href='/cans/can/{$lenh->compounda_order_item_uuid}'>Bắt đầu cân</a>"; ?>
							</td>
							<td>
								
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

	$('#compounda_order_uuid_text').keypress(function (e) {
		if (e.which == 13) {
			$('#seachlenh').submit();
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