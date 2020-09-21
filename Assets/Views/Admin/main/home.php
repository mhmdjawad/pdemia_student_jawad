<?php
    //if(!isset($_SESSION[SI]['user']) && $_SESSION[SI]['user']['account_role_fk'] < 3){
        //die("only admin can access this page");
    //}
?>
<div class="container-fluid">
    <h1>Admin Page</h1>
    <div class="row">
        <div class="col-md-3 AdminPageNav">
            <div class="title">sidenav 
            <button type="button" id="collapseSideNav" data-collapsed="false" class="btn fa fa-arrow-left"></button>
            </div>
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
<script>
$(document).ready(function(){
    let lis = $(".admin_side_nav li");
    for(let i =0; i < lis.length; i++){
        let li = lis[i];
        if(li == undefined) continue;
        $(li).attr("title",$(li).text().trim());
    }
});
$(document).on('click',`#collapseSideNav`,function(){
    $(".AdminPageNav").toggleClass("col-md-3");
    $(".AdminPageNav").toggleClass("col-md-1");
    $(".AdminPageContents").toggleClass("col-md-9");
    $(".AdminPageContents").toggleClass("col-md-11");
    $(this).toggleClass("fa-arrow-left");
    $(this).toggleClass("fa-arrow-right");
});
</script>