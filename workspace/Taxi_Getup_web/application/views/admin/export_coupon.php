<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>Untitled Document</title>

	<style>
	    .voucher_table{background:url(<?php echo $form_url.'public/images/coupon_card.png'; ?>) no-repeat;border:0px;border-spacing:0px;height: 340px;}
	    td.amount{padding: 0;margin:0;vertical-align: top;}
	    td.amount .coupon_amt{font:normal 60px/80px arial;color:#fff;margin:0;vertical-align:top;}
	    td.amount .currency_format{color: #fff; margin: 0px; vertical-align: top; font: 18px arial;}
	    .coupon_code{margin: 0px; vertical-align: top; color: #333; padding-left: 235px; font: 26px arial; padding-top: 7px;}
	    .contact_info{font:14px arial; color:#FD334D; display:inline-block; margin-top:10px; margin-bottom:0px;}
	    .contact_info span{color:#fff;}
	    .serial_number{font:12px arial; color:#fff; display:inline-block; margin:0;}
	    @media all {
		.page-break	{ display: none; }
	    }

	    @media print {
		.page-break	{ display: block; page-break-before: always; }
	    }
	</style>
    </head>


    <body style="-webkit-print-color-adjust:exact;">
<?php if(isset($drivers_coupons) && count($drivers_coupons) > 0)
		{
			$count = 1;
			$items_count = count($drivers_coupons);
			foreach ($drivers_coupons as $coupon) 
			{ ?>
	<table width="552" cellpadding="10" cellspacing="0" class="voucher_table" valign="top">
	    <tr>
		<td colspan="2" align="right"><p style="color:#fff;font-size:16px;font-family: arial;margin:5px 0">Serial No : <?php echo $coupon['serial_number']; ?></p></td>
	    </tr>
	    <tr>
		<td colspan="2">
		    <p style="height: 100px;">&nbsp;</p>
		</td>
	    </tr>
	    <tr>
		<td><p  style="border:1px solid #333;padding: 10px 20px; color:#333;font:26px arial;float: left;margin:0 0 0 15px;"><?php echo $coupon['coupon_code']; ?></p></td>
		<td align="center"><p style="font-size: 65px;color:#fff;font-family: arial;margin:0;"><?php echo $coupon['site_currency']; ?><?php echo $coupon['coupon_amt']; ?></p></td>
	    </tr>
	    <tr>
		<td align="center" valign="top" colspan="2">
		    <p style="color:#fff;font-size:15px;font-family: arial;margin:0px 0;color:#333;">For assistance, please contact &nbsp; &nbsp; <span style="color:#fff;float:right;"><?php echo $coupon['phone_number']; ?> or <?php echo $coupon['email_id']; ?></span></p>
		</td>
	    </tr>
	    			
	</table>
    </center><br/>
<?php if($count % 2 == 0 && $count != $items_count) { ?>
					<div class="page-break"></div>
				<?php } ?>
				<?php $count++; ?>
			<?php }
		} ?>
<script type="text/javascript" src="<?php echo $form_url; ?>public/js/jquery.min.js"></script>	
    <script>
	$(document).ready(function(){
	    window.print();
	})
    </script>		
</body>
</html>
