<?php 
class UTIL{
    public static function printDataTable($d){
        $hideCols = array("id");
        if(count($d) ==0) die('empty table');
        echo '<table id="DataT" class="table" >';
        echo '<thead>';
        foreach($d[0] as $k=>$v){
            if(in_array($k,$hideCols)) continue;
            $title = $k;
            $title = str_replace("_"," ",$title);
            echo '<th class="tableHeader" >' .$title. '</th>';
        }
        echo '</thead>';
        echo '<tbody>';
        for($i = 0 ; $i < count($d) ; $i++){
            echo '<tr>';
            foreach($d[$i] as $c=>$v){
                if(in_array($c,$hideCols)) continue;
                if($c == "cv"){
                    echo '<td> <a download="'.$d[$i]['first_name'].'.pdf" href="'.SELF_DIR."Assets/cvs/" . $v . '">Download CV </a></td>';
                }
                else{
                    echo '<td>' . $v . '</td>';
                }
            } 
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '<script> $("#DataT").DataTable();</script>';
    }
    public static function ext($name){
        return substr($name,strrpos($name, '.', -0)+1);
    }
    public static function captcha(){
        $image = imagecreatetruecolor(200, 50);
        $background_color = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image,0,0,200,50,$background_color);
        $line_color = imagecolorallocate($image, 64,64,64);
        $number_of_lines=rand(3,7);
        for($i=0;$i<$number_of_lines;$i++){
            imageline($image,0,rand()%50,250,rand()%50,$line_color);
        }
        $pixel = imagecolorallocate($image, 0,0,255);
        for($i=0;$i<500;$i++){
            imagesetpixel($image,rand()%200,rand()%50,$pixel);
        }
        $allowed_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $length = strlen($allowed_letters);
        $letter = $allowed_letters[rand(0, $length-1)];
        $word='';
        $text_color = imagecolorallocate($image, 0,0,0);
        $cap_length=6;// No. of character in image
        for ($i = 0; $i< $cap_length;$i++){
            $letter = $allowed_letters[rand(0, $length-1)];
            imagestring($image, 5,  5+($i*30), 20, $letter, $text_color);
            $word.=$letter;
        }
        $_SESSION[SI]['captcha_string'] = $word;
        // imagepng($image, "captcha_image.png");
        header("Content-type: image/png");
        imagepng($image);
        imagedestroy($image);
    }
    public static function drawTableDefault($d){
        if(count($d)==0) return;
        echo '<table class="table mjTableDefault"><thead>';
        foreach($d[0] as $k=>$v){
            echo '<th>'.$k.'</th>';
        }
        echo '</thead></tbody>';
        foreach($d as $row){
            echo '<tr>';
            foreach($row as $col) echo '<td>'.$col.'</td>';
            echo '</td>';
        }
        echo '</tbody></table>';
    }
    private function multiexplode ($delimiters,$string) {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
    public static function getSize($size){
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
    /**Parse Configuration For Charts */
    public static function ParseCgfForPieChart($title,$given_data){
        $labels = [];
        $values = [];
        foreach($given_data as $k=>$v){
            array_push($labels,$k);
            array_push($values,$v);
        }
        $config = "
        {
			type: 'pie',
			data: {
				datasets: [{
					data: [".implode(",",$values)."],
					backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        '#0ead1480',
                        '#075d0ab3',
                        '#ab631b',
					],
					label: 'Dataset 1'
				}],
				labels: ['".implode("','",$labels)."'
				]
			},
			options: {
				responsive: true
			}
		}
        ";
        $title = str_replace("_"," ",$title);
        $title = strtoupper($title);
        self::DrawChart($title,$config);
    }
    public static function ParseCgfForBarChart($title,$given_data){
        $labels = [];
        $values = [];
        foreach($given_data as $k=>$v){
            array_push($labels,$k);
            array_push($values,$v);
        }
        $title = str_replace("_"," ",$title);
        $title = strtoupper($title);
        $config = "
        {
			type: 'bar',
			data: {
				datasets: [{
					data: [".implode(",",$values)."],
					backgroundColor: '#1167a0',
                    borderColor: '#51aeec',
                    fill:false,
					label: '".$title."'
				}],
				labels: ['".implode("','",$labels)."'
				]
			},
			options: {
				responsive: true
			}
		}
        ";
        
        $id = 'chart-'.time().rand();
        echo '
        <div class="chart-container chartContainer barchart" >
            <div class="chart-title">'.$title.'</div>
            <canvas id="'.$id.'"></canvas>
        </div>';
        echo '<script>
        var config = '.$config.';
        var myPieChart = new Chart($("#'.$id.'"),config);
        </script>';
    }
    public static function ParseCgfForHistoChart($title,$given_data){
        $labels = [];
        $values = [];
        foreach($given_data as $k=>$v){
            array_push($labels,$k);
            array_push($values,$v);
        }
        $config = "
        {
			type: 'line',
			data: {
				datasets: [{
					data: [".implode(",",$values)."],
					backgroundColor: '#1167a0',
                    borderColor: '#51aeec',
                    fill:false,
					label: 'Added Reports'
				}],
				labels: ['".implode("','",$labels)."'
				]
			},
			options: {
				responsive: true
			}
		}
        ";
        $title = str_replace("_"," ",$title);
        $title = strtoupper($title);
        $id = 'chart-'.time().rand();
        echo '
        <div class="chart-container chartContainer linechart" >
            <div class="chart-title">'.$title.'</div>
            <canvas id="'.$id.'"></canvas>
        </div>';
        echo '<script>
        var config = '.$config.';
        var myPieChart = new Chart($("#'.$id.'"),config);
        </script>';
    }
    /**CHART DRAWER */
    public static function DrawChart($title,$config){
        $id = 'chart-'.time().rand();
        echo '
        <div class="chart-container chartContainer" style="">
            <div class="chart-title">'.$title.'</div>
            <canvas id="'.$id.'"></canvas>
        </div>';
        echo '<script>
        var config = '.$config.';
        var myPieChart = new Chart($("#'.$id.'"),config);
        </script>';
    }
}
?>