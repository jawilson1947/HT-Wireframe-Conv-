<?php
//testrating.php
include 'ChromePhp.php';
ChromePhp::warn('churchratings.php');
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
  if($db_found) {
 // 	if(isset($_POST["info"])) {
     ChromePhp::warn('Running');
      $events = json_decode($_POST["info"]);
      $status =  array('status' => '',
      	              'action' => '',
      	              'error' => '',
      	              'sql' => ''); 
      	$churchid = $events[0]->ChurchID;
      	ChromePhp::warn("Church ID is: ".$churchid);                 	
        if(empty($churchid)) {
      	   $churchid = 0;
                  $status['status'] = 'no';
                  $status['action'] = 'Deriving Church ID from Event Stack';
                  ChromePhp::warn('Unable to determine church ID from Event Stack');
                  echo json_encode($status);
                  die();
                  }
      $sql = "select DefaultRatingsCreated from ht.tblchurch where ChurchID = ".$churchid;
      $result = mysql_query($sql);
      ChromePhp::warn('SQL is '.$sql);
      if($dbfield = mysql_fetch_assoc($result)){
      	$defaultRatings = $dbfield['DefaultRatingsCreated'];
// check Default Events Flag
        if(empty($defaultRatings)) {
               ChromePhp::warn('DefaultRatingFlag is false and Church ID ='.$churchid);
               $sql = "select * from ht.tblEventRatings where ChurchID = ".$churchid;
               ChromePhp::warn($sql);
               $result = mysql_query($sql) or die(mysql_error());
               for($i = 0; $ratings[$i] = mysql_fetch_assoc($result); $i++) ;
               array_pop($ratings); 
               $ratingcount = sizeof($ratings); 
               ChromePhp::warn(" Ratings Count for ".$churchid." is ".$ratingcount);         	
               if ($ratingcount < 1 ) {
                    ChromePhp::warn('Creating Default Ratings for Events for '.$churchid); 
                    foreach($events as $value) {
                    	$sql = "insert into ht.tblEventRatings(ChurchID,EventID,Category,DontTallyIfZero,datecreated) 
                    	        select ".$value->ChurchID.",".$value->EventID.", Category, DontTallyIfZero,now() from ht.tblDefaultRatings";
                    	        ChromePhp::warn($sql);
                    	        $retval = mysql_query($sql);
                    	        if(!$retval) {
                    	          ChromePhp::warn("Could not insert this query: Error:".mysql_error());
                    	         }
                              }
                        $sql = "select * from ht.tblEventRatings where ChurchID = ".$churchid;
                        ChromePhp::warn("Collecting all Ratings for JSON Pacakge - sql: ".$sql);
                        $result = mysql_query($sql) or die(mysql_error());
                        for($i = 0; $ratings[$i] = mysql_fetch_assoc($result); $i++) ;
                        array_pop($ratings); 
                        $ratingcount = sizeof($ratings); 
                        ChromePhp::warn("Total Ratings for ".$churchid.": ".$ratingcount);
                        if($ratingcount > 0) {
                          $sql = "update ht.tblchurch set DefaultRatingsCreated = true where ChurchID = ".$churchid;
                           $retval = mysql_query($sql); 
                           if($retval) {  
                    	   ChromePhp::warn('Default Ratings Created Flag update was successful for ChurchID: '.$churchid);
                            echo json_encode($ratings);   
                          } else {
                             ChromePhp::warn("Unable to update Default Ratings Flag in Church ID: ".$churchid);                	
                                 }
                          } else {
                               ChromePhp::warn("No Ratings found even after insert .. Clearing Flag");
                               $sql = "update ht.tblchurch set DefaultRatingsCreated = false where ChurchID = ".$churchid;
                               ChromePhp::warn($sql);
                               $retval = mysql_query($sql);
                                  }    
                   } else {
                         ChromePhp::warn("Found Ratings for ChurchID: ".$churchid);
                     $sql = "select * from ht.tblEventRatings where ChurchID = ".$churchid;
                        ChromePhp::warn("Collecting all Ratings for JSON Pacakge - sql: ".$sql);
                        $result = mysql_query($sql) or die(mysql_error());
                        for($i = 0; $ratings[$i] = mysql_fetch_assoc($result); $i++) ;
                        array_pop($ratings);
                        $ratingcount = sizeof($ratings);
                        ChromePhp::warn("Total Ratings for ".$churchid.": ".$ratingcount);
                                if($ratingcount > 0) {
                          $sql = "update ht.tblchurch set DefaultRatingsCreated = true where ChurchID = ".$churchid;
                           $retval = mysql_query($sql);
                           if($retval) {  
                           ChromePhp::warn('Default Ratings Created Flag update was successful for ChurchID: '.$churchid);
                          echo json_encode($ratings);
                          } else {
                             ChromePhp::warn("Unable to update Default Ratings Flag in Church ID: ".$churchid);
                                 }
                          } else {
                               ChromePhp::warn("No Ratings found even after insert .. Clearing Flag");
                               $sql = "update ht.tblchurch set DefaultRatingsCreated = false where ChurchID = ".$churchid;
                               ChromePhp::warn($sql);
                               $retval = mysql_query($sql);
                                  }

                         }           
        }else {
          ChromePhp::warn('Default Ratings  Flag is True and ChurchID ='.$churchid);
          $sql = "select * from ht.tblEventRatings where ChurchID = ".$churchid;
          ChromePhp::warn($sql);
          $result = mysql_query($sql) or die(mysql_error());
          for($i = 0; $ratings[$i] = mysql_fetch_assoc($result); $i++) ;
          array_pop($ratings); 
          $ratingcount = sizeof($ratings); 
          ChromePhp::warn(" Ratings Count for ".$churchid." is ".$ratingcount);  
            foreach($ratings as $element): 
            ChromePhp::warn($element['Category']);
          endforeach; 
         }
// End Default Ratings check      	
         echo json_encode($ratings);
                       
   		} else {

               $data = array('status' => 'no',
                             'action' => 'searching for church id for '.$data->firstname.' '.$data->lastname,
                             'error' => mysql_error(),
                             'sql' => $sql);
                     echo json_encode($data);
                }

  } else {
           $sorry = array('status' => "database not initialized",
                  'firstname' => '',
                  'lastname' => '',
                  'churchname' => '',
                  'placeid' => '',
                  'email' => '',
                  'id' => 0,
                 'churchadmin' => false,
                  'action' => 'login.php',
                  'error' => mysql_error(),
                  'sql' => $sql,
                 'password' =>'');
                  ChromePhp::warn('Database not initialized. error: '.$info['error']);
            echo json_encode($sorry);             
      mysql_close($db_handle);
 // } if(isset($_POST    
      //
  }//$dbfound
//}//isset($_SERVER
function getChurchID($e) {
	return $e[0]->ChurchID;
  foreach ($e as $value) {
   ChromePhp::warn( $value->EventName); 
   ChromePhp::warn($value->ChurchID);    
   }
   die();
}
?>
