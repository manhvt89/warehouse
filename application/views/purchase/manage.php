<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
$(document).ready(function()
{
	// when any filter is clicked and the dropdown window is closed
	$('#filters').on('hidden.bs.select', function(e) {
		table_support.refresh();
	});
	
	// load the preset datarange picker
	<?php $this->load->view('partial/daterangepicker'); ?>

	$("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
		table_support.refresh();
	});

	<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

	table_support.init({
		resource: '<?php echo site_url($controller_name);?>',
		headers: <?php echo $table_headers; ?>,
		pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
		uniqueId: 'purchase_id',
		onLoadSuccess: function(response) {
			if($("#table tbody tr").length > 1) {
				//$("#payment_summary").html(response.payment_summary);
				//$("#table tbody tr:last td:first").html("");
				//$("#table tr td:nth-child(1)").html("");
				//$("#table tr th:nth-child(1)").html("");
			}
		},
		queryParams: function() {
			return $.extend(arguments[0], {
				start_date: start_date,
				end_date: end_date,
				filters: $("#filters").val() || [""]
			});
		},
		columns: {
			'total_quantity': {
				align: 'right'
			},
			'purchase_id':{
				'data-align': 'center'
			}
		}
	});
});
function rowStyle(row, index) {
    var classes = [
      'bg-blue',
      'bg-green',
      'bg-orange',
      'bg-yellow',
      'bg-red'
    ]
	console.log(row.style);
	switch (row.style) {
		case '0':
			return {
				css: {
					color: '#000000',
					'background-color':'#FF851B'
				}
			}
			break;
		case '2':
			return {
				css: {
					color: '#000000',
					'background-color':'#FFDC00'
				}
			}
			break;	
		case '3':
			return {
				css: {
					color: '#000000',
					'background-color':'#FFDC00'
				}
			}
			break;
		case '4':
			return {
				css: {
					color: '#000000',
					'background-color':'#2ECC40'
				}
			}
			break;
		case '6':
			return {
				css: {
					color: '#000000',
					'background-color':'#0074D9'
				}
			}
			break;
		default:
			return {
				css: {
					color: 'red',
					'background-color':'#0074D9'
				}
			}
			break;
	}
  }
</script>

<?php $this->load->view('partial/print_receipt', array('print_after_sale'=>false, 'selected_printer'=>'takings_printer')); ?>

<?php if($is_created == 1): ?>
<div id="title_bar" class="print_hide btn-toolbar">
	<button onclick="javascript:printdoc()" class='btn btn-info btn-sm pull-right'>
		<span class="glyphicon glyphicon-print">&nbsp</span><?php echo $this->lang->line('common_print'); ?>
	</button>
	<?php echo anchor("purchases", '<span class="glyphicon glyphicon-shopping-cart">&nbsp</span>' . 'Tạo đơn đặt hàng', array('class'=>'btn btn-info btn-sm pull-right', 'id'=>'show_sales_button')); ?>
</div>
<?php endif; ?>
<div id="toolbar">
	<div class="pull-left form-inline" role="toolbar">
		<!--
		<button id="delete" class="btn btn-default btn-sm print_hide">
			<span class="glyphicon glyphicon-trash">&nbsp</span><?php echo $this->lang->line("common_delete");?>
		</button>
		-->

		<?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
		<?php echo form_multiselect('filters[]', $filters, '', array('id'=>'filters', 'data-none-selected-text'=>$this->lang->line('common_none_selected_text'), 'class'=>'selectpicker show-menu-arrow', 'data-selected-text-format'=>'count > 1', 'data-style'=>'btn-default btn-sm', 'data-width'=>'fit')); ?>
	</div>
</div>

<div id="table_holder">
	<table id="table" data-sort-order="desc" data-sort-name="edited_time" data-checkbox="false" data-row-style="rowStyle"></table>
</div>

<?php $this->load->view("partial/footer"); ?>
