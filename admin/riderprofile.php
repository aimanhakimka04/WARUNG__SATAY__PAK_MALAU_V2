<!---profile-->
<?include 'admin/db_connect.php'?>
<script src="toogledarkmode.js"></script>
<link rel="stylesheet" href="toogledarkmode.css">


  <div style="display: flex;justify-content: center;align-items: center;">
    <div class="riderprofile">
        <div class="simpledata">
            <img src="../assets\img\user.png" class="imgrider">
            <p class="topp">Rider Id</p>
            <p class="lowp"><?php echo  $_SESSION['login_id']; ?></p>

            <p class="topp">Rider Name</p>
            <p class="lowp"><?php echo  $_SESSION['login_username']; ?></p>
        </div>

        <div class="fulldata">
            <div class="datadesign">
                <h2>
                    Rider Information
                </h2>
                <h3>
                    Username :
                </h3>
                <input type="text" value="<?php echo  $_SESSION['login_username']; ?>" placeholder="<?php echo $userData['login_username']; ?>" class="textdesign">
                <h3>
                    Name :
                </h3>
                <input type="text" value="<?php echo  $_SESSION['login_name']; ?>" placeholder="<?php echo  $_SESSION['login_name']; ?>" class="textdesign">

                <h3>
                    Email : 
                </h3>
                <input type="text" value="<?php echo  $_SESSION['login_email']; ?>" placeholder="<?php echo  $_SESSION['login_email']; ?>" class="textdesign">

                <h3>
                    Phone Number :
                </h3>
                <input type="text" value="<?php echo  $_SESSION['login_phone_no']; ?>" placeholder="<?php echo  $_SESSION['login_phone_no']; ?>" class="textdesign">
                <h3>
                    Gender : 
                </h3>
                <select name="gender" id="gender" class="selectbox">
                <option value="1" >Male</option>
                    
                </select>
                <h3>
                    Current Password :
                </h3>
                <input type="password" value="123456" placeholder="123456" id="mypass" class="textdesign"> 
                <input type="checkbox" onclick="showpass()">Show Password
                <br>
                <button class="btnupdate">
                    Update
                </button>
                <button class="btncancel">
                    Cancel
                </button>
            </div>
        </div>

     
    </div>
</div>

</body>
<style>
    .btnupdate
    {
        margin-top: 40px;
        margin-left: 265px;
        width:200px;
        height:45px;    
        border-radius: 10px;
        text-decoration: none;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-weight: bold;
        font-size: 15px;
        background-color: rgb(31, 204, 146);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .btnupdate:hover
    {
        background-color: rgb(0, 128, 91);
    }

    .btncancel
    {
        width:200px;
        height:45px;    
        border-radius: 10px;
        text-decoration: none;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-weight: bold;
        font-size: 15px;
        background-color: rgb(199, 0, 0);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-left: 10px;
    }

    .btncancel:hover
    {
        background-color: rgb(128, 0, 0);
    }

    .btnhistory
    {
        margin-top: 30px;
        width:200px;
        height:45px;    
        border-radius: 10px;
        text-decoration: none;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-weight: bold;
        font-size: 15px;
        background-color: #4781c0db;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-left:30px;
        margin-right:30px;
    }

    .btnhistory:hover
    {
        background-color: #0056b3;
    }

    .selectbox
    {
        margin-top:-10px;
        width:100px;
        height:45px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        border:0px;
        text-indent: 10px;
    }
    .textdesign
    {
        margin-top:-10px;
        width:500px;
        height:45px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        border:0px;
        text-indent: 10px;
    }

    .riderprofile
    {
        display: block;
        margin-left: auto;
        margin-right: auto;
        padding: 50px;
        font-size:16px;
    }


    .historypage
    {
        margin-top: 30px;
        width: 260px;
        height: 100px;
        background-color: #fafafa;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    .simpledata
    {
        width: 260px;
        height: 250px;
        background-color: #fafafa;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .fulldata
    {
        width: 740px;
        height: 750px;
        background-color: #fafafa;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin-top: -250px;
        margin-left:290px;
    }

    .datadesign
    {
        padding: 30px;
    }

    .imgrider
    {
        width:95px;
        height:95px;
        border-radius: 50px;
        text-align: center;
        padding:10px;
        border:1.5px solid #fafafa80;
        margin-left: auto;
        margin-right: auto;
        display: block;
    }

    h3
    {
        font-size:20px;
        margin-bottom: 10px;
    }
   
    .topp
    {
        font-size: 15px;
        font-weight: bold;
        text-align: center;
    }

    .lowp
    {
        font-size: 13px;
        margin-top: -15px;
        text-align: center;
        width: 240px;
        margin-left: 10px;
        word-wrap: break-word;
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
    function showpass()
    {
        var x = document.getElementById("mypass");
        if (x.type == "password")
        {
            x.type = "text";
        }
        else 
        {
            x.type = "password";
        }

        return 0;
    }//end showpass


    function backpage()
    {
        location.href="index.html";
    }

    function historypage()
    {
        location.href="history_rider.html"
    }
</script>