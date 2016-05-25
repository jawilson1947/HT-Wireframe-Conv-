<?php
 //enroll.php
 include 'ChromePhp.php';
ChromePhp::warn('saveuserrating.php');
$username = 'root';
$password = 'DanaDenyse32';
$dbname = 'ht';
$host = '127.0.0.1';
$UserRating = json_decode($_POST["info"]);
$status = array('status' => '',
                'action' => '',
                'error' => '',
                'sql' => '');
$good = false; 
$indexRequired = true;               
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
    	if($conn) {
    	   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        	ChromePhp::warn('Connection is Good');
 	        	$isql = "insert into ht.tblUserRating(CategoryID,ChurchID,EventID,UserID,EventDate,Score,daterated,Category,rank)
                             values (:ai,:ci,:ei,:ui,:ed,:sc,:dr,:ca,:ra)";
                    $usql = "update ht.tblUserRating set CategoryID = :ai, ChurchID = :ci, EventID = :ei, UserID = :ui, EventDate = :ed, Score = :sc, daterated = :dr, Category = :ca, rank = :ra 
                             where RatingID = :ri";           
                       try {      
                          foreach($UserRating as $rating):
                            if($rating->ratingid == 0) {
                           $stmt = $conn->prepare($isql);
                           ChromePhp::warn($isql);
                         } else {
                         	$stmt = $conn->prepare($usql);
                       	    ChromePhp::warn($usql);
                      	    $stmt->bindParam(':ri',$rating->ratingid,PDO::PARAM_INT);
                      	    $indexRequired = false;
                          }
                         $stmt->bindParam(':ai',$rating->categoryid,PDO::PARAM_INT);
                         ChromePhp::warn('CategoryID: '.$rating->categoryid);
                         $stmt->bindParam(':ci',$rating->churchid,PDO::PARAM_INT);
                         ChromePhp::warn('ChurchID: '.$rating->churchid);
                       	 $stmt->bindParam(':ei',$rating->eventid,PDO::PARAM_INT);
                         ChromePhp::warn('EventID: '.$rating->eventid);
                         $stmt->bindParam(':ui',$rating->userid,PDO::PARAM_INT);
                         ChromePhp::warn('UserID: '.$rating->userid);                         
                         $stmt->bindParam('ed',$rating->eventdate, PDO::PARAM_STR);
                         ChromePhp::warn('Event Date in Core: '.$rating->eventdate);
                       	 $stmt->bindParam(':sc',$rating->score,PDO::PARAM_INT);
                         ChromePhp::warn('Score: '.$rating->score);                       	 
               	         $stmt->bindParam(':dr',$rating->daterated, PDO::PARAM_STR);
                         ChromePhp::warn('Date Rated in Core: '.$rating->daterated);
                       	 $stmt->bindParam(':ca',$rating->category,PDO::PARAM_STR);
                         ChromePhp::warn('Category: '.$rating->category);
                         $stmt->bindParam(':ra',$rating->rank, PDO::PARAM_INT);
                      	 
                      	 if($stmt->execute()) {
                      	 ChromePhp::warn('Query Executed!');
                       	 $stmt->closeCursor();
                       	 ChromePhp::warn('Cursor Closed');
                         ChromePhp::warn('Fetching New ID');
                         $r = $conn->query("SELECT AUTO_INCREMENT AS rid FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'ht' AND TABLE_NAME = 'tblUserRating'")->fetch(PDO::FETCH_ASSOC);
                         $rating->ratingid = $r['rid']; 
                     	   ChromePhp::warn('Action is successful: Rating ID is:'.$rating->ratingid);
                     	   $good = true;
                      	 } else {
                      	 	$good = false;
                      	    ChromePhp::warn('Action not successful '.$rating->category);
                      	 }
                   endforeach;
                   ChromePhp::warn('Save User Rating is All Good!');
                 } catch (PDOException $e){
                  	
                  	ChromePhp::warn($e->getMessage);
                    $status['status'] = "Data failure";
                    $status['action'] = "Catch Error";
                    $status['error'] = $e->getMessage;
                    $status['sql'] = $isql;
                    echo json_encode($status);	
                  }
                  if($good == true) {
                   echo json_encode($UserRating);
                   updateChurch($conn,$UserRating);
                   updateMember($conn,$UserRating);
                   updateEvents($conn,$UserRating);
                   ChromePhp::warn("All Data Transactions completed");    
                   } else {
                   	ChromePhp::warn("Entire Transaction has failed");
                   	}                         }
      } catch (PDOException $pe) {
        $status['status'] = "failure";
        $status['action'] = "Catch Error";
        $status['error'] = $pe->getMessage;
        $status['sql'] = $sql;
        ChromePhp::warn($pe-getMessage);
        echo json_encode($status);	
      } 

 function updateChurch($cn,$ur) {
 ChromePhp::warn("Updating Church");
 $status = array('status' => '',
                'action' => '',
                'error' => '',
                'sql' => '');
 try {
     $sql = "update ht.tblchurch set EventRatingCount = EventRatingCount + 1 where ChurchID = :ci";
     ChromePhp::warn($sql);
     $churchid = $ur[0]->churchid;
     $stmt = $cn->prepare($sql);
     $stmt->bindParam(':ci',$churchid,PDO::PARAM_INT);
     if($stmt->execute()) {
     	ChromePhp::warn("Event Rating Count updated in ChurchID: ".$churchid);
     } else {
        ChromePhp::warn("Unable to update Event Rating Count in: ".$churchid);	
     }
  	   
  } catch (PDOException $pe) {
        $status['status'] = "Update Church with User Ratings failure";
        $status['action'] = "Catch Error";
        $status['error'] = $pe->getMessage;
        $status['sql'] = $sql;
        ChromePhp::warn($pe-getMessage);
        echo json_encode($status);	
   }
 }
 function updateMember($cn,$ur) {
 ChromePhp::warn("Updating Member");
$status = array('status' => '',
                'action' => '',
                'error' => '',
                'sql' => '');
 try {
       $sql = "update ht.tblMember set RatingCount = RatingCount + 1 where UserID = :ui";
     ChromePhp::warn($sql);
     $userid = $ur[0]->userid;
     $stmt = $cn->prepare($sql);
     $stmt->bindParam(':ui',$userid,PDO::PARAM_INT);
     if($stmt->execute()) {
     	ChromePhp::warn("Event Rating Count updated in UserID: ".$userid);
     } else {
        ChromePhp::warn("Unable to update Event Rating Count in: ".$userid);	
     }
  	   
 	
  } catch (PDOException $pe) {
        $status['status'] = "Update Member with User Ratings failure";
        $status['action'] = "Catch Error";
        $status['error'] = $pe->getMessage;
        $status['sql'] = $sql;
        ChromePhp::warn($pe-getMessage);
        echo json_encode($status);	
   }

 }
 function updateEvents($cn,$ur) {
 ChromePhp::warn("Updating Events");
$status = array('status' => '',
                'action' => '',
                'error' => '',
                'sql' => '');
 try {
      $sql = "update ht.tblEvents set RatingCount = RatingCount + 1 where EventID = :ei";
     ChromePhp::warn($sql);
     $eventid = $ur[0]->eventid;
     $stmt = $cn->prepare($sql);
     $stmt->bindParam(':ei',$eventid,PDO::PARAM_INT);
     if($stmt->execute()) {
     	ChromePhp::warn("Event Rating Count updated in EventID: ".$eventid);
     } else {
        ChromePhp::warn("Unable to update Event Rating Count in: ".$eventid);	
     }
  	   
  	
  } catch (PDOException $pe) {
        $status['status'] = "Update Events with User Ratings failure";
        $status['action'] = "Catch Error";
        $status['error'] = $pe->getMessage;
        $status['sql'] = $sql;
        ChromePhp::warn($pe-getMessage);
        echo json_encode($status);	
   }
 	
 }
?>