<?php
include 'db_connect.php';
$order_id = $_GET['id'];
$qrydelivery = $conn->query("SELECT * FROM users where type = 2");
?>
<style>
    .formdp {
        background-color: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: 350px;
        text-align: center;
        animation: fadeIn 1s ease-in-out;
        margin: 0 auto;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .formdp h2 {
        color: #333;
        margin-bottom: 20px;
    }

    .form-groupdp {
        margin-bottom: 20px;
        text-align: left;
    }

    .formdp label {
        display: block;
        margin-bottom: 8px;
        color: #555;
    }

    .formdp input,
    .formdp select,
    .formdp button {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }

    .formdp input:focus,
    .formdp select:focus {
        border-color: #007BFF;
        outline: none;
    }

    .formdp button {
        background-color: #007BFF;
        color: #fff;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .formdp button:hover {
        background-color: #0056b3;
    }

    .modal-footer {
        display: none;
    }
</style>

<div class="formdp">
    <h2>Select Staff To Deliver Order</h2>
    <form id="form1">
        <div class="form-groupdp">
            <label for="order-id">Order ID</label>
            <input type="text" id="order-id" name="orderid" value="<?php echo htmlspecialchars($order_id); ?>" readonly>
        </div>
        <div class="form-groupdp">
            <label for="staff-name">Staff Name</label>
            <select id="staff-name" name="staffid" required>
                <option value="" disabled selected>Select staff</option>
                <?php while ($row = $qrydelivery->fetch_assoc()) : ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>" name="staffId"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="button" onclick="confirmOrder()">Confirm</button>
        <button type="button" data-dismiss="modal">Close</button>
    </form>
</div>

<?php
//include 'footeradmin.php';
?>
<script>
    function confirmOrder() {
        const orderId = document.getElementById('order-id').value;
        const staffName = document.getElementById('staff-name').value;

        if (orderId && staffName) {
            start_load();
            $.ajax({
                url: 'ajax.php?action=readydelivery_order',
                method: 'POST',
                data: $('#form1').serialize(),
                error: function(err) {
                    console.error(err);
                    alert("An error occurred. Please try again.");
                },
                success: function(resp) {
                    const response = JSON.parse(resp);
                    if (response.status == 1) {
                        alert_toast(response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        alert(response.message);
                    }
                }
            });
        } else {
            alert('Please fill in all fields.');
        }
    }

    // Ensure start_load function exists

</script>

