<?php $this->load->view("partial/header"); ?>
<script src="/dist/jquery.number.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
    

	// when any filter is clicked and the dropdown window is closed
	$('#filters').on('hidden.bs.select', function(e)
	{
        table_support.refresh();
    });

	
    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>

    table_support.init({
        employee_id: <?php echo $this->Employee->get_logged_in_employee_info()->person_id; ?>,
        resource: '<?php echo site_url($controller_name);?>',
        headers: <?php echo $table_headers; ?>,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'location_id',
        showExport: true,
        queryParams: function() {
            return $.extend(arguments[0], {
            });
        },
        onLoadSuccess: function(response) {
            $('a.rollover').imgPreview({
				imgCSS: { width: 200 },
				distanceFromCursor: { top:10, left:-210 }
			})
        }
    });
});
</script>

<div id="title_bar" class="btn-toolbar print_hide">
    <?php if ($this->Employee->has_grant($controller_name.'_view')) { ?>
    <button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-new='<?php echo $this->lang->line('common_new') ?>' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name . "/view"); ?>'
            title='<?php echo $this->lang->line($controller_name . '_new'); ?>'>
        <span class="glyphicon glyphicon-tag">&nbsp</span><?php echo $this->lang->line($controller_name . '_new'); ?>
    </button>
    <?php } ?>
</div>

<div id="toolbar">
    <div class="pull-left form-inline" role="toolbar">
    <?php if ($this->Employee->has_grant($controller_name.'_delete')) { ?>
        <button id="delete" class="btn btn-default btn-sm print_hide">
            <span class="glyphicon glyphicon-trash">&nbsp</span><?php echo $this->lang->line("common_delete"); ?>
        </button>
    <?php } ?>      
    </div>
</div>

<div id="table_holder">
    <table 
        id="table" 
        data-sort-order="desc" 
        data-sort-name="location_id" 
        data-search="false" 
        data-export-types="['excel']">
    </table>
</div>

<?php $this->load->view("partial/footer"); ?>
