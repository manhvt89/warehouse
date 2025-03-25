
<?php $this->load->view("partial/header"); ?>
<script src="/dist/jquery.number.min.js"></script>
<style type="text/css">
#compounda-order-body-kneader-a {
    width: 100%;
    border-collapse: collapse;
}

#compounda-order-body-kneader-a .two {
    display: flex;
    justify-content: space-between;
}

#compounda-order-body-kneader-a .two td {
    width: 50%; /* Mỗi hàng có 2 QR Code */
    padding: 5px;
    border: 1px solid #ccc;
    text-align: center;
}

#compounda-order-body-kneader-a img {
    width: 80px; /* Chỉnh kích thước QR Code */
    height: 80px;
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

	.print-btn {
    background-color: #007bff;
    color: white;
    font-size: 14px;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
    transition: background 0.3s, transform 0.2s;
}
.print-btn:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}
@media print {
    .print-btn {
        display: none;
    }

		body * {
            visibility: hidden;
        }
		body {
        width: 21cm;
        height: 29.7cm;
        margin: 0;
        padding: 1cm;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    td, th {
        border: 1px solid black;
        padding: 5px;
    }
    button {
        display: none; /* Ẩn nút in khi in */
    }
		#compounda-order-body-kneader-a {
        border: none !important; /* Loại bỏ viền bảng ngoài */
    }

    #compounda-order-body-kneader-a td,
    #compounda-order-body-kneader-a th {
        border: none !important; /* Loại bỏ viền ô trong bảng ngoài */
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
<button class="print-btn" onclick="printContent()">🖨️ In nội dung</button>
<div id="recipe_basic_info" width="100%">

	<!-- #region recipe-info-->
	<?php if($item_info->compounda_order_item_id > 0):?>
	<?php $_oList_batchs = $item_info->list_batchs;?>
	<!-- #endregion -->
	<!-- #region recipe-body-kneader-a-->
	<table id="compounda-order-body-kneader-a">
    <?php
    if (!empty($_oList_batchs)) {
        $batch_count = count($_oList_batchs);
        for ($i = 0; $i < $batch_count; $i += 2) { ?>
            <tr class="two">
                <!-- QR Code 1 -->
                <td class="code">
                    <table id="tag-cpa-<?= $_oList_batchs[$i]->code ?>">
                        <tr>
                            <td colspan="2" class="code"> 
                                <span>THẺ COMPOUND - A</span><br/>
                                <span>CARBON MASTER BATCH (CMB)</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Mác liệu: <?= $_oList_batchs[$i]->ms ?></td>
                            <td rowspan="2">
                                <?php $qrcode1 = generate_qrcode($_oList_batchs[$i]->code); ?>
                                <img src='data:image/png;base64,<?= $qrcode1; ?>' /><br/>
                                <?= $_oList_batchs[$i]->code ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Số LSX: <?= $item_info->order_number ?></td>
                        </tr>
                    </table>
                </td>

                <!-- QR Code 2 (Nếu còn dữ liệu) -->
                <?php if (isset($_oList_batchs[$i + 1])) { ?>
                    <td class="code">
                        <table id="tag-cpa-<?= $_oList_batchs[$i + 1]->code ?>">
                            <tr>
                                <td colspan="2" class="code"> 
                                    <span>THẺ COMPOUND - A</span><br/>
                                    <span>CARBON MASTER BATCH (CMB)</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Mác liệu: <?= $_oList_batchs[$i + 1]->ms ?></td>
                                <td rowspan="2">
                                    <?php $qrcode2 = generate_qrcode($_oList_batchs[$i + 1]->code); ?>
                                    <img src='data:image/png;base64,<?= $qrcode2; ?>' /><br/>
                                    <?= $_oList_batchs[$i + 1]->code ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Số LSX: <?= $item_info->order_number ?></td>
                            </tr>
                        </table>
                    </td>
                <?php } else { ?>
                    <td class="code"></td> <!-- Cột trống nếu không có QR thứ 2 -->
                <?php } ?>
            </tr>
    <?php }
    } ?>
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
	function printContent() {
			
			window.print();
			
		}
	(function($)
	{
		

		$("#submit").click(function() {
			stay_open = false;
		});
	
	})(jQuery);
</script>

<?php $this->load->view("partial/footer"); ?>