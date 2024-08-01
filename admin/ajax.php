<?php ///
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action(); //this is the class name

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
if($action == "save_category"){
	$save = $crud->save_category();
	if($save)
		echo $save;
}
if($action == "delete_category"){
	$save = $crud->delete_category();
	if($save)
		echo $save;
}
if($action == "check_category_products"){
	$save = $crud->delete_category();
	if($save)
		echo $save;
}

if($action == "save_menu"){
	$save = $crud->save_menu();
	if($save)
		echo $save;
}
if($action == "delete_menu"){
	$save = $crud->delete_menu();
	if($save)
		echo $save;
}
if($action == "add_to_cart"){
	$save = $crud->add_to_cart();
	if($save)
		echo $save; //this will return 1 if successful
}
if($action == "get_cart_count"){
	$save = $crud->get_cart_count();
	if($save)
		echo $save;
}
if($action == "delete_cart"){
	$delete = $crud->delete_cart();
	if($delete)
		echo $delete;
}
if($action == "update_cart_qty"){
	$save = $crud->update_cart_qty();
	if($save)
		echo $save;
}
if($action == "save_order"){
	$save = $crud->save_order();
	if($save)
		echo $save;
}

if($action == "confirm_order"){
	$save = $crud->confirm_order(); 
	if($save)
		echo $save;
}

if($action == "cancel_order"){
	$save = $crud->cancel_order(); 
	if($save)
		echo $save;
}

if($action == "reject_order"){
	$save = $crud->reject_order(); 
	if($save)
		echo $save;
}


if($action == "reset_img"){
	$save = $crud->reset_img(); 
	if($save)
		echo $save;
}

if($action == "paypal_checkout"){
	$save = $crud->paypal_checkout(); 
	if($save)
		echo $save;
}


//untuk letak product drink name dalam preview product
if($action == "productdrink"){
	$save = $crud->productdrink();
	if($save)
		echo $save; //this will return 1 if successful
}
if ($action == "productaddon") {
	$save = $crud->productaddon();
	if ($save)
		echo $save; //this will return 1 if successful/
}

if($action == "readydelivery_order"){
	$save = $crud->readydelivery_order(); 
	if($save)
		echo $save;
}