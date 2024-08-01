


    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .custom-rating-container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 50px auto;
        }

        .custom-stars img {
            width: 50px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .custom-stars img:hover {
            transform: scale(1.1);
        }

        .custom-rating-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .custom-rating-form textarea {
            resize: vertical;
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .custom-rating-form input[type="file"] {
            padding: 10px;
            font-size: 14px;
        }

        .custom-rating-form button {
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .custom-rating-form button:hover {
            background-color: #0056b3;
        }

        #custom-img-preview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 10px;
            cursor: pointer;
        }

        /* Modal styles */
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .custom-modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .custom-close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .custom-close:hover,
        .custom-close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* Comments section */
        .comments-container {
            width: 90%;
            max-width: 1000px; /* Optional: Set a maximum width to prevent it from becoming too large on wide screens */
            margin: 0 auto; /* Centering the container */
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }




        .comment {
            width: 300px;
            height:auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        
        .comment img.user-img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }

        .comment-text {
            margin: 5px 0;
            word-wrap: break-word;
            width: 100%;
            text-align: left;
        }

        .comment img{
            width:230px;
            height:170px;
        }
        .comment img.comimg {
            cursor: pointer;
            border-radius: 10px;
            width: 230px;
            height: 170px;
            object-fit: cover; /* Ensures the image covers the entire area without distortion */

        }

        .ratestar img {
            width: 25px;
            height: 25px;
        }
    </style>
</head>
<body>
    
<div class="custom-rating-container">
    <h1>Rate Us</h1>
    <div class="custom-stars" id="custom-stars" required>
        <img src="admin/assets/img/star_empty.png" alt="Star" data-value="1">
        <img src="admin/assets/img/star_empty.png" alt="Star" data-value="2">
        <img src="admin/assets/img/star_empty.png" alt="Star" data-value="3">
        <img src="admin/assets/img/star_empty.png" alt="Star" data-value="4">
        <img src="admin/assets/img/star_empty.png" alt="Star" data-value="5">
    </div>
    <p id="custom-rating-value">Rating: </p>
    <form class="custom-rating-form" id="custom-ratingForm">
        <input type="hidden" name="custom-rating" id="custom-rating" value="">
        <textarea id="custom-description" name="custom-description" placeholder="Write your review..." required></textarea>
        <input type="file" name="custom-img_comment" id="custom-img_comment" accept="image/png, image/jpeg, image/gif" required>
        <img id="custom-img-preview" src="#" alt="Image Preview" style="display:none;">
        <button type="submit">Submit</button>
    </form>
</div>

<!-- The Modal -->
<div id="custom-myModal" class="custom-modal">
    <span class="custom-close">&times;</span>
    <img class="custom-modal-content" id="custom-img-modal">
</div>

<script>document.addEventListener('DOMContentLoaded', function () {
            const stars = document.querySelectorAll('.custom-stars img');
            const ratingValue = document.getElementById('custom-rating-value');
            const ratingInput = document.getElementById('custom-rating');
            const ratingForm = document.getElementById('custom-ratingForm');
            const imgComment = document.getElementById('custom-img_comment');
            const imgPreview = document.getElementById('custom-img-preview');
            const modal = document.getElementById('custom-myModal');
            const modalImg = document.getElementById('custom-img-modal');
            const closeModal = document.getElementsByClassName('custom-close')[0];
            let currentRating = 0;

            stars.forEach(star => {
                star.addEventListener('click', function () {
                    currentRating = parseInt(this.getAttribute('data-value'));
                    ratingInput.value = currentRating; // Update rating value
                    updateStars(currentRating);
                    ratingValue.textContent = `Rating: ${currentRating}`;
                });

                star.addEventListener('mouseover', function () {
                    const hoverRating = parseInt(this.getAttribute('data-value'));
                    updateStars(hoverRating);
                });

                star.addEventListener('mouseout', function () {
                    updateStars(currentRating);
                });
            });

            function updateStars(rating) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.src = 'admin/assets/img/star.png';
                    } else {
                        star.src = 'admin/assets/img/star_empty.png';
                    }
                });
            }

            imgComment.addEventListener('change', function () {
                const file = imgComment.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imgPreview.src = e.target.result;
                        imgPreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });

            imgPreview.addEventListener('click', function () {
                modal.style.display = 'block';
                modalImg.src = imgPreview.src;
            });

            closeModal.onclick = function () {
                modal.style.display = 'none';
            }

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }

            ratingForm.addEventListener('submit', function (event) {

                if (ratingInput.value === '') {
                    alert('Please rate by clicking on a star before submitting the form.');
                    event.preventDefault();
                    return false;
                }


                event.preventDefault(); // Prevent the default form submission

                const formData = new FormData(ratingForm);
                formData.append('user_id', <?php echo $_SESSION['login_user_id']; ?>); // Add user ID to the form data

                fetch('save_rate.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Show response from PHP script
                })
                .catch(error => {
                    console.error('Error:', error);
                });
                location.reload(); // Reload the page after submitting the form
            });

        });
    </script>
</body>
</html>