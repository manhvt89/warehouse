
<?php $this->load->view("partial/header"); ?>
<script src="/dist/jquery.number.min.js"></script>
<style type="text/css">
		.choLam { background-color: #FF9800 !important; color: white; } /* Cam - Chờ làm */
.dangLam { background-color: #2196F3 !important; color: white; } /* Xanh dương - Đang làm */
.choQC { background-color: #FFC107 !important; color: black; } /* Vàng - Chờ QC */
.dangQC { background-color: #9C27B0 !important; color: white; } /* Tím - Đang QC */
.qcNotOK { background-color: #F44336 !important; color: white; } /* Đỏ - QC không đạt */
.daQCOK { background-color: #4CAF50 !important; color: white; } /* Xanh lá - Đã QC OK */
.batDauCan { background-color: #795548 !important; color: white; } /* Nâu - Bắt đầu cân */
.daLam { background-color: #616161 !important; color: white; } /* Xám - Đã hoàn thành */
	
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
			<?php echo form_open($controller_name."/seachbatch", ['id' => 'seachcan', 'class' => 'form-horizontal panel panel-default']); ?>
				<input type="text" name="code" value="" id="code" class="form-control input-sm ui-autocomplete-input" size="50" tabindex="1" autocomplete="off">
			<?php echo form_close(); ?>
				<button id="start-scan">📷 Quét Barcode</button>
    			<video id="scanner" style="display: none; width: 100%;"></video>
			</div></td>
		</tr>

	</table>
	<!-- #endregion recipe-header -->
	<!-- #endregion -->
	<!-- #region recipe-info-->
	<?php if(!empty($aoListBatchs)):?>
	<?php $_oList_batchs = $aoListBatchs;?>
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
					</td>
				</tr>
				
				<?php
					if(!empty($_oList_batchs))
					{
						foreach($_oList_batchs as $batch)
						{ //var_dump($batch);die();
				?>

						<tr class="one <?=$statusClass[$batch->status]?>" data-status="<?=$statusClass[$batch->status]?>">
							<td class="code">
							<?php $barcode_code = $this->barcode_lib->generate_receipt_barcode($batch->code); ?>
									<img src='data:image/png;base64,<?php echo $barcode_code; ?>' /><br/>
									<?=$batch->code ?>
							</td>
							
							<td>
								<?php // Hiển thị thông tin recipe với mác nguyên liệu
									echo $batch->status_text
								?>	
							</td>
							<td>
								<?php echo $batch->button ?>
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
<!-- Âm thanh beep -->
<audio id="beep-sound" src="images/beep.mp3"></audio>
<script src="https://unpkg.com/@zxing/library@latest"></script>

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
	document.addEventListener("DOMContentLoaded", async function () {
    const videoElement = document.getElementById("scanner");
    const barcodeInput = document.getElementById("code");
    const startScanButton = document.getElementById("start-scan");
    const beepSound = document.getElementById("beep-sound");
    const formElement = document.getElementById("seachcan");
    const codeReader = new ZXing.BrowserMultiFormatReader();

    let scanning = false; // Biến để kiểm tra trạng thái quét

    startScanButton.addEventListener("click", async () => {
        if (scanning) {
            stopScanning();
            return;
        }

        try {
            scanning = true;
            videoElement.style.display = "block"; // Hiển thị camera

            await codeReader.decodeFromVideoDevice(null, videoElement, (result, err) => {
                if (result) {
                    beepSound.play(); // Phát âm thanh beep
                    barcodeInput.value = result.text; // Điền vào input

                    // Tự động submit form
                    formElement.submit();

                    stopScanning();
                }
            }, {
                video: { facingMode: "environment", width: 1280, height: 720 } // Chỉ định camera sau
            });

        } catch (error) {
            console.error("Lỗi khi mở camera:", error);
            alert("Không thể mở camera. Kiểm tra quyền truy cập!");
            scanning = false;
        }
    });

    function stopScanning() {
        scanning = false;
        codeReader.reset(); // Reset camera
        videoElement.style.display = "none"; // Ẩn camera
    }
});


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
		
		let $tableBody = $("#compounda-order-body-kneader-a tbody");

		// **1. Lấy danh sách tất cả các dòng và sắp xếp**
		let rows = $tableBody.find("tr.one").get();
		console.log(rows);
		

			rows.sort(function(a, b) {
				let statusOrder = {
					"choLam": 1,
					"dangLam": 2,
					"choQC": 3,
					"dangQC": 2,
					"qcNotOK": 5,
					"daQCOK": 6,
					"batDauCan": 2,
					"daLam": 8,

				};

				let statusA = $(a).data("status");
				let statusB = $(b).data("status");

				return statusOrder[statusA] - statusOrder[statusB];
			});

			// **2. Đưa lại các dòng vào bảng theo thứ tự đã sắp xếp**
			$.each(rows, function(index, row) {
				$tableBody.append(row);
			});

			// **3. Thêm màu sắc theo trạng thái**
			$tableBody.find("tr.one").each(function() {
				let $row = $(this);
				let status = $row.data("status");

				$row.addClass(status);
			});
	
	})(jQuery);
</script>

<?php $this->load->view("partial/footer"); ?>