<!---orderstatus-->

<body class="light-mode">
  <button onclick="toogledarkmode()" class="colormode" ><img src="imgicon/dark_mode.png" style="height: 25px;width: 25px;margin-left: -6px;margin-top: -2px;" onclick="imgchangee()"></button>

    <div style="display: block; margin-left: auto; margin-right: auto;">
        <div class="orderstatus">
            <h1>Order Status</h1>
            <div class="orderrun" >
                <div class="progressbar">
                    <div class="progress"></div>
                </div>
            
                <p><strong>Status:</strong> Out for Delivery</p>
                <p><strong>Delivery Person:</strong> John Doe</p>
                <p><strong>Contact:</strong> 123-456-7890</p>
            </div>


            <div class="orderdetail">
                <h2>Your Order Details:</h2>
                <p><strong>Order ID:</strong> 123456789</p>
                <p><strong>Delivery Address:</strong> MMU Melaka</p>
                <p><strong>Estimated Delivery Time:</strong> 30 minutes</p>
                <p><strong>Food:</strong></p>
                <ul>
                    <li>Nasi Ayam goreng</li>
                    <li>Air </li>
                    <li>Satay</li>
                </ul>
                <p style="text-align: right;"><strong>Total Amount:</strong> RM 25.00</p>
            </div>
          
          
        </div>
    </div>


    <button class="btnback" onclick="backpage()">
        <a href="index.html" class="word">Previous Page</a>
    </button>

</body>
<style>
    
    .orderdetail
    {
        margin-left: 20px;
        margin-top: -150px;
    }
    .orderrun {
        margin-left: 400px;
    }

    .progressbar {
        width: 300px;
        height: 20px;
        background-color: #f0f0f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress {
        width: 100%;
        height: 100%;
        background-color: #4caf50;
        animation: progress-animation 13s linear infinite;
    }

    @keyframes progress-animation {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    .btnback
    {
        height: 60px;
        width:290px;
        border-radius: 10px;
        background-color: #4781c0db;
        color: #fff;
        text-decoration: none;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-weight: bold;
        font-size: 15px;
        margin-left: auto;
        margin-right: auto;
        display: block;
        margin-top:50px;
    }

    
    .btnback:hover
    {
        background-color: #0056b3;
    }
    .word
    {
        text-decoration: none;
        color: white;
    }
</style>


<script>
     function backpage()
    {
        location.href="index.html";
    }

    
</script>