<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $this->lang->line('items_generate_barcodes'); ?></title>
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>dist/barcode_font.css" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>dist/barcode_print.css" />
</head>
<style>
</style>

<body class= "" style="font-size:<?php echo $barcode_config['barcode_font_size']; ?>px">
	<?php if(!empty($this->config->item('Thuoc'))): ?>
	  <div class="buttonpr no-print">
	  	<button onclick="window.print()" class="bt-print-barcode">Print</button>
	  </div>
	  <div id="main_barcode_printer" class="<?php echo "font_".$this->barcode_lib->get_font_name($barcode_config['barcode_font']); ?>" style="font-size:<?php echo $barcode_config['barcode_font_size']; ?>px">
	  <?php print_barcode($items,$this->config->item('Thuoc')['template'],$barcode_config);?>
	  </div>
	  <?php else : ?>
		<div>
			Hiện tại chưa thiết lập mẫu in barcode thuốc. Hãy liên hệ với người hỗ trợ.
		</div>  
	<?php endif; ?> 
</body>

</html>
