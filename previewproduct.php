<?php

use Google\Service\Sheets\NumberFormat;

include 'admin/db_connect.php';

// Sanitize input to prevent SQL injection
$id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 11;
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

$qry = $conn->query("SELECT * FROM product_list WHERE id = $id")->fetch_array(); //fetch_array is used to fetch the data from the database

$newtype = false;

$totalprice = number_format($qry['price'], 2) * $qty;

$drink = $conn->query("SELECT * FROM product_list WHERE category_id = 17");

$addon = $conn->query("SELECT * FROM product_list WHERE category_id = 18");




?>



<script>
				localStorage.removeItem('selectedDrink');
			</script>";
<html>
<div class="menuitem" style="margin-left: 25%; margin-right: 25%; width: 940px; padding: 25px;">



    <?php if ($qry['category_id'] == 13) : ?>
        <div id="circle">
            <div id="plate"></div> <!-- Place the plate inside the circle container -->
            <div id="kuah"></div> <!-- Place the kuah inside the circle container -->
        </div>

        <label for="mangkukTimunImage">Need Mangkuk Timun? :</label>
        <select id="mangkukTimunImage">
            <option value="images/mangkuk_timun.png">Yes</option>
            <option value="images/mangkuk_timun2.png">No</option>
            <!-- Add more options here if needed -->
        </select>

        <label for="bawangImage">Need Bawang? :</label>
        <select id="bawangImage">
            <option value="images/bawang.png">Yes</option>
            <option value="images/bawang2.png">No</option>
            <!-- Add more options here if needed -->
        </select>

        <label for="kuahImage">Need Free Kuah ?:</label>
        <select id="kuahImage">
            <option value="images/kuah.png">Yes</option>
            <option value="images/kuah2.png">No</option>
            <!-- Add more options here, each option corresponds to a kuah image -->
        </select>
    <?php elseif ($qry['category_id'] != 13) : ?>
        <div class="menuimage" style="margin-top:30px;">
            <img src="assets/img/<?php echo $qry['img_path'] ?>" style="height: 360px; width: 540px; border-radius: 20px;">
        </div>
    <?php endif; ?>
    

    <script>
    // Function to map image paths to corresponding text values
    function getImageText(imagePath) {
        switch (imagePath) {
            case 'images/mangkuk_timun.png':
                return 'Need Timun';
            case 'images/mangkuk_timun2.png':
                return 'No need Timun';
            case 'images/bawang.png':
                return 'Need Bawang';
            case 'images/kuah.png':
                return 'Need kuah';
            case 'images/bawang2.png':
                return 'No need Bawang';
            case 'images/kuah2.png':
                return 'No need kuah';
            default:
                return '';
        }
    }

    // Function to save selected option to local storage
    function saveToLocalStorage(id) {
        var selectedOption = document.getElementById(id).value;
        var textValue = getImageText(selectedOption);
        localStorage.setItem(id, textValue);
    }

    // Event listeners for select elements
    document.getElementById('mangkukTimunImage').addEventListener('change', function() {
        saveToLocalStorage('mangkukTimunImage');
    });

    document.getElementById('bawangImage').addEventListener('change', function() {
        saveToLocalStorage('bawangImage');
    });

    document.getElementById('kuahImage').addEventListener('change', function() {
        saveToLocalStorage('kuahImage');
    });


    // Check local storage on page load and set selected options
    document.addEventListener('DOMContentLoaded', function() {
        var mangkukTimunImage = localStorage.getItem('mangkukTimunImage');
        if (mangkukTimunImage) {
            var selectElement = document.getElementById('mangkukTimunImage');
            var index = Array.from(selectElement.options).findIndex(option => getImageText(option.value) === mangkukTimunImage);
            if (index !== -1) {
                selectElement.selectedIndex = index;
            }
        }

        var bawangKuahImage = localStorage.getItem('bawangKuahImage');
        if (bawangKuahImage) {
            var selectElement = document.getElementById('bawangKuahImage');
            var index = Array.from(selectElement.options).findIndex(option => getImageText(option.value) === bawangKuahImage);
            if (index !== -1) {
                selectElement.selectedIndex = index;
            }
        }
        
    });
</script>










    <div class="menuiteminfo">
        <div class="menuiteminfochild">
            <?php if ($newtype == true) : ?>
                <span><img src="https://www.rera.tn.gov.in/cms/images/new.gif" style="height: 22px;width:45px; background-color: gainsboro; border-radius: 10px; margin-top: 5px;"></span>
            <?php endif ?>
            <br>
            <br>
            <div style="display: flex; justify-content: space-between; margin-bottom:10px;">
                <span style="font-weight: bold; text-transform: capitalize; font-size: 18px;"><?php echo $qry['name'] ?></span>
                <span style="font-weight: bold; font-size: 15px;"><?php echo "RM" . number_format($qry['price'], 2) ?></span>
                <p>Stock: <span style="font-weight: bold; font-size: 15px;" class="stock"><?php echo number_format($qry['quantity']) ?></span></p>


            </div>


            <span>
                <p class="product-decs"><?php echo $qry['name'] ?></p>
                <div id="addedproduct">

                </div>
                <div id="addedproductaddon">

                </div>

            </span>
            <div class="bttn-cart">
                <span style="text-align: center; margin-left: 10%;margin-right:10%;">

                    <button class="add-btn" style="height: 30px; width: 30px; border-radius: 50px; border: 1px solid gray; background-color: white; "><img src=".\images\add.png" style="height: 20px; width: 20px; border-radius: 10px; background-color: white; margin-left: -3px;"></button>
                    <input id="qty-input" name="qty" type="text" value="1" oninput="this.value = (parseInt(this.value.replace(/[^0-9]/g, '')) || 1); this.value = Math.min(parseInt(this.value), <?php echo $qry['quantity']; ?>);" style="height: 30px; width: 157px; text-align:center; border-radius: 20px; border: 1px solid white">
                    <button class="sub-btn" style="height: 30px; width: 30px; border-radius: 50px; border: 1px solid gray; background-color: white; "><img src=".\images\sub.png" style="height: 20px; width: 20px; border-radius: 10px; background-color: white; margin-left: -2px;"></button>

                </span>
                <br>
                <span>
                    <button id="add_to_cart_modal" style="text-align: center; width:90%; height:30px;border-radius: 20px; margin-left: 5%;margin-right:5%;margin-top: 15px; background-color:red; color:white; border: 0px solid gray;">Add To Cart - RM0.00</button>
                </span>
            </div>
        </div>

    </div>












    <?php if ($qry['category_id'] != 17) : ?>
        <div class="drink" style="width: 540px; height: auto;">
            <h3>Choose Your Drink&nbsp&nbsp&nbsp&nbsp<sub>Pick 1</sub></h3>
            <div class="drink-container">
                <input type="radio" class="drinkpick" name="drinkadd" value="null" checked onchange="showdrinkradio(this.value)">
                <label for="drinkadd" style=" width: auto; margin-left: 10px; text-transform: capitalize; font-size: 15px;">No Drink</label>
                <div class="drinkprice">
                    <span style=" width: auto; font-weight: bold; font-size: 15px;"></span>
                </div>

            </div>
            <?php
            $count = 0;
            while ($drinkrow = $drink->fetch_assoc()) : //fetch_assoc is used to fetch the data from the database
                $count++;
            ?>
                <div class="drink-container" style="<?php echo $count > 5 ? 'display: none;' : '' ?>">
                    <input type="radio" class="drinkpick" name="drinkadd" value="<?php echo $drinkrow['id'] ?>" onchange="showdrinkradio(this.value)">
                    <label for="drinkadd" style="width: auto; margin-left: 10px; text-transform: capitalize; font-size: 15px;"><?php echo $drinkrow['name'] ?></label>
                    <div class="drinkprice">
                        <span id="price_<?php echo $drinkrow['id'] ?>" style="width: auto; font-weight: bold; font-size: 15px;"><?php echo $drinkrow['price'] ?></span>
                    </div>
                    <?php if ($count < 5) : ?>

                    <?php endif; ?>
                </div>
            <?php endwhile; ?>

            <?php if ($count > 5) : ?>
                <button id="viewMoreButton" onclick="toggleMoreDrinks()">View More</button>
            <?php endif; ?>

        </div>




        <?php if ($qry['category_id'] == 13) : ?>
            <div class="addon" style="width: 540px; height: auto;">
                <h3>We Think You'll Love These (Add-Ons)</h3>
                <?php while ($addonrow = $addon->fetch_assoc()) : ?>

                    <span>
                        <div class="div-container">
                            <img src="assets/img/<?php echo $addonrow['img_path'] ?>">
                            <div class="add-prod-details">
                                <h6 style="font-weight:bold;"><?php echo $addonrow['name'] ?></h6>
                                <div class="addon-container">
                                    <div class=addon-price-container>
                                        +RM<span name="addon-price"><?php echo number_format($addonrow['price'], 2) ?></span>
                                        <p>Stock: <span class="addon-stock"><?php echo number_format($addonrow['quantity']) ?></span></p>
                                        </div>
                                    <div class="product-button">
                                        <input type="hidden" id="category_id" value="<?php echo $qry['category_id']; ?>">
                                        <button class="add-btn-addon<?php echo $addonrow['img_path'] ?>" style="height: 30px; width: 30px; border-radius: 50px; border: 1px solid gray; background-color: white; "><img src=".\images\add.png" style="height: 20px; width: 20px; border-radius: 10px; background-color: white; margin-left: -3px;"></button>
                                        <input name="qty-addon<?php echo $addonrow['id'] ?>" type="text" value="0" oninput="this.value = (parseInt(this.value.replace(/[^0-9]/g, '')) || 0); this.value = Math.min(parseInt(this.value), <?php echo $addonrow['quantity']; ?>);" style="background-color: rgba(243, 243, 243, 0.715); height: 30px; width: 50px; text-align:center; border-radius: 20px; border: 1px solid rgba(243, 243, 243, 0.715);">
                                        <button class="sub-btn-addon<?php echo $addonrow['img_path'] ?>" style="height: 30px; width: 30px; border-radius: 50px; border: 1px solid gray; background-color: white; "><img src=".\images\sub.png" style="height: 20px; width: 20px; border-radius: 10px; background-color: white; margin-left: -2px;"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </span>

                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>









</html>

<script>
    let priceofdrink = 0;
    let totalAddonPrice = 0;

    document.addEventListener("DOMContentLoaded", function() { //function get the price of the selected drink
        var drinkRadios = document.querySelectorAll('.drinkpick');
        drinkRadios.forEach(function(radio) {
            radio.addEventListener('click', function() {
                var drinkId = this.value;
                if (drinkId !== "null") {
                    var priceElement = document.getElementById('price_' + drinkId);
                    if (priceElement) {
                        priceofdrink = priceElement.textContent.trim() || priceElement.innerText.trim();
                    }

                } else {
                    priceofdrink = 0;
                }
                updateTotalPrice();
            });
        });
    });


    const addButton = document.querySelector('.add-btn');
    const subButton = document.querySelector('.sub-btn');
    const qtyInput = document.getElementById('qty-input');
    const stock = <?php echo $qry['quantity']; ?>; // Assuming $qry['quantity'] is the available stock


    addButton.addEventListener('click', function() {
        if (parseInt(qtyInput.value) < stock) {
            qtyInput.value = parseInt(qtyInput.value) + 1;
            updateTotalPrice();
            createFoodItems(qtyInput.value);
            getDrinkPrice();
        } else {
            alert('Cannot add more than available stock');
            return;
        }

    });

    subButton.addEventListener('click', function() {
        if (parseInt(qtyInput.value) > 1) {
            qtyInput.value = parseInt(qtyInput.value) - 1;
            updateTotalPrice();
            createFoodItems(qtyInput.value);

        }
    });


    
    function updateTotalPrice() {
        var qty = parseInt(document.getElementById('qty-input').value);
        var price = <?php echo $qry['price']; ?>;
        var selectedDrink = localStorage.getItem('selectedDrink');


        var addonp = totalAddonPrice;
        var totalPricemain = (qty * price).toFixed(2);
        var drinkPrice = priceofdrink;
        

        var totalPrice = (parseFloat(totalPricemain) + parseFloat(addonp) + parseFloat(drinkPrice)).toFixed(2);
        document.getElementById('add_to_cart_modal').innerText = "Add To Cart - RM" + totalPrice;
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateTotalPrice();
    });

    $('#add_to_cart_modal').click(function() {
    start_load(); // Show the loading icon

    // Collect main product data
    var mainProduct = '<?php echo $_GET['product_id'] ?>'; // Main product ID
    var mainProductQty = $('[name="qty"]').val(); // Main product quantity
    const stock = <?php echo $qry['quantity']; ?>; // Assuming $qry['quantity'] is the available stock

    // Check if the main product quantity is 0
    if (stock == 0) {
        alert("Please choose a product for waiting us to adding stock");
        window.location.href = "index.php?page=menu"; // Redirect to the menu page
        return;
    }


    // Initialize array to store selected products
    var products = [];

    // Collect selected drink data
    var selectedDrink = localStorage.getItem('selectedDrink');
    var qtyd = 1; // Quantity for selected drink
    if (selectedDrink) {
        products.push({
            product_id: selectedDrink,
            qty: qtyd
        });
    }

    // Collect add-on data
    $('.addon .div-container').each(function() {
        var addonQtyInput = $(this).find('input[name^="qty-addon"]');
        var addonId = addonQtyInput.attr('name').replace('qty-addon', '');
        var addonQty = addonQtyInput.val();
        if (addonQty > 0) {
            products.push({
                product_id: addonId,
                qty: addonQty
            });
        }
    });

    // Add main product data
    products.push({
        product_id: mainProduct,
        qty: mainProductQty
    });

    // Log the products array to verify data
    console.log(products);

    // Send all products in one AJAX request
    $.ajax({
        url: 'admin/ajax.php?action=add_to_cart',
        method: 'POST',
        data: { products: JSON.stringify(products) }, // Sending the products array as JSON string
        success: function(resp) {
            if (resp == 1) {
                alert_toast("Order successfully added to cart");
                $('.item_count').html(parseInt($('.item_count').html()) + parseInt(mainProductQty));
                end_load();
                location.replace("index.php?page=home");
            } else {
                alert_toast("Failed to add order to cart", "danger");
                end_load();
            }
        }
    });
});






<?php
// Assuming $qry['name'] contains the food name
$imgName = $qry['name'];

// Check if the image name is not 'Sate Ayam' or 'Sate Daging'
if ($imgName != 'Sate Ayam' && $imgName != 'Sate Daging') {
    $imgName = 'Sate Ayam';
}
?>

const imgName = '<?php echo $imgName; ?>';

    //for satay part
    const circle = document.getElementById('circle');

    function createFoodItems(count) {
        const plate = document.getElementById('plate');
        plate.innerHTML = ''; // Clear plate contents

        const angleIncrement = (2 * Math.PI) / count; // Calculate angle increment

        for (let i = 0; i < count; i++) {
            const foodItem = document.createElement('img'); // Create a new food item by creating an img element
            foodItem.className = 'food';
            foodItem.src = `images/${imgName}.png`; // Image path
            const angle = i * angleIncrement; // Calculate current food angle
            const radius = 120; // Plate radius, including distance from plate to food
            const x = radius * Math.cos(angle); // Radius * cos(angle)
            const y = radius * Math.sin(angle); // Radius * sin(angle)
            foodItem.style.left = (125 + x - 55) + 'px'; // Center the food image
            foodItem.style.top = (125 + y - 25) + 'px'; // Center the food image
            foodItem.style.transform = `rotate(${angle + Math.PI}rad)`; // Rotate the food image, adding Math.PI to point it outward
            plate.appendChild(foodItem); // Append the food item to the plate
        }
    }

    // Initially create 20 food items


    /*

        // Update food items when different quantity selected
        document.getElementById('qty-input').addEventListener('change', function(e) {
            const count = parseInt(e.target.value); // e is the event object, e.target is the select element parseint is a function that converts a string to an integer 
            createFoodItems(count);
        });*/

    // Update kuah image when different option selected
    document.getElementById('kuahImage').addEventListener('change', function(e) {
        const kuah = document.getElementById('kuah');
        const newImageSrc = e.target.value;
        kuah.style.backgroundImage = `url('${newImageSrc}')`;
    });
    document.getElementById('mangkukTimunImage').addEventListener('change', function(e) {
        const mangkukTimun = document.getElementById('mangkukTimun');
        const newImageSrc = e.target.value;
        mangkukTimun.style.backgroundImage = `url('${newImageSrc}')`;
    });

    document.getElementById('bawangImage').addEventListener('change', function(e) {
        const bawang = document.getElementById('bawang');
        const newImageSrc = e.target.value;
        bawang.style.backgroundImage = `url('${newImageSrc}')`;
    });

    createFoodItems(1); // Create 20 food items initially
    //end of satay part

    let str = "";
    let addonvar = "";
    let addonqtyvar = []; // for array of addon quantity by id
    document.addEventListener('DOMContentLoaded', function() {
    const addButton = document.querySelector('.add-btn');
    const subButton = document.querySelector('.sub-btn');
    const quantityInput = document.querySelector('input[type="text"]');
    const stock = <?php echo $qry['quantity']; ?>; // Assuming $qry['quantity'] is the available stock



    addButton.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue <stock)
        {
        if (parseInt(quantityInput.value) <= 30) {
                createFoodItems(quantityInput.value);
                quantityInput.value = currentValue + 1;

            } else {
                alert("Maximum quantity is 30. Please add to cart first and then add new ones.");
                quantityInput.value = 30;
            }
        } else {
            
            return;
        }
    });

    subButton.addEventListener('click', function() {
        if (parseInt(quantityInput.value) > 1) {
            quantityInput.value = parseInt(quantityInput.value) - 1;
            createFoodItems(quantityInput.value);
            quantityInput.value = currentValue - 1;

        }  
    }); 
});

    //it for add on part 



    document.addEventListener('DOMContentLoaded', function() {
        const addButtons = document.querySelectorAll('[class^="add-btn-addon"]');
        const subButtons = document.querySelectorAll('[class^="sub-btn-addon"]');
        const quantityInputs = document.querySelectorAll('input[name^="qty-addon"]');
        const addonPrices = document.querySelectorAll('[name="addon-price"]');
        const addonStocks = document.querySelectorAll('.addon-stock');


        // Function to calculate subtotal
        function calculateSubtotal() {
            totalAddonPrice = 0;
            addonPrices.forEach((price, index) => {
                totalAddonPrice += parseFloat(price.textContent.replace('+RM', '')) * parseInt(quantityInputs[index].value);
            });
        return totalAddonPrice;                
        }



        addButtons.forEach((addButton, index) => {
            addButton.addEventListener('click', function() {
                const currentQuantity = parseInt(quantityInputs[index].value);
            const maxQuantity = parseInt(addonStocks[index].textContent);
            if (currentQuantity < maxQuantity) {
                quantityInputs[index].value = currentQuantity + 1;
                let id = quantityInputs[index].getAttribute('name').replace('qty-addon', '');
                addonqtyvar[id] = parseInt(quantityInputs[index].value);
                updateTotalPrice();
                addonproduct(id, addonqtyvar[id]); // Implement this function according to your needs
            } else {
                alert('Cannot add more than available quantity');
            }
        });
    });

        subButtons.forEach((subButton, index) => {
            subButton.addEventListener('click', function() {
                if (parseInt(quantityInputs[index].value) > 0) {
                    quantityInputs[index].value = parseInt(quantityInputs[index].value) - 1;
                    if (parseInt(quantityInputs[index].value) > 0) {
                        let id = quantityInputs[index].getAttribute('name').replace('qty-addon', ''); // get the id from the name attribute
                        addonqtyvar[id] = parseInt(quantityInputs[index].value);
                        calculateSubtotal();
                        updateTotalPrice()
                        addonproduct(id, addonqtyvar[id]);
                        

                     
                    


                    } else if (parseInt(quantityInputs[index].value) == 0) {
                        let id = quantityInputs[index].getAttribute('name').replace('qty-addon', ''); // get the id from the name attribute
                        addonqtyvar[id] = parseInt(quantityInputs[index].value);
                        //remove element
                        addonproduct(id, addonqtyvar[id]);
                        updateTotalPrice()

                    }
                }
            });
        });
    });

    function addonproduct(addonid, addonqtyvalue) {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            let addonprodid = 'addonid' + addonid;
            let existingElement = document.getElementById(addonprodid);
            if (existingElement !== null) {
                // If the product already exists, remove it
                existingElement.remove();
            }
            // Append the updated product
            $('#addedproductaddon').append(this.responseText);

        }
        xhttp.open("GET", "admin/ajax.php?action=productaddon&addonpickid=" + addonid + "&addonqtyvar=" + addonqtyvalue);
        xhttp.send();
    }

    $(document).ready(function() {
        var menuiteminfo = $('.menuiteminfo'); //dapatkan menuiteminfo class css ubah ke variable
        var menuiteminfochild = $('.menuiteminfochild'); //dapatkan menuiteminfochild class css ubah ke variable
        if (menuiteminfochild.height() > menuiteminfo.height()) { //compare height of menuiteminfochild and menuiteminfo
            menuiteminfo.css('position', 'absolute'); // tukar setting css position ke absolute
        } else {
            menuiteminfo.css('position', 'fixed');
        }
        drinkpickedreceiver(); // to receive the selected drink radio

    });




    function drinkpickedreceiver() { // to save the selected drink radio
        var selectedDrink = localStorage.getItem('selectedDrink');
        if (selectedDrink != null) {
            $('.drinkpick[value="' + selectedDrink + '"]').prop('checked', true);
            showdrinkradio(selectedDrink);
        }
    }

    function addonproductpickreceiver() {

    }

    function showdrinkradio(str) { // to show the selected drink detail in price box and save the selected drink
        localStorage.removeItem('selectedDrink');
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            $('#addedproduct').html(this.responseText);
        }
        xhttp.open("GET", "admin/ajax.php?action=productdrink&drinkvar=" + str);
        xhttp.send();
        localStorage.setItem('selectedDrink', str);
    }

    function toggleMoreDrinks() {
        var moreDrinks = document.querySelectorAll('.drink-container:nth-child(n+6)');
        for (var i = 0; i < moreDrinks.length; i++) {
            moreDrinks[i].style.display = moreDrinks[i].style.display === 'none' ? 'block' : 'none';
        }
        var buttonText = document.getElementById('viewMoreButton');
        buttonText.innerHTML = buttonText.innerHTML === 'View More' ? 'View Less' : 'View More';
    }
</script>

<style>
    /*google font link*/
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap');

    .menuiteminfo {
        margin-top: -360px;
        margin-left: 560px;
        padding: 0px 15px 0px;
        background-color: rgba(243, 243, 243, 0.715);
        width: 335px;
        height: auto;
        border-radius: 25px;
        position: fixed;
        padding-bottom: 15px;

    }

    .product-decs {
        font-family: 'Oswald', sans-serif;
        /*font-weight: bold;*/
        font-size: 15px;
        margin-bottom: 0;
    }

    .bttn-cart {
        margin-top: 20px;

    }

    .addon {
        margin-top: 50px;
        margin-left: 15px;

    }

    .addon h3 {
        font-family: "Roboto Condensed", sans-serif;
        margin-bottom: 20px;
        font-size: 22px;

    }

    .addon span hr {
        margin-top: 6px;
        margin-bottom: 0;
    }

    .drink {
        margin-top: 100px;
        margin-left: 15px;

    }

    .drink h3 {
        font-family: "Roboto Condensed", sans-serif;
        margin-bottom: 20px;
        font-size: 22px;

    }

    .drink div hr {
        margin-top: 6px;
        margin-bottom: 0;
    }

    .drink h3 sub {
        font-size: 14px;
        font-weight: normal;
        font-style: oblique;
    }

    .div-container {
        display: flex;
        margin-bottom: 20px;
    }

    .div-container img {
        height: 93.75px;
        width: 75px;
        border-radius: 10px;
        background-color: white;
        margin-left: -3px;
    }

    .add-prod-details {
        height: auto;
        width: 100%;
        display: inline-block;
        margin-left: 11px;
        margin-top: 10px;
    }

    .addon {
        background-color: rgba(243, 243, 243, 0.715);
        padding: 20px;
        /*top right bottom left*/
        margin-left: -2px;
    }

    .product-button {
        margin-left: 220px;
        align-items: right;
        justify-content: right;
        height: auto;
        width: auto;
    }

    .addon-container {
        display: flex;
        margin-top: 20px;

    }

    .drink-container {
        position: relative;
        /* Set the container as a relative positioning context */
        display: flex;
        align-items: center;
        margin-bottom: 0;
        margin-top: 0;
        width: 370px;

    }

    .drinkprice {
        position: absolute;
        /* Position the price absolutely */
        right: 0;
        /* Align the price to the right of the container */
    }

    hr {
        margin-top: 0;
    }

    /*satay section*/
    #circle {
        position: relative;
        width: 400px;
        height: 400px;
        margin: 50px auto;
        margin-left: -1px;
        left: 80px;
        overflow: visible;
        /* Let the plate overflow the circle container */
    }

    #plate {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        /* Adjust plate size */
        height: 300px;
        /* Adjust plate size */
        background: url('images/123plate.png') no-repeat center center;
        background-size: contain;
        z-index: 1;
        /* Place plate on top of the circle */
    }

    #kuah {
        position: absolute;
        top: calc(50% - 85px);
        /* Move the kuah image slightly up */
        left: calc(50% - 75px);
        /* Center the kuah image */
        width: 200px;
        height: 200px;
        background: url('images/kuah.png') no-repeat center center;
        background-size: contain;
        z-index: 2;
        /* Place kuah on top of the circle */
        margin-top: -20px;
        margin-left: -20px;
    }

    .food {
        position: absolute;
        width: 110px;
        height: 50px;
        border-radius: 50%;
        transform-origin: center center;
        margin-top: 20px;
        margin-left: 30px;
    }

    #mangkukTimun {
        position: absolute;
        /* Position the mangkuk timun image absolutely */
        top: calc(50% - 85px);
        /* Adjust position as needed */
        left: calc(50% - 75px);
        /* Adjust position as needed */
        width: 200px;
        height: 200px;
        background: url('images/timun.png') no-repeat center center;
        background-size: contain;
        z-index: 2;
        /* Adjust z-index as needed */
        margin-top: -20px;
        /* Adjust margin as needed */
        margin-left: -20px;
        /* Adjust margin as needed */
    }

    #bawang {
        position: absolute;
        /* Position the bawang image absolutely */
        top: calc(50% - 85px);
        /* Adjust position as needed */
        left: calc(50% - 75px);
        /* Adjust position as needed */
        width: 200px;
        height: 200px;
        background: url('images/bawang.png') no-repeat center center;
        background-size: contain;
        z-index: 2;
        /* Adjust z-index as needed */
        margin-top: -20px;
        /* Adjust margin as needed */
        margin-left: -20px;
        /* Adjust margin as needed */
    }
</style>