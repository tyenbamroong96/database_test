<?php
$logged_in = $_SESSION['logged_in'];
// echo $logged_in;
if ($logged_in == TRUE) {
    include 'header_member.php';
} else {
    include 'header.php';
}
?>
