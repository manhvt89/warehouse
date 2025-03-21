<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
$(document).ready(function()
{
	<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

	table_support.init({
		resource: '<?php echo site_url($controller_name);?>',
		headers: <?php echo $table_headers; ?>,
		pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
		uniqueId: 'people.person_id',
		enableActions: function()
		{
			<?php if ($this->Employee->has_grant($controller_name.'_email')) {?>
			var email_disabled = $("td input:checkbox:checked").parents("tr").find("td a[href^='mailto:']").length == 0;
			$("#email").prop('disabled', email_disabled);
			<?php } ?>
		}
	});

	<?php if ($this->Employee->has_grant($controller_name.'_email')) {?>
	$("#email").click(function(evvent)
	{
		var recipients = $.map($("tr.selected a[href^='mailto:']"), function(element)
		{
			return $(element).attr('href').replace(/^mailto:/, '');
		});
		location.href = "mailto:" + recipients.join(",");
	});
	<?php } ?>

});

</script>

<div id="title_bar" class="btn-toolbar">
	<?php
	if ($controller_name == 'customers')
	{
		if ($this->Employee->has_grant($controller_name.'_excel_import')) {
			?>
				<button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/excel_import"); ?>'
						title='<?php echo $this->lang->line('customers_import_items_excel'); ?>'>
					<span class="glyphicon glyphicon-import">&nbsp</span><?php echo $this->lang->line('common_import_excel'); ?>
				</button>
			<?php
		}
	}
	?>
	<?php if ($this->Employee->has_grant($controller_name.'_view')) { ?>
	<button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/view"); ?>'
			title='Tạo mới cộng tác viên'>
		<span class="glyphicon glyphicon-user">&nbsp</span>Tạo mới
	</button>
	<?php } ?>
</div>

<div id="toolbar">
	<div class="pull-left btn-toolbar">
		<?php if ($this->Employee->has_grant($controller_name.'_delete')) {
			?>
		<button id="delete" class="btn btn-default btn-sm">
			<span class="glyphicon glyphicon-trash">&nbsp</span><?php echo $this->lang->line("common_delete");?>
		</button>
		<?php } ?>
		<?php if ($this->Employee->has_grant($controller_name.'_email')) {?>
		<button id="email" class="btn btn-default btn-sm">
			<span class="glyphicon glyphicon-envelope">&nbsp</span><?php echo $this->lang->line("common_email");?>
		</button>
		<?php } ?>
	</div>
</div>

<div id="table_holder">
	<table id="table" data-search="true" data-pagination-v-align="both"></table>
</div>

<?php $this->load->view("partial/footer"); ?>
