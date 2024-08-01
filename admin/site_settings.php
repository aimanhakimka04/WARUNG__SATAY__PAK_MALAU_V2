    <?php
    include 'db_connect.php';
    
    $qry = $conn->query("SELECT * from system_settings limit 1");
    if($qry->num_rows > 0){
        foreach($qry->fetch_array() as $k => $val){
            $meta[$k] = $val;
        }
    }
    ?>
    <?php include('header.php');?>
<script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<? include('footeradmin.php');?>

    <div class="container-fluid">
        
        <div class="card col-lg-12">
            <div class="card-body">
                <form action="" id="manage-settings">
                    <div class="form-group">
                        <label for="name" class="control-label">System Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Please enter a valid email address" value="<?php echo isset($meta['email']) ? $meta['email'] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="contact" class="control-label">Contact</label>
                        <input type="tel" pattern="[0-9]*" class="form-control" id="contact" name="contact" value="<?php echo isset($meta['contact']) ? $meta['contact'] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="about" class="control-label">About Content</label>
                        <textarea name="about" class="text-jqte"><?php echo isset($meta['about_content']) ? $meta['about_content'] : '' ?></textarea>

                        


                        <div class="form-group">
        <label for="" class="control-label">Image</label>
        <input type="file" class="form-control" name="img0" accept="image/jpeg, image/png, image/jpg, image/gif" onchange="displayImg(this, $(this), 0)">
        <img src="<?php echo isset($meta['cover_img']) ? '../assets/img/' . $meta['cover_img'] : '' ?>" alt="" id="cimg0">
    </div>

    <div class="form-group">
        <label for="" class="control-label">Billboard 1</label>
        <input type="file" class="form-control" name="img1" accept="image/jpeg, image/png, image/jpg, image/gif" onchange="displayImg(this, $(this), 1)">
        <img src="<?php echo isset($meta['Billboard1']) ? '../assets/img/' . $meta['Billboard1'] : '' ?>" alt="" id="cimg1">
    </div>

    <div class="form-group">
        <label for="" class="control-label">Billboard 2</label>
        <input type="file" class="form-control" name="img2" accept="image/jpeg, image/png, image/jpg, image/gif" onchange="displayImg(this, $(this), 2)">
        <img src="<?php echo isset($meta['Billboard2']) ? '../assets/img/' . $meta['Billboard2'] : '' ?>" alt="" id="cimg2">
    </div>

    <div class="form-group">
        <label for="" class="control-label">Billboard 3</label>
        <input type="file" class="form-control" name="img3" accept="image/jpeg, image/png, image/jpg, image/gif" onchange="displayImg(this, $(this), 3)">
        <img src="<?php echo isset($meta['Billboard3']) ? '../assets/img/' . $meta['Billboard3'] : '' ?>" alt="" id="cimg3">
    </div>





                    <center>
                        <button class="btn btn-info btn-primary btn-block col-md-2">Save</button>
                    </center>
                </form>
            </div>
        </div>
        <style>
    img#cimg0{
                height: 150px;
                width: 150px;
                margin-top: 25px; /* Add some margin for better spacing */
            }

            img#cimg1, img#cimg2, img#cimg3{
            height: 50px;
                width: 192px;
                margin-top: 25px; /* Add some margin for better spacing */

            }
    </style>

     <script>
            function displayImg(input, _this, index) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = new Image();
            img.onload = function() {
                if (this.width === 1920 && this.height === 500) {
                    // Set the source attribute for the corresponding image
                    $('#cimg' + index).attr('src', e.target.result);
                } else {
                    alert('Image dimensions must be 1920x500 pixels.');
                    // 清除输入
                    $(input).val('');
                    // 清除对应的图像显示
                    $('#cimg' + index).attr('src', ''); 
                }
            };
            img.src = e.target.result;
        };

        reader.readAsDataURL(input.files[0]);
    }

        }

            // Existing JavaScript code for text-jqte and form submission
            $('.text-jqte').jqte();

            $('#manage-settings').submit(function (e) {
                e.preventDefault()
                start_load()
                $.ajax({
                    url: 'ajax.php?action=save_settings',
                    data: new FormData($(this)[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                    error: err => {
                        console.log(err)
                    },
                    success: function (resp) {
                        if (resp == 1) {
                            alert_toast('Data successfully saved.', 'success')
                            setTimeout(function () {
                                alert("Data successfully saved.");
                                location.reload()
                            }, 1000)
                        }
                    }
                })
            })
        </script>
    <style>
        
    </style>
    </div>