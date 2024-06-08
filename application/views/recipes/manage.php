<?php $this->load->view("partial/header"); ?>
<script src="/dist/jquery.number.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
    $('#runCronBtn').on('click', function () {
      // Sử dụng Ajax để gửi yêu cầu chạy cron khi người dùng nhấn nút
      $.ajax({
        url: '<?=base_url('items/run_synchro')?>', // Đường dẫn đến tệp xử lý cron
        method: 'GET',
        success: function (response) {
          alert('Đã chạy cron thành công!');
        },
        error: function (error) {
          alert('Đã xảy ra lỗi khi chạy cron.');
          console.log(error);
        }
      });
    });

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
                field: 'payment_button',
                title: 'Thanh Toán',
                formatter: paymentFormatter,
                events: {
                    'click .payment-btn': openPaymentPopup,
                },
            };
    _headers.push($_obt);
    table_support.init({
        employee_id: <?php echo $this->Employee->get_logged_in_employee_info()->person_id; ?>,
        resource: '<?php echo site_url($controller_name);?>',
        headers: _headers,
        pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
        uniqueId: 'recipes.recipe_id',
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
        //console.log(row.remain_amount);
        if(row.remain_amount == "0") {
            return 'Đã thanh toán';
        }
        return '<button class="btn btn-info payment-btn btn-sm">Thanh Toán</button>';
}

function openPaymentPopup(e, value, row, index) {
    // Hiển thị popup và truyền thông tin đơn hàng (row) vào popup
    // ...
    console.log(index);
    $('#paymentModalLabel').html('Thanh Toán Đơn Hàng <b>'+row.code+'</b>');
    $('#lblRemainAmount').html('Số tiền thanh toán (<b>'+row.remain_amount+'</b>)');
    $('#hdd_remain_amount').val(row.remain_amount);
    $('#hdd_receiving_id').val(row.receiving_uuid);
    $('#hdd_row_index').val(index);
    $('#hdd_total_amount').val(row.total_amount);
    $('#hdd_paid_amount').val(row.paid_amount);
    // Ví dụ sử dụng Bootstrap Modal
    $('#paymentModal').modal('show');
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
    
    <?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
    <?php //echo form_multiselect('filters[]', $filters, '', array('id'=>'filters', 'class'=>'selectpicker show-menu-arrow', 'data-none-selected-text'=>$this->lang->line('common_none_selected_text'), 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
        
    </div>
</div>

<div id="table_holder" class="print_hide">
    <table 
        id="table" 
        data-sort-order="desc" 
        data-sort-name="recipe_id" 
        data-search="true" 
        data-export-types="['excel']">
    </table>
</div>

<?php $this->load->view("partial/footer"); ?>
