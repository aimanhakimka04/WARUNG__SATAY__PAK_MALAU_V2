
    <?php
    include 'admin/db_connect.php';
    $chk = $conn->query("SELECT * FROM cart where user_id = 1 ")->num_rows;//
//

    ?>

<div style="margin-top:70px"></div>

 
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Profile</title>
        <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
    <form action="save_profile.php? action=save_profile" method="post" enctype="multipart/form-data">

        <div class="container light-style flex-grow-1 container-p-y">
            <h4 class="font-weight-bold py-3 mb-4">
                Account settings
            </h4>
            <div class="card overflow-hidden">
                <div class="row no-gutters row-bordered row-border-light">
                    <div class="col-md-3 pt-0">
                        <div class="list-group list-group-flush account-settings-links">
                            <a class="list-group-item list-group-item-action active" data-toggle="list"
                                href="#account-general">General</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list"
                                href="#account-change-password">Change password</a><!----
                            <a class="list-group-item list-group-item-action" data-toggle="list"
                                href="#account-info">Info</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list"
                                href="#account-social-links">Social links</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list"
                                href="#account-connections">Connections</a>
                            <a class="list-group-item list-group-item-action" data-toggle="list"
                                href="#account-notifications">Notifications</a>---->
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="account-general">

                               

                                <div class="card-body media align-items-center">
                                <?php
                                    // Check if login_img_user is null or empty
                                    if (empty($_SESSION['login_img_user'])) {
                                        // If it's null or empty, display user.png
                                        $user_image = "user.png";
                                    } else {
                                        // If it's not null or empty, use the value of login_img_user
                                        $user_image = $_SESSION['login_img_user'];
                                    }
                                    ?>
                                    <img src="assets/img/<?php echo $user_image; ?>" class="user" name="user" onclick="chooseFile()"  alt="User Image" style="width: 100px; height: 100px;">
                                    <div class="media-body ml-4">
                                        <label class="btn btn-outline-primary">
                                            Upload new photo
                                            <input type="file" style="display: none;" class="account-settings-fileinput" id="profile_image" name="profile_image"  accept=".jpg, .jpeg, .png, .gif" onchange="previewImage(event)">
                                        </label> 
                                        <!---button type="button" class="btn btn-default md-btn-flat" id="reset_img" onclick="resetPhoto()">Reset</button---->
                                        <div class="text-light small mt-1">Allowed JPG, GIF, or PNG. </div><!--Max size of 800K---->
                                    </div>
                                </div>


                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">User ID</label>
                                        <input type="text" class="form-control mb-1"  id="id" name="user_id" placeholder="e.g. 1211208288" value="<?php echo $_SESSION['login_user_id'] ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="10" pattern="[0-9]">
                                    </div>
                                    <div class="form-group">

                                        <label class="form-label"><span style="color:red;">*</span>First Name</label>
                                        <input type="text" class="form-control" id="fname" name="first_name" required placeholder="e.g. jian hau" value="<?php echo $_SESSION['login_first_name'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><span style="color:red;">*</span>Last Name</label>
                                        <input type="text" class="form-control" id="lname"  name="last_name" required placeholder="e.g. tan" value="<?php echo $_SESSION['login_last_name'] ?>">

                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">E-mail</label>
                                        <input type="text" class="form-control mb-1" id="email" name="email" placeholder="e.g.hhh41626@gmail.com" value="<?php echo $_SESSION['login_email'] ?>">
                                        <!---<div class="alert alert-warning mt-3">
                                            Your email is not confirmed. Please check your inbox.<br>
                                            <a href="javascript:void(0)">Resend confirmation</a>
                                        </div>--->
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><span style="color:red;">*</span>Phone Number</label>
                                        <input type="text" class="form-control"  id="phonenum"  name="mobile" required placeholder="e.g. 0123456789" value="<?php echo $_SESSION['login_mobile']; ?>"  pattern="60\d{6,10}" oninput="validatePhoneNumber(this)">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><span style="color:red;">*</span>Address</label>
                                        <textarea class="form-control"  id="address"  name="address" required placeholder="<?php echo $_SESSION['login_address'] ?>"><?php echo $_SESSION['login_address'] ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Last Login</label>
                                        <input type="text" class="form-control" id="lastlogin" name="lastlogin" value="<?php echo $_SESSION['login_last_login'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Discount Point</label>
                                        <input type="text" class="form-control" id="discount_point" name="discount_point" value="<?php echo $_SESSION['login_discount_point'] ?>">
                                    </div>

                                  
                            </div>
                            </div>

                          
                            
                        
                            <div class="tab-pane fade" id="account-change-password">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label"><span style="color:red;">*</span><a href="forgotpassword.php" class="">Forgot/Change Your Password?</a>
                                        </label>
                                        <!---input type="password" class="form-control" name="current_password">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><span style="color:red;">*</span>New password</label>
                                        <input type="password" class="form-control" name="new_password">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><span style="color:red;">*</span>Repeat new password</label>
                                        <input type="password" class="form-control" name="confirm_password"--->
                                    </div>
                                </div>
                                
                            </div>

                           

                            </div><!----
                            <div class="tab-pane fade" id="account-info">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label">Bio</label>
                                        <textarea class="form-control"
                                            rows="5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris nunc arcu, dignissim sit amet sollicitudin iaculis, vehicula id urna. Sed luctus urna nunc. Donec fermentum, magna sit amet rutrum pretium, turpis dolor molestie diam, ut lacinia diam risus eleifend sapien. Curabitur ac nibh nulla. Maecenas nec augue placerat, viverra tellus non, pulvinar risus.</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Birthday</label>
                                        <input type="text" class="form-control" value="May 3, 1995">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Country</label>
                                        <select class="custom-select">
                                            <option>USA</option>
                                            <option selected>Canada</option>
                                            <option>UK</option>
                                            <option>Germany</option>
                                            <option>France</option>
                                        </select>
                                    </div>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body pb-2">
                                    <h6 class="mb-4">Contacts</h6>
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" value="+0 (123) 456 7891">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Website</label>
                                        <input type="text" class="form-control" value>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="account-social-links">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label">Twitter</label>
                                        <input type="text" class="form-control" value="https://twitter.com/user">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Facebook</label>
                                        <input type="text" class="form-control" value="https://www.facebook.com/user">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Google+</label>
                                        <input type="text" class="form-control" value>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">LinkedIn</label>
                                        <input type="text" class="form-control" value>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Instagram</label>
                                        <input type="text" class="form-control" value="https://www.instagram.com/user">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="account-connections">
                                <div class="card-body">
                                    <button type="button" class="btn btn-twitter">Connect to
                                        <strong>Twitter</strong></button>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <h5 class="mb-2">
                                        <a href="javascript:void(0)" class="float-right text-muted text-tiny"><i
                                                class="ion ion-md-close"></i> Remove</a>
                                        <i class="ion ion-logo-google text-google"></i>
                                        You are connected to Google:
                                    </h5>
                                    <a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                                        data-cfemail="f9979498818e9c9595b994989095d79a9694">[email&#160;protected]</a>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <button type="button" class="btn btn-facebook">Connect to
                                        <strong>Facebook</strong></button>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body">
                                    <button type="button" class="btn btn-instagram">Connect to
                                        <strong>Instagram</strong></button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="account-notifications">
                                <div class="card-body pb-2">
                                    <h6 class="mb-4">Activity</h6>
                                    <div class="form-group">
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher-input" checked>
                                            <span class="switcher-indicator">
                                                <span class="switcher-yes"></span>
                                                <span class="switcher-no"></span>
                                            </span>
                                            <span class="switcher-label">Email me when someone comments on my article</span>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher-input" checked>
                                            <span class="switcher-indicator">
                                                <span class="switcher-yes"></span>
                                                <span class="switcher-no"></span>
                                            </span>
                                            <span class="switcher-label">Email me when someone answers on my forum
                                                thread</span>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher-input">
                                            <span class="switcher-indicator">
                                                <span class="switcher-yes"></span>
                                                <span class="switcher-no"></span>
                                            </span>
                                            <span class="switcher-label">Email me when someone follows me</span>
                                        </label>
                                    </div>
                                </div>
                                <hr class="border-light m-0">
                                <div class="card-body pb-2">
                                    <h6 class="mb-4">Application</h6>
                                    <div class="form-group">
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher-input" checked>
                                            <span class="switcher-indicator">
                                                <span class="switcher-yes"></span>
                                                <span class="switcher-no"></span>
                                            </span>
                                            <span class="switcher-label">News and announcements</span>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher-input">
                                            <span class="switcher-indicator">
                                                <span class="switcher-yes"></span>
                                                <span class="switcher-no"></span>
                                            </span>
                                            <span class="switcher-label">Weekly product updates</span>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher-input" checked>
                                            <span class="switcher-indicator">
                                                <span class="switcher-yes"></span>
                                                <span class="switcher-no"></span>
                                            </span>
                                            <span class="switcher-label">Weekly blog digest</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>---->
            </div>
            <div class="text-right mt-3">
                <button type="submit" class="btnprof">Save changes</button>

                <button type="button" class="btn btn-default">Cancel</button>
            </div>
        </div>
        <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript">

        </script>

    </form>

    </body>

    </html>

    <style >
        
        body {
        background: #f5f5f5;
        margin-top: 20px;
    }

    .ui-w-80 {
        width : 80px !important;
        height: auto;
    }

    .btnprof
    {
        background-color: #4CAF50; /* Green */
        border: none;
        color: white;
        padding: 10px 27px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        

    }

    .btn-default {
        border-color: rgba(24, 28, 33, 0.1);
        background  : rgba(0, 0, 0, 0);
        color       : #4E5155;
        padding: 10px 27px;
        margin: 4px 2px;


    }

    label.btn {
        margin-bottom: 0;
    }

    .btn-outline-primary {
        border-color: #26B4FF;
        background  : transparent;
        color       : #26B4FF;
    }

    .btn {
        cursor: pointer;
    }

    .text-light {
        color: #babbbc !important;
    }

    .btn-facebook {
        border-color: rgba(0, 0, 0, 0);
        background  : #3B5998;
        color       : #fff;
    }

    .btn-instagram {
        border-color: rgba(0, 0, 0, 0);
        background  : #000;
        color       : #fff;
    }

    .card {
        background-clip: padding-box;
        box-shadow     : 0 1px 4px rgba(24, 28, 33, 0.012);
    }

    .row-bordered {
        overflow: hidden;
    }

    .account-settings-fileinput {
        position  : absolute;
        visibility: hidden;
        width     : 1px;
        height    : 1px;
        opacity   : 0;
    }

    .account-settings-links .list-group-item.active {
        font-weight: bold !important;
    }

    html:not(.dark-style) .account-settings-links .list-group-item.active {
        background: transparent !important;
    }

    .account-settings-multiselect~.select2-container {
        width: 100% !important;
    }

    .light-style .account-settings-links .list-group-item {
        padding     : 0.85rem 1.5rem;
        border-color: rgba(24, 28, 33, 0.03) !important;
    }

    .light-style .account-settings-links .list-group-item.active {
        color: #4e5155 !important;
    }

    .material-style .account-settings-links .list-group-item {
        padding     : 0.85rem 1.5rem;
        border-color: rgba(24, 28, 33, 0.03) !important;
    }

    .material-style .account-settings-links .list-group-item.active {
        color: #4e5155 !important;
    }

    .dark-style .account-settings-links .list-group-item {
        padding     : 0.85rem 1.5rem;
        border-color: rgba(255, 255, 255, 0.03) !important;
    }

    .dark-style .account-settings-links .list-group-item.active {
        color: #fff !important;
    }

    .light-style .account-settings-links .list-group-item.active {
        color: #4E5155 !important;
    }

    .light-style .account-settings-links .list-group-item {
        padding     : 0.85rem 1.5rem;
        border-color: rgba(24, 28, 33, 0.03) !important;
    }
    </style>


    <script>

document.addEventListener('DOMContentLoaded', function () {
        var firstNameInput = document.getElementById('fname');
        var lastNameInput = document.getElementById('lname');

        function validateInput(event) {
            var charCode = event.charCode || event.keyCode;
            // Allow only letters (A-Z, a-z) and spaces (charCode 32)
            if ((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && charCode !== 32) {
                event.preventDefault();
            }
        }

        firstNameInput.addEventListener('keypress', validateInput);
        lastNameInput.addEventListener('keypress', validateInput);
    });


        // JavaScript img select
        function chooseFile() {
            document.querySelector('input[type="file"]').click();
        }

        function previewImage(event) {
            const file = event.target.files[0];
            
            if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgElement = document.querySelector('.user');
                imgElement.src = e.target.result;
            }
            reader.readAsDataURL(file);
            }
        }
        function validatePhoneNumber(input) {
            const value = input.value;
            const regex = /^60\d{6,10}$/; // Must start with "60" and followed by 6 to 10 digits
            
            if (!regex.test(value)) {
                input.setCustomValidity("Phone number must start with '60' and be between 8 to 12 digits long.");
            } else {
                input.setCustomValidity("");
            }
        }

        
        
        //id and last login  only can be view
        document.getElementById('id').readOnly = true;
        document.getElementById('lastlogin').readOnly = true;
        document.getElementById('discount_point').readOnly = true;
        document.getElementById('email').readOnly = true;

    


        function resetPhoto() {
        var imgElement = document.querySelector('.user');
        imgElement.src = 'assets/img/user.png'; // Use forward slashes in the path
        }

    

        //funtion save profile data
        document.querySelector('.btnprof').addEventListener('click', function() {
            
            saveProfile();
        });

        function reset_img(reset_img){
        start_load()
        $.ajax({
            url:'admin/ajax.php?action=reset_img',
            method:'POST',
            data:{id: reset_img},
            success:function(resp){
                if(resp == 1){
                    alert_toast("Img reset.")
                    setTimeout(function(){
                        location.reload()
                    },1500)
                }
            }
        })
    }
    </script>