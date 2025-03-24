<?php

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

function recipe_header()
{
	$CI =& get_instance();
	$html = "<table id='recipe-header'>
				<tr>
					<td><div class='recipe-header-company-name'>{$CI->config->item('company')}</div></td>
					<td>
					<div class='recipe-header-company-info'>
						<p>{$CI->config->item('address')}</p>
						<p>Tel : (251) 352 5199 / 352 5200  _ Fax:(251) 352 5222</p>
					</div>
					</td>
				</tr>
			</table>";
	return $html;		
}

function recipe_title($sApproved)
{
	$CI =& get_instance();
	$html = "<table id='recipe-title'>
		<tr>
			<td>
				<div class='recipe-title {$sApproved} '>
						{$CI->lang->line('recipe_title')}
					</div>
			</td>
		</tr>
	</table>";
	return $html;	
}

function recipe_info($item_info)
{
	$CI =& get_instance();
	$barcode = $CI->barcode_lib->generate_receipt_barcode($item_info->name);
	$form_hidden_uuid = form_hidden('uuid',$item_info->recipe_uuid);
	$date_issued = date('d/m/Y',$item_info->date_issued);
	$html = "<table id='recipe-info'>
		<tr>
			<td rowspan='3'>
				
				<img src='data:image/png;base64,{$barcode}' /><br/>{$form_hidden_uuid}
			</td>
			<td>{$CI->lang->line('recipes_master_batch')}:</td>
			<td><b>{$item_info->name}</b></td>
			<td>{$CI->lang->line('recipes_grade_of_standard')}:</td>
			<td><b>{$item_info->grade_of_standard}</b></td>
		</tr>
		<tr>
			
			<td>{$CI->lang->line('recipes_date_issued')}:</td>
			<td><b>{$date_issued}</b></td>
			<td>{$CI->lang->line('recipes_certificate_no')}:</td>
			<td><b>{$item_info->certificate_no}</b></td>
		</tr>
		<tr>
			
			<td>{$CI->lang->line('recipe_product_code')}:</td>
			<td colspan='3'><b>N/A</b></td>
			
		</tr>
	</table>";
	return $html;
}

function recipe_body_A($item_info, $Items,$grand=1)
{
	$arrItem_as = $Items;
	$CI =& get_instance();
	$html = "<table id='recipe-header-kneader-a'>
			<tr>
				<td colspan='2'>
					{$item_info->kneader_a}
				</td>
				<td>
					{$CI->lang->line('recipe_processing_time')}:
				</td>
				<td>
					{$item_info->processing_time_a} phút
				</td>
				<td>
					{$CI->lang->line('recipe_weight')}:
				</td>
				<td>
					{$item_info->weight_a} Kg
				</td>	
			<tr>
		</table>
		<table id='recipe-body-kneader-a'>
			<tr class='recipe-header-body-kneader-a'>
				<td>
					{$CI->lang->line('recipe_group')}
				</td>
				<td>
					{$CI->lang->line('recipe_component_mix')}
				</td>
				<td>
					{$CI->lang->line('recipe_unit')}
				</td>
				<td>
					{$CI->lang->line('recipe_weight')}
				</td>
				<td>
					{$CI->lang->line('recipe_tolerance')}
				</td>
				<td>
					{$CI->lang->line('recipe_contains_percentage')}
				</td>
			</tr>";
			if(!empty($arrItem_as)):
				foreach($arrItem_as as $item_a):
					//var_dump($item_a);die();
					$_item_mix = '';
					$grand == 5 ? $_item_mix = " - {$item_a->normal_name}": $_item_mix = '';
					$_item_mix = "{$item_a->item_mix}{$_item_mix}";
					$html = "$html
				<tr class='recipe-item-body-kneader-a'>
					<td>
						{$item_a->item_group}
					</td>
					<td>
						{$_item_mix}
					</td>
					<td>
					{$item_a->uom_name}
					</td>
					<td>
					{$item_a->weight}
					</td>
					<td>
					{$item_a->tolerace}
					</td>
					<td>
					N/A
					</td>
				</tr>";
				endforeach;
			endif;
			$html = "$html </table>";
			return $html;
}
function recipe_body_B($item_info,$Items,$grand = 1)
{
	$CI =& get_instance();
	$arrItem_bs = $Items;
	$html = "<table id='recipe-header-kneader-b'>
		<tr>
			<td colspan='2'>
				{$item_info->kneader_b}
			</td>
			<td>
				{$CI->lang->line('recipe_processing_time')}:
			</td>
			<td>
				{$item_info->processing_time_b} phút
			</td>
			<td>
				{$CI->lang->line('recipe_weight')}:
			</td>
			<td>
				{$item_info->weight_b} Kg
			</td>	
		<tr>
	</table>

	<table id='recipe-body-kneader-b'>
				<tr class='recipe-header-body-kneader-b'>
					<td>
						{$CI->lang->line('recipe_group')}
					</td>
					<td>
					{$CI->lang->line('recipe_component_mix')}
					</td>
					<td>
					{$CI->lang->line('recipe_unit')}
					</td>
					<td>
					{$CI->lang->line('recipe_weight')}
					</td>
					<td>
					{$CI->lang->line('recipe_tolerance')}
					</td>
					<td>
					{$CI->lang->line('recipe_contains_percentage')}
					</td>
				</tr>";
				if(!empty($arrItem_bs)):
					foreach($arrItem_bs as $item_b):
					$_item_mix = '';
					$grand == 5 ? $_item_mix = " - {$item_b->normal_name}": $_item_mix = '';
					$_item_mix = "{$item_b->item_mix}{$_item_mix}";
					$html = "$html <tr class='recipe-item-body-kneader-b'>
						<td>
							{$item_b->item_group}
						</td>
						<td>
						{$_item_mix}
						</td>
						<td>
						{$item_b->uom_name}
						</td>
						<td>
						{$item_b->weight}
						</td>
						<td>
						{$item_b->tolerace}
						</td>
						<td>
						N/A
						</td>
					</tr>";
					endforeach;
				endif;
				$html = "$html </table>";
				return $html;
}
function form_recipe($isApproved)
{
	

	
}
