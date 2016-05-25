<?php
include 'ChromePhp.php';
ChromePhp::warn('churchevents.php');
function customError($errno, $errstr) {
  $info = array('status' => 'system error',
      	              'action' => 'process',
      	              'error' => $errno. '  - '. $errstr,
                 	              'sql' => '');
                 	  ChromePhp::warn('PHP System error: '.$info['error']);
               echo json_encode($info);  
               die();	              
                   }
//set_error_handler("customError");
//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  $user_name = 'root';
  $password = 'DanaDenyse32';
  $database = 'ht';
  $server = '127.0.0.1';
  $db_handle = mysql_connect($server,$user_name,$password);
  $db_found = mysql_select_db($database,$db_handle);
  ChromePhp::warn('Running');
  if($db_found) {
 // 	if(isset($_POST["info"])) {
      $churchData = json_decode($_POST["info"]);
      $churchid = $churchData->churchid;
      $placeid = $churchData->placeid;
      $status =  array('status' => '',
      	              'action' => '',
      	              'error' => '',
      	              'sql' => '');    	
        if(empty($churchid)) {
      	   $churchid = 0;
           }
         $sql = "select DefaultEventsCreated,ChurchID from ht.tblchurch where ((ChurchID = ".$churchid.") or (GoogleMapPlaceID = '".$placeid."'))";
      $result = mysql_query($sql);
      ChromePhp::warn('SQL is '.$sql);
      if($dbfield = mysql_fetch_assoc($result)){
      	$defaultEvents = $dbfield['DefaultEventsCreated'];
      	$churchid = $dbfield['ChurchID'];
// check Default Events Flag
        if(empty($defaultEvents)) {
        	if($churchid > 0) { 
               ChromePhp::warn('DefaultEvents Flag is false and Church ID ='.$churchid);
               $sql = "select * from ht.tblEvents where ChurchID = ".$churchid;
               ChromePhp::warn($sql);
               $result = mysql_query($sql) or die(mysql_error());
               for($i = 0; $events[$i] = mysql_fetch_assoc($result); $i++) ;
               array_pop($events); 
               $eventcount = sizeof($events); 
               ChromePhp::warn(" Events Count for ".$churchid." is ".$eventCount);         	
               if ($eventcount < 1 ) {
                 $sql = "insert into ht.tblEvents(ChurchID,EventName,`interval`,rank,Recurring)
                 select ".$churchid.",EventName,`interval`,rank,Recurring from ht.tblDefaultEvents";
                 ChromePhp::warn($sql);
                 $retval = mysql_query($sql); 
                 if(! $retval ) { 
                   $status['status'] = "No";
                   $status['action'] = 'Error Inserting Default Events for ChurchID: '.$churchid;
                   $status['error'] = mysql_error();
                   $status['sql'] = $sql;
                   ChromePhp::warn($status['error']);
                  } else {
                    ChromePhp::warn('Default Events successfully inserted for ChurchID: '.$churchid);    
                     $sql = "update ht.tblchurch set DefaultEventsCreated = true where ChurchID = ".$churchid;
                     $retval = mysql_query($sql); 
                     if($retval) {
                     	ChromePhp::warn('Default Events Created Flag update was successful for ChurchID: '.$churchid);
                     $churchData->status = "ok";
                     $churchData->action = 'Default Events inserted';
                     } else {
                       ChromePhp::warn('Default Events flag not updatedfor ChurchID: '.$churchid);    
                     $status['status'] = "no";
                     $status['action'] = 'Unable to update Default Events Flag for ChurchID:'.$cchurchid;
                     $status['error' ]= mysql_error();
                     $status['sql'] = $sql;                   	
                      }
                    } 
               } else {
               	ChromePhp::warn('Found '.$eventcount.' Events for ChurchID: '.$churchid);
                $churchData->status="ok";
                echo json_encode($churchData);            	
               }
        	} else {
        	   $status['status'] = "no Go";
        	   $status['action'] = "Getting Default Events";
        	   $status['error'] = "Church not Found in HT Database: ".$churchData.churchname;
        	   $status['sql'] = $sql;
               ChromePhp::warn($status['error']);
         	} 
        }else {
          ChromePhp::warn('DefaultEvents Flag is True and ChurchID ='.$churchid);
          $sql = "select * from ht.tblEvents where ChurchID = ".$churchid;
          ChromePhp::warn($sql);
          $result = mysql_query($sql) or die(mysql_error());
          for($i = 0; $events[$i] = mysql_fetch_assoc($result); $i++) ;
          array_pop($events); 
          $eventcount = sizeof($events); 
          ChromePhp::warn(" Events Count for ".$churchid." is ".$eventcount);  
          ChromePhp::warn("churchData id = ".$churchData->id);
          ChromePhp::warn("churchData Churchname = ".$churchData->churchname);
          ChromePhp::warn("church ChurchID = ".$churchData->id);       	
          foreach($events as $element): 
            ChromePhp::warn($element['EventName']);
          endforeach; 
         }
// End Default Events check      	
         echo json_encode($events);
                       
   		} else {

               ChromePhp::warn("Error in database query: ".mysql_error());
               $data = array('status' => 'no',
                             'action' => 'searching for church id',
                             'error' => mysql_error(),
                             'sql' => $sql);
                     echo json_encode($data);
                }

  } else {
                $data = array('status' => 'no',
                             'action' => 'Opening Data Connection',
                             'error' => mysql_error(),
                             'sql' => $sql);
                     echo json_encode($data);
                 ChromePhp::warn('Database not initialized. error: '.$info['error']);
            echo json_encode($sorry);             
      mysql_close($db_handle);
 // } if(isset($_POST    
      //
  }//$dbfound
//}//isset($_SERVER
?>
