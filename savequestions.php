<?php
 //enroll.php
 include 'ChromePhp.php';
ChromePhp::warn('savequestions.php');
$username = 'root';
$password = 'DanaDenyse32';
$dbname = 'ht';
$host = '127.0.0.1';
$Question= json_decode($_POST["info"]);
$stmt = null;
$status = array('status' => '',
                'action' => '',
                'error' => '',
                'sql' => '');
  // saves the rating questions for a given church event called from event.html   
  // Question object passed          
try {
      $conn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
      if($conn) {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        ChromePhp::warn('Connection is Good');
        if($Question->CategoryID == 0) {
          $sql = "insert into ht.tblEventRatings(ChurchID,EventID,Category,datecreated,DontTallyIfZero,rank,VisitorOnly,MemberOnly) values (:ci,:ei,:cg,:dc,:dz,:ra,:vo,:mo)"; 
          $stmt = $conn->prepare($sql);
          ChromePhp::warn($sql);
          $stmt->bindParam(':ci',$Question->ChurchID,PDO::PARAM_INT);
          ChromePhp::warn('ChurchID: '.$Question->ChurchID);
          $stmt->bindParam(':ei',$Question->EventID,PDO::PARAM_INT);
          ChromePhp::warn('EventID: '.$Question->EventID);
       	  date_default_timezone_set('America/Chicago');
       	  $today = date('Y-m-d H:i:s');
          ChromePhp::warn('Current Date: '.$today);
          $stmt->bindParam(':dc',$today, PDO::PARAM_STR);
        } else {
                $sql = "update ht.tblEventRatings set Category = :cg, rank = :ra, DontTallyIfZero = :dz, VisitorOnly = :vo, MemberOnly = :mo where CategoryID = :gi";           
                ChromePhp::warn($sql);
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':gi',$Question->CategoryID,PDO::PARAM_INT);
                 ChromePhp::warn('CategoryID: '.$Question->CategoryID);
        }
        ChromePhp::warn('Category: '.$Question->Category);
        $stmt->bindParam(':cg',$Question->Category, PDO::PARAM_STR);
                         
        ChromePhp::warn('DontTallyIfZero '.$Question->DontTallyIfZero);                         
        $stmt->bindParam(':dz',$Question->DontTallyIfZero,PDO::PARAM_BOOL);
                         
        ChromePhp::warn('Rank: '.$Question->rank);                       	 
        $stmt->bindParam(':ra',$Question->rank, PDO::PARAM_INT);
                       
        ChromePhp::warn('VisitorOnly : '.$Question->VisitorOnly);
        $stmt->bindParam(':vo',$Question->VisitorOnly, PDO::PARAM_BOOL);
                	     
        ChromePhp::warn('MemberOnly : '.$Question->MemberOnly);
        $stmt->bindParam(':mo',$Question->MemberOnly,PDO::PARAM_BOOL);
                      	 
        if($stmt->execute()) {
          ChromePhp::warn('Statement Executed!');
          $Question->status="ok";
          $Question->sql = $sql;
          if($Question->CategoryID == 0) {  
            $stmt->closeCursor();       
            ChromePhp::warn('Fetching New Category ID');
            $r = $conn->query("SELECT AUTO_INCREMENT AS eid FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'ht' AND TABLE_NAME = 'tblEventRatings'")->fetch(PDO::FETCH_ASSOC);
            $Question->CategoryID = $r['eid']; 
            ChromePhp::warn('Action is successful: New Category ID: '.$Question->CategoryID);
           } 
           echo json_encode($Question);
        } else {
         	   ChromePhp::warn('Statement failed!');
         	   $Question->status="no";
         	   $Question->sql = $sql;
         	   $question->error = "Statement not implemented";	
           echo json_encode($Question);	   
        }
      } else {
      	ChromePhp::warn('!!!*** Unable to consumate Database Connection ***!!!');
          	ChromePhp::warn($e->getMessage());
            $status['status'] = "Data Connection failure";
            $status['action'] = "Acquiring database onnection";
            $status['error'] = $e->getMessage();
            $status['sql'] = $sql;
            echo json_encode($status);	
      } 
    } catch (PDOException $e){
          	ChromePhp::warn($e->getMessage());
            $status['status'] = "Data failure";
            $status['action'] = "Catch Error";
            $status['error'] = $e->getMessage();
            $status['sql'] = $sql;
            echo json_encode($status);	
    }
?>