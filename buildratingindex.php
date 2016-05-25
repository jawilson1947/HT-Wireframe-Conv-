<?php
//'buildratingindex.php';
// !!***NOTE***!!! Delete all records in the tblIndex table before running
// This script rebuilds the tblIndex table fro mhe HT data stack
$username = 'root';
$password = 'DanaDenyse32';
$dbname = 'ht';
$host = '127.0.0.1';
$status = array('status' => '',
                'action' => '',
                'error' => '',
                'sql' => '');
              
try {
     $conn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
     if($conn) {
       $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       print_r("Connection is Good \n");
       print_r("Rebuilding the User Rating Index Table \n");
       $run = $conn->prepare('select * from ht.tblUserRating order by ChurchID, UserID, EventID, RatingID', array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $top = $conn->prepare('select * from ht.tblUserRating order by ChurchID, UserID, EventID, RatingID LIMIT 1');
       $commentsql = "select CommentID, RatingID from ht.tblUserComments where ChurchID = :ci and UserID = :ui and EventID = ':ei and RatingID in
       (select RatingID from ht.tblUserRating where ChurchID = :cid and EventID = :eid and UserID = :uid)";
       $churchsql = "select churchname from ht.tblchurch where ChurchID = :ci";
       $eventsql = "select EventName from ht.tblEvents where EventID = :ei";
       $top->execute();
       $qt = $top->fetch(PDO::FETCH_ASSOC);
       if(!$qt) {
       	print_r("Unable to collect the starter Row \n");
       	die();
       }
       $breakpoint = strval($qt['ChurchID'])."|".strval($qt['UserID'])."|".strval($qt['EventID']);
       $firstrid = $qt['RatingID'];
       print_r("First Rating ID is; ".$firstrid." \n");
       print_r("Initial Break Point is: ".$breakpoint." \n");
       $ratingStack = "|";
       $run->execute(); 
       while($row = $run->fetch(PDO::FETCH_ASSOC))  {
      	       $formula = strval($row['ChurchID'])."|".strval($row['UserID'])."|".strval($row['EventID']);
      	       if($formula == $breakpoint) {
      	       	$eventdate = $row['EventDate'];
      	       	$cID = $row['ChurchID'];
      	       	$eID = $row['EventID'];
      	       	$uID = $row['UserID'];
      	        $ratingStack += $ratingStack.strval($row['RatingID']).'|';
      	        print_r($row['RatingID']." \n");
      	       } else {
      	       	print_r("BreakPoint on ".$breakpoint." \n");
  // get church name
                 $chh = $conn->prepare($churchsql);
                 $chh->bindParam(':ci',$cID,PDO::PARAM_INT); 
                 if($chh->execute()) {
                 $result= $chh->fetch(PDO::FETCH_ASSOC);
                 $churchname = $result['churchname'];
                 $chh->closeCursor();  
                 print_r("Church Name retrieved \n");
                 } else {
                  print_r("Church Name NOT retrieved \n");
                  die();	
                 }
// get Event Name
                 $evh = $conn->prepare($eventsql);
                 $evh->bindParam(':ei',$eID,PDO::PARAM_INT); 
                 if($evh->execute())  {
                 $result= $evh->fetch(PDO::FETCH_ASSOC);
                 $eventname = $result['EventName'];
                 $evh->closeCursor(); 
                 print_r("Event Name retrieved \n"); 
                 } else {
                 	print_r("Event Name NOT Retrieved \n");
                 	die();
                 }
                 
/*
                 $coh = $conn->prepare($commentsql);
                 $coh->bindParam(':ci',$cID,PDO::PARAM_INT);
                 $coh->bindParam(':ui',$uID,PDO::PARAM_INT);
                 $coh->bindParam(':ei',$eID,PDO::PARAM_INT); 
                 $coh->bindParam(':cid',$cID,PDO::PARAM_INT);
                 $coh->bindParam(':eid',$eID,PDO::PARAM_INT);
                 $coh->bindParam(':uid',$uID,PDO::PARAM_INT);
                 print_r("Comment Parameters Bound \n");
                 if($coh->execute()) {
                 	print_r("Comment Query Executed \n");
                   $result = $coh->fetch(PDO::FETCH_ASSOC);
                   $commentID = $result['CommentID'];
                   $ratingID = $result['RatingID'];
                    print_r("Comment Retrieved \n");
                   if($ratingID == 0) {
                   	 print_r("Rating ID missing for CommentID".$commentID." \n");
                   	 $fxh = $conn->prepare('update ht.tblUserComments set RatingID = :ri where CommentID = :oi');
                   	 $fxh->bindParam(':ri',$firstrid,PDO::PARAM_INT);
                   	 $fxh->bindParam(':oi',$commentID,PDO::PARAM_INT);
                  	 $fxh->execute();
                   	 $fxh->closeCursor();
                    } // ratingID == 0
                   } else {
                  	print_r("No comment found for Breakpoint: ".$breakpoint." \n");
                 	$commentID = 0;
                  } // No Comments for this Rating Index record
                  $coh->closeCursor();
      */          $commentID = 0;  
                  $firstrid = $row['RatingID']; 
     	       	  $breakpoint = $formula;
     	       	  $ratingStack = "|";
          print_r("RatingStack is: ".$ratingStack." \n");	      
          print_r("ChurchID is: ".$cID." \n"); 
          print_r("EventID is: ".$eID." \n");
          print_r("UserID is: ".$uID." \n");
          print_r("Eventdate is: ".$eventdate." \n");
          print_r("Eventname is: ".$eventname." \n");
          print_r("Churchname is: ".$churchname." \n");
          print_r("Commentid is: ".$commentID." \n");
          print_r("*********************************** \n");
          print_r(" \n");
/*
          $insert = 'insert into ht.tblIndex(ChurchID,churchname,CommentID,EventDate,EventID,EventName,RatingStack,UserID)
                values
                (:ci,:cn,:oi,:ed,:ei,:en,:rs,:ui)';
                $ixh = $conn->prepare($insert);
                $ixh->bindParam(':ci',$cID,PDO::PARAM_INT);
                $ixh->bindParam(':cn',$churchname,PDO::PARAM_STR,50);
                $ixh->bindParam(':oi',$commentID,PDO::PARAM_INT);
                $ixh->bindParam(':ed',$eventdate,PDO::PARAM_STR,50);
                $ixh->bindParam(':ei',$eID,PDO::PARAM_INT);
                $ixh->bindParam(':en',$eventname,PDO::PARAM_STR,50);
                $ixh->bindParam(':rs',$ratingStack,PDO::PARAM_STR,50);
                $ixh->bindParam(':ui',$uID,PDO::PARAM_INT);
                if($ixh->execute()) {
  	              print_r("tblIndex Summary record saved \n");
                } else {
                  print_r("tblIndex Summary record not saved \n");	                
                } //execute good?
              */  
      	       }//breakpoint?
             }// while row is active
        }//(if(conn))
} catch (PDOException $e){
     print_r("Oops!!".$e->getMessage." \n");
     die();	
     }
 // try
?>
