<?php
include 'admin/db_connect.php';
$category = $conn->query("SELECT * FROM category_list order by id asc");
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        button 
        {
            background-color: white;
            border: 0px;
            width: auto;
            min-width: 127px;
            height: 48px;
        }
        button:hover {
            box-shadow: 2px 2px 2px rgb(135, 135, 219);
        }

        /* Wrapper to center content */
        .wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        /* Styling for menulist */
        .menulist {
            background-color: white;
            display: flex;
            box-shadow: 2px 2px 2px rgb(233, 233, 235);
            width: auto;
            margin-bottom: 30px;
            margin-left: -30px; /* Consider revising this if it causes issues */
        }
        .search_func {
            display: flex;
            justify-content: center;
            margin-top: -45px;
            margin-right:-780px;
            margin-bottom: 30px;
        }

       

    </style>
</head>
<h2 style="text-align:center;font-weight:bold;margin-top:120px;margin-left:-800px;">MENU</h2>

<span class="search_func">
    <input type="text" id="search" placeholder="Enter search term" >
    <button onclick="searchprod()" style="background-color: #4CAF50; color: white;margin-left:10px;">Search</button>
</span>

<div class="wrapper">
        <div class="menulist">            <!-- Category buttons -->
            <?php while($row = $category->fetch_assoc()): ?>
                <button onclick="scrollToSection('<?php echo $row['name'] ?>')" style="text-transform: capitalize; background-color: white; border: 0px; width:auto;height: 48px;">
                    <p style="color: gray; font-size: 15px;"><?php echo $row['name'] ?></p>
                </button>
            <?php endwhile; ?>
        </div>
    </div>


<body>
<div class="menupage" style="margin-left: 30%; margin-right: 50%; width: 940px; padding: 25px;">
    
    <div>
  
        <?php
        // Reset the internal pointer of $category to the beginning
        $category->data_seek(0);

        // Loop through categories to display menu items under each category
        while($row = $category->fetch_assoc()):
            $category_id = $row['id'];
            $category_name = $row['name'];
        ?>
        <div id="<?php echo $category_name; ?>" style="width: 940px;">
            <h2 style="text-transform: capitalize;font-weight:bold;font-size:27px;margin-left:-120px"><?php echo $category_name; ?></h2>
            <div class="mmm" style="display: flex; flex-wrap: wrap;margin-left:-190px;margin-top:-65px;margin-bottom:10px;">
                <!-- Fetch products for the current category -->
                <?php
                $qry = $conn->query("SELECT * FROM product_list WHERE category_id = $category_id ORDER BY `name`");
                while ($row = $qry->fetch_assoc()) :
                ?>
                <a href="index.php?productpage=previewproduct&product_id=<?php echo $row['id'] ?>">
                    <div class="" style="width:220px; margin-bottom:20px; color:black; border-radius: 20px; margin-right:30px; margin-bottom:4px; margin-top:1px;">
                        <div class="card" style="width:220px; height:330px;margin:60px; border-radius: 25px; margin-bottom:5px;">
                            <img class="card-img-top view_prod" src="assets/img/<?php echo $row['img_path'] ?>" alt="Card image cap" style="border-radius: 20px; height:220px; width: 220px;" data-id="<?php echo $row['id'] ?>">
                            <div class="card-body">
                                <h5 class="card-title" style="text-align: left; margin-left: 0;"><?php echo $row['name'] ?></h5>
                                <p class="card-text" style="text-align: left; margin-left: 0; font-weight: bold;">RM<?php echo number_format($row['price'], 2) ?></p>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
    <script>
      function scrollToSection(sectionId) {
    var section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
        // Wait for the smooth scrolling to complete, then adjust the scroll position
        setTimeout(function() {
            var yOffset = -80; // Offset value
            var y = section.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({ top: y, behavior: 'smooth' });
        }, 500); // Adjust the timeout value if necessary
    }
}

        
        function searchprod() {
    var searchTerm = document.getElementById('search').value.toLowerCase();
    var cards = document.getElementsByClassName('card');

    var found = false;

    // Loop through each card
    for (var i = 0; i < cards.length; i++) {
        var card = cards[i];
        var productName = card.getElementsByClassName('card-title')[0].innerText.toLowerCase();
        var productPrice = card.getElementsByClassName('card-text')[0].innerText.toLowerCase();
        var sectionId = card.closest('div[id]').id;
        var categoryName = sectionId.toLowerCase();

        // Check if product name, price, or category name contains the search term
        if (productName.includes(searchTerm) || productPrice.includes(searchTerm) || categoryName.includes(searchTerm)) {
            scrollToSection(sectionId);
            found = true;
            break; // Exit loop after first occurrence is found
        }
    }

    if (!found) {
        alert('No matching results found.');
    }
}



        // JavaScript for displaying product details
        $('.view_prod').click(function() {
            uni_modal_right('Product Detail', 'view_prod.php?id=' + $(this).attr('data-id'));
        });
    </script>
</body>
</html>
