<?php $this->load->view("partial/header"); ?>
<script src="/dist/jquery.number.min.js"></script>
<style type="text/css">
    #DetailRecipeView .modal-dialog{
        width: 1194px;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        #recipe_basic_info * {
            visibility: visible;
        }
        #recipe_basic_info {
            display: block;
            float: left;
            font-size: 10px;
            width: 96%;
            margin: 10px;
        }
    }
</style>
<script type="text/javascript">
$(document).ready(function()
{
   
    <?php if ($this->Employee->has_grant($controller_name.'_generate_barcodes')) {?>
    $('#generate_barcodes').click(function()
    {
        window.open(
            '<?=base_url('items/generate_barcodes/')?>'+table_support.selected_ids().join(':'),
            '_blank' // <- This is what makes it open in a new window.
        );
    });

    $('#add_barcodes').click(function()
    {
        window.open(
            '<?=base_url('items/add_barcodes/')?>'+table_support.selected_ids().join(':'),
            '_blank' // <- This is what makes it open in a new window.
        );
    });
    <?php }?>
	
	// when any filter is clicked and the dropdown window is closed
	$('#filters').on('hidden.bs.select', function(e)
	{
        table_support.refresh();
    });

	// load the preset datarange picker
	<?php $this->load->view('partial/daterangepicker'); ?>
    // set the beginning of time as starting date
    $('#daterangepicker').data('daterangepicker').setStartDate("<?php echo date($this->config->item('dateformat'), mktime(0,0,0,01,01,2010));?>");
	// update the hidden inputs with the selected dates before submitting the search data
    var start_date = "<?php echo date('Y-m-d', mktime(0,0,0,01,01,2010));?>";
	$("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
        table_support.refresh();
    });

    $("#stock_location").change(function() {
       table_support.refresh();
    });

    <?php $this->load->view('partial/bootstrap_tables_locale'); ?>

    var _headers = <?php echo $table_headers; ?>;
    $_obt = {
                field: 'view_button',
                title: 'Xem lệnh cán CA',
                formatter: paymentFormatter,
                events: {
                    'click .view-recipe-btn': openPaymentPopup,
                },
            };
    _headers.push($_obt);

    table_support.init({
        employee_id: <?php echo $this->Employee->get_logged_in_employee_info()->person_id; ?>,
        resource: '<?php echo site_url($controller_name);?>',
        headers: _headers,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'compounda_orders.compounda_order_id',
        showExport: true,
        queryParams: function() {
            return $.extend(arguments[0], {
                start_date: start_date,
                end_date: end_date,
                stock_location: $("#stock_location").val(),
                filters: $("#filters").val() || [""]
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

function paymentFormatter(value, row, index) {
        console.log(row);
        console.log(value);
        if(row.istatus < 5) {
            return '...';
        }
        return '<button class="btn btn-info view-recipe-btn btn-sm">Xem</button>';
}

function openPaymentPopup(e, value, row, index) {
    // Hiển thị popup và truyền thông tin đơn hàng (row) vào popup
    // ...
    console.log(index);
    console.log(row);
    console.log(e);
    console.log(value);
    var node = $('#body-recipe-view-modal');
    $.get(row.view, function(data) {
        node.html(data);
    });
    // Ví dụ sử dụng Bootstrap Modal
    $('#DetailRecipeView').modal('show');
}
</script>

<div id="title_bar" class="btn-toolbar print_hide">
    <?php if ($this->Employee->has_grant($controller_name.'_excel_import')) { ?>
    <button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/excel_import"); ?>'
            title='<?php echo $this->lang->line('items_import_items_excel'); ?>'>
        <span class="glyphicon glyphicon-import">&nbsp</span><?php echo $this->lang->line('common_import_excel'); ?>
    </button>
    <?php } ?>
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
    <?php if ($this->Employee->has_grant($controller_name.'_bulk_edit')) { ?>
        <button id="bulk_edit" class="btn btn-default btn-sm modal-dlg print_hide", data-btn-submit='<?php echo $this->lang->line('common_submit') ?>', data-href='<?php echo site_url($controller_name."/bulk_edit"); ?>'
				title='<?php echo $this->lang->line('items_edit_multiple_items'); ?>'>
            <span class="glyphicon glyphicon-edit">&nbsp</span><?php echo $this->lang->line("items_bulk_edit"); ?>
        </button>
    <?php } ?>
    <?php if ($this->Employee->has_grant($controller_name.'_generate_barcodes')) {?>
        <button id="add_barcodes" class="btn btn-default btn-sm print_hide" data-href='<?php echo site_url($controller_name."/add_barcodes"); ?>' title='<?php echo $this->lang->line('items_generate_barcodes');?>'>
            <span class="glyphicon glyphicon-barcode">&nbsp</span><?php echo 'Thêm SP Tạo barocde'; ?>
        </button>
    <?php } ?>    
        <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
        <?php //echo form_multiselect('filters[]', $filters, '', array('id'=>'filters', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>$this->lang->line('common_none_selected_text'), 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
    </div>
</div>

<div id="table_holder">
    <table 
        id="table" 
        data-sort-order="desc" 
        data-sort-name="compounda_order_no" 
        data-search="true" 
        data-export-types="['excel']">
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="DetailRecipeView" tabindex="-1" role="dialog" aria-labelledby="RecipeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header print_hide">
        <h5 class="modal-title" id="RecipeModalLabel">...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="body-recipe-view-modal" class="modal-body">
        <!-- Form để nhập số tiền và chọn phương thức thanh toán -->
      </div>
      <div class="modal-footer print_hide">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        <button type="button" class="btn btn-primary" id="PrintBtn">Print</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<?php $this->load->view("partial/footer"); ?>