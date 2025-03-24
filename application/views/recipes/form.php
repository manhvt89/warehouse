<style type="text/css">
	.approved {
		position: relative;
	}

	.approved::before {
		content: "ĐÃ PHÊ DUYỆT";
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
</style>
<div id="recipe_basic_info" width="100%">
	
	<?=$recipe_header ?>
	<!-- #endregion recipe-header -->
	<!-- #region recipe-title-->
	<?=$recipe_title ?>
	<!-- #endregion -->
	<!-- #region recipe-info-->
	<?=$recipe_info ?>
	<!-- #endregion -->
	<!-- #region recipe-header-kneader-a-->
	<?=$recipe_body_A ?>
	<?=$recipe_body_B ?>
	<!-- #region -->
	
	<!-- #endregion -->
</div>
<script type="text/javascript">
	//validation and submit handling
	//(function($) {
        // You pass-in jQuery and then alias it with the $-sign
        // So your internal code doesn't change
    //})(jQuery);
	//$(document).ready(function()
	(function($)
	{
		

		$("#submit").click(function() {
			stay_open = false;
		});
	
	})(jQuery);
</script>

