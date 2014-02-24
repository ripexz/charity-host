<!--
	when donate buttons are created:
	- the email address of the paypal account will need to be extracted from the DB
	- each animal id will need to be extracted as well
-->

<?php
	//example of queried data
	$account = "gerardprunty@gmail.com";
	$id = 3;
?>

<form method="POST" action="https://www.paypal.com/cgi-bin/webscr" target="_blank">
	<input type="hidden" name="cmd" value="_donations">
	<input type="hidden" name="business" value="<?php echo $account; ?>">
	<input type="hidden" name="item_number" value="<?php echo $id; ?>">
	<input type="hidden" name="currency_code" value="EUR">
	<input type="hidden" name="lc" value="IE">
	<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	<br/>
	<label for="amount">&euro; </label><input type="text" name="amount" value="0.00">
	
	<!--<input type="submit" name="submit" value="Pay with PayPal">
	<input name = "return" value = "name of our site goes here" type = "hidden">
	<input name = "cbt" value = "Return to My Site" type = "hidden">
	<input name = "cancel_return" value = "name of our site would go here" type = "hidden">
	-->

</form>
