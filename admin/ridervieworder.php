<!---view order detail-->

<script src="toogledarkmode.js"></script>
<link rel="stylesheet" href="toogledarkmode.css">

<body class="light-mode">
  <button onclick="toogledarkmode()" class="colormode" ><img src="imgicon/dark_mode.png" style="height: 25px;width: 25px;margin-left: -6px;margin-top: -2px;" onclick="imgchangee()"></button>

    <div class="view" >
        <div class="intop" style="text-align: center;">
            <img src="imgicon\food.png" style="width: 60px;height:60px;">
            <p style="font-weight: bold;">Order ID : </p><p>12345</p>
            <p style="font-weight: bold;">Order Address : </p>MMU Melaka<p></p>
            <p style="font-weight: bold;">Order Time : </p><p>12:00 PM</p>
            <br>
            <hr>
            <p style="font-size: 25px;font-weight: bold;">Order Details</p>
            <hr>
        </div>
        <div class="forfood" style="width: 1000px;display: block; margin-left: auto;margin-right: auto;">
            <div class="food" style="height: 120px; margin-top: 20px;">
                <img src="imgicon\food.png" class="imgfood">
                <p class="foodname">Nasi Ayam Goreng</p>
                <p class="fooddescription">Hidangan nasi ayam ini dibuat dengan nasi yang dimasak menggunakan air rebusan ayam dan ayam yang diperapkan dengan sos campuran bawang merah, bawang putih, dan halia sebelum digoreng. Ayam akan dihidangkan bersama dengan nasi, kuah kicap dan sos cili. Hidangan ini boleh dihidangkan dengan atau tanpa sayur-sayuran.</p>
                <p class="foodquantity">Quantity : 10</p>
                <p class="foodprice">Price Per: RM 5.50</p>
                <hr style="margin-top:50px; border: 1px solid rgba(201, 201, 201, 0.513);">
            </div>

            <div class="food" style="height: 120px; margin-top: 20px;">
                <img src="imgicon\food.png" class="imgfood">
                <p class="foodname">Nasi Ayam Goreng</p>
                <p class="fooddescription">Hidangan nasi ayam ini dibuat dengan nasi yang dimasak menggunakan air rebusan ayam dan ayam yang diperapkan dengan sos campuran bawang merah, bawang putih, dan halia sebelum digoreng. Ayam akan dihidangkan bersama dengan nasi, kuah kicap dan sos cili. Hidangan ini boleh dihidangkan dengan atau tanpa sayur-sayuran.</p>
                <p class="foodquantity">Quantity : 10</p>
                <p class="foodprice">Price Per: RM 5.50</p>
                <hr style="margin-top:50px; border: 1px solid rgba(201, 201, 201, 0.513);">
            </div>

            <div class="food" style="height: 120px; margin-top: 20px;">
                <img src="imgicon\food.png" class="imgfood">
                <p class="foodname">Nasi Ayam Goreng</p>
                <p class="fooddescription">Hidangan nasi ayam ini dibuat dengan nasi yang dimasak menggunakan air rebusan ayam dan ayam yang diperapkan dengan sos campuran bawang merah, bawang putih, dan halia sebelum digoreng. Ayam akan dihidangkan bersama dengan nasi, kuah kicap dan sos cili. Hidangan ini boleh dihidangkan dengan atau tanpa sayur-sayuran.</p>
                <p class="foodquantity">Quantity : 10</p>
                <p class="foodprice">Price Per: RM 5.50</p>
                <hr style="margin-top:50px; border: 1px solid rgba(201, 201, 201, 0.513);">
            </div>
        </div>
    </div>

    <button class="btnback" onclick="takeorderpage()">
        <a href="takeorder.html" class="word">Previous Page</a>
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


    .imgfood
    {
        height: 105px;
        width:105px;
        
    }
    .foodname
    {
        margin-top: -100px;
        margin-left: 115px;
        font-size: 20px;
        font-weight: bold;
    }

    .fooddescription
    {
        height:60px;
        width:600px;
        font-size:15px;
        margin-left:115px;
        word-wrap: break-word;
        overflow: hidden;
        /* Add a scrollbar for overflowed content */
        overflow-y: auto;
    }
    .foodquantity
    {
        margin-left:800px;
        margin-top:-100px;
    }

    .foodprice
    {
        margin-left:800px;
    }
</style>

<script>
    function takeorderpage()
    {
        location.href = "takeorder.html";
    }
</script>