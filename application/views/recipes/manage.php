<?php $this->load->view("partial/header"); ?>
<style type="text/css">
    #DetailRecipeView .modal-dialog{
        width: 760px;
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
<script src="/dist/jquery.number.min.js"></script>
<?php if($grant_id  > 2): ?>
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
                field: 'view_button',
                title: 'Xem đơn pha chế',
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
        uniqueId: 'recipes.uniqueId',
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

    $(document).on('click', '#PrintBtn', function() {
        
        window.print();
    });

    $(document).on('click', '#approveButton', function() {
        //alert('Nút phê duyệt đã được click!');
        var uuid = $(this).data('uuid');
        var csrf_ospos_v3 = csrf_token();
        var rowIndex = $(this).data('index'); // Lấy index hàng
        console.log('Row Index:', rowIndex);
        //console.log(uuid);
        $.ajax({
            url: '<?=base_url('recipes/approve')?>',
            type: 'POST',
            data: { uuid: uuid, csrf_ospos_v3:csrf_ospos_v3 },
            dataType: 'json',
            success: function (rs) {
                if(rs.success == true)
                {
                    $('#DetailRecipeView').modal('hide');
                    // Cập nhật dòng tương ứng
                    $('#table').bootstrapTable('updateRow', {
                        index: rowIndex,
                        row: {
                            status: 'Đã phê duyệt' // Thay đổi giá trị cột "Trạng thái"
                        }
                    });
                    var $row = $('#table tbody tr[data-index="' + rowIndex + '"]');
                    $row.css('background-color', '#d4edda').animate({ backgroundColor: "#fff" }, 2000);
                }
                
            },
            error: function () {
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            }
        });
    });

    $(document).on('click', '#sentButton', function() {
        //alert('Nút phê duyệt đã được click!');
        var uuid = $(this).data('uuid');
        var csrf_ospos_v3 = csrf_token();
        var rowIndex = $(this).data('index'); // Lấy index hàng
        console.log('Row Index:', rowIndex);
        console.log(uuid);
        $.ajax({
            url: '<?=base_url('recipes/sent')?>',
            type: 'POST',
            data: { uuid: uuid, csrf_ospos_v3:csrf_ospos_v3 },
            dataType: 'json',
            success: function (rs) {
                if(rs.success == true)
                {
                    $('#DetailRecipeView').modal('hide');
                    // Cập nhật dòng tương ứng
                    $('#table').bootstrapTable('updateRow', {
                        index: rowIndex,
                        row: {
                            status: 'Đã gửi phê duyệt' // Thay đổi giá trị cột "Trạng thái"
                        }
                    });
                    var $row = $('#table tbody tr[data-index="' + rowIndex + '"]');
                    $row.css('background-color', '#d4edda').animate({ backgroundColor: "#fff" }, 2000);
                }
                
            },
            error: function () {
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            }
        });
    });
    
});
function paymentFormatter(value, row, index) {
        //console.log(row);
        //console.log(value);
        if(row.istatus > "5") {
            return '...';
        }
        return '<button class="btn btn-info view-recipe-btn btn-sm">Xem</button>';
}

function openPaymentPopup(e, value, row, index) {
    // Hiển thị popup và truyền thông tin đơn hàng (row) vào popup
    // ...
    console.log(index);
    console.log(row);
    //console.log(e);
   // console.log(value);
    var node = $('#body-recipe-view-modal');
    
    $.get(row.view, function(data) {
        node.html(data);
        
    });
        // Kiểm tra trạng thái bản ghi và thêm các nút phù hợp
    var modalFooter = $('#DetailRecipeView .modal-footer');
    modalFooter.empty(); // Xóa các nút cũ tránh trùng lặp
    modalFooter.append('<button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>');
    modalFooter.append('<button type="button" class="btn btn-primary" id="PrintBtn">Print</button>');
    if (row.istatus == '4') {
        modalFooter.append('<button class="btn btn-success" id="approveButton" data-index="'+index+'" data-uuid="'+row.recipe_uuid+'">Phê duyệt</button>');
    } 
    if (row.istatus < 4) {
        modalFooter.append('<button class="btn btn-danger" id="sentButton" data-index="'+index+'" data-uuid="'+row.recipe_uuid+'">Gửi phê duyệt</button>');
    }

    // Ví dụ sử dụng Bootstrap Modal
    $('#DetailRecipeView').modal('show');
}

function rowStyle(row, index) {
    var classes = [
      'bg-blue',
      'bg-green',
      'bg-orange',
      'bg-yellow',
      'bg-red'
    ]
	console.log(row);
	switch (row.istatus) {
		case '1':
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
		case '5':
			return {
				css: {
					color: '#000000',
					'background-color':'#fff'
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

<div id="title_bar" class="btn-toolbar print_hide">
    <?php if ($this->Employee->has_grant($controller_name.'_excel_import')) { ?>
    <button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/excel_import"); ?>'
            title='<?php echo $this->lang->line('items_import_items_excel'); ?>'>
        <span class="glyphicon glyphicon-import">&nbsp</span><?php echo $this->lang->line('common_import_excel'); ?>
    </button>
    <?php } ?>
    <?php if ($this->Employee->has_grant($controller_name.'_viewX')) { ?>
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
        data-export-types="['excel']"
        data-row-style="rowStyle">
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
        
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<?php else : ?>

<?php endif; ?>
<?php $this->load->view("partial/footer"); ?>
