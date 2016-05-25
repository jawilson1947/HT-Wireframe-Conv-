<?php
//getratingdata.php
//Selects the events and rating information for a given church and returns via JSON object arrays
// directive is in the status variable of the JSON object should be either 'events' or 'ratings'
include 'ChromePhp.php';
ChromePhp::warn('getratingdata');
$username = 'root';
$password = 'DanaDenyse32';
$dbname = 'ht';
$host = '127.0.0.1';
$c = array( 'status' => '',
  'action' => '',
  'error' =>  '',
  'sql' => '');
 
try {
  $cn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
  $pkg = json_decode($_POST["info"]);
  $mode = $pkg->status;
  $churchid = $pkg->id;
  if($mode == 'ratings') {
    $sql = "select * from ht.tblEventRatings where ChurchID = :ci order by EventID,rank";
  } else {
    $sql = "select * from ht.tblEvents where ChurchID = :ci order by rank";
  }
  $xsql = $cn->prepare($sql);
  $xsql->bindParam(':ci',$churchid,PDO::PARAM_INT);
  ChromePhp::warn('SQL : '.$sql);
  if($xsql->execute()) {
  	$data = $xsql->fetchALL(PDO::FETCH_ASSOC);
     ChromePhp::warn('Search is successful');
    foreach($data as $d):
     ChromePhp::warn('Church ID:'.$d['ChurchID'].'Event ID:'.$d['EventID']);
     endforeach;
     echo json_encode($data);
   } else {
      ChromePhp::warn('Query Unsuccessful');
      $c['status'] = 'no';
      $c['action'] = 'query';
      $c['error'] = "!Not Found";
      $c['sql'] = $sql;
      echo json_encode($c);
  }
 
} catch (PDOException $e) {
  $c['status'] = 'no';
  $c['action'] = 'selecting rating/event items ';
  $c['error'] =  $e->getMessage;
  $c['sql'] = $sql;
   ChromePhp::warn($c['error']);
   echo json_encode($c);
   die();
 }
 










?>