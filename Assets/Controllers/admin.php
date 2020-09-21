<?php
class Admin{
    public static function startWeb(){
        try {
            define("Views_Admin",join(DS, array(Views,"Admin")));
            ($_SERVER['REQUEST_METHOD'] == 'GET') ? self::HGET() : self::HPOST();
        }catch(PDOException $e){
            p(basename($e->getFile()) .' '. $e->getLine() .' '. $e->getMessage() );
		}catch(Error $e){
			p(basename($e->getFile()) .' '. $e->getLine() .' '. $e->getMessage() );
		}
    }
    public static function HGET($f = null){
        if($f == null) $f = URI2;
        $f = explode("?",$f)[0];
        $view =  join(DIRECTORY_SEPARATOR, array(Views_Admin,"main",$f.".php" ));
        
        if(method_exists(new Admin(),$f)){
            self::$f();
        }
        elseif(file_exists($view)){
            self::load_view("part","header");
            self::load_view("part","nav");
            self::load_view("main","$f");
            self::load_view("part","footer");
        }
        else{ 
            self::load_view("part","header");
            self::load_view("part","nav");
            self::load_view("main","home");
            self::load_view("part","footer");
        }
    }
    public static function HPOST($f = null){
        $URI = explode("/",URI2);
        if($f == null) $f = (isset($URI[IX]) && $URI[IX]!="") ? $URI[IX] : "";
        if(method_exists(new Admin(),$f)){self::$f();}
        else{
            $f = explode("/",$_POST[key($_POST)])[0];
            if(method_exists(new Admin(),$f)){self::$f();}
            else{echo "POST/$f not found";p($_POST);}
        }
    }
    public static function load_view($dir = "main",$view){
        $view_path = join(DIRECTORY_SEPARATOR, array(Views_Admin,$dir,"$view.php" ));
        if(file_exists($view_path)){
            include $view_path;
        }
        else{
            echo "<br> $view removed";
        }
    }
    public static function show_tables(){
        p("showing tables...");
        $tables = DAL::getTables();
        foreach($tables as $table){
            echo "<h2>$table</h2>";
            $html = DAL::genViewTable($table);
            echo $html;
            echo '<hr/>';
        }
    }
    ///DATABASE ACCESS
    public static function DBView(){
        $f = (GORP == "GET") ? explode("/",URI) : explode("/",$_POST[key($_POST)]);
        $IX = (GORP == "GET") ? IX+1 :0;
        $table = $f[$IX +1];
        $html = ''; $html .= '<h2>Table : '.str_replace("_"," ",$f[$IX +1]).'</h2>';$c = "DBNew/".$f[$IX +1];
        $html .= '<button type="button" class="btn btn-sm btn-success" onclick="SYS.LoadXHR(\'Ct982\',\''.$c.'\');" ><i class="fa fa-plus"></i></button>';
        $html .= DAL::genViewTable($f[$IX +1],
        ['edit'=>"DBEdit",'disable'=>"DBDisable",'delete'=>"DBDelete"]
        );echo $html;
    }
    public static function DBEdit(){
        $f = (GORP == "GET") ? explode("/",URI) : explode("/",$_POST[key($_POST)]);
        $IX = (GORP == "GET") ? IX+1 :0;
        $html = DAL::getEditTable(
            $f[$IX +1]
            ,$f[$IX+2]
            ,$f[$IX+3]
            ,[]
            ,"DBSave");echo $html;
    }
    public static function DBDisable(){
        $f = (GORP == "GET") ? explode("/",URI) : explode("/",$_POST[key($_POST)]);
        $IX = (GORP == "GET") ? IX+1 :0;
        $d=[
            "active"=>0
        ];
        $r = DAL::update($f[$IX +1],$d,$f[$IX+2],$f[$IX+3]);
        if($r > 0){
            p("success");
        }
        else{
            p("error occured");
        }
    }
    public static function DBDelete(){
        $f = (GORP == "GET") ? explode("/",URI) : explode("/",$_POST[key($_POST)]);
        $IX = (GORP == "GET") ? IX+1 :0;
        $r = DAL::delete($f[$IX +1],$f[$IX+2],$f[$IX+3]);
        if($r > 0){
            p("success");
        }
        else{
            p("error occured");
        }
    }
    public static function DBNew(){
        $f = (GORP == "GET") ? explode("/",URI) : explode("/",$_POST[key($_POST)]);
        $IX = (GORP == "GET") ? IX+1 :0;
        $html = DAL::getFormForTable(
            $f[$IX +1]
            ,null
            ,[]
            ,"DBSave"
            );echo $html;
    }
    public static function DBSave(){
        $d = $_POST;
        if(!isset($d['table'])) die("unknown table");
        $t = $d['table'];
        unset($d['key']);
        unset($d['table']);
        foreach($d as $k=>$v){
            if($v == ""){
                unset($d[$k]);
            }
        }
        if($t == "account"){
            $time = time();
            $d['salt'] = md5($time);
            $d['password'] = md5($d['password'].$d['salt']);
        }
        if(isset($d['id'])){
            //update
            $r = DAL::update($t,$d,"id",$d['id']);
        }
        else{
            //insert
            $r = DAL::insert($t,$d);
        }
        if($r > 0){
            p("success");
        }
        else{
            p("error occured");
        }

    }
    public static function DALImageUpload(){
        $file = $_FILES['image'];
        if(file_exists($file['tmp_name'])){
            $ext = UTIL::ext($file['name']);
            $folder = join(DIRECTORY_SEPARATOR, array(Assets,"Images",$ext));
            $destination = join(DIRECTORY_SEPARATOR, array($folder,$file['name']));
            if(!is_dir($folder)){
                mkdir($folder);
            }
            $url = SELF_DIR . "Assets/Images/$ext/" . $file['name'];
            if(file_exists($destination)){
                echo json_encode(["result"=>true,"name"=>$file['name'],"url"=>$url]);
            }
            else{
                if(move_uploaded_file($file['tmp_name'],$destination)){
                    echo json_encode(["result"=>true,"name"=>$file['name'],"url"=>$url]);
                }
                else{
                    echo json_encode(["result"=>false,"msg"=>"could not upload file"]);
                }
            }
        }
        else{
            echo json_encode(["result"=>false,"msg"=>"could not upload file"]);
        }
    }
    public static function getImageUrl($name){
        $ext = UTIL::ext($name);
        $img_url = SELF_DIR."Assets/Images/$ext/$name";
        return $img_url;
    }
    //DATABASE BUILDING KIT
    public static function DBBuilder(){
        $f = (GORP == "GET") ? explode("/",URI) : explode("/",$_POST[key($_POST)]);
        $IX = (GORP == "GET") ? IX+1 :0;
        
        ///TODO authenticate this
        if(!isset($f[$IX+1])){
            self::load_view("main","db_builder");
        }
        elseif(strtolower($f[$IX+1]) == strtolower("getTables")){
            ob_clean();
            header('Content-Type: application/json');
            $tables = DAL::getTables();
            echo json_encode($tables);
        }
        elseif($f[$IX+1] == "createTable"){
            $table_name = $_POST['table_name'];
            $tables = DAL::getTables();
            if(in_array($table_name,$tables)){
                die(("table with same name $table_name exist in database"));
            }
            $createq = "CREATE TABLE `$table_name` (`id` int unsigned AUTO_INCREMENT PRIMARY KEY) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $r = DAL::execute_query($createq);
            if($r < 1){
                die(p("could not create table"));
            }
            $columns = json_decode($_POST['columns']);
            foreach($columns as $col){
                $name = $col->name;
                $type =$col->type;
                $query = "";
                if($type == "yes/no"){
                    $query = "ALTER TABLE $table_name ADD active_$name varchar(1) DEFAULT '1';";
                }
                elseif($type == "html"){
                    $query = "ALTER TABLE $table_name ADD html_$name TEXT;";
                }
                else{
                    $query = "ALTER TABLE $table_name ADD $name VARCHAR(255);";
                }
                $r = DAL::execute_query($query);
                if($r < 1){
                    p("could not execute $query");
                }
                else{
                    p("executed $query");
                }
            }
        }
        else{
            p($IX);
            p($f[$IX+1]);
            p($f);
        }
    }
}