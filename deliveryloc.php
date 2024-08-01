<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
<!-- Third party plugin JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<!-- Core theme JS-->
<script src="js/scripts.js"></script>

<!-- Remove redundant jQuery script -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->

<div class="getdatetime" id="getdatetime">
    <h3 style="text-align: center !important;">Let's Start Ordering</h3>
    <p style="text-align: center !important; ">How would you like to receive your order?</p>
    <p style="text-align: center !important; ">Note: Price may vary based on ordering method.</p>
 
    <div class="buttons">
        <button><a href="index.php?productpage=delivery-address&method=delivery">Delivery</a></button>
        <button onclick="selfcollect()"><a href="index.php?page=home"></a>Self Collect<a></a></button>
      </div>


</div>

<style>
   .buttons {
  display: flex;
  width: 380px;
  gap: 10px;
  --b: 5px;   /* the border thickness */
  --h: 1.8em; /* the height */
  align-items: center;
  justify-content: center;
  margin: auto; /* Add this line */
}

.buttons button {
  --_c: #88C100;
  flex: calc(1.25 + var(--_s,0));
  min-width: 0;
  font-size: 20px;
  font-weight: bold;
  height: var(--h);
  cursor: pointer;
  color: var(--_c);
  border: var(--b) solid var(--_c);
  background: 
    conic-gradient(at calc(100% - 1.3*var(--b)) 0,var(--_c) 209deg, #0000 211deg) 
    border-box;
  clip-path: polygon(0 0,100% 0,calc(100% - 0.577*var(--h)) 100%,0 100%);
  padding: 0 calc(0.288*var(--h)) 0 0;
  margin: 0 calc(-0.288*var(--h)) 0 0;
  box-sizing: border-box;
  transition: flex .4s;
}
.buttons button + button {
  --_c: #FF003C;
  flex: calc(.75 + var(--_s,0));
  background: 
    conic-gradient(from -90deg at calc(1.3*var(--b)) 100%,var(--_c) 119deg, #0000 121deg) 
    border-box;
  clip-path: polygon(calc(0.577*var(--h)) 0,100% 0,100% 100%,0 100%);
  margin: 0 0 0 calc(-0.288*var(--h));
  padding: 0 0 0 calc(0.288*var(--h));
}
.buttons button:focus-visible {
  outline-offset: calc(-2*var(--b));
  outline: calc(var(--b)/2) solid #000;
  background: none;
  clip-path: none;
  margin: 0;
  padding: 0;
}
.buttons button:focus-visible + button {
  background: none;
  clip-path: none;
  margin: 0;
  padding: 0;
}
.buttons button:has(+ button:focus-visible) {
  background: none;
  clip-path: none;
  margin: 0;
  padding: 0;
}
button:hover,
button:active:not(:focus-visible) {
  --_s: .75;
}
button:active {
  box-shadow: inset 0 0 0 100vmax var(--_c);
  color: #fff;
}



    #uni_modal .modal-footer {
        display: none;
    }

    .comfirm {
        border: 1px solid #f1f1f1;
        background-color: #04AA6D;
        color: white;
        width: 200px;
        height: 30px;
        font-size: 15px;
        font-weight: bold;
        border-radius: 5px;
        margin-top: 30px;
    }

    .comfirm:hover {
        background-color: #0acc03;
    }

    .getdatetime {
        display: block;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        /* Center-align the content */
    }

    .fordate {
        margin-top: 40px;
        margin-left: -260px;
    }

    .fortime {
        margin-top: -79px;
        margin-right: -260px;
    }

    .datetime {
        width: 200px;
        height: 40px;
        border: 0px;
        border-bottom: 1px solid rgb(194, 194, 194);
    }

    .dt {
        font-size: 12px;
        font-family: 'Times New Roman', Times, serif;
        color: rgb(158, 158, 158);
    }
</style>

<script>
    $(document).ready(function(){
        $('.main-button').click(function(){
            $(this).toggleClass('clicked');
            $('.mini-button').toggleClass('clicked');
        });
    });

    function selfcollect() {
      localStorage.removeItem('location');
      localStorage.removeItem('method_location');
      localStorage.removeItem('del_fee');
      window.location.href = "index.php?page=home";
        
    }
</script>
