
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

	<!-- #region recipe-info-->
	<?php if($item_info->compounda_order_item_id > 0):?>
	<table id="compounda-order-info">
	
		<tr>
			<td class="code">
				<?php $barcode = $this->barcode_lib->generate_receipt_barcode($item_info->order_number); ?>
				<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br/>
			</td>
		</tr>
	
	</table>
	<?php $_oList_batchs = $item_info->list_batchs;?>
	<!-- #endregion -->
	<!-- #region recipe-body-kneader-a-->
	<table id="compounda-order-body-kneader-a">
				
				<?php
					if(!empty($_oList_batchs))
					{
						foreach($_oList_batchs as $batch)
						{ 
				?>
						<tr class="two">
							<td class="code">
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
					Lệnh cán luyện không tồn tại!
				</td>

			</tr>
		</table>
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

<?php $this->load->view("partial/footer"); ?>