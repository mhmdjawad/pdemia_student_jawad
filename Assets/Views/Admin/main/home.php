<?php
    //if(!isset($_SESSION[SI]['user']) && $_SESSION[SI]['user']['account_role_fk'] < 3){
        //die("only admin can access this page");
    //}
?>
<div class="container-fluid">
    <h1>Admin Page</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="title">sidenav</div>
            <ul class="admin_side_nav">
                <li class="nav_item" onclick="SYS.XHRFct('DBBuilder','Ct982');" > Create tables </li>
                <li class="nav_item" onclick="SYS.XHRFct('show_tables','Ct982');" > Show tables </li>
                <?php foreach(DAL::getTables() as $table){ ?>
                    <li class="nav_item db_item" onclick="SYS.XHRFct('DBView/<?= $table ?>','Ct982');" > <?= $table ?> </li>
                <?php }  ?>
            </ul>
        </div>
        <div id="Ct982" class="col-md-9 AdminPageContents">
            contents
        </div>
    </div>
</div>
