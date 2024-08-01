<!---history-->
<script src="toogledarkmode.js"></script>
<link rel="stylesheet" href="toogledarkmode.css">

<body class="light-mode">
  <button onclick="toogledarkmode()" class="colormode" ><img src="imgicon/dark_mode.png" style="height: 25px;width: 25px;margin-left: -6px;margin-top: -2px;" onclick="imgchangee()"></button>


  <div class="historyrider" style="display: flex;justify-content: center;align-items: center;">
  <div class="history">
    <div class="simpledata-light">
      <img src="../assets\img\user.png" class="imgrider">
      <p class="topp">Rider Id</p>
      <p class="lowp">123456</p>

      <p class="topp">Rider Name</p>
      <p class="lowp">Tan Jian Hau</p>
    </div>

    <div style="margin-top: -280px; margin-left: 280px;">
      <div class="orderinfo-light">
        <h3>Order ID : </h3>
        <p class="pdesign">123443</p>
        <h3 style="margin-top: -10px;">Order Address :</h3>
        <p class="pdesign">12, Jalan 1/2, Taman Seri, 43000 Kajang, Selangor</p>
        <h3 style="margin-top: -10px;">Order Product :</h3>
        <p class="pdesign">Nasi Lemak, Teh Tarik, Roti Canai, Nasi Goreng</p>
        <p style="text-align: right;">x <span>5</span></p>
        <hr>
        <p style="text-align: right;">Total Price : RM <span>5</span></p>
        <hr>
        
        
          <img src="imgicon\riderpagehistory.png" style="height: 35px;width:35px;">
          <p style="margin-top: -25px; margin-left: 50px;">Food has been arrived</p>
          <p style="text-align: right; margin-top: -34px;">2024-04-24 10:45:59</p>
      
        <hr>

      </div>

      <div class="orderinfo">
        <h3>Order ID : </h3>
        <p class="pdesign">123443</p>
        <h3 style="margin-top: -10px;">Order Address :</h3>
        <p class="pdesign">12, Jalan 1/2, Taman Seri, 43000 Kajang, Selangor</p>
        <h3 style="margin-top: -10px;">Order Product :</h3>
        <p class="pdesign">Nasi Lemak, Teh Tarik, Roti Canai, Nasi Goreng</p>
        <p style="text-align: right;">x <span>5</span></p>
        <hr>
        <p style="text-align: right;">Total Price : RM <span>5</span></p>
        <hr>
        
        
          <img src="imgicon\riderpagehistory.png" style="height: 35px;width:35px;">
          <p style="margin-top: -25px; margin-left: 50px;">Food has been arrived</p>
          <p style="text-align: right; margin-top: -34px;">2024-04-24 10:45:59</p>
      
        <hr>

      </div>

      <div class="orderinfo">
        <h3>Order ID : </h3>
        <p class="pdesign">123443</p>
        <h3 style="margin-top: -10px;">Order Address :</h3>
        <p class="pdesign">12, Jalan 1/2, Taman Seri, 43000 Kajang, Selangor</p>
        <h3 style="margin-top: -10px;">Order Product :</h3>
        <p class="pdesign">Nasi Lemak, Teh Tarik, Roti Canai, Nasi Goreng</p>
        <p style="text-align: right;">x <span>5</span></p>
        <hr>
        <p style="text-align: right;">Total Price : RM <span>5</span></p>
        <hr>
        
        
          <img src="imgicon\riderpagehistory.png" style="height: 35px;width:35px;">
          <p style="margin-top: -25px; margin-left: 50px;">Food has been arrived</p>
          <p style="text-align: right; margin-top: -34px;">2024-04-24 10:45:59</p>
      
        <hr>

      </div>




    </div>
      

     
    
    




    <button class="btnback" onclick="indexpage()">
      <a href="index.html" class="word">Previous Page</a>
    </button>
  </div>

</div>

</body>
<style>



  .simpledata-light
  {
      margin-top: 30px;
        width: 260px;
        height: 250px;
        background-color: #fafafa;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
  }
  
  .simpledata-dark
  {
      background-color: #333;
  }


  .orderinfo
  {
    
  }

    .orderinfo-light
    {
      padding: 10px;
    background-color: #fafafa;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    width: 700px;
    height: auto;
    margin-top:30px;    }

    .orderinfo-dark
    {
        background-color: #333;
    }

  
  hr
  {
    border-top: 1px solid rgba(201, 201, 201, 0.513); 
    margin-top: 5px; 
    width: 100%;
  }
  .pdesign
  {
    margin-top:-38px; 
    margin-left:140px;
    width:600px;
    word-wrap: break-word;
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
  function indexpage()
  {
    location.href="index.html"
  }

</script>