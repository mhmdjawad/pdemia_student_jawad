<?php
///-------------------------------------------------------------------------|
/*                                                                          |
 * @author   Lebanon [USERNAME] [Pdemia.com] [MJZ]                          |
 * @date     18-09-2020                                                     |
 *///                                                                       |
///-------------------------------------------------------------------------|
define("DS",DIRECTORY_SEPARATOR);//                                         |
define("CWD",getcwd());//                                                   |
define("Assets",join(DS, array(CWD,"Assets")));//                           |
define("Views",join(DS, array(CWD,"Assets", "Views")));//                   |
define("Classes",join(DS, array(CWD,"Assets","Classes")));//                |
define("Controllers",join(DS, array(CWD,"Assets","Controllers")));//        |
///-------------------------------------------------------------------------|
if(file_exists(Controllers.'//web.php')){//                                 |
include Controllers.'/web.php';//                                           |
WEB::startWeb();}//                                                         |
else echo 'CRITICAL : WEB Control not found';//                             |
die();//                                                                    |
///-------------------------------------------------------------------------|
?>