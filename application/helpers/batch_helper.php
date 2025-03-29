<?php
if (!function_exists('css_recipe')) {
	function css_recipe()
	{
		$html = "<style type='text/css'>
		.approved {
			position: relative;
		}

		.approved::before {
			content: 'ĐÃ PHÊ DUYỆT';
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
	</style>";
		return $html;
	}

}

if (!function_exists('css_can')) {
	function css_can()
	{
		$html = <<<HTML
				<style type="text/css">
					.choLam { background-color: #FF9800 !important; color: white; } /* Cam - Chờ làm */
					.dangLam { background-color: #2196F3 !important; color: white; } /* Xanh dương - Đang làm */
					.choQC { background-color: #FFC107 !important; color: black; } /* Vàng - Chờ QC */
					.dangQC { background-color: #9C27B0 !important; color: white; } /* Tím - Đang QC */
					.qcNotOK { background-color: #F44336 !important; color: white; } /* Đỏ - QC không đạt */
					.daQCOK { background-color: #4CAF50 !important; color: white; } /* Xanh lá - Đã QC OK */
					.batDauCan { background-color: #795548 !important; color: white; } /* Nâu - Bắt đầu cân */
					.daLam { background-color: #616161 !important; color: white; } /* Xám - Đã hoàn thành */


					.btn-group-1,.btn-group-2,.btn-group-3,.btn-group-4,.btn-group-5  {
						padding: 10px 15px;
						border: none;
						cursor: not-allowed;
						opacity: 0.5; /* Mặc định làm mờ */
						transition: all 0.3s ease;
					}
					
					.btn-group-1.active,.btn-group-2.active,.btn-group-3.active,.btn-group-4.active,.btn-group-5.active {
						cursor: pointer;
						font-weight: bold;
						
						box-shadow: 0 0 10px rgba(255, 215, 0, 0.8); /* Ánh sáng vàng */
						transform: scale(1.05); /* Phóng to nhẹ */
					}
					
					
					.btn-group-1 { background-color: #2E7D32; color: white; }
					.btn-group-2 { background-color: #1565C0; color: white; }
					.btn-group-3 { background-color: #F9A825; color: black; }
					.btn-group-4 { background-color: #EF6C00; color: white; }
					.btn-group-5 { background-color: #C62828; color: white; }

					
					.btn-group-1.active { background-color: #1B5E20; box-shadow: 0px 0px 10px #1B5E20; border: 3px solid #EE0C0C; /* Viền vàng nổi bật */}
					.btn-group-2.active { background-color: #0D47A1; box-shadow: 0px 0px 10px #0D47A1; border: 3px solid #EE0C0C; /* Viền vàng nổi bật */}
					.btn-group-3.active { background-color: #F57F17; box-shadow: 0px 0px 10px #F57F17; border: 3px solid #EE0C0C; /* Viền vàng nổi bật */}
					.btn-group-4.active { background-color: #E65100; box-shadow: 0px 0px 10px #E65100; border: 3px solid #EE0C0C; /* Viền vàng nổi bật */}
					.btn-group-5.active { background-color: #B71C1C; box-shadow: 0px 0px 10px #B71C1C; border: 3px solid #FFD700; /* Viền vàng nổi bật */}

				



				

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
		
		HTML;
		return $html;
	}

}

if (!function_exists('transform_data1')) {
    function transform_data1($items)
    {
        if (empty($items)) return [];

        $statusText = [
            3 => "Chờ QC",
            4 => "Đang QC",
            5 => "QC Không đạt",
            6 => "QC đạt",
            7 => "Bắt đầu cán",
            8 => "Hoàn thành cán"
        ];

        $baseLink = base_url("qccans/detail/");

        $statusButton = [
            3 => fn($uuid) => "<a href='{$baseLink}{$uuid}'>Bắt đầu QC</a>",
            4 => fn($uuid) => "<a href='{$baseLink}{$uuid}'>Hoàn thành QC</a>",
        ];

        return array_map(function ($item) use ($statusText, $statusButton) {
            $status = $item->status ?? 0;
            $item->status_text = $statusText[$status] ?? "Không xác định";
            $item->button = $statusButton[$status]($item->compounda_order_item_completed_uuid) ?? "";
            return $item;
        }, $items);
    }
}

if (!function_exists('transform_data')) {
	/**
	 * step là thứ tự các bước, với
	 * step = ...
	 * step = 3: QC 
	 * step = 6: cán luyện
	 */
	function transform_data($items,$step = 3) {
		if (empty($items)) {
			return [];
		}
		switch ($step) {
			case 3:
				$_aStatusText = [
					1 => "",
					2 => "",
					3 => "Chờ QC",
					4 => "Đang QC",
					5 => "QC Không đạt",
					6 => "QC đạt",
					7 => "Bắt đầu cán",
					8 => "Hoàn thành cán"
				];
			
				$baseLink = base_url("qccans/detail/"); // Tối ưu base_url()
				$viewLinkRS = base_url("qccans/detail_rs/");
				$_aStatusButton = [
					1 => fn($uuid) => "",
					2 => fn($uuid) => "",
					3 => fn($uuid) => "<a class='btn btn-primary' href='{$baseLink}{$uuid}'>Bắt đầu QC</a>",
					4 => fn($uuid) => "<a class='btn btn-primary' href='{$baseLink}{$uuid}'>Hoàn thành QC</a>",
					5 => fn($uuid) => "<a class='btn btn-primary' href='{$viewLinkRS}{$uuid}'>Xem kết quả QC</a>",
					6 => fn($uuid) => "<a class='btn btn-primary' href='{$viewLinkRS}{$uuid}'>Xem kết quả QC</a>",
					7 => fn($uuid) => "<a class='btn btn-primary' href='{$viewLinkRS}{$uuid}'>Xem kết quả QC</a>",
					8 => fn($uuid) => "<a class='btn btn-primary' href='{$viewLinkRS}{$uuid}'>Xem kết quả QC</a>"
				];
				
				break;
			case 6:
				$_aStatusText = [
					1 => "",
					2 => "",
					3 => "",
					4 => "",
					5 => "QC chưa đạt",
					6 => "Chờ cán",
					7 => "Đang cán",
					8 => "Hoàn thành cán"
				];
			
				$baseLink = base_url("cpas/detail/"); // Tối ưu base_url()
				$viewLinkRS = base_url("cpas/detail_rs/");
				$NextLinkRS = base_url("cpas/detail_next/");
				$_aStatusButton = [
					1 => fn($uuid) => "",
					2 => fn($uuid) => "",
					3 => fn($uuid) => "",
					4 => fn($uuid) => "",
					5 => fn($uuid) => "",
					6 => fn($uuid) => "<a class='btn btn-primary' href='{$baseLink}{$uuid}'>Bắt đầu cán</a>",
					7 => fn($uuid) => "<a class='btn btn-primary' href='{$NextLinkRS}{$uuid}'>Xem thông tin</a>",
					8 => fn($uuid) => "<a class='btn btn-primary' href='{$viewLinkRS}{$uuid}'>Xem thông tin</a>"
				];			
				break;
			case 2:
				$_aStatusText = [
					1 => "Chờ cân",
					2 => "Đang cân",
					3 => "Cân xong",
					4 => "Đang QC",
					5 => "QC chưa đạt",
					6 => "QC đạt",
					7 => "Bắt đầu cán",
					8 => "Hoàn thành cán luyện"
				];
			
				$baseLink = base_url("cans/recan/"); // Tối ưu base_url()
				$viewLinkRS = base_url("cans/detail_rs/");
				$NextLinkRS = base_url("cans/detail_next/");
				$_aStatusButton = [
					1 => fn($uuid) => "",
					2 => fn($uuid) => "",
					3 => fn($uuid) => "",
					4 => fn($uuid) => "",
					5 => fn($uuid) => "<a class='btn btn-primary' href='{$baseLink}{$uuid}'>Bắt đầu cán</a>",
					6 => fn($uuid) => "",
					7 => fn($uuid) => "",
					8 => fn($uuid) => ""
				];	
				break;
			default:
				// Thực hiện nếu không khớp với bất kỳ case nào
		}
	
		foreach ($items as $item) {
			$status = $item->status ?? 0; // Tránh lỗi undefined property
	
			$item->status_text = $_aStatusText[$status] ?? "Không xác định";
			$item->button = isset($_aStatusButton[$status]) ? $_aStatusButton[$status]($item->compounda_order_item_completed_uuid) : "";
		}
	
		return $items;
	}

}

if (!function_exists('transform_batch_info')) {
    function transform_batch_info($item)
    {
        if (empty($item)) return [];

        $statusText = [
            3 => "Chờ QC",
            4 => "Đang QC",
            5 => "QC Không đạt",
            6 => "QC đạt",
            7 => "Bắt đầu cán",
            8 => "Hoàn thành cán"
        ];

        $baseLink = base_url("qccans/detail/");
		$viewLinkRS = base_url("qccans/detail_rs/");
        $statusButton = [
            3 => fn($uuid) => "<a href='{$baseLink}{$uuid}'>Bắt đầu QC</a>",
            4 => fn($uuid) => "<a href='{$baseLink}{$uuid}'>Hoàn thành QC</a>",
			5 => fn($uuid) => "<a href='{$viewLinkRS}{$uuid}'>Xem kết quả QC</a>",
			6 => fn($uuid) => "<a href='{$viewLinkRS}{$uuid}'>Xem kết quả QC</a>",
        ];

        if (!empty($item->qc_cpa_document)) {
            $item->qc_cpa_document->tieu_chi = json_decode($item->qc_cpa_document->tieu_chi ?? "[]", true);
            $item->qc_cpa_document->result = json_decode($item->qc_cpa_document->result ?? "[]", true);
        }

        return $item;
    }
}

if (!function_exists('transform_batch_info1')) {
	function transform_batch_info1($item) {
		if (empty($item)) {
			return [];
		}
	
		$_aStatusText = [
			3 => "Chờ QC",
			4 => "Đang QC",
			5 => "QC Không đạt",
			6 => "QC đạt",
			7 => "Bắt đầu cán",
			8 => "Hoàn thành cán"
		];
	
		$baseLink = base_url("qccans/detail/");
	
		$_aStatusButton = [
			3 => fn($uuid) => "<a href='{$baseLink}{$uuid}'>Bắt đầu QC</a>",
			4 => fn($uuid) => "<a href='{$baseLink}{$uuid}'>Hoàn thành QC</a>",
			5 => fn($uuid) => "",
			6 => fn($uuid) => "",
			7 => fn($uuid) => "",
			8 => fn($uuid) => ""
		];
	
		// Kiểm tra xem thuộc tính có tồn tại không trước khi xử lý
		if (!empty($item->qc_cpa_document)) {
			$item->qc_cpa_document->tieu_chi = json_decode($item->qc_cpa_document->tieu_chi ?? "[]",true);
	
			if (!empty($item->qc_cpa_document->result)) {
				$decodedResult = json_decode($item->qc_cpa_document->result, true);
				$item->qc_cpa_document->result = is_array($decodedResult) ? $decodedResult : [];
			}
		}
	
		return $item;
	}

}

if (!function_exists('form_qc_cpa')) {
	function form_qc_cpa($item, $grand = 3) {
		$CI =& get_instance();
	
		if (!isset($item['R']['kneader_a'], $item['R']['kneader_b'])) {
			return "<p>Không có dữ liệu</p>";
		}
	
		// Tạo tiêu đề bảng
		$html = <<<HTML
			<table id='recipe-body-kneader'>
				<tr><td colspan='6'><b>{$item['R']['kneader_a']}</b></td></tr>
				<tr class='recipe-header-body'>
				<td class="code">{$CI->lang->line('recipe_group')}</td>
					<td class="code">{$CI->lang->line('recipe_component_mix')}</td>
					<td class="code">{$CI->lang->line('recipe_unit')}</td>
					<td class="code">{$CI->lang->line('recipe_weight')}</td>
					<td class="code">{$CI->lang->line('recipe_tolerance')}</td>
					<td class="code">Lỗi</td>
				</tr>
		HTML;
	
		// Thêm nội dung cho từng nhóm
		$html .= build_qc_rows($item['A'] ?? [], "A", $grand);
		$html .= "<tr><td colspan='6'><b>{$item['R']['kneader_b']}</b></td></tr>";
		$html .= build_qc_rows($item['B'] ?? [], "B", $grand);
		$html .= "</table>";
	
		return $html;
	}
}
if (!function_exists('build_qc_rows')) {
	// Hàm tái sử dụng để xây dựng từng nhóm `A` hoặc `B`
	function build_qc_rows($items, $group, $grand) {
		if (empty($items)) return "";
	
		$html = "";
		foreach ($items as $item) {
			$item_mix = $grand == 5 ? "{$item['item_mix']} - {$item['normal_name']}" : $item['item_mix'];
			$checkbox = form_checkbox([
				'name' => "name_{$group}[]",
				'id' => "id_{$item['item_id']}",
				'value' => $item['item_id'],
				'checked' => false,
				'style' => 'margin:10px'
			]);
	
			$html .= <<<HTML
				<tr class='recipe-item-body-{$group}'>
					<td>{$item['item_group']}</td>
					<td>{$item_mix}</td>
					<td class="code">{$item['uom_name']}</td>
					<td class='number'>{$item['weight']}</td>
					<td>{$item['tolerace']}</td>
					<td class="code">{$checkbox}</td>
				</tr>
			HTML;
		}
		return $html;
	}
}


if (!function_exists('result_json'))
{
	function result_json($batch, $result_a, $result_b) {
		$batch = transform_batch_info($batch);
		
		if (!$batch || empty($batch->qc_cpa_document->tieu_chi)) {
			return json_encode([]);
		}
		$result_a = is_array($result_a) ? $result_a : [];
        $result_b = is_array($result_b) ? $result_b : [];
		$tieu_chi = $batch->qc_cpa_document->tieu_chi;
		$result_a = array_flip($result_a); // Tạo key lookup để tìm nhanh hơn
		$result_b = array_flip($result_b);
	
		$_aReturn = [
			'A' => process_qc_criteria($tieu_chi['A'] ?? [], $result_a),
			'B' => process_qc_criteria($tieu_chi['B'] ?? [], $result_b)
		];
	
		return json_encode($_aReturn);
	}
}
if (!function_exists('process_qc_criteria'))
{	/**
	** type = 1: hàm xử lý result_json
	*/
	function process_qc_criteria($criteriaList, $resultSet) {
		$result = [];
		foreach ($criteriaList as $item) {
			if (isset($resultSet[$item['item_id']])) {
				$item['qc_status'] = 5;
				$result[] = $item;
			}
		}
		return $result;
	}
}


if (!function_exists('process_qc_rs'))
{	/**
	** type = 2: hàm sử lý form_qc_cpa_rs
	*/
	function process_qc_rs($criteriaList, $resultSet) {
		
		$itemIds = array_column($resultSet, 'item_id');

		// Duyệt qua từng phần tử trong mảng 2
		foreach ($criteriaList as $item) {
			// Nếu item_id chưa có trong mảng 1 thì thêm vào
			if (!in_array($item['item_id'], $itemIds)) {
				$item['qc_status'] = 6;
				$resultSet[] = $item;
			}
		}
		return $resultSet;
	}
}

if (!function_exists('form_qc_cpa_rs')) {
	function form_qc_cpa_rs($batch, $grand = 3) {
		$CI =& get_instance();
		$item = $batch->qc_cpa_document->tieu_chi;
		$_aResuleQC = json_decode($batch->qc_cpa_document->results ?? [],true);
		if($_aResuleQC == null)
		{
			$result_a = [];
			$result_b = [];
		} else {
			$result_a = is_array($_aResuleQC['A']) ? $_aResuleQC['A']:[];
			$result_b = is_array($_aResuleQC['B']) ? $_aResuleQC['B']:[];
		}
		
		
		$item['A'] = process_qc_rs($item['A'],$result_a);
		$item['B'] = process_qc_rs($item['B'],$result_b);
		//var_dump($item['B']);die();

		if (!isset($item['R']['kneader_a'], $item['R']['kneader_b'])) {
			return "<p>Không có dữ liệu</p>";
		}
	
		// Tạo tiêu đề bảng
		$html = <<<HTML
			<table id='recipe-body-kneader'>
				<tr><td colspan='6'><b>{$item['R']['kneader_a']}</b></td></tr>
				<tr class='recipe-header-body'>
					<td class="code">{$CI->lang->line('recipe_group')}</td>
					<td class="code">{$CI->lang->line('recipe_component_mix')}</td>
					<td class="code">{$CI->lang->line('recipe_unit')}</td>
					<td class="code">{$CI->lang->line('recipe_weight')}</td>
					<td class="code">{$CI->lang->line('recipe_tolerance')}</td>
					<td class="code">Kết quả</td>
				</tr>
		HTML;
	
		// Thêm nội dung cho từng nhóm
		$html .= build_qc_rows_rs($item['A'] ?? [], "A", $grand);
		$html .= "<tr><td colspan='6'><b>{$item['R']['kneader_b']}</b></td></tr>";
		$html .= build_qc_rows_rs($item['B'] ?? [], "B", $grand);
		$html .= "</table>";
	
		return $html;
	}
}

if (!function_exists('build_qc_rows_rs')) {
	// Hàm tái sử dụng để xây dựng từng nhóm `A` hoặc `B`
	function build_qc_rows_rs($items, $group, $grand) {
		if (empty($items)) return "";
	
		$html = "";
		

		foreach ($items as $item) {
			$item_mix = $grand == 5 ? "{$item['item_mix']} - {$item['normal_name']}" : $item['item_mix'];
			$checkbox = form_checkbox([
				'name' => "name_{$group}[]",
				'id' => "id_{$item['item_id']}",
				'value' => $item['item_id'],
				'checked' => false,
				'style' => 'margin:10px'
			]);
			$data_status = $item['qc_status']==5 ? 'error':'ok';
			$html .= <<<HTML
				<tr class='recipe-item-body-{$group}'>
					<td>{$item['item_group']}</td>
					<td>{$item_mix}</td>
					<td class='code'>{$item['uom_name']}</td>
					<td class='number'>{$item['weight']}</td>
					<td>{$item['tolerace']}</td>
					<td class='code status' data-status="{$data_status}"></td>
				</tr>
			HTML;
		}
		return $html;
	}
}

if (!function_exists('build_batch_block_info')) {
	// Hiển thị thông tin block of Batch info
	function build_batch_block_info($batch) {
		$item_info = $batch;
		$started_date = $item_info->thoi_gian_can_luyen_bat_dau;
		$completed_date = $item_info->thoi_gian_can_luyen_ket_thuc;
		$qrcode = generate_qrcode($item_info->code);
		$checker_name = $item_info->qc_cpa_document->qc_name;

		
		if($started_date == 0)
		{
			$started_date = 'Chưa bắt đầu';
		} else {
			$started_date = date('d/m/Y h:i',$started_date);
		}

		if($completed_date == 0)
		{
			$completed_date = 'Đang cán';
		} else {
			$completed_date = date('d/m/Y h:i',$completed_date);
		}


		$html = <<<HTML
				<table id='block-batch-info'>
				<tr>
					<td class='code'>
						<img src="data:image/png;base64,{$qrcode}" /><br/>{$item_info->code}
					</td>
					<td>
						<span>Số LSX: {$item_info->lenh->order_number}</span><br>
						<span>Mã sản phẩm: {$item_info->lenh->item_code}</span><br>
						<span>Người cân: {$item_info->nguoi_can_name}</span><br>
						<span>Người kiểm tra: {$checker_name}</span><br>
						<span>Người cán luyện: {$item_info->nguoi_can_luyen_name}</span><br>
					</td>
					<td>
						Bắt đầu cán luyện: <b>{$started_date}</b><br/>
						Kết thúc cán luyện: <b>{$completed_date}</b>
					</td>
				</tr>
				</table>
				HTML;

		return $html;
	}
}