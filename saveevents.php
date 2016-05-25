<?php
 //enroll.php
 include 'ChromePhp.php';
ChromePhp::warn('saveevents.php');
$username = 'root';
$password = 'DanaDenyse32';
$dbname = 'ht';
$host = '127.0.0.1';
$ChurchEvent= json_decode($_POST["info"]);
$stmt = null;
$boolean = false;
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
         if($ChurchEvent->EventID == 0) {
          $sql = "insert into ht.tblEvents(ChurchID,EventName,Recurring,rank,startdate,enddate,`interval`,Online,Enabled,hyperlink,`default`) values (:ci,:en,:re,:ra,:sd,:ed,:in,:ol,:eb,:hy,:de)"; 
          $stmt = $conn->prepare($sql);
          ChromePhp::warn($sql);
          $stmt->bindParam(':ci',$ChurchEvent->ChurchID,PDO::PARAM_INT);
          ChromePhp::warn('ChurchID: '.$ChurchEvent->ChurchID);
          } else {
                  $boolean = !empty($ChurchEvent->defaultevent); 
               	 if($ChurchEvent->mode == 'delet') {
         	      ChromePhp::warn($ChurchEvent->EventName.' is to be deleted');
         	      $sql = 'select IFNULL(count(*),0) as ecount from ht.tblUserRating where EventID = '.$ChurchEvent->EventID;
         	      ChromePhp::warn($sql);
         	      $stmt = $conn->prepare($sql);
         	      $stmt->execute();
         	      $result = $stmt->fetch(PDO::FETCH_ASSOC);	
          	      ChromePhp::warn('There are '.$result['ecount'].' entries found in UserRating Table');
        	      if($result['ecount'] > 0) {
         	        $ChurchEvent->status = 'children';
         	        $ChurchEvent->sql = $sql;
         	        $churchEvent->error = $result['ecount'].' user ratings for '.$ChurchEvent->EventName;
         	        ChromePhp::warn($ChurchEvent->error);
         	        echo json_encode($ChurchEvent);
         	        die();	
         	       } else {
         	        $stmt->closeCursor();
         	        $sql = 'delete from ht.tblEventRatings where EventID = '.$ChurchEvent->EventID;
         	        ChromePhp::warn($sql);
         	        $zap = $conn->prepare($sql);
          	        if($zap->execute()) {
         	       	   ChromePhp::warn($ChurchEvent->EventName."'s  event ratings successfully deleted");
         	       	   $zap->closeCursor();
          	        } else {
         	       	  ChromePhp::warn('Unable to delete Event Rating Directives for '.$ChurchEvent->EventName);
         	       	  $ChurchEvent->status = 'no';
        	       	  $ChurchEvent->error = 'Cannot delete from tblEventRatings : '.$ChurchEvent->EventName;
         	       	  $ChurchEvent->sql = $sql;
          	       	  echo json_encode($ChurchEvent);
         	       	  die();
         	         } // tblEventRatings deleted
         	         $sql = 'delete from ht.tblEvents where EventID = '.$ChurchEvent->EventID;
         	         ChromePhp::warn($sql);
         	         $stmt = $conn->prepare($sql);
         	         if($stmt->execute()) {
         	           ChromePhp::warn($ChurchEvent->EventName.' has been removed');
         	       	   $ChurchEvent->status = 'ok';
         	       	   $ChurchEvent->action = 'deletion';
         	       	   $ChurchEvent->sql = $sql;
         	       	   echo json_encode($ChurchEvent);
         	       	   die();
         	       	  } else {
         	       	  	ChromePhp::warn('Unable to delete record from ht.tblEvents table: ' .$ChurchEvent.EventName);
         	       	  	$ChurchEvent->status = 'no';
         	       	  	$ChurchEvent->error = 'Cannot delete from tblEvents: '.$ChurchEvent->EventName;
         	       	  	$ChurchEvent->sql = $sql;
         	       	  	echo json_encode($ChurchEvent);
         	       	  	die();
         	        } // event deleted
           	    } //$result['ecount']>0
                } //mode is delete
               
                $sql = "update ht.tblEvents set EventName = :en, Recurring = :re, rank = :ra, startdate = :sd, enddate = :ed, 
                `interval` = :in, Online = :ol, Enabled = :eb, hyperlink = :hy , `default` = :de where EventID = :ei";           
                ChromePhp::warn($sql);
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':ei',$ChurchEvent->EventID,PDO::PARAM_INT);
                ChromePhp::warn('EventID: '.$ChurchEvent->EventID);
          
          }
                ChromePhp::warn('EventName: '.$ChurchEvent->EventName);
                $stmt->bindParam(':en', $ChurchEvent->EventName, PDO::PARAM_STR);
         
        if($ChurchEvent->interval > 0) {
          $recurring = true;
        } else {
           $recurring = false;
        }
                        
        ChromePhp::warn('Recurring '.$recurring);                         
        $stmt->bindParam(':re', $recurring,PDO::PARAM_BOOL);
                         
        ChromePhp::warn('Rank: '.$ChurchEvent->rank);                       	 
        $stmt->bindParam(':ra', $ChurchEvent->rank, PDO::PARAM_INT);
        // fix date
        if(empty($ChurchEvent->startdate) ) {
          	$ChurchEvent->startdate = null;
        }
         if(empty($ChurchEvent->enddate) ) {
          	$ChurchEvent->enddate = null;
        }
                        
        ChromePhp::warn('Start Date : '.$ChurchEvent->startdate);
        $stmt->bindParam(':sd', $ChurchEvent->startdate, PDO::PARAM_STR);
                	     
        ChromePhp::warn('End Date : '.$ChurchEvent->enddate);
        $stmt->bindParam(':ed', $ChurchEvent->enddate,PDO::PARAM_STR);
 
        ChromePhp::warn('Interval: '.$ChurchEvent->interval);                       	 
        $stmt->bindParam(':in', $ChurchEvent->interval, PDO::PARAM_INT);
        
        if($ChurchEvent->Online == "yes") {
           $online = true;
        } else {
           $online = false;
        }
                        	 
        ChromePhp::warn('Online '.$online);                         
        $stmt->bindParam(':ol', $online,PDO::PARAM_BOOL);
   
        if($ChurchEvent->Enabled == "yes") {
          $enabled = true;
        } else {
           $enabled = false;
        }
     
        ChromePhp::warn('Enabled '.$enabled);                         
        $stmt->bindParam(':eb', $enabled,PDO::PARAM_BOOL);
 
        ChromePhp::warn('Hyperlink: '.$ChurchEvent->hyperlink);
        $stmt->bindParam(':hy', $ChurchEvent->hyperlink, PDO::PARAM_STR);
       
        $stmt->bindParam(':de',$boolean,PDO::PARAM_BOOL);
 
        if($stmt->execute()) {
  	      ChromePhp::warn('Data Update/Insert is Successful');
//          $stmt->closeCursor();
          $ChurchEvent->status = "ok";
          if($ChurchEvent->EventID == 0) {         
            ChromePhp::warn('Fetching New Event ID');
              $ChurchEvent->EventID = $conn->lastInsertId(); 
              ChromePhp::warn('Action is successful: New EventID: '.$ChurchEvent->EventID);
            if($ChurchEvent->EventID > 0) {  
              ChromePhp::warn('Creating Default Ratings');
              $sql = "insert into ht.tblEventRatings(ChurchID,EventID,Category,rank,DontTallyIfZero,VisitorOnly,MemberOnly,datecreated) select ".$ChurchEvent->ChurchID.",".$ChurchEvent->EventID.", Category, rank,DontTallyIfZero,VisitorOnly,MemberOnly,now() from ht.tblDefaultRatings";
              ChromePhp::warn($sql);
              $stmt = $conn->prepare($sql);
              if($stmt->execute()) {
            	ChromePhp::warn("Default Event Questions Successfully Created");
              } else {
             	ChromePhp::warn("Default Event Questions Not Created");
              }
            } else {
              ChromePhp::warn('Unable to determine new Event ID');
          	$ChurchEvent->status = 'no';
            }
          } else {
// check for default
            if(!empty($ChurchEvent->defaultevent)) {
              if($ChurchEvent->defaultevent != $ChurchEvent->EventName) {
              	 ChromePhp::warn('Creating New Default Event for: '.$ChurchEvent->defaultevent);
                 $sql = "insert into ht.tblEvents(ChurchID,EventName,`interval`,rank,Recurring,`default`)
                 select ".$ChurchEvent->ChurchID.",EventName,`interval`,rank,Recurring,`default` from ht.tblDefaultEvents where EventName in (select EventName from ht.tblDefaultEvents where EventName = '".$ChurchEvent->defaultevent;
                 ChromePhp::warn($sql);
                 $stmt = $conn->prepare($sql);
                 if($stmt->execute()) {
                   $ChurchEvent->EventID = $stmt->lastInsertId();
                   if($ChurchEvent->EventID > 0) {
                     ChromePhp::warn('New Default Entry created in '.$ChurchEvent->ChurchID.' for '.$ChurchEvent->defaultevent);
                     ChromePhp::warn('Creating Default Ratings');
                     $sql = "insert into ht.tblEventRatings(ChurchID,EventID,Category,rank,DontTallyIfZero,VisitorOnly,MemberOnly,datecreated) select ".$ChurchEvent->ChurchID.",".$ChurchEvent->EventID.", Category, rank,DontTallyIfZero,VisitorOnly,MemberOnly,now() from ht.tblDefaultRatings";
                     ChromePhp::warn($sql);
                     $stmt = $conn->prepare($sql);
                     if($stmt->execute()) {
            	        ChromePhp::warn("Default Event Questions Successfully Created for new default event");
            	        $ChurchEvent->status='ok';
                     } else {
             	       ChromePhp::warn("Default Event Questions Not Created");
                       $ChurchEvent->status="no";
                     } //$tmmt->execute()
                   } else {
                     ChromePhp::warn('Unable to retrieve an autogenerated ID for Default Event: '.$ChurchEvent->EventName);
                     $ChurchEvent->status='ok';	
                   } //eventid > 0
                 } else {	
                  ChromePhp::warn('Unable to create a replacement default event:'.$ChurchEvent->defaultevent.' for ' .$ChurchEvent->ChurchID);
                  $ChurchEvent->status = 'no';     
                }//if($stmt->execute)
              }//defaultevent ! = eventname
            } //!empty(default event)
          } //EventID == 0
  	     echo json_encode($ChurchEvent);
        } else {
         	   ChromePhp::warn('Statement failed!');
         	   $ChurchEvent->status="no";
         	   $ChurchEvent->sql = $sql;
         	   $ChurchEvent->error = "Statement not implemented";	
           echo json_encode($ChurchEvent);	   
        }
      } else {
      	ChromePhp::warn('!!!*** Unable to consumate Database Connection ***!!!');
          	ChromePhp::warn($e->getMessage());
            $ChurchEvent->status = "Data Connection failure";
            $ChurchEvent->action = "Acquiring database connection";
            $ChurchEvent->error = $e->getMessage();
            $ChurchEvent->sql = $sql;
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
