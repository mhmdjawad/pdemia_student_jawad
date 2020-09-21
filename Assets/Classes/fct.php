<?php
function p($o, $c = "#83b8ff"){
    echo '<pre style="padding :5px ; background:'.$c.'" > ';
    print_r($o);
    echo '</pre>';
}

?>