<?php
session_start();

class Action
{
	private $db;
	public $ng_name, $ng_email, $ng_picture, $ng_id; //ng name is the name of the user


	public function __construct()
	{
		ob_start(); // this will turn on output buffering  the buffering (temporary storage) of output before it is flushed (sent and discarded) to the browser (in a web context) or to the shell (on the command line).
		include '../admin/db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{ // this function will be called automatically at the end of the class
		$this->db->close();
		ob_end_flush();
	}

    function deliveryrider() {
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
	
				$stmt1 = $db->prepare("UPDATE rider SET status_delivery = 1 WHERE order_id = ? AND staff_id = ?");
				$stmt1->bind_param("ii", $orderid, $staffid);
				$save1 = $stmt1->execute();
	
				if ($save1) {
					$response['status'] = 1;
					$response['message'] = "Order ready for delivery and assigned to staff.";
				} else {
					$response['status'] = 0;
					$response['message'] = "Failed to assign staff to order.";
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
 