<?php
if (count($argv) == 0) exit;
$userid = $argv[1];
if(empty($userid)) {
	print_r("USerid is undefined \n");
	die();
}

$username = 'root';
$password = 'DanaDenyse32';
$dbname = 'ht';
$host = '127.0.0.1';
try {
     $conn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
     if($conn) {
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      print_r("Connection is Good \n");
      print_r("Managing User Rating Index Table \n");
      print_r("UserID: ".$userid." \n");
/*
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
      */
 // check for duplicate
       $stmt = $conn->prepare("select * from ht.tblUserRating where UserID = ".$userid." order by EventID,RatingID");
       $stmt->execute();
       $rRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
       if($rRecords) {
       	$eventbreak = $rRecords[0]['EventID'];
       	print_r("Event ID: " .$eventbreak." \n");
       	$rStack = "|";
       	$rCount = 0;
       	$Score = 0;
       	//Look for Comments
       print_r("Records found! \n");   	
       	foreach($rRecords as $row => $question) {
       		$rStack = $rStack.$question['RatingID']."|";
       		if($rCount == 0) {
       		 $eventid = $question['EventID'];
       		 $churchid = $question['ChurchID'];
       		 $userid = $question['UserID'];	
       		 $eventdate = $question['EventDate'];
       		}
       		$Score+= $question['Score']; 
       		if($eventbreak != $question['EventID']) {
       			print_r("New tblIndex Record to be created \n");
       			print_r("***************** \n");
            	print_r("Rating Stack: ".$rStack." \n");
            	print_r("Score: ".$Score. "\n");
        	//get Church Name
      	       $sql = "select churchname from ht.tblchurch where ChurchID = ".$churchid;
               $res = $conn->query($sql);
       	       if($res) {
       	 	    $val=$res->fetch(PDO::FETCH_ASSOC);
       		    $churchname = $val['churchname'];
       	       }
       	      print_r("Church Name is: ".$churchname." \n");
      	//Get EventName
              $sql = "select EventName from ht.tblEvents where EventID = ".$eventid;
    	      $res = $conn->query($sql);
       	      if($res) {
      	 	    $val=$res->fetch(PDO::FETCH_ASSOC);
       		    $eventname = $val['EventName'];
       	       }
         	    print_r("Event Name is: ".$eventname." \n");
       	     // look for a comment
       	     $sql = "select CommentID from ht.tblUserComments where UserID = ".$userid." and ChurchID = ".$churchid." and EventID = ".$eventid." LIMIT 1";
     	        $res = $conn->query($sql);
       	     if($res) {
       	 	$val=$res->fetch(PDO::FETCH_ASSOC);
      		$commentid = $val['CommentID'];
          	}
           print_r("Comment ID is: ".$commentid." \n");
          $score = $Score / $rCount;
                print_r("Average Score : ".$score. " \n");	
              $sql = "select count(*) from ht.tblIndex where ChurchID = ".$churchid."
                and EventID = ".$eventid." and UserID = ".$userid." and EventDate = '".$eventdate."'";
                print_r($sql." \n");
              $chk = $conn->query($sql);
              if(!$chk) {  
               $sql = 'insert into ht.tblIndex(ChurchID,churchname,CommentID,EventDate,EventID,EventName,RatingStack,UserID,Score)
                values
                (:ci,:cn,:oi,:ed,:ei,:en,:rs,:ui,:sc)';
               $stmt = $conn->prepare($sql);
               $stmt->bindParam(':ci',$churchid,PDO::PARAM_INT);
               $stmt->bindParam(':cn',$churchname,PDO::PARAM_STR,50);
               $stmt->bindParam(':oi',$commentid,PDO::PARAM_INT);
               $stmt->bindParam(':ed',$eventdate,PDO::PARAM_STR,50);
               $stmt->bindParam(':ei',$eventid,PDO::PARAM_INT);
               $stmt->bindParam(':en',$eventname,PDO::PARAM_STR,50);
               $stmt->bindParam(':rs',$rStack,PDO::PARAM_STR,50);
               $stmt->bindParam(':ui',$userid,PDO::PARAM_INT);
               $stmt->bindParam(':sc',$score,PDO::PARAM_INT);
                if($stmt->execute()) {
  	              print_r("tblIndex Summary record added \n");
                } else {
  	              print_r("tblIndex Summary record has not been saved \n");
                }
              } else {
              print_r("Church".$churchname." Event: ".$eventname." on ".$eventdate." already on file......omitting \n");	
              }
      		  $rCount = 0;
       		  $Score = 0;
       		 $rStack = "|".$question['RatingID']."|";
         	 $eventid = $question['EventID'];
       		 $churchid = $question['ChurchID'];
       		 $userid = $question['UserID'];	
       		 $eventdate = $question['EventDate'];
       		 $eventbreak = $eventid;
         		}
       	  $rCount++ ;
       	}
       	
  /*
        print_r("Last Break in Event ID \n");
        print_r("********************** \n"); 
        print_r("Rating Stack: ".$rStack." \n");
       	print_r("Score: ".$Score. "\n");
      	print_r("Church Name is: ".$churchname." \n");
        print_r("Event Name is: ".$eventname." \n");
        print_r("Comment ID is: ".$commentid." \n");
   */
       } else {
       	print_r("No Ratings found for UserID: ".$userid." \n");
       }
    //if $records
  } //(if(conn))
} catch (PDOException $e){
     print_r("Oops!!:** ".$e->getMessage()." \n");
     print_r($sql." \n");
     }
 // try
?>