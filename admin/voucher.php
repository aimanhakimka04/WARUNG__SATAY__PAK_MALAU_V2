<?php include('db_connect.php');?>
<?php include('header.php');?>
<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<?php include('footeradmin.php');?>

<div class="container">
    <h1>Create Voucher Code</h1>
    <button onclick="createVoucher()">Create Voucher</button>
    <h2>Existing Voucher Codes</h2>
    <table id="voucherTable" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Voucher Code</th>
                <th>Voucher Points</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch existing voucher codes from the database
            $result = $conn->query("SELECT * FROM voucher");
            $i = 1; // Initialize the counter
            while ($row = $result->fetch_assoc()) {
                echo "<tr id='voucherRow_{$row['v_id']}'><td>{$i}</td><td>{$row['v_code']}</td><td>{$row['v_point']}</td><td><button onclick='deleteVoucher({$row['v_id']})'>Delete</button></td></tr>";
                $i++; // Increment the counter
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('footeradmin.php'); ?>

<style>
    .container {
        margin: 20px;
    }
    h1, h2 {
        color: #0070ba;
    }
    button {
        background-color: #0070ba;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-bottom: 20px;
    }
    button:hover {
        background-color: #005087;
    }
</style>

<script>
    function createVoucher() {
    var numVouchers = prompt("How many vouchers do you want to create?");
    var voucherPoints = prompt("How many points for each voucher?");

    // Check if the input is not empty
    if (numVouchers !== null && voucherPoints !== null) {
        // Check if the input is a number
        if (!isNaN(numVouchers) && !isNaN(voucherPoints)) {
            // Check if the input is a valid number and total number of vouchers not exceeding 100
            if (Number.isInteger(parseInt(numVouchers)) && !isNaN(parseFloat(voucherPoints)) && parseInt(numVouchers) <= 100) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "create_voucher.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var newVouchers = JSON.parse(xhr.responseText);
                        var table = document.getElementById('voucherTable').getElementsByTagName('tbody')[0];
                        newVouchers.forEach(function(newVoucher) {
                            var newRow = table.insertRow();
                            var cell1 = newRow.insertCell(0);
                            var cell2 = newRow.insertCell(1);
                            var cell3 = newRow.insertCell(2);
                            var cell4 = newRow.insertCell(3);
                            cell1.innerHTML = table.rows.length;
                            cell2.innerHTML = newVoucher.v_code;
                            cell3.innerHTML = newVoucher.v_points;
                            cell4.innerHTML = '<button onclick="deleteVoucher(' + newVoucher.v_id + ')">Delete</button>';
                        });
                        location.reload();
                    }
                };
                xhr.send("num_vouchers=" + numVouchers + "&v_points=" + voucherPoints);
            } else {
                alert("Please fill in a valid value for both quantity of vouchers and voucher points. The total number of vouchers should not exceed 100.");
            }
        } else {
            alert("Please fill in a valid value for both quantity of vouchers and voucher points.");
        }
    } else {
        alert("Please fill in both quantity of vouchers and voucher points.");
    }
}



    function deleteVoucher(voucherId) {
        if (confirm("Are you sure you want to delete this voucher?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_voucher.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    if (xhr.responseText == 'success') {
                        // Remove the voucher row from the table
                        var row = document.getElementById('voucherRow_' + voucherId);
                        row.parentNode.removeChild(row);
                        location.reload();
                    } else {
                        alert("Failed to delete voucher.");
                    }
                }
            };
            xhr.send("voucher_id=" + voucherId);
        }
    }
</script>
