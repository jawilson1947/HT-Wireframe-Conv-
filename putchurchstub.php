<?php
 //putchurchstub.php
//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
 //{
include 'ChromePhp.php';
ChromePhp::warn('putchurchstub.php');
$username = 'root';
$password = 'DanaDenyse32';
$dbname = 'ht';
$host = '127.0.0.1';
$rs = json_decode($_POST["info"]);
$status = array('status' => '',
                'action' => '',
                'error' => '',
               'sql' => '');
            
try {
     $conn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
     if($conn) {
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      ChromePhp::warn('Connection is Good');
      $church = json_decode($_POST["info"]);
      $domain = $church->domain;
      $placeid = $church->placeid;
      $isql = "insert into ht.tblchurch(churchname,GoogleMapPlaceID,domain,address,city,phone,website,state,country,zipcode,datecreated) values (:cn,:pl,:do,:ad,:ci,:ph,:we,:st,:co,:zi,:dc)";
      $usql = "update ht.tblchurch set churchname = :cn,GoogleMapPlaceID = :pl,domain = :do,address = :ad, city = :ci,phone = :ph,website = :we,
         state = :st, country = :co, zipcode = :zi where ChurchID = :id";
       if($church->id > 0){
         $stmt = $conn->prepare($usql);
         $stmt->bindParam(':id',$church->id,PDO::PARAM_INT);
         ChromePhp::warn("Updating ".$church->name." with ID: ".$church->id);
       } else {
       	  $stmt = $conn->prepare($isql);
       	  date_default_timezone_set('America/Chicago');
       	  $today = date('Y-m-d H:i:s');
       	  $stmt->bindParam(':dc',$today,PDO::PARAM_STR); 
       }
        $stmt->bindParam(':cn',$church->name,PDO::PARAM_STR);
        $stmt->bindParam(':pl',$church->placeid,PDO::PARAM_STR);
        $stmt->bindParam(':do',$church->domain,PDO::PARAM_STR);
        $stmt->bindParam(':ad',$church->street,PDO::PARAM_STR);
        $stmt->bindParam(':ci',$church->city,PDO::PARAM_STR);
        $stmt->bindParam(':ph',$church->phone,PDO::PARAM_STR);
        $stmt->bindParam(':we',$church->website,PDO::PARAM_STR);
        $stmt->bindParam(':st',$church->province,PDO::PARAM_STR);
        $stmt->bindParam(':co',$church->country,PDO::PARAM_STR);
        $stmt->bindParam(':zi',$church->postalcode,PDO::PARAM_STR);
        if($stmt->execute()) {
          $church->status='ok';
          ChromePhp::warn("Action is successful!"); 	
          $stmt->closeCursor();
          ChromePhp::warn('Cursor Closed');
          if($church->id == 0 ) {
           ChromePhp::warn('Fetching New ID');
           $r = $conn->query("SELECT AUTO_INCREMENT AS cid FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'ht' AND TABLE_NAME = 'tblchurch'")->fetch(PDO::FETCH_ASSOC);
           $church->id = $r['cid']; 
           ChromePhp::warn('Action is successful: New ID is:'.$church->id);
         }
        }
  	     echo json_encode($church);
       } else {
  	              ChromePhp::warn('tblchurch record has not been saved');
  	              $church->status="no";
  	             echo json_encode($church);
       }
 } catch (PDOException $e){
     ChromePhp::warn('Oops!!'.$e->getMessage);
     $status['status'] = "Data failure";
     $status['action'] = "Catch Error";
     $status['error'] = $e->getMessage;
     $status['sql'] = $query;
     echo json_encode($status);	
  } 
?>
