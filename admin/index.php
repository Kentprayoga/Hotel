<?php
include "./config/db.php";
// Periksa apakah pengguna telah login dan apakah mereka adalah admin
?>

<title>Dashboard Admin</title>
<?php require ('./view/link.php'); ?>
<style>
.h-font {
    font-family: 'Cardo', serif;
}

#dashboard-menu {
    position: absolute;
    height: 100%;
}

@media screen and (max-width: 992px) {
    #dashboard-menu {
        height: auto;
        width: 100%;
    }
}
</style>
<?php require('view/header.php'); ?>
<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui, reiciendis. Laboriosam molestiae harum
            dolorem tempora eos hic quo, a architecto, est in provident libero ipsum placeat. Reprehenderit quam
            ipsum nulla?
        </div>
    </div>
</div>

<?php require ('./view/script.php'); ?>

</html>