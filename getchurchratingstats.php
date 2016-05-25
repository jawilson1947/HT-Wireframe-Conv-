<?php  
include'ChromePhp.php';
//getchurchratingstats.php
ChromePhp::warn('getchurchratingstats.php');
$username = 'root';
$password = 'DanaDenyse32';
$dbname = 'ht';
$host = '127.0.0.1';
$pkg = json_decode($_POST["info"]);
$churchid = $pkg->id;
$churchname = $pkg->name;

$status = array('status' => '',
                'action' => '',
                'error' => '',
                'sql' => '');
    try {
          $conn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
    	  if($conn) {
    	  	ChromePhp::warn("Connection is Good");
    	  	ChromePhp::warn('Church ID: is: '.$churchid);
           $sql = "select count(distinct(userid)) as persons, count(distinct(EventID)) as events, count(distinct(RatingID)) as ratings, IFNULL(round(avg(Score)),0) as scoreaverage from ht.tblUserRating where ChurchID  = ".$churchid;
           ChromePhp::warn($sql);
           $result = $conn->query($sql)->fetch(PDO::FETCH_ASSOC);
           if($result) {
             ChromePhp::warn('Stats for '.$churchname.' retrieved!');
             $info = array('persons' => $result['persons'],
      	                'events' => $result['events'],
      	                'ratings' => $result['ratings'],
      	                'scoreaverage' => $result['scoreaverage'],
      	                'status' => 'ok');
      	            echo json_encode($info);          
           } else {
             $info = array('status' => 'no',
              'action' => 'select',
                     'error'=> mysql_error(),
                     'sql' => $sql,
                     'source' => 'GetuserRatingStats');
                     echo json_encode($info);  
             }
    	}
   } catch (PDOException $e){
                  	ChromePhp::warn($e->getMessage);
                    $status['status'] = "Data failure";
                    $status['action'] = "Catch Error";
                    $status['error'] = $e->getMessage;
                    $status['sql'] = $sql;
                    echo json_encode($status);	
  }
  $conn = null;
?>
   