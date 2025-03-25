
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

.scan-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
}

.scan-wrapper input {
    flex-grow: 1;
    max-width: 300px;
}

.scan-wrapper button {
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 5px;
}

.scan-wrapper button svg {
    width: 28px;
    height: 28px;
    color: #007bff;
    transition: transform 0.2s ease-in-out;
}

.scan-wrapper button:hover svg {
    transform: scale(1.1);
    color: #0056b3;
}


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
				<div class="scan-wrapper">
					<input type="text" name="code" value="" id="code" class="form-control input-sm ui-autocomplete-input" size="50" tabindex="1" autocomplete="off">
					<button id="start-scan">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
							<path d="M5 3v2H3v2h2v2H3v2h2v2H3v2h2v2H3v2h2v2h2v-2h2v2h2v-2h2v2h2v-2h2v-2h-2v-2h2v-2h-2v-2h2V9h-2V7h2V5h-2V3h-2v2h-2V3h-2v2h-2V3H9v2H7V3H5zm4 4h2v2H9V7zm4 0h2v2h-2V7zm-4 4h2v2H9v-2zm4 0h2v2h-2v-2zm-4 4h2v2H9v-2zm4 0h2v2h-2v-2zm4-8h2v2h-2V7zm0 4h2v2h-2v-2zm0 4h2v2h-2v-2z"/>
						</svg>
					</button>
				</div>
				<?php echo form_hidden('compounda_order_item_uuid',$item_info->compounda_order_item_uuid) ?>
			<?php echo form_close(); ?>
			<video id="scanner" style="display: none; width: 100%;"></video>
			</div></td>
		</tr>

	</table>
	<!-- #endregion recipe-header -->
	<!-- #endregion -->
	<!-- #region recipe-info-->
	<?php //var_dump($item_info); die(); 
	if($item_info->compounda_order_id > 0):?>
	<?php $_oList_batchs = $item_info->list_batchs;?>
	<?php //$_oList_lenh_can = $item_info->list_compound_a;?>
	<!-- #endregion -->
	<!-- #region recipe-body-kneader-a-->
	<table id="compounda-order-body-kneader-ab">
		<thead>
		<tr class="compounda-order-header-body-kneader-a">
			<td >
				
			</td>
					
			<td >
				Thông tin đơn pha chế
			</td>
			<td >
				
			</td>
		</tr>

		<tr class="code">
			<td>
				Tổng số mẻ: <?=$item_info->so_luong_batch?><br>
				Tông số đã cán: <?=$aCount_by_status['daLam']?><br>
				Tổng số đã QC đạt: <?=$aCount_by_status['daQCOK']?><br>
				Tổng số QC chưa đạt: <?=$aCount_by_status['qcNotOK']?><br>
				Tổng số chờ QC: <?=$aCount_by_status['choQC']?><br>

				Chờ làm	🟠 Cam (#FF9800) - Đang đợi sản xuất<br>
				Đang làm	🔵 Xanh dương (#2196F3) - Đang xử lý<br>
				Chờ QC	🟡 Vàng (#FFC107) - Cần kiểm tra<br>
				Đang QC	🟣 Tím (#9C27B0) - Đang kiểm tra chất lượng<br>
				QC không OK	🔴 Đỏ (#F44336) - Lỗi, cần kiểm tra lại<br>
				Đã QC OK	🟢 Xanh lá (#4CAF50) - Đạt tiêu chuẩn<br>
				Bắt đầu cán	🟤 Nâu (#795548) - Giai đoạn cân đo<br>
				Đã làm	⚫ Xám đậm (#616161) - Hoàn thành<br>
			</td>
			<td class="code">
			<?php // Hiển thị thông tin recipe với mác nguyên liệu
								echo $recipe_info_;
								echo $recipe_body_A;
								echo $recipe_body_B;
							?>
			</td>
			<td>
			</td>
		</tr>
		</thead>
		
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
		</table>
		<table id="compounda-order-body-kneader-a">
		<tbody>
			<?php
					if(!empty($_oList_batchs))
					{
						foreach($_oList_batchs as $batch)
						{ //var_dump($batch);die();
				?>

						<tr class="one <?=$statusClass[$batch->status]?>" data-status="<?=$statusClass[$batch->status]?>">
							<td class="code">
							<?php $qrcode = generate_qrcode($batch->code); ?>
									<img src='data:image/png;base64,<?php echo $qrcode; ?>' /><br/>
									<?=$batch->code ?>
							</td>
							
							<td rowspan="1">
								<?=$statusText[$batch->status]?>
							</td>
							<td rowspan="1">
							</td>
						</tr>
					
				<?php
						}
						

					}
				
				?>
		</tbody>		
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
<audio id="beep-sound" src="images/beep.mp3"></audio>
<script src="https://unpkg.com/@zxing/library@latest"></script>
<script type="text/javascript">

document.addEventListener("DOMContentLoaded", async function () {
    const videoElement = document.getElementById("scanner");
    const barcodeInput = document.getElementById("code");
    const startScanButton = document.getElementById("start-scan");
    const beepSound = document.getElementById("beep-sound");
    const formElement = document.getElementById("seachcan");
    const codeReader = new ZXing.BrowserMultiFormatReader();

    let scanning = false; // Biến để kiểm tra trạng thái quét

	barcodeInput.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Không cho Enter bấm nút quét QR
            formElement.submit(); // Nếu muốn cho phép Enter submit form
        }
    });

    startScanButton.addEventListener("click", async () => {
		event.preventDefault(); // Chặn form submit ngay
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
		rows.forEach(row => {
    console.log($(row).data("status")); // Kiểm tra giá trị data-status của từng dòng
});

		rows.sort(function(a, b) {
			let statusOrder = {
				"choLam": 1,
				"dangLam": 2,
				"choQC": 3,
				"dangQC": 4,
				"qcNotOK": 5,
				"daQCOK": 6,
				"batDauCan": 7,
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