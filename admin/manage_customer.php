<?php 
include 'db_connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $customerid = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM user_info WHERE user_id = ?");
    $stmt->bind_param("i", $customerid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Detail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        h2 {
            margin-top: 0;
            color: #333;
        }
        form div {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .error-mobile, .error-email {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Customer Detail</h2>
        
        <form method="post" action="update_customer.php">
            <div>
                <label>Customer ID:</label>
                <input type="text" name="customer_id" value="<?php echo ($row['user_id']); ?>" readonly>
            </div>
            <div>
                <label>Customer Name (First Name):</label>
                <input type="text" name="first_name" value="<?php echo ($row['first_name']); ?>" required>
            </div>
            <div>
                <label>Customer Name (Last Name):</label>
                <input type="text" name="last_name" value="<?php echo ($row['last_name']); ?>" required>
            </div>
            <div>
                <label>Customer Address:</label>
                <input type="text" name="customer_address" value="<?php echo ($row['address']); ?>" required>
            </div>
            <div>
                <label>Customer Mobile:</label>
                <input type="text" name="customer_mobile" value="<?php echo ($row['mobile']); ?>" pattern="[0-9]{6,10}" required>
                <span class="error-mobile" aria-live="polite"></span>
            </div>
            <div>
                <label>Customer Email:</label>
                <input type="email" name="customer_email" value="<?php echo ($row['email']); ?>" required>
                <span class="error-email" aria-live="polite"></span>
            </div>
            <div>
            </div>
        </form>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            var emailInput = document.querySelector('input[name="customer_email"]');
            var emailErrorSpan = document.querySelector('.error-email');
            var mobileInput = document.querySelector('input[name="customer_mobile"]');
            var mobileErrorSpan = document.querySelector('.error-mobile');

            if (!emailInput.validity.valid) {
                emailErrorSpan.textContent = 'Please enter a valid email address.';
                event.preventDefault();
            } else {
                emailErrorSpan.textContent = '';
            }

            if (!mobileInput.validity.valid) {
                mobileErrorSpan.textContent = 'Please enter a valid mobile number starting with 60 followed by 6 to 10 digits.';
                event.preventDefault();
            } else {
                mobileErrorSpan.textContent = '';
            }
        });
    </script>
</body>
</html>

<?php 
    } else {
        echo "No customer found for ID: $customerid.";
    }
    $stmt->close();
} else {
    echo "Invalid customer ID.";
}
$conn->close();
?>
