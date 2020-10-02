<?php

class WEB{

    public static function getURI(){
        $URI = trim($_SERVER['REQUEST_URI'],"/");
        $URI = strtolower($URI);
        $URI = str_replace("-","_",$URI);
        $URI = str_replace(" ","_",$URI);
        $URI = str_replace("%20","_",$URI);
        return $URI;
    }
    public static function getURI1($URI_str){
        $URI = explode("/",$URI_str);
        $f = (isset($URI[IX]) && $URI[IX] !="") ? $URI[IX] : "home";
        return $f;
    }
    public static function getURI2($URI_str){
        $URI = explode("/",$URI_str);
        $f = (isset($URI[IX+1]) && $URI[IX+1] !="") ? $URI[IX+1] : "";
        if(strpos($f,"?") > -1){
            $f = substr($f,0,strpos($f,"?"));
        }
        return $f;
    }
    public static function StartWeb(){
        try {
            include(join(DIRECTORY_SEPARATOR, array(Classes,"fct.php" )));//contain p
            include(join(DIRECTORY_SEPARATOR, array(Classes,"set.php" )));//contain basic define
            include(join(DIRECTORY_SEPARATOR, array(Classes,"UTIL.php" )));//utilities for helping
            include(join(DIRECTORY_SEPARATOR, array(Classes,"DAL.php" )));//data access layer
            define('URI',self::getURI());
            define('URI1',self::getURI1(URI));
            define('URI2',self::getURI2(URI));
            ($_SERVER['REQUEST_METHOD'] == 'GET') ? self::HGET() : self::HPOST();
        }catch(PDOException $e){
            if(ADMIN) {p($e->getFile() . $e->getLine() . $e->getMessage() );}
            else  p('Please Contact ADMIN');
		}catch(Error $e){
			if(ADMIN) {p($e->getFile() . $e->getLine() . $e->getMessage() );}
            else  p('Please Contact ADMIN');
		}
    }
    public static function HGET($f = null){
        if($f == null) $f = URI1;
        $view =  join(DIRECTORY_SEPARATOR, array(Views,"Main",$f.".php" ));
        $resource = $_SERVER['REQUEST_URI'];
        $resource = str_replace("index.php/","",$resource);
        $resource = str_replace("/index.php","",$resource);
        $resource = str_replace("index.php","",$resource);
        $resource = str_replace("/Assets","Assets",$resource);
        if(strpos($f,"?") > -1){
            $f = substr($f,0, strpos($f,"?"));        
        }
        if(method_exists(new WEB(),$f)){self::$f();}
        elseif(file_exists($view)){include($view);}
        else{ self::home(); }
    }
    public static function HPOST($f = null){
        $URI = explode("/",URI);
        if($f == null) $f = (isset($URI[IX]) && $URI[IX]!="") ? $URI[IX] : "";
        if(method_exists(new WEB(),$f)){self::$f();}
        else{
            $f = explode("/",$_POST[key($_POST)])[0];
            if(method_exists(new WEB(),$f)){self::$f();}
            else{echo "POST/$f not found";p($_POST);}
        }
    }
    public static function getImageUrl($name){
        $ext = UTIL::ext($name);
        $img_url = SELF_DIR."Assets/Images/$ext/$name";
        return $img_url;
    }
    //CUSTOM WEB PAGES
    public static function home(){
        include join(DIRECTORY_SEPARATOR, array(Views,"Part","head.php" ));
        include join(DIRECTORY_SEPARATOR, array(Views,"Part","nav.php" ));
        include join(DIRECTORY_SEPARATOR, array(Views,"Main","home.php" ));
        include join(DIRECTORY_SEPARATOR, array(Views,"Part","foot.php" ));
    }
    public static function login($f = null){
        if($f == null || count($f) < 2){
            if(GORP == "GET"){
                include join(DIRECTORY_SEPARATOR, array(Views,"Part","head.php" ));
                include join(DIRECTORY_SEPARATOR, array(Views,"Part","nav.php" ));
            }
            include join(DIRECTORY_SEPARATOR, array(Views,"Main","login.php" ));
        }
        elseif($f[1] == "submitlogin"){
            $email = $_POST['email'];
            $passw = $_POST['password'];
            $d = DAL::call_sp("select * from account where email=:email",[
                ["k"=>"email","v"=>$email]
            ]);
            if(count($d) == 0){
                p("email not found","red");
            }
            else{
                $d = $d[0];
                $salt = $d['salt'];
                $passw = md5($passw.$salt);
                if($d["password"] == $passw){
                    $_SESSION["user"] = $d;
                    p("login successful");
                }
                else{
                    p("wrong password","red");
                }
            }
        }
    }
    public static function register(){
        $f = (GORP == "GET") ? explode("/",URI) : explode("/",$_POST[key($_POST)]);
        $IX = (GORP == "GET") ? IX+1 :1;
        if(GORP == "GET"){
            include join(DIRECTORY_SEPARATOR, array(Views,"Part","head.php" ));
            include join(DIRECTORY_SEPARATOR, array(Views,"Part","nav.php" ));
        }
        if(!isset($f[$IX])){
            echo '<div class="container RegisterFormContaienr">';
            $html = DAL::getFormForTable("account",[],["salt","tstp"],"register/submit");
            echo $html;
            echo '</div>';        
        }
        elseif($f[$IX]=="submit"){
            $d = $_POST;
            $table = $d['table'];
            unset($d['key']);
            unset($d['table']);
            if($table == "account"){
                $d = DAL::call_sp("select count(*) exist from account where email=:email",[
                    ["k"=>"email","v"=>$d['email']]
                ]);
                if($d[0]['exist'] > 0){
                    die("email used by another account use to loguin");
                }
                $time = time();
                $d['salt'] = md5($time);
                $d['password'] = md5($d['password'].$d['salt']);
                $r = DAL::insert($table,$d);
                if($f > 0){
                    p("account registered");
                }
                else{
                    p("account not registered");
                }
            }
        }
        else{
            die("unknown request $f");
        }
    }
    public static function profile(){
        $f = (GORP == "GET") ? explode("/",URI) : explode("/",$_POST[key($_POST)]);
        $IX = (GORP == "GET") ? IX+1 :1;
        if(!isset($_SESSION[SI]['user'])) die("need to login");
        
        if(!isset($f[$IX])){

        }
        elseif($f[$IX]=="DBCtrl"){
            
        }
        elseif($f[$IX]=="DBSave"){
            if($_SESSION[SI]['user']['account_role_fk'] < 3) die("only admin are allowed");
            $d = $_POST;
            $table = $d['table'];
            unset($d['key']);
            unset($d['table']);
            if($table == "account"){
                $d = DAL::call_sp("select count(*) exist from account where email=:email",[
                    ["k"=>"email","v"=>$d['email']]
                ]);
                if($d[0]['exist'] > 0){
                    die("email used by another account use to loguin");
                }
                $time = time();
                $d['salt'] = md5($time);
                $d['password'] = md5($d['password'].$d['salt']);
                $r = DAL::insert($table,$d);
                if($f > 0){
                    p("account registered");
                }
                else{
                    p("account not registered");
                }
            }
        }
        else{
            p("unkonw key ");
        }

    }
    public static function viewall(){
        $key = "asdfnwf";
        $keyMd5 = "f221d1e274874b7dae1e53bcb60c8290";
        //if(!isset($_GET['key']) || md5($_GET['key']) == $keyMd5){ die('404');}
        include join(DIRECTORY_SEPARATOR, array(Views,"Part","head.php" ));
        include join(DIRECTORY_SEPARATOR, array(Views,"Part","nav.php" ));
        p("sowing all");    

        $tables = DAL::getTables();
        foreach($tables as $table){
            $html = DAL::genViewTable($table);
            echo $html;
        }

    }
    public static function article(){
        $f = (GORP == "GET") ? explode("/",URI) : explode("/",$_POST[key($_POST)]);
        $IX = (GORP == "GET") ? IX+1 :0;
        include join(DIRECTORY_SEPARATOR, array(Views,"Part","head.php" ));
        include join(DIRECTORY_SEPARATOR, array(Views,"Part","nav.php" ));
        if(!isset($f[$IX])) die("no id for article found");
        $id = $f[$IX];
        $article = DAL::getDALT("articles",$id);
        if($article == null) die("article removed");
        p($article);
        echo $article[$id]['html_contents'];
        include join(DIRECTORY_SEPARATOR, array(Views,"Part","foot.php" ));
        
    }
    //ADMIN CUSTOM PAGES
    public static function admin(){
        include(join(DIRECTORY_SEPARATOR, array(Controllers,"admin.php" )));
        try{
            Admin::startWeb();
        }
        catch(Error $e){die("admin page error");}
    }
    public static function api(){
        include(join(DIRECTORY_SEPARATOR, array(Controllers,"api.php" )));
        try{
            API::startWeb();
        }
        catch(Error $e){die("api page error");}
    }
    public static function getHtml(){
        $f = (GORP == "GET") ? explode("/",URI) : explode("/",$_POST[key($_POST)]);
        $IX = (GORP == "GET") ? IX : 1;
        if(!isset($f[$IX+1])) die("missing table");
        if(!isset($f[$IX+2])) die("missing column");
        if(!isset($f[$IX+3])) die("missing id");
        $table = $f[$IX+1];
        $col = $f[$IX+2];
        $id = $f[$IX+3];
        $tables = DAL::getTables();
        if(in_array($table,$tables)){
            $d = DAL::getDALT($table,$id);
            if(count($d) > 0){
                $d = $d[$id];
                if(isset($d[$col])){
                    echo $d[$col];
                }
                else{
                    p("unknown column $col");
                }
            }
            else{
                p("record identifier malformed $id");
            }
        }
        else{
            p("table $table is misformed in url");
        }
    }
}
?>