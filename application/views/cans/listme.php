
<?php $this->load->view("partial/header"); ?>
<script src="/dist/jquery.number.min.js"></script>
<?php echo css_can(); ?>
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
			
		</tr>

		<tr class="code">
			<td>
				Tổng số mẻ: <?=$item_info->so_luong_batch?><br>
				Tông số đã cán: <?=$aCount_by_status['daLam']?><br>
				Tổng số đã QC đạt: <?=$aCount_by_status['daQCOK']?><br>
				Tổng số QC chưa đạt: <?=$aCount_by_status['qcNotOK']?><br>
				Tổng số chờ QC: <?=$aCount_by_status['choQC']?><br>
				<table>
					<?php foreach($doingBatches as $_oBatch): ?>
					<tr>
						<td>
							<?php $qrcode = generate_qrcode($_oBatch->code); ?>
							<img src='data:image/png;base64,<?php echo $qrcode; ?>' /><br/>
									<?=$_oBatch->code ?>
						</td>
						<td>
						<?php
                // Lấy trạng thái hiện tại của batch
							
					$colors = [
						'#2E7D32', // Nhóm 1 - Xanh lá cây (Bắt đầu, an toàn)
						'#1565C0', // Nhóm 2 - Xanh dương (Đang tiến hành)
						'#F9A825', // Nhóm 3 - Vàng (Cần chú ý, sắp hoàn thành)
						'#EF6C00', // Nhóm 4 - Cam (Giai đoạn quan trọng, kiểm tra)
						'#C62828'  // Nhóm 5 - Đỏ (Hoàn thành hoặc cảnh báo)
					];
					$weighing_count = $_oBatch->weighing_count; // Lấy số lần cân hiện tại
					$max_weighing = 5; // Số lần cân tối đa
		
					for ($i = 1; $i <= $max_weighing; $i++):
                    $disabled = ($i < $weighing_count) ? 'disabled' : ''; // Khóa các nút trước đó
                    $active = ($i == $weighing_count) ? 'active' : ''; // Chỉ nút tiếp theo sáng lên
                    $locked = ($i > $weighing_count) ? 'disabled' : ''; // Chặn các nút lớn hơn
                    $color = $colors[$i - 1]; // Lấy màu theo nhóm
            ?>
                <button 
                    class="weighing-btn btn-group-<?=$i?> <?= $active ?>" 
                    data-uuid="<?= $_oBatch->compounda_order_item_completed_uuid ?>" 
                    data-weighing="<?= $i ?>"
                    <?= $disabled ?> <?= $locked ?>
                >
                    Nhóm <?= $i ?>
                </button>
            <?php endfor; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</table>
			</td>
			<td class="code">
			<?php // Hiển thị thông tin recipe với mác nguyên liệu
								echo $recipe_info_;
								echo $recipe_body_A;
								echo $recipe_body_B;
							?>
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
								<?=$batch->status_text?>
							</td>
							<td rowspan="1">
								<?=$batch->button?>
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
	$(".weighing-btn").click(function () {
        let btn = $(this);
        let batchId = btn.data("uuid");
        let currentWeighing = btn.data("weighing");

      
        // Lấy trạng thái hiện tại (nút cuối cùng đã được nhấn trước đó)
        //let currentWeighing = parseInt(btn.closest("td").find("button[disabled]").last().data("weighing")) || 0;
        console.log("currentWeighing:", currentWeighing);

        // Chỉ cho phép nhấn nút tiếp theo
        /*if (newWeighing !== currentWeighing + 1) {
            alert("Bạn chỉ có thể nhấn vào nút tiếp theo!");
            return;
        }*/
		let newWeighing = currentWeighing + 1;
        // Gửi AJAX cập nhật trạng thái
        $.post("<?=base_url('cans/ajax_update_weighing')?>", { uuid: batchId, weighing_count: newWeighing }, function (response) {
            if (response.success) {
                // Cập nhật giao diện sau khi cập nhật thành công
                btn.prop("disabled", true).removeClass("active").addClass("inactive-btn");
				console.log(`Next: ${currentWeighing + 1}`);
				let nextBtn = btn.closest("td").find(`button[data-weighing="${currentWeighing + 1}"]`);
				console.log(nextBtn);
				if (nextBtn.length) {
					nextBtn.prop("disabled", false).removeClass("inactive-btn").addClass("active");
				} else {
					alert("Cân mẻ này đã hoàn thành!");
				}
            } else {
                alert("Lỗi cập nhật trạng thái!");
            }
        }, "json");
    });
		
	})(jQuery);
</script>

<?php $this->load->view("partial/footer"); ?>