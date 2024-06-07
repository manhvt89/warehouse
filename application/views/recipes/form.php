<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<table id="recipe_basic_info">
	<tr>
		<td>
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
		</td>
	</tr>
	<tr>
		<td>
			<div class="recipe-title">
					<?=$this->lang->line('recipe_title')?>
				</div>
		</td>
	</tr>
	<tr>
		<td>
			<table id="recipe-info">
				<tr>
					<td rowspan="3">
						QRCODE
					</td>
					<td><?=$this->lang->line('recipes_master_batch')?>:</td>
					<td><b><?=$item_info->name?></b></td>
					<td><?=$this->lang->line('recipes_grade_of_standard')?>:</td>
					<td><b><?=$item_info->grade_of_standard?></b></td>
				</tr>
				<tr>
					
					<td><?=$this->lang->line('recipes_date_issued')?>:</td>
					<td><b><?=date('d/m/Y',$item_info->date_issued)?></b></td>
					<td><?=$this->lang->line('recipes_certificate_no')?>:</td>
					<td><b><?=$item_info->certificate_no?></b></td>
				</tr>
				<tr>
					
					<td><?=$this->lang->line('recipe_product_code')?>:</td>
					<td colspan="3"><b>N/A</b></td>
					
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="recipe-header-kneader-a">
				<tr>
					<td colspan="2">
						<?=$item_info->kneader_a?>
					</td>
					<td>
						<?=$this->lang->line('recipe_processing_time')?>:
					</td>
					<td>
						<?=$item_info->processing_time_a?>
					</td>
					<td>
						<?=$this->lang->line('recipe_weight')?>:
					</td>
					<td>
						<?=$item_info->weight_a?>
					</td>	
				<tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="recipe-body-kneader-a">
				<tr class="recipe-header-body-kneader-a">
					<td>
						<?=$this->lang->line('recipe_group')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_component_mix')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_unit')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_weight')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_tolerance')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_contains_percentage')?>
					</td>
				</tr>
				<?php if(!empty($arrItem_as)): ?>
					<?php foreach($arrItem_as as $item_a): ?>
					<tr class="recipe-item-body-kneader-a">
						<td>
							<?=$item_a->item_group?>
						</td>
						<td>
						<?=$item_a->item_mix?>
						</td>
						<td>
						<?=$item_a->uom_name?>
						</td>
						<td>
						<?=$item_a->weight?>
						</td>
						<td>
						<?php echo $item_a->tolerace;?>
						</td>
						<td>
						N/A
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id="recipe-header-kneader-b">
				<tr>
					<td colspan="2">
						<?=$item_info->kneader_b?>
					</td>
					<td>
						<?=$this->lang->line('recipe_processing_time')?>:
					</td>
					<td>
						<?=$item_info->processing_time_b?>
					</td>
					<td>
						<?=$this->lang->line('recipe_weight')?>:
					</td>
					<td>
						<?=$item_info->weight_b?>
					</td>	
				<tr>
			</table>
		</td>
	</tr>

	<tr>
		<td>
			<table id="recipe-body-kneader-b">
				<tr class="recipe-header-body-kneader-b">
					<td>
						<?=$this->lang->line('recipe_group')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_component_mix')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_unit')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_weight')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_tolerance')?>
					</td>
					<td>
					<?=$this->lang->line('recipe_contains_percentage')?>
					</td>
				</tr>
				<?php if(!empty($arrItem_bs)): ?>
					<?php foreach($arrItem_bs as $item_b): ?>
					<tr class="recipe-item-body-kneader-b">
						<td>
							<?=$item_b->item_group?>
						</td>
						<td>
						<?=$item_b->item_mix?>
						</td>
						<td>
						<?=$item_b->uom_name?>
						</td>
						<td>
						<?=$item_b->weight?>
						</td>
						<td>
						<?php echo $item_a->tolerace;?>
						</td>
						<td>
						N/A
						</td>
					</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</td>
	</tr>
</table>

