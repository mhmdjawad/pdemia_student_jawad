<?php

class API{
    
    public static function startWeb(){
        ($_SERVER['REQUEST_METHOD'] == 'GET') ? self::HGET() : self::HPOST();
    }

    public static function HGET($f = null){
        if($f == null) $f = URI2;
        $f = explode("?",$f)[0];
        if(method_exists(new API(),$f)){
            self::$f();
        }
        else{
            die("call to undefined function");
        }
    }
    public static function HPOST($f = null){
        $URI = explode("/",URI2);
        if($f == null) $f = (isset($URI[IX]) && $URI[IX]!="") ? $URI[IX] : "";
        if(method_exists(new API(),$f)){self::$f();}
        else{
            $f = explode("/",$_POST[key($_POST)])[0];
            if(method_exists(new API(),$f)){self::$f();}
            else{echo "POST/$f not found";p($_POST);}
        }
    }

    public static function getusers(){
        $d = DAL::getDALT("account");
        $output = [];
        foreach($d as $de){
            array_push($output,[
                "id"=> $de['id']
                ,"username"=> $de['name']
                ,"useremail"=>$de['email']
            ]);
        }
        header('Content-Type: application/json');
        echo json_encode($output);
    }
    public static function getroles(){
        p("sending roles");
    }
    
}