<?php $this->load->view("partial/header"); ?>
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
<div id="search_tool">
	<div class="panel-info">
		Hãy nhập thông tin của lệnh cán 
	</div>
	<form method="get" action="compoundas/index">
		<div class="form-group">
			<label class="col-md-2 control-label" for="search">Mã Lệnh SX</label>
			<div class="col-md-4">
				<input id="search" name="search" class="form-control input-md" type="text">
				<input id="hddinput" name="hddinput" value="1" type="hidden" />
			</div>
			<div class="col-md-4">
				<button id="button1id" name="button1id" class="btn btn-success">Tìm</button>
			</div>
		</div>
		<?php if(!empty($message))
		{
			?>
			<div class="warning col-md-12" style="color: red;"><?php echo $message; ?></div>
			<?php
		}
		?>
	</form>
</div>
<?php if(!empty($item_info)): ?>
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
					<tr class="detail">
						<td></td>
						<td colspan="11">
							<?php if(!empty($item_a->export_documents)): ?>
								<table id="<?=$item_a->compounda_order_item_uuid?>">
								
									<?php foreach($item_a->export_documents as $exd_key=>$exd_value):?>
									<tr>
										<td>
											<?=$exd_key+1?>
										</td>
										<td>
										<?php $barcode = $this->barcode_lib->generate_receipt_barcode($exd_value->export_code); ?>
										<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br/>
										<?=$exd_value->export_code?>
										</td>
										<td>
											<?php if(!empty($exd_value->list_items)): ?>
											<table id="<?=$exd_value->export_code?>">
												<tr>
													<td><?=$this->lang->line('export_document_no')?></td>
													<td><?=$this->lang->line('export_document_mix')?></td>
													<td><?=$this->lang->line('export_document_weight')?></td>
													<td><?=$this->lang->line('export_document_uom_name')?></td>													
													
													<td rowspan="<?=count($exd_value->list_items)+1?>">
														<?php if($exd_value->status == 4): ?>
															<?php if($is_inventory): ?>
																<?=$this->lang->line('export_document_waiting_export_status')?>
															<?php elseif($is_worker): ?>
																<?=$this->lang->line('export_document_waiting_export_status')?>
															<?php endif;?>
														<?php elseif($exd_value->status == 5): ?>
															<?php if($is_inventory): ?>
																<?=$this->lang->line('export_document_waiting_confirm_status')?>
															<?php elseif($is_worker): ?>
																<?=$this->lang->line('export_document_waiting_confirm_status')?>
															<?php endif;?>
														
														<?php elseif($exd_value->status == 6): ?>
															<?php if($is_inventory): ?>
																<?=$this->lang->line('export_document_do_confirmed_status')?>
															<?php elseif($is_worker): ?>
																<?=$this->lang->line('export_document_ready_to_do_status')?>
															<?php endif;?>

														<?php elseif($exd_value->status == 7): ?>
															<?php if($is_inventory): ?>
																<?=$this->lang->line('export_document_doing_status')?>
															<?php elseif($is_worker): ?>
																<?=$this->lang->line('export_document_doing_status')?>
															<?php endif;?>

														<?php elseif($exd_value->status == 8): ?>
															<?php if($is_inventory): ?>
																<?=$this->lang->line('export_document_completed_status')?>
															<?php elseif($is_worker): ?>
																<?=$this->lang->line('export_document_completed_status')?>
															<?php endif;?>
																
														<?php endif; ?>
													</td>
													<td rowspan="<?=count($exd_value->list_items)+1?>">
														<?php if($exd_value->status == 4): ?>
																<?php if($is_inventory): ?>
																	<button id="exp_btn_<?=$exd_value->export_document_id?>" 
																		data-uuid="<?=$exd_value->export_document_uuid?>"
																		data-id="<?=$exd_value->export_document_id?>" name="exp_btn" class="exp_btn btn btn-success">
																		<?=$this->lang->line('export_document_do_export_btn')?>
																	</button>
																	
																<?php elseif($is_worker): ?>
																	
																<?php endif;?>
															<?php elseif($exd_value->status == 5): ?>
																<?php if($is_inventory): ?>
																	
																<?php elseif($is_worker): ?>
																	<button id="confirm_btn_<?=$exd_value->export_document_id?>" 
																		data-uuid="<?=$exd_value->export_document_uuid?>"
																		data-id="<?=$exd_value->export_document_id?>" name="confirm_btn" class="confirm_btn btn btn-success">
																		<?=$this->lang->line('export_document_do_confirm_btn')?>
																	</button>
																	
																<?php endif;?>
															
															<?php elseif($exd_value->status == 6): ?>
																<?php if($is_inventory): ?>
																	
																<?php elseif($is_worker): ?>
																	<button id="ready_btn_<?=$exd_value->export_document_id?>" 
																		data-uuid="<?=$exd_value->export_document_uuid?>"
																		data-id="<?=$exd_value->export_document_id?>" name="ready_btn" class="ready_btn btn btn-success">
																		<?=$this->lang->line('export_document_ready_to_do_btn')?>
																	</button>
																	
																<?php endif;?>

															<?php elseif($exd_value->status == 7): ?>
																<?php if($is_inventory): ?>
																	
																<?php elseif($is_worker): ?>																	
																	<button id="completed_btn_<?=$exd_value->export_document_id?>" 
																		data-uuid="<?=$exd_value->export_document_uuid?>"
																		data-id="<?=$exd_value->export_document_id?>" name="completed_btn" class="completed_btn btn btn-success">
																		<?=$this->lang->line('export_document_completed_btn')?>
																	</button>
																	
																<?php endif;?>

															<?php elseif($exd_value->status == 8): ?>
																<?php if($is_inventory): ?>
																	
																<?php elseif($is_worker): ?>
																	
																<?php endif;?>
																	
															<?php endif; ?>
													</td>
												</tr>
												<?php foreach($exd_value->list_items as $k=>$v): ?>
													<tr>
													<td><?=$k+1?></td>
													<td><?=$v->encode?></td>
													<td><?=$v->quantity?></td>
													<td><?=$v->uom_name?></td>
													
												</tr>
												<?php endforeach; ?>
											</table>
											<?php endif; ?>
										</td>
									</tr>
									<?php endforeach; ?>	
								</table>	
							<?php endif; ?>
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