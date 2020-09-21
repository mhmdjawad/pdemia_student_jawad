<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background:rgb(4, 61, 94);">
    <a class="navbar-brand" href="<?= SELF_DIR ?>">
        <img height="30px" src="<?=SELF_DIR?>Assets/Images/pdemia.svg" alt="" srcset="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarctx"
        aria-controls="navbarctx" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarctx">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?= SELF_DIR ?>"> <i class="fa fa-home"></i> Home <span class="sr-only">(current)</span></a>
                
            </li>
            <?php if(!isset($_SESSION['user'])){  ?>
            <li class="nav-item"> <a class="nav-link" href="<?= SELF_DIR ?>login">Login</a></li>
            <?php } else {?>
            <li class="nav-item"> <a class="nav-link" href="<?= SELF_DIR ?>logout">Logout</a></li>
            <?php } ?>
        </ul>
    </div>
</nav>