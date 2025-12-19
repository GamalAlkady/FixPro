<?php
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">';
    echo $_SESSION['error'];
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    unset($_SESSION['error']);
} elseif (isset($_SESSION['success'])) {
    echo '         <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">';
    echo $_SESSION['success'];
    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    unset($_SESSION['success']);
}


//echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
//    echo 'error';
//echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
