<?php
include "admin/db_connect.php";
$balance =  $conn->query("SELECT * FROM user_info where user_id = ".$_SESSION['login_user_id'])->fetch_array()['walletbalance'];
$discountPoints =  $conn->query("SELECT * FROM user_info where user_id = ".$_SESSION['login_user_id'])->fetch_array()['discount_point'];
$_SESSION['ewallet_balance'] = $balance;
$_SESSION['discount_points'] = $discountPoints;
?>


<head>

    <title>EWallet Pak Malau</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        .container-name {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            margin-top: 70px;
        }
        .ewallet-container {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.2);
            max-width: 450px;
            width: 90%;
            position: relative;
            overflow: hidden;
        }
        .ewallet-container::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background: #0070ba;
            z-index: -1;
            transform: rotate(45deg);
            border-radius: 50%;
            transition: transform 0.5s;
        }
        .ewallet-container:hover::before {
            transform: rotate(0);
        }
        h1 {
            color: #fff;
            background: linear-gradient(45deg, #0070ba, #00d4ff);
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: glow 1.5s infinite alternate;
        }
        @keyframes glow {
            from {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3), 0 0 10px #0070ba, 0 0 20px #00d4ff;
            }
            to {
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3), 0 0 20px #0070ba, 0 0 40px #00d4ff;
            }
        }
        p {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .balance, .discount-points {
            font-size: 22px;
            font-weight: bold;
            color: #009688;
            margin-bottom: 20px;
        }
        .voucher-section {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: calc(100% - 40px);
            margin-right: 10px;
            transition: border 0.3s;
        }
        input[type="text"]:focus {
            border-color: #0070ba;
            outline: none;
        }
        button {
            background: linear-gradient(45deg, #0070ba, #00d4ff);
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            transition: background 0.3s, transform 0.3s;
            position: relative;
            overflow: hidden;
        }
        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: transform 0.3s;
            transform: scaleX(0);
            transform-origin: left;
        }
        button:hover::before {
            transform: scaleX(1);
        }
        button:hover {
            transform: translateY(-3px);
            background: linear-gradient(45deg, #005087, #00a1d4);
        }
        .img_logo {
            max-height: 200px;
            margin-bottom: 20px;
        }
        .user-info {
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>

    <div class="bodyewallet">
    <div class="container-name">
        <div class="ewallet-container">
            <div>
                <img src="images/logo.png" alt="User Image" class="img_logo">
            </div>
            <div class="user-info">
                <p>Username: <?php echo $_SESSION['login_email']; ?></p>
            </div>
            <h1>EWallet Pak Malau Satay</h1>
            <div class="balance">
                Balance In The Wallet: RM <?php echo $balance; ?>
            </div>
            <form id="claimVoucherForm" method="post" action="claim_voucher.php">
                <div class="voucher-section">
                    <input type="text" id="voucherCode" name="voucherCode" placeholder="Enter voucher code">
                    <button type="submit">Claim Voucher</button>
                </div>
            </form>
            <div class="discount-points">
                Discount Points: <?php echo $discountPoints; ?>
            </div>
            <button onclick="paymentfunc()">Top Up</button>
            <button>Withdraw</button>
        </div>
    </div>
    </div>

    <script>
       function paymentfunc() {
            let amount;
            while (true) {
                amount = prompt('Enter amount to top up');
                if (amount === null) return; // User canceled the prompt
                amount = parseFloat(amount);
                if (!isNaN(amount) && amount > 0) break;
                alert('Please enter a valid positive number greater than 0');
            }
            const form = document.createElement('form');
            form.action = 'ewallettopup.php';
            form.method = 'get';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'amount';
            input.value = amount;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    </script>

