<?php $this->load->view("partial/header"); ?>
<style type="text/css">
	.number{
		text-align: right;
	}
	.code {
		text-align: center;
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

<?php if(!empty($item_info)): ?>
<div id="recipe_basic_info" width="100%">
	
	<!-- #endregion recipe-header -->
	<!-- #region recipe-title-->
	<table id="compounda-order-title">
		<tr>
			<td>
				<div class="compounda-order-title">
						Công đoạn cán luyện
					</div>
			</td>
		</tr>

	</table>
	<!-- #endregion -->
	<!-- #region recipe-info-->
	<table id="compounda-order-info">
		<tr>
			<td rowspan="3">
				<?php $qrcode = generate_qrcode($item_info->code); ?>
				<img src='data:image/png;base64,<?php echo $qrcode; ?>' /><br/>
			</td>
			
			<td>Bắt đầu: <b><?=$started_date?></b></td>
			<td>Kết thúc: <b><?=$completed_date?></b></td>
		</tr>
	</table>
	<!-- #endregion -->
	<!-- #region recipe-body-kneader-a-->
	<?php echo form_open($controller_name."/completed", ['id' => 'completed', 'class' => 'form-horizontal panel panel-default']); ?>
	<?=$form_qc_cpa?>
	<div class="form-group">
			<div class="col-md-4">
				<input id="batch_uuid" name="batch_uuid" value="<?=$item_info->compounda_order_item_completed_uuid?>" type="hidden" />
				<button id="button1id" name="button1id" class="btn btn-success">Hoàn thành</button>
			</div>
		</div>
	<?php echo form_close(); ?>
	
</div>
<?php endif; ?>
<script type="text/javascript">
	//validation and submit handling
	//(function($) {
        // You pass-in jQuery and then alias it with the $-sign
        // So your internal code doesn't change
    //})(jQuery);
	//$(document).ready(function()
	(function($)
	{
		function addCompletedButton(button, uuid, id,text) {
			var row = $(button).closest('tr');
			var readyBtn = $('<button/>', {
				id: 'completed_btn_' + id,
				'data-uuid': uuid,
				'data-id': id,
				name: 'completed_btn',
				class: 'completed_btn btn btn-success',
				text: text
			});
			// Thêm nút ready_btn vào cột cuối cùng của hàng
			row.find('td').last().append(readyBtn);
		}
		function addReadyButton(button, uuid, id,text) {
			var row = $(button).closest('tr');
			var readyBtn = $('<button/>', {
				id: 'ready_btn_' + id,
				'data-uuid': uuid,
				'data-id': id,
				name: 'ready_btn',
				class: 'ready_btn btn btn-success',
				text: text
			});
			// Thêm nút ready_btn vào cột cuối cùng của hàng
			row.find('td').last().append(readyBtn);
		}
		function updateStatusColumn(button, statusMessage) {
			// Tìm hàng cha của button
			var row = $(button).closest('tr');

			// Tìm cột trạng thái (cột thứ 2 từ cuối lên)
			var statusColumn = row.find('td').eq(-2);
			statusColumn.text(statusMessage);
		}
		$('button[name="exp_btn"]').click(function() {
			var uuid = $(this).data('uuid');
			var id = $(this).data('id');
			button = $(this);

			console.log(id);
			
			var csrf_ospos_v3 = csrf_token();
			var location_id = 0;
			

			$.ajax({
				method: "POST",
				url: "<?php echo site_url('compoundas/ajax_export_document')?>",
				data: { location_id: location_id, uuid:uuid ,csrf_ospos_v3: csrf_ospos_v3 },
				dataType: 'json'
				})
				.done(function( msg ) {
					if(msg.success == true)
					{
						
						updateStatusColumn(button,msg.status);
						button.hide();
					}else{
						$('#view_report_lens_category').html('<strong>Không tìm thấy báo cáo phù hợp, hãy thử lại</strong>');
					}

				});
		});

		// Sự kiện click cho button confirm_btn
		$('button[name="confirm_btn"]').click(function() {
			var uuid = $(this).data('uuid');
			var id = $(this).data('id');
			button = $(this);
			
			var csrf_ospos_v3 = csrf_token();
			var location_id = 0;
			
			console.log(id);

			$.ajax({
				method: "POST",
				url: "<?php echo site_url('compoundas/ajax_confirm_document')?>",
				data: { location_id: location_id, uuid:uuid ,csrf_ospos_v3: csrf_ospos_v3 },
				dataType: 'json'
				})
				.done(function( msg ) {
					if(msg.success == true)
					{
						updateStatusColumn(button,msg.status);
						addReadyButton(button,uuid,id,msg.text)
						button.hide();
					}else{
						$('#view_report_lens_category').html('<strong>Không tìm thấy báo cáo phù hợp, hãy thử lại</strong>');
					}

				});
		});

		// Sự kiện click cho button ready_btn
		$('button[name="ready_btn"]').click(function() {
			var uuid = $(this).data('uuid');
			var id = $(this).data('id');
			button = $(this);
			
			var csrf_ospos_v3 = csrf_token();
			var location_id = 0;
			
			console.log(id);

			$.ajax({
				method: "POST",
				url: "<?php echo site_url('compoundas/ajax_ready_document')?>",
				data: { location_id: location_id, uuid:uuid ,csrf_ospos_v3: csrf_ospos_v3 },
				dataType: 'json'
				})
				.done(function( msg ) {
					if(msg.success == true)
					{
						updateStatusColumn(button,msg.status);
						addCompletedButton(button,uuid,id,msg.text)
						button.hide();
					}else{
						$('#view_report_lens_category').html('<strong>Không tìm thấy báo cáo phù hợp, hãy thử lại</strong>');
					}

				});
		});
		
		
		$("#submit").click(function() {
			stay_open = false;
		});
	
	})(jQuery);
</script>

<?php $this->load->view("partial/footer"); ?>