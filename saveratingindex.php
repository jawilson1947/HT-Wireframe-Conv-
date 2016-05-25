<?php
 //enroll.php
 include 'ChromePhp.php';
ChromePhp::warn('saveratingindex.php');
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
      ChromePhp::warn('Managing User Rating Index Table');

      $ratingStack = $rs->ratingstack;
      $churchid = $rs->churchid;
      $eventid = $rs->eventid;
      $userid = $rs->userid;
      $eventdate = $rs->eventdate;
      $eventname = $rs->eventname;
      $churchname = $rs->churchname;
      $commentid = $rs->commentid;
      $score = $rs->score;
      $id = $rs->id;

  ChromePhp::warn('RatingStack is: '.$ratingStack);	      
 ChromePhp::warn('ChurchID is: '.$churchid); 
 ChromePhp::warn('EventID is: '.$eventid);
 ChromePhp::warn('UserID is: '.$userid);
 ChromePhp::warn('Eventdate is: '.$eventdate);
   ChromePhp::warn('Eventname is: '.$eventname);
   ChromePhp::warn('Churchname is: '.$churchname);
   ChromePhp::warn('Commentid is: '.$commentid);
   ChromePhp::warn('Score is: '.$score);
   
  // check for duplicate
   $dsql = "select IFNULL(id,0) as id from ht.tblIndex where EventID = :ei and UserID = :ui and EventDate = :ed";
      $chkdup = $conn->prepare($dsql);
      $chkdup->bindParam(':ei',$eventid,PDO::PARAM_INT);
      $chkdup->bindParam(':ui',$userid,PDO::PARAM_INT);
      $chkdup->bindParam(':ed',$eventdate,PDO::PARAM_STR);
      $oldIdx = $chkdup->execute();
      if($oldidx) {
      	$id = $oldidx['id'];
        ChromePhp::warn('Previous Record found, id: '.$id);	
       } 
   
   if($id > 0 ) {
       $usql = 'update ht.tblIndex set Score = :sc , EventDate = :ed where id = :id';
       ChromePhp::warn('Updating tblIndex - id is: '.$id);
       $sql = $conn->prepare($usql);
       $sql->bindParam(':sc',$score,PDO::PARAM_INT);
       $sql->bindParam(':ed',$eventdate,PDO::PARAM_INT);
       $sql->bindParam(':id',$id,PDO::PARAM_INT);
       if($sql->execute()) {
    	 ChromePhp::warn('tblIndex Summary record updated');
      	  $rs->status="ok";
      	  $rs->id = $id;
      	  echo json_encode($rs); 
       } else {
       	 ChromePhp::warn('tblIndex Summary record NOT updated');
       	 $rs->status = 'no';
       	 echo json_encode($rs);
       }
   } else { 
       ChromePhp::warn('Ready to insert the index record for this session');
       $isql = 'insert into ht.tblIndex(ChurchID,churchname,CommentID,EventDate,EventID,EventName,RatingStack,UserID,Score)
                values
                (:ci,:cn,:oi,:ed,:ei,:en,:rs,:ui,:sc)';
               $sql = $conn->prepare($isql);
               $sql->bindParam(':ci',$churchid,PDO::PARAM_INT);
               $sql->bindParam(':cn',$churchname,PDO::PARAM_STR,50);
               $sql->bindParam(':oi',$commentid,PDO::PARAM_INT);
               $sql->bindParam(':ed',$eventdate,PDO::PARAM_STR,50);
               $sql->bindParam(':ei',$eventid,PDO::PARAM_INT);
               $sql->bindParam(':en',$eventname,PDO::PARAM_STR,50);
               $sql->bindParam(':rs',$ratingStack,PDO::PARAM_STR,50);
               $sql->bindParam(':ui',$userid,PDO::PARAM_INT);
               $sql->bindParam(':sc',$score,PDO::PARAM_INT);
                if($sql->execute()) {
  	              ChromePhp::warn('tblIndex Summary record added');
                  $sql->closeCursor();
                  ChromePhp::warn('Cursor Closed');
                  ChromePhp::warn('Fetching New ID');
                  $r = $conn->query("SELECT AUTO_INCREMENT AS cid FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'ht' AND TABLE_NAME = 'tblIndex'")->fetch(PDO::FETCH_ASSOC);
                  $rs->id = $r['cid']; 
                  ChromePhp::warn('Action is successful: Comment ID is:'.$rs->id);
                  $rs->status='ok';
  	              echo json_encode($rs);
                } else {
  	              ChromePhp::warn('tblIndex Summary record has not been saved');
  	              $rs->status="no";
  	             echo json_encode($rs);
              }//$sql execute
        }//$id > 0
      } //(if(conn))
} catch (PDOException $e){
     ChromePhp::warn('Oops!!'.$e->getMessage);
     $status['status'] = "Data failure";
     $status['action'] = "Catch Error";
     $status['error'] = $e->getMessage;
     $status['sql'] = $query;
     echo json_encode($status);	
     }
 // try
?>