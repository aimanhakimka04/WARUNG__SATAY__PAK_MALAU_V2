




<div class="container mt-3">
     <div class="row">
         <div class="col-12">
             <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                 <ol class="carousel-indicators">
                     <li data-target="#carouse" data-slide-to="0" class="active"></li>
                     <li data-target="#carouse" data-slide-to="1"></li>
                     <li data-target="#carouse" data-slide-to="2"></li>
                 </ol>
                 <div class="carousel-inner">
                     <div class="carousel-item active">
                         <img src="../assets/img/<?php echo $_SESSION['setting_Billboard1']; ?>" alt="First slide" id="slideimg1" style="width:100% ; height:100%">
                         <div class="carousel-caption d-none d-md-block">

                         </div>
                     </div>
                     <div class="carousel-item">
                         <img src="../assets/img/<?php echo $_SESSION['setting_Billboard2']; ?>" alt="Second slide" id="slideimg2" style="width:100% ; height:100%">
                         <div class="carousel-caption d-none d-md-block">

                         </div>
                     </div>
                     <div class="carousel-item">
                         <img src="../assets/img/<?php echo $_SESSION['setting_Billboard3']; ?>" alt="Third slide" id="slideimg3" style="width:100%; height:100%">
                         <div class="carousel-caption d-none d-md-block">
                         </div>
                     </div>
                 </div>
                 <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                     <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                     <span class="sr-only">Previous</span>
                 </a>
                 <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                     <span class="carousel-control-next-icon" aria-hidden="true"></span>
                     <span class="sr-only">Next</span>
                 </a>
             </div>
         </div>
     </div>
     <hr>
 </div>
 <div class="container"><!---
     <div class="row">
         <div class="col-4">
             <div class="row">
                 <div class="col-2"><img class="rounded-circle" alt="Free Shipping" src="images/40X40.gif"></div>
                 <div class="col-lg-6 col-10 ml-1">
                     <h4>desc 1</h4>
                 </div>
             </div>
         </div>
         <div class="col-4">
             <div class="row">
                 <div class="col-2"><img class="rounded-circle" alt="Free Shipping" src="images/40X40.gif"></div>
                 <div class="col-lg-6 col-10 ml-1">
                     <h4>desc 2</h4>
                 </div>
             </div>
         </div>
         <div class="col-4">
             <div class="row">
                 <div class="col-2"><img class="rounded-circle" alt="Free Shipping" src="images/40X40.gif"></div>
                 <div class="col-lg-6 col-10 ml-1">
                     <h4>Low Prices</h4>
                 </div>
             </div>
         </div>
     </div>--->
 </div>
 <hr>
 <h2 class="text-center">RECOMMENDED PRODUCTS</h2>
 <hr>
 
 <div class="container">
    <div class="navigation"></div>
        <div class="row text-center product-container">
            <?php
             include 'admin/db_connect.php';
             $limit = 10;
             $page = (isset($_GET['_page']) && $_GET['_page'] > 0) ? $_GET['_page'] - 1 : 0; //default page is 1
             $offset = $page > 0 ? $page * $limit : 0; //what is this for? this is for the pagination query
             $all_menu = $conn->query("SELECT id FROM  product_list")->num_rows; //get the total count of all menu
             $page_btn_count = ceil($all_menu / $limit); //get the total number of pages
             $qry = $conn->query("SELECT * FROM product_list ORDER BY total_sold DESC LIMIT 10");
                       while ($row = $qry->fetch_assoc()) : //loop through the menu
             ?>
              <a href="index.php?productpage=previewproduct&product_id=<?php echo $row['id'] ?>"><div class="" style="width:220px; margin-bottom:20px; color:black; border-radius: 20px; margin-right:30px; margin-bottom:4px; margin-top:1px; ">
                  <div class="card" style="width:220px; height:330px;margin:60px; border-radius: 25px; margin-bottom:5px;">
                  
     <img class="card-img-top view_prod" src="assets/img/<?php echo $row['img_path'] ?>" alt="Card image cap" style="border-radius: 20px; height:220px; width: 220px;" data-id="<?php echo $row['id'] ?>">
 
                      <div class="card-body view_prod" data-id="<?php echo $row['id'] ?>">
                          <h5 class="card-title" style="text-align: left; margin-left: 0; overflow:hidden;"><?php echo $row['name'] ?></h5>
                          <?php $satay = $row['category_id'] == 13 ? "/pcs" : "" ?>
                         <p class="card-text" style="text-align: left; margin-left: 0; font-weight: bold;">RM<?php echo number_format($row['price'], 2);  echo $satay?></p>
                      </div>
                  </div>
              </div></a>
          <?php endwhile; ?>
        </div>
        
            <button class="prev"><img src="/images/Double Left.png" class="arrow-button left"/></button>
            <button class="next"><img src="/images/Double Right.png" class="arrow-button right"/></button>
        </div>
    </div>



 <hr>
 <h2 class="text-center">FEATURED PRODUCTS</h2>
 <hr>
 <div class="container">
     <div class="row">
        <div class="row text-center">
            <?php
                include 'admin/db_connect.php';
                $limit = 10;
                $page = (isset($_GET['_page']) && $_GET['_page'] > 0) ? $_GET['_page'] - 1 : 0; //default page is 1
                $offset = $page > 0 ? $page * $limit : 0; //what is this for? this is for the pagination query
                $all_menu = $conn->query("SELECT id FROM  product_list")->num_rows; //get the total count of all menu
                $page_btn_count = ceil($all_menu / $limit); //get the total number of pages
                $qry = $conn->query("SELECT * FROM  product_list order by `name` asc Limit $limit OFFSET $offset "); //get the menu for the current page 
                while ($row = $qry->fetch_assoc()) : //loop through the menu
                ?>
                             <a href="index.php?productpage=previewproduct&product_id=<?php echo $row['id'] ?>"><div class="" style="width:220px; margin-bottom:20px; color:black; border-radius: 20px; margin-right:30px; margin-bottom:4px; margin-top:1px; ">
                    <div class="card" style="width:220px; height:370px;margin:60px; border-radius: 25px; margin-bottom:5px;">
                    
    <img class="card-img-top view_prod" src="assets/img/<?php echo $row['img_path'] ?>" alt="Card image cap" style="border-radius: 20px; height:220px; width: 220px;" data-id="<?php echo $row['id'] ?>">
                       <div class="card-body">
                       <h5 class="card-title" style="text-align: left; margin-left: 0; overflow:hidden;"><?php echo $row['name'] ?></h5>
                          <?php $satay = $row['category_id'] == 13 ? "/pcs" : "" ?>
                         <p class="card-text" style="text-align: left; margin-left: 0; font-weight: bold;">RM<?php echo number_format($row['price'], 2);  echo $satay?></p>
                        </div>
                    </div>
                </div></a>
            <?php endwhile; ?>
        </div>
     </div>
 </div>
 
 <hr> 



 </body>



 </section>

 <script>
 $(document).ready(function(){
            var slideCount = $('.product-container').children().length; //children is the number of products
            var currentIndex = 0;

            function showProducts(index) {
                var visibleItems = $('.product-container').children().slice(index, index + 4);
                $('.product-container').children().hide();
                visibleItems.show();
            }

            // Show initial set of products
            showProducts(currentIndex);

            // Next button click handler
            $('.next').click(function() {
                if (currentIndex < slideCount - 4) {
                    currentIndex++;
                    showProducts(currentIndex);
                }
            });

            // Previous button click handler
            $('.prev').click(function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    showProducts(currentIndex);
                }
            });
        });
</script>
<style>
     .container {
            position: relative;
        }
        .product-container {
            overflow: hidden;
            white-space: nowrap;
        }
        .product-item {
            display: inline-block;
            vertical-align: top;
            margin: 0 10px; /* Adjust spacing between products */
        }
        .navigation {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }
        .prev, .next {
            background-color: #ffffff;
            border: none;
            color: #000000;
            cursor: pointer;
            padding: 10px;
            font-size: 18px;
            transition: background-color 0.3s;
            position: absolute;
            top: 0;
            bottom: 0;
            margin: auto;
        }
        .prev {
            left: -40px; /* Adjust position */
        }
        .next {
            right: -40px; /* Adjust position */
        }

        button, .arrow-button {
            width: 100px;
            height: 100px;
            background-color: transparent;
        }
        .arrow-button.right {
            margin-left: -65px;
        }
        .arrow-button.left {
            margin-left: -50px;
          
        }
</style>