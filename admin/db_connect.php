<?php

try {
    $conn = new mysqli('localhost', 'root', '', 'pakmalausatay_db') or die("Could not connect to mysql" . mysqli_error($con));
} catch (Exception $e) {
    phpinfo();
    $conn = null;
}
