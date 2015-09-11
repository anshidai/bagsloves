<?php 
class Payments {
	function __construct(){
		redirect(GetValue ( "Payments_url" ),3,'Your order has been submitted successfully!');
	}
 

}
?>


