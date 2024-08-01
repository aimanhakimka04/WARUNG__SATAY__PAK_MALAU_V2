<?php  
include 'db_connect.php';


// Assuming $conn is your database connection
$qry = $conn->query("SELECT * FROM orders WHERE status = 1");

if ($qry) {
    while ($row = $qry->fetch_assoc()) {
        ?>
        <div class="chooseorder" style="display: block; margin-left: auto; margin-right: auto; padding: 30px;">

            <div class="tocenter" style="text-align: center;">
                <img src="../assets/img/user.png" alt="chooseorder" style="width: 100px; height: 100px; margin-left: -600px;">
                <div style="margin-top: -90px; margin-left: -340px;">
                    <p style="font-size: 15px; font-weight: bold;"><?php echo $row['name']; ?></p>
                    <p style="color: gray; margin-left: -55px;">23km</p>
                </div>
                <button class="btngetorder" onclick="pagetakeorder()">
                    <a href="takeorder.html" class="word">Get Order</a>
                </button>
                
                <hr style="border-top: 1px solid rgba(201, 201, 201, 0.513); margin-top: 30px; width: 750px;">

                <div class="detailorder" style="display: flex; justify-content: center; align-items: center; margin-left: auto; margin-right: auto;">
                    <img src="assets\img\calander.png" class="formarimg" alt="Date">
                    <p class="formar"><?php echo $row['date']; ?></p>
                    <img src="assets\img\time.png" class="formarimg" alt="Duration">
                    <p class="formar"><?php echo $row['duration']; ?></p>
                    <img src="assets\img\cash.png" class="formarimg" alt="Price">
                    <p class="formar"><?php echo $row['price']; ?></p>     
                </div>
            </div>
        </div>
        <?php
    }
}
?>






    
</div>

<button class="btnback" onclick="backpage()">
    <a href="index.html" class="word">Previous Page</a>
  </button>

</body>
<style>
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

    .btngetorder {
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
        height: 50px;
        width: 140px;
        margin-top: -70px;
        margin-left: 500px;
    }

    .btngetorder:hover {
        background-color: #0056b3;
    }

    .formarimg
    {
        margin-left: 80px;
        width: 40px;
        height: 40px;
        
    }
    .formar {
        margin-left: 10px;
        margin-right: 30px;
    }



</style>

<script>
    function pagetakeorder()
    {
        location.href="takeorder.html";
    }

    function backpage()
    {
        location.href="index.html";
    }

    
</script>