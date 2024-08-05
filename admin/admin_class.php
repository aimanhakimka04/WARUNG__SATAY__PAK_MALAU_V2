<?php
session_start();

class Action
{
	private $db;
	public $ng_name, $ng_email, $ng_picture, $ng_id;


	public function __construct()
	{
		ob_start(); // this will turn on output buffering  the buffering (temporary storage) of output before it is flushed (sent and discarded) to the browser (in a web context) or to the shell (on the command line).
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{ // this function will be called automatically at the end of the class
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM `users` where username = '" . $username . "' ");
		if ($qry->num_rows > 0) {
			$result = $qry->fetch_array();
			$is_verified = password_verify($password, $result['password']);
			if ($is_verified) {
				foreach ($result as $key => $value) { // => is used to assign value to the key
					if ($key != 'password' && !is_numeric($key))
						$_SESSION['login_' . $key] = $value;
				}
					return 1;
				
			}
		}
		return 3;
	}
	
	function login2()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM user_info where email = '" . $email . "' ");
		if ($qry->num_rows > 0) {
			$result = $qry->fetch_array();
			$is_verified = password_verify($password, $result['password']);
			if ($is_verified) {
				foreach ($result as $key => $value) {
					if ($key != 'passwors' && !is_numeric($key))
						$_SESSION['login_' . $key] = $value;
				}
				$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
				$this->db->query("UPDATE cart set user_id = '" . $_SESSION['login_user_id'] . "' where client_ip ='$ip' ");
				return 1;
			}
		}
		return 3;
	}
	function logout()
	{
		session_destroy(); // this will destroy all the session
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}
	function save_user()
{
    extract($_POST);
    $password = password_hash($password, PASSWORD_DEFAULT);
    $data = "`name` = '$name' ";
    $data .= ", `username` = '$username' ";
    $data .= ", `password` = '$password' ";
    $data .= ", `type` = '$type' ";
    $data .= ", `phone_no` = '$phone_no' ";
    $data .= ", `gender` = '$gender' ";
    $data .= ", `email` = '$email' ";

    // Check if username already exists
    if (empty($id)) {
        $check_username = $this->db->query("SELECT COUNT(*) as count FROM users WHERE username = '$username'");
    } else {
        $check_username = $this->db->query("SELECT COUNT(*) as count FROM users WHERE username = '$username' AND id != $id");
    }

    $username_exists = $check_username->fetch_assoc()['count'];

    if ($username_exists > 0) {
        return -2; // Username already exists
    }

    // Check if phone number already exists
    if (empty($id)) {
        $check_phone = $this->db->query("SELECT COUNT(*) as count FROM users WHERE phone_no = '$phone_no'");
    } else {
        $check_phone = $this->db->query("SELECT COUNT(*) as count FROM users WHERE phone_no = '$phone_no' AND id != $id");
    }

    $phone_exists = $check_phone->fetch_assoc()['count'];

    if ($phone_exists > 0) {
        return -3; // Phone number already exists
    }

    // Check if email already exists
    if (empty($id)) {
        $check_email = $this->db->query("SELECT COUNT(*) as count FROM users WHERE email = '$email'");
    } else {
        $check_email = $this->db->query("SELECT COUNT(*) as count FROM users WHERE email = '$email' AND id != $id");
    }

    $email_exists = $check_email->fetch_assoc()['count'];

    if ($email_exists > 0) {
        return -4; // Email already exists
    }

    if (empty($id)) {
        $save = $this->db->query("INSERT INTO users SET " . $data);
    } else {
        $save = $this->db->query("UPDATE users SET " . $data . " WHERE id = " . $id);
    }

    if ($save) {
        return 1; // Successfully saved
    } else {
        return 0; // Failed to save
    }
}

function signup()
{
    extract($_POST);
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email exists in user_info table
    $stmt = $this->db->prepare("SELECT * FROM user_info WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result_info = $stmt->get_result();

    // Check if email exists in users table
    $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result_users = $stmt->get_result();
    
    // Check if email already exists in either table
    if ($result_info->num_rows > 0 || $result_users->num_rows > 0) {
        return 2; // Email already exists
        exit;
    }

    // Validate mobile number format
    if (!preg_match("/^60\d{8,12}$/", $mobile)) {
        return 3; // Invalid mobile number format
    }

    // Insert user data into user_info table
    $stmt = $this->db->prepare("INSERT INTO user_info (first_name, last_name, mobile, address, email, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $mobile, $address, $email, $password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Registration successful
        $login = $this->login2(); // Assuming login functionality exists
        return 1;
    } else {
        // Registration failed
        return 0;
    }
}


	function save_settings()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
		if ($_FILES['img0']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img0']['name'];
			$move = move_uploaded_file($_FILES['img0']['tmp_name'], '../assets/img/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}
		if ($_FILES['img1']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img1']['name'];
			$move = move_uploaded_file($_FILES['img1']['tmp_name'], '../assets/img/' . $fname);
			$data .= ", Billboard1 = '$fname' ";
		}
		if ($_FILES['img2']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img2']['name'];
			$move = move_uploaded_file($_FILES['img2']['tmp_name'], '../assets/img/' . $fname);
			$data .= ", Billboard2 = '$fname' ";
		}
		if ($_FILES['img3']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img3']['name'];
			$move = move_uploaded_file($_FILES['img3']['tmp_name'], '../assets/img/' . $fname);
			$data .= ", Billboard3 = '$fname' ";
		}












		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set " . $data . " where id =" . $chk->fetch_array()['id']);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['setting_' . $key] = $value;
			}

			return 1;
		}
	}


	function save_category()
{
    extract($_POST);
    $data = " name = '$name' ";
    
    // Check if the name already exists
    $check = $this->db->query("SELECT * FROM category_list WHERE name = '$name'");
    if ($check->num_rows > 0) {
        // Name already exists, handle error or return false
        return 3;
    }

    if (empty($id)) {
        $save = $this->db->query("INSERT INTO category_list SET " . $data);
    } else {
        $save = $this->db->query("UPDATE category_list SET " . $data . " WHERE id = " . $id);
    }
    
    if ($save) {
        return 1;
    }
}


	function delete_category()
	{
		extract($_POST);
		// Check if category is used by any products
		$check_products = $this->db->query("SELECT * FROM product_list WHERE category_id = " . $id);
		if ($check_products->num_rows > 0) {
			// Products are using this category, cannot delete
			return 2;
		}

		// No products are using this category, proceed with deletion
		$delete = $this->db->query("DELETE FROM category_list WHERE id = " . $id);
		if ($delete) {
			return 1;
		}
	}


	
	function save_menu()
{
    // Extract POST data
    extract($_POST);
    $data = " name = '$name' ";
    $data .= ", price = '$price' ";
    $data .= ", quantity = '$quantity' ";
    $data .= ", category_id = '$category_id' ";
    $data .= ", description = '$description' ";
    if (isset($status) && $status == 'on')
        $data .= ", status = 1 ";
    else
        $data .= ", status = 0 ";

    // Check if the name already exists
    $check = $this->db->query("SELECT * FROM product_list WHERE name = '$name' AND id != '$id'");
    if ($check->num_rows > 0) {
        // Name already exists, return specific code
        return 3;
    }

    // Handle image upload if a new image is provided
    if ($_FILES['img']['tmp_name'] != '') {
        $fname = strtotime(date('Y-m-d H:i')) . '_' . $_FILES['img']['name'];
        $move = move_uploaded_file($_FILES['img']['tmp_name'], '../assets/img/' . $fname);
        $data .= ", img_path = '$fname' "; //.= is used to append the string
    }

    // Insert or update the product
    if (empty($id)) {
        $save = $this->db->query("INSERT INTO product_list SET " . $data);
    } else {
        $save = $this->db->query("UPDATE product_list SET " . $data . " WHERE id = " . $id);
    }

    // Check if the save operation was successful
    if ($save) {
        return 1;
    } else {
        // Handle potential save errors
        return 0;
    }
}


	function delete_menu()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM product_list where id = " . $id);
		if ($delete)
			return 1;
	}

	
	function delete_cart()
	{
		extract($_GET);
		$delete = $this->db->query("DELETE FROM cart where id = " . $id);
		if ($delete)
			header('location:' . $_SERVER['HTTP_REFERER']);
	}
	function add_to_cart() {
		// Get the array of products from the POST request
		$products = json_decode($_POST['products'], true);
		if (!$products || !is_array($products)) {
			return 0; // Return 0 indicating failure
		}
	
		foreach ($products as $product) {
			$product_id = $product['product_id'];
			$qty = $product['qty'];
	
			// Check if user is logged in and get user ID or client IP
			if (isset($_SESSION['login_user_id'])) {
				$user_id = $_SESSION['login_user_id'];
				$condition = "user_id = '$user_id'";
			} else {
				$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
				$condition = "client_ip = '$ip'";
			}
	
			// Check if the product already exists in the cart
			$existing = $this->db->query("SELECT * FROM cart WHERE product_id = '$product_id' AND $condition");
	
			if ($existing && $existing->num_rows > 0) {
				// Product exists, update the quantity
				$existing_row = $existing->fetch_assoc();
				$new_qty = $existing_row['qty'] + $qty;
				$update = $this->db->query("UPDATE cart SET qty = '$new_qty' WHERE product_id = '$product_id' AND $condition");
				if (!$update) {
					return 0; // Return 0 indicating failure
				}
			} else {
				// Product does not exist, insert new record
				$insertData = "product_id = '$product_id', qty = '$qty', " . $condition;
				$save = $this->db->query("INSERT INTO cart SET " . $insertData);
				if (!$save) {
					return 0; // Return 0 indicating failure
				}
			}
		}
	
		return 1; // Return 1 indicating success
	}
	
	
	
	
	function get_cart_count()
	{
		extract($_POST);
		if (isset($_SESSION['login_user_id'])) {
			$where = " where user_id = '" . $_SESSION['login_user_id'] . "'  ";
		} else {
			$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
			$where = " where client_ip = '$ip'  ";
		}
		$get = $this->db->query("SELECT sum(qty) as cart FROM cart " . $where);
		if ($get->num_rows > 0) {
			return $get->fetch_array()['cart'];
		} else {
			return '0';
		}
	}

	function update_cart_qty()
	{
		extract($_POST);
		$data = " qty = $qty "; //qty from the post data
		$save = $this->db->query("UPDATE cart set " . $data . " where id = " . $id);
		if ($save)
			return 1;
	}

	function save_order()
{
    extract($_POST);
    $data = " name = '" . $first_name . "," . $last_name . "' ";
    $data .= ", mobile = '$mobile' ";
    $data .= ", email = '$email' ";
    $data .= ", address = '$address' ";
    $orderrequest = isset($_POST['orderrequest']) ? $_POST['orderrequest'] : ''; 
    $data .= ", orderrequest = '$orderrequest' ";
    $data .= ", payment_method = '$paymentMethod'";
    $data .= ", collect_method = '$collectionMethod' ";
    $data .= ", delivery_fee = '$delivery_fee' ";

    $save = $this->db->query("INSERT INTO orders SET " . $data);
    if ($save) {
        $id = $this->db->insert_id;
        $qry = $this->db->query("SELECT * FROM cart WHERE user_id =" . $_SESSION['login_user_id']);
        while ($row = $qry->fetch_assoc()) {
            $product_id = $row['product_id'];
            $qty = $row['qty'];

            // Insert into order_list
            $data = " order_id = '$id' ";
            $data .= ", product_id = '$product_id' ";
            $data .= ", qty = '$qty' ";
            $save2 = $this->db->query("INSERT INTO order_list SET " . $data);
            if ($save2) {
                 // Update product_list quantity and total_sold
				 $update_product = $this->db->query("UPDATE product_list SET 
				 quantity = quantity - $qty, 
				 total_sold = total_sold + $qty 
				 WHERE id = '$product_id'");
				if (!$update_product) {
				// Log the error
				error_log("Failed to update product_list for product_id: $product_id - " . $this->db->error);
				}
                // Delete from cart
                $this->db->query("DELETE FROM cart WHERE id= " . $row['id']);
            }
        }
        return 1;
    }
    return 0;
}


	function confirm_order()
	{
		extract($_POST); // this will extract all the post data to the variable
		//id get from the post data
		$save = $this->db->query("UPDATE orders set status = 1 where id= " . $id);
		if ($save) // if save is successful
			return 1;
	}

	function reject_order()
	{
		extract($_POST); // this will extract all the post data to the variable
		//id get from the post data
		$save = $this->db->query("UPDATE orders set status = 0 where id= " . $id);
		if ($save) // if save is successful
			return 1;
	}

	function cancel_order()
	{
		extract($_POST); // this will extract all the post data to the variable
		//id get from the post data
		$save = $this->db->query("UPDATE orders set status = 2 where id= " . $id);
		//get order user email 
		$order = $this->db->query("SELECT * FROM orders where id = " . $id);
		$order = $order->fetch_array();
		//update ewallet balance by email (add back the amount to the ewallet)
		$ewallet = $this->db->query("UPDATE user_info set walletbalance = walletbalance + " . $order['total_fee'] . " where email = '" . $order['email'] . "'");
		if ($save && $ewallet) // if save is successful
			return 1;
	}
	function reset_drink()
	{
		$_SESSION['drinkvar'] = null;
		$_SESSION['drinkpicked_id'] = null;
		$_SESSION['drinkpicked_name'] = null;
		$_SESSION['drinkpicked_price'] = null;
		$_SESSION['drinkpicked_quantity'] = null;
		$_SESSION['drinkpicked_category_id'] = null;
		$_SESSION['drinkpicked_description'] = null;
		$_SESSION['drinkpicked_status'] = null;
		$_SESSION['drinkpicked_img_path'] = null;
		return 1;
	}

	
		


	function productdrink()
	{
		extract($_GET); // this will extract all the get data to the variable
		$_SESSION['drinkpicked_id'] = null;
		if (isset($_GET['drinkvar'])) {

			// Get the drinkvar from the URL
			$drinkvar = $_GET['drinkvar'];


			if ($drinkvar == "null") {
				$drinkvar = null;
			} else if ($drinkvar != "null" && $drinkvar != null) {
				// convert the string to integer 
				$drinkvar = (int)$drinkvar; // this will convert the string to integer
				$sql = "SELECT * FROM product_list WHERE id = ?";
				$stmt = $this->db->prepare($sql);
				$stmt->bind_param("i", $drinkvar); // i means integer
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($id, $category_id, $name, $description, $price, $quantity, $img_path, $status, $total_sold);
				$stmt->fetch(); // this will fetch the data
				$stmt->close();

				echo  "<p class='product-decs'>1 " . $name . " + RM" . number_format($price, 2) . "</p>";

				// Set the session
				$_SESSION['drinkpicked_id'] = $id;
			}
		}
		return 0;  /// if $_GET['drinkvar'] is not set
	}
	function productaddon()
	{
		extract($_GET); // this will extract all the get data to the variable
		if (isset($_GET['addonpickid']) && isset($_GET['addonqtyvar'])) {
			// Get the addonvar from the URL
			$addonvar = $_GET['addonpickid'];
			$addonqtyvar = $_GET['addonqtyvar'];
			if ($addonvar == "null" && $addonqtyvar == "null") {
				$addonvar = null;
				
			} else if ($addonvar != "null" && $addonvar != null && $addonqtyvar != "null" && $addonqtyvar != null && $addonqtyvar != 0) {
				// convert the string to integer 
				$addonvar = (int)$addonvar; // this will convert the string to integer
				$addonqtyvar = (int)$addonqtyvar; // this will convert the string to integer
				$sql = "SELECT * FROM product_list WHERE id = ?";
				$stmt = $this->db->prepare($sql);
				$stmt->bind_param("i", $addonvar); // i means integer
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($id, $category_id, $name, $description, $price, $quantity, $img_path, $status, $total_sold);
				$stmt->fetch(); // this will fetch the data
				$stmt->close();

				echo "<p class='product-decs' id='addonid$id' data-valueaddon='' ><span class='quantity'>$addonqtyvar</span> $name + RM" . number_format($price, 2) . "</p>";
			}else if($addonqtyvar == 0){
			}
		}
	}

	//delivery function 

	function readydelivery_order() {
		// Extract POST data
		extract($_POST); 
	
		// Debugging: Check received data
		error_log("Order ID: " . $orderid);
		error_log("Staff ID: " . $staffid);
	
		// Prepare the response array
		$response = array();
	
		// Database connection
		$db = $this->db;
	
		try {
			// Use prepared statements to prevent SQL injection
			$stmt = $db->prepare("UPDATE orders SET status = 3 WHERE id = ?");
			$stmt->bind_param("i", $orderid);
			$save = $stmt->execute();
	
			if ($save) {
				$stmt1 = $db->prepare("INSERT INTO rider (order_id, staff_id) VALUES (?, ?)");
				$stmt1->bind_param("ii", $orderid, $staffid);
				$save1 = $stmt1->execute();
	
				if ($save1) {
					$response['status'] = 1;
					$response['message'] = "Order ready for delivery and assigned to staff.";
				} else {
					$response['status'] = 0;
					$response['message'] = "Failed to assign staff to order.";
				}
			} else {
				$response['status'] = 0;
				$response['message'] = "Failed to update order status.";
			}
		} catch (Exception $e) {
			$response['status'] = 0;
			$response['message'] = "An error occurred: " . $e->getMessage();
		}
	
		// Return response as JSON
		echo json_encode($response);
		return;
	}
	
	
}//
 