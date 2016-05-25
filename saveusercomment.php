<?php
 //saveusercomment.php
 include 'ChromePhp.php';
ChromePhp::warn('saveusercomment.php');
$username = 'root';
$password = 'DanaDenyse32';
$dbname = 'ht';
$host = '127.0.0.1';
$comment = json_decode($_POST["info"]);
$status = array('status' => '',
                'action' => '',
                'error' => '',
                'sql' => '');
$good = false;                
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
    	if($conn) {
    	  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        	ChromePhp::warn('Connection is Good');
 	        	$isql = "insert into ht.tblUserComments(ChurchID,EventID,UserID,Comment,CommentDate,RatingID,EventDate)
                             values (:ci,:ei,:ui,:co,:cd,:ri,:ed)";
                $usql = "update ht.tblUserComments set ChurchID = :ci, EventID = :ei, UserID = :ui, Comment = :co, CommentDate = :cd, RatingID = :ri, EventDate = :ed where CommentID = :id)";           

                       try {      

                         if($comment->id == 0) {
                           $stmt = $conn->prepare($isql);
                           ChromePhp::warn($isql);
                         } else {
                         	$stmt = $conn->prepare($usql);
                       	    ChromePhp::warn($usql);
                      	    $stmt->bindParam(':id',$comment->id,PDO::PARAM_INT);
                      	    ChromePhp::warn('Comment ID: '.$comment->id);
                          }
                         $stmt->bindParam(':ci',$comment->churchid,PDO::PARAM_INT);
                         ChromePhp::warn('ChurchID: '.$comment->churchid);
                       	 $stmt->bindParam(':ei',$comment->eventid,PDO::PARAM_INT);
                         ChromePhp::warn('EventID: '.$comment->eventid);
                         $stmt->bindParam(':ui',$comment->userid,PDO::PARAM_INT);
                         ChromePhp::warn('UserID: '.$comment->userid);                         
                         $stmt->bindParam('co',$comment->comment, PDO::PARAM_STR,300);
                         ChromePhp::warn('Comment: '.$comment->comment);
                       	 $stmt->bindParam(':cd',$comment->commentdate,PDO::PARAM_STR,50);
                         ChromePhp::warn('Comment Date: '.$comment->commentdate);                       	 
               	         $stmt->bindParam(':ri',$comment->ratingid, PDO::PARAM_INT);
                         ChromePhp::warn('Rating ID: '.$comment->ratingid);
                         $stmt->bindParam(':ed',$comment->eventdate, PDO::PARAM_STR,50);
                      	 ChromePhp::warn('Event Date: '.$eventdate);
                      	 if($stmt->execute()) {
                      	   ChromePhp::warn('Query Executed!');
                       	   $stmt->closeCursor();
                      	    ChromePhp::warn('Cursor Closed');
                        	if($comment->id == 0) {
                               ChromePhp::warn('Fetching New ID');
                               $r = $conn->query("SELECT AUTO_INCREMENT AS cid FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'ht' AND TABLE_NAME = 'tblUserComments'")->fetch(PDO::FETCH_ASSOC);
                               $comment->id = $r['cid']; 
                       	       }
                     	      ChromePhp::warn('Action is successful: Comment ID is:'.$comment->id);
                     	      $comment->status="ok";
                     	       echo json_encode($comment);
                      	 } else {
                      	    ChromePhp::warn('Action not successful '.$comment->comment);
                      	    $comment->status="sorry";
                      	    echo json_encode($comment);
                      	 }
                      	 
                  } catch (PDOException $e){
                  	
                  	ChromePhp::warn($e->getMessage);
                    $status['status'] = "Data failure";
                    $status['action'] = "Catch Error";
                    $status['error'] = $e->getMessage;
                    $status['sql'] = $isql;
                    echo json_encode($status);	
                  }
            }
       } catch (PDOException $pe) {
        $status['status'] = "failure";
        $status['action'] = "Catch Error";
        $status['error'] = $pe->getMessage;
        $status['sql'] = $sql;
        ChromePhp::warn($pe-getMessage);
        echo json_encode($status);	
      } 
?>
