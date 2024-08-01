<?php ///
ob_start();
$action = $_GET['action'];
include 'riderclass.php';
$crud = new Action(); //this is the class name

if($action == 'deliveryrider'){
	$deliveryrider = $crud->deliveryrider();
	if($deliveryrider)
		echo $deliveryrider;
}
