<div class="container text-white bg-dark p-4" id="footer-div">
     <div class="row">
         <div class="col-6 col-md-8 col-lg-7">
             <div class="row text-center">
                 <div class="col-sm-6 col-md-4 col-lg-4 col-12">
                      <img src="..\images\pakmalau.png" alt="logo" style="width: 500px; height: 200px; border-radius:0px;">
                 </div><!---
                 <div class="col-sm-6 col-md-4 col-lg-4 col-12">
                     <ul class="list-unstyled">
                         <li class="btn-link"> <a>Link anchor</a> </li>
                         <li class="btn-link"> <a>Link anchor</a> </li>
                         <li class="btn-link"> <a>Link anchor</a> </li>
                         <li class="btn-link"> <a>Link anchor</a> </li>
                         <li class="btn-link"> <a>Link anchor</a> </li>
                     </ul>
                 </div>
                 <div class="col-sm-6 col-md-4 col-lg-4 col-12">
                     <ul class="list-unstyled">
                         <li class="btn-link"> <a>Link anchor</a> </li>
                         <li class="btn-link"> <a>Link anchor</a> </li>
                         <li class="btn-link"> <a>Link anchor</a> </li>
                         <li class="btn-link"> <a>Link anchor</a> </li>
                         <li class="btn-link"> <a>Link anchor</a> </li>
                     </ul>
                 </div>--->
             </div>
         </div>
         <div class="col-md-4 col-lg-5 col-6">
             <address>
              <strong></strong><br>
                 <strong><?php echo $_SESSION['setting_name'] ?></strong><br>
                 <strong></strong><br>
                 Warung SATAY PAK MALAU,<br>
                 75100 Malacca<br>
                 <br>
                 <abbr title="Phone">P:</abbr> <?php echo $_SESSION['setting_contact'] ?>
             </address>
             <address>
              
                 <a href="mailto:#"><?php echo $_SESSION['setting_email'] ?></a>
             </address>
         </div>
     </div>
 </div>
 <footer class="text-center">
     <div class="container">
         <div class="row">
             <div class="col-12">
                 <p>Copyright © Pak Malau Satay. All rights reserved.</p>

             </div>
         </div>
     </div>
 </footer>

 
 
 <script>
 	$('.datepicker').datepicker({
 		format:"yyyy-mm-dd"
 	})
 	 window.start_load = function(){ 
    $('body').prepend('<div id="preloader2"><img src="./images/load.gif" alt="Loading..."></div>')  //prepend is used to add the element at the beginning of the body
  }
  window.end_load = function(){
  window.setTimeout(function() {
    $('#preloader2').fadeOut('fast', function() { //fast means the speed of the fadeout
      $(this).remove();
    });
  }, 2500); 
}; //0 is the time in milliseconds

 	window.uni_modal = function($title = '' , $url=''){
    start_load()
    $.ajax({
        url:$url,
        error:err=>{
            console.log()
            alert("An error occured")
        },
        success:function(resp){
            if(resp){
                $('#uni_modal .modal-title').html($title)
                $('#uni_modal .modal-body').html(resp)
                $('#uni_modal').modal('show')
                end_load()
            }
        }
    })
}
  window.uni_modal_right = function($title = '' , $url=''){
    start_load()
    $.ajax({
        url:$url,
        error:err=>{
            console.log()
            alert("An error occured")
        },
        success:function(resp){
            if(resp){
                $('#uni_modal_right .modal-title').html($title)
                $('#uni_modal_right .modal-body').html(resp)
                $('#uni_modal_right').modal('show')
                end_load()
            }
        }
    })
}
window.alert_toast= function($msg = 'TEST',$bg = 'success'){
      $('#alert_toast').removeClass('bg-success')
      $('#alert_toast').removeClass('bg-danger')
      $('#alert_toast').removeClass('bg-info')
      $('#alert_toast').removeClass('bg-warning')

    if($bg == 'success')
      $('#alert_toast').addClass('bg-success')
    if($bg == 'danger')
      $('#alert_toast').addClass('bg-danger')
    if($bg == 'info')
      $('#alert_toast').addClass('bg-info')
    if($bg == 'warning')
      $('#alert_toast').addClass('bg-warning')
    $('#alert_toast .toast-body').html($msg)
    $('#alert_toast').toast({delay:3000}).toast('show');
  }
  window.load_cart = function(){
    $.ajax({
      url:'admin/ajax.php?action=get_cart_count',
      success:function(resp){
        if(resp > -1){
          resp = resp > 0 ? resp : 0;
          $('.item_count').html(resp)
        }
      }
    })
  }
  $('#login_now').click(function(){
    uni_modal("",'login.php') //uni modal is a custom function i made it opens a bootstrap modal
  })
  $('#delivery-click').click(function(){
    uni_modal("Delivery Location",'deliveryloc.php') //uni modal is a custom function i made it opens a bootstrap modal
  })
  $('#date-click').click(function(){
    uni_modal("Delivery Or Pickup Date",'deliverydate.php') //uni modal is a custom function i made it opens a bootstrap modal
  })
  $(document).ready(function(){
    load_cart()
  })
 </script>
 <!-- Bootstrap core JS-->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>