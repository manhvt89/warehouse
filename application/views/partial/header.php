<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<base href="<?php echo base_url();?>" />
	<title><?php echo $this->config->item('company') . ' | ' . $this->lang->line('common_powered_by') . ' OSPOS ' . $this->config->item('application_version') ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

	<?php if ($this->input->cookie('debug') == "true" || $this->input->get("debug") == "true") : ?>
		<!-- bower:css -->
		<link rel="stylesheet" href="bower_components/jquery-ui/themes/base/jquery-ui.css" />
		<link rel="stylesheet" href="bower_components/bootstrap3-dialog/dist/css/bootstrap-dialog.min.css" />
		<link rel="stylesheet" href="bower_components/jasny-bootstrap/dist/css/jasny-bootstrap.css" />
		<link rel="stylesheet" href="bower_components/smalot-bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
		<link rel="stylesheet" href="bower_components/bootstrap-select/dist/css/bootstrap-select.css" />
		<link rel="stylesheet" href="bower_components/bootstrap-table/src/bootstrap-table.css" />
		<link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css" />
		<link rel="stylesheet" href="bower_components/chartist/dist/chartist.min.css" />
		<link rel="stylesheet" href="bower_components/chartist-plugin-tooltip/dist/chartist-plugin-tooltip.css" />
		<!-- endbower -->
		<!-- start css template tags -->
		<link rel="stylesheet" type="text/css" href="css/bootstrap.autocomplete.css"/>
		<link rel="stylesheet" type="text/css" href="css/invoice.css"/>
		<link rel="stylesheet" type="text/css" href="css/ospos.css"/>
		<link rel="stylesheet" type="text/css" href="css/ospos_print.css"/>
		<link rel="stylesheet" type="text/css" href="css/popupbox.css"/>
		<link rel="stylesheet" type="text/css" href="css/receipt.css"/>
		<link rel="stylesheet" type="text/css" href="css/register.css"/>
		<link rel="stylesheet" type="text/css" href="css/reports.css"/>
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
		<!-- end css template tags -->
		<!-- bower:js -->
		<script src="bower_components/jquery/dist/jquery.js"></script>
		<script src="bower_components/jquery-form/jquery.form.js"></script>
		<script src="bower_components/jquery-validate/dist/jquery.validate.js"></script>
		<script src="bower_components/jquery-ui/jquery-ui.js"></script>
		<script src="bower_components/bootstrap/dist/js/bootstrap.js"></script>
		<script src="bower_components/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js"></script>
		<script src="bower_components/jasny-bootstrap/dist/js/jasny-bootstrap.js"></script>
		<script src="bower_components/smalot-bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
		<script src="bower_components/bootstrap-select/dist/js/bootstrap-select.js"></script>
		<script src="bower_components/bootstrap-table/src/bootstrap-table.js"></script>
		<script src="bower_components/bootstrap-table/dist/extensions/export/bootstrap-table-export.js"></script>
		<script src="bower_components/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.js"></script>
		<script src="bower_components/moment/moment.js"></script>
		<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
		<script src="bower_components/file-saver.js/FileSaver.js"></script>
		<script src="bower_components/html2canvas/build/html2canvas.js"></script>
		<script src="bower_components/jspdf/dist/jspdf.min.js"></script>
		<script src="bower_components/jspdf-autotable/dist/jspdf.plugin.autotable.js"></script>
		<script src="bower_components/tableExport.jquery.plugin/tableExport.min.js"></script>
		<script src="bower_components/chartist/dist/chartist.min.js"></script>
		<script src="bower_components/chartist-plugin-axistitle/dist/chartist-plugin-axistitle.min.js"></script>
		<script src="bower_components/chartist-plugin-pointlabels/dist/chartist-plugin-pointlabels.min.js"></script>
		<script src="bower_components/chartist-plugin-tooltip/dist/chartist-plugin-tooltip.min.js"></script>
		<script src="bower_components/chartist-plugin-barlabels/dist/chartist-plugin-barlabels.min.js"></script>
		<script src="bower_components/remarkable-bootstrap-notify/bootstrap-notify.js"></script>
		<script src="bower_components/js-cookie/src/js.cookie.js"></script>
		<script src="bower_components/blockUI/jquery.blockUI.js"></script>
		<!-- endbower -->
		<!-- start js template tags -->
		<script type="text/javascript" src="js/imgpreview.full.jquery.js"></script>
		<script type="text/javascript" src="js/manage_tables.js"></script>
		<script type="text/javascript" src="js/nominatim.autocomplete.js"></script>
		<!-- end js template tags -->
	<?php else : ?>
		<!--[if lte IE 8]>
		<link rel="stylesheet" media="print" href="dist/print.css" type="text/css" />
		<![endif]-->
		<!-- start mincss template tags -->
		<link rel="stylesheet" type="text/css" href="dist/jquery-ui.css"/>
		<link rel="stylesheet" type="text/css" href="dist/opensourcepos.min.css?rel=d5b9522f2f"/>
		<link rel="stylesheet" type="text/css" href="dist/style.css"/>
		<link rel="stylesheet" type="text/css" href="dist/font-awesome.min.css"/>
		<!-- end mincss template tags -->
		<!-- start minjs template tags -->
		<script type="text/javascript" src="dist/opensourcepos.min.js?rel=bc5842b19a"></script>
		<!-- end minjs template tags -->
	<?php endif; ?>
	<link rel="stylesheet" type="text/css" href="<?php echo 'dist/bootswatch/' . (empty($this->config->item('theme')) ? 'flatly' : $this->config->item('theme')) . '/bootstrap.min.css' ?>"/>
	<!-- Added By ManhVT support data grid like excel-->
	<script src="dist/jspreadsheet/jexcel.js"></script>

	<script src="dist/jspreadsheet/jsuites.js"></script>
	
	<link rel="stylesheet" href="dist/jspreadsheet/jexcel.css" type="text/css" />

	<link rel="stylesheet" href="dist/jspreadsheet/jsuites.css" type="text/css" />
	<link rel="stylesheet" href="dist/pres.css" type="text/css" />

	<?php $this->load->view('partial/header_js'); ?>
	<?php $this->load->view('partial/lang_lines'); ?>

	<style type="text/css">
		html {
			overflow: auto;
		}
	</style>
	<?php if($this->Employee->has_grant('test_step_one')):?>
		<style type="text/css">
		.s-100 {
			display: none;
		}
	</style>
	<?php endif; ?>
</head>

<body>
	<div class="wrapper">
		<header class="fixed-menu" id="header1">
			<div class=" topbar">
				<div class="container">
					<div class="navbar-left">
						<div id="liveclock"><?php echo date($this->config->item('dateformat') . ' ' . $this->config->item('timeformat')) ?></div>
					</div>
					
					<div class="navbar-right" style="margin:0">
						<?php echo strip_tags($this->config->item('company')) . "  |  $user_info->first_name $user_info->last_name  |  " . ($this->input->get("debug") == "true" ? $this->session->userdata('session_sha1') : ""); ?>
						<?php echo anchor("home/logout", $this->lang->line("common_logout")); ?>
					</div>
					<div class="navbar-left" style="margin:0px 25px">
						<a href="<?=$this->config->item('guide')==''?'https://docs.google.com/document/d/15-AAz6FNdPSoJUmGpATPykfwYCRMvezyttfjvHXp7yM/edit#heading=h.oja56yjzebgv':$this->config->item('guide')?>" target="_blank"><b style="color: #fff;">Hướng dẫn sử dụng</b></a>
					</div>
				</div>
			</div>

			<div class="navbar navbar-default " role="navigation">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<!-- <a class="navbar-brand hidden-sm" href="<?php echo site_url(); ?>">ESS</a> -->
					</div>

					<div class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<?php foreach($allowed_modules as $module): ?>
							<li class="<?php echo $module->module_key == $this->uri->segment(1)? 'active': ''; ?>">
								<a href="<?php echo site_url("$module->module_key");?>" title="<?php echo $this->lang->line("module_".$module->module_key);?>" class="menu-icon">
									<img src="<?php echo base_url().'images/menubar/'.$module->module_key.'.png';?>" border="0" alt="Module Icon" /><br />
									<?php echo $this->lang->line("module_".$module->module_key) ?>
								</a>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</header>

		<div class="container" id="content">
			<div class="row">
	 
