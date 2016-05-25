<?php
//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  include 'ChromePhp.php';
  ChromePhp::warn('getgooglechurch.php');
  $username = 'root';
  $password = 'DanaDenyse32';
  $dbname = 'ht';
  $host = '127.0.0.1';
  $c = array( 'status' => '',
  'action' => '',
  'error' =>  '',
  'sql' => '');
   try {
 	    ChromePhp::warn("Opening Data Connection");
 	    $cn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
        $pkg = json_decode($_POST["info"]);
        $domain = $pkg->domain;
        $placeid = $pkg->placeid;
        ChromePhp::warn("Domain is: ".$domain.", GoogleMapPlaceID is:".$placeid);
          $sql = $cn->prepare("select * from ht.tblchurch where TRIM(IFNULL(domain,'')) = :do ");
          $sql->bindParam(':do',$domain,PDO::PARAM_STR,50);
          ChromePhp::warn('Query submitted');
          $sql->execute();
          $dbfield = $sql->fetch(PDO::FETCH_ASSOC);
          ChromePhp::warn('Count of dbfield is: '.count($dbfield));
          if($dbfield == false)  {
                     ChromePhp::warn($pkg->name.' not found, adding to database');
                     insertchurch($pkg);
          } else {
           ChromePhp::warn('Church found! Domain : '.$dbfield['domain'].' PlaceID is: '.$dbfield['GoogleMapPlaceID']);
           $info = array('status' => "ok",
                     'action' => 'Google Map Church Lookup in ht.tblchurch',
                     'error' => '',
                     'sql' => $sql,
                     'source' => 'ht.tblChurch',
                     'id' => $dbfield['ChurchID'],
                     'name' => $dbfield['churchname'],
                     'domain' => $domain,
                     'placeid' => $placeid,
                     'street' => $dbfield['address'],
                     'city' => $dbfield['city'],
                     'phone' => $dbfield['phone'],
                     'website' => $dbfield['website'],
                     'province' => $dbfield['state'],
                     'postalcode' => $dbfield['zipcode'],
                     'vicinity' => '',
                     'address' => $dbfield['address'],
                     'country' => $dbfield['country'],
                     'id' => $dbfield['ChurchID']
                     );
                     ChromePhp::warn("Church ID for ".$info['name']." is ".$info['id']);
                    if(empty($dbfield['GoogleMapPlaceID'])) {                    	
                     	$isql = $cn->prepare("Update ht.tblchurch set GoogleMapPlaceID = :pl where ChurchID = :ci");
                     	ChromePhp::warn('Record found but GoogleMapPlaceID is empty, updating....');
                     	$isql->bindParam(':pl',$placeid,PDO::PARAM_STR,50);
                     	$isql->bindParam(':ci',$info['id'],PDO::PARAM_INT);
                     	if($isql->execute()) {
                     	  ChromePhp::warn("GoogleMapPlaceID updated successfully for ".$info['name']);
                     	} else {
                     	  ChromePhp::warn("Unable to update GoogleMapPlaceID for  ".$info['name']);	
                     	 }
                       } 
                 echo json_encode($info);
                setupratings($info['id'],$info['placeid'],$info['domain']);
          }

  }catch (PDOException $e) {
      ChromePhp::warn('Query Unsuccessful');
      $c['status'] = 'no';
      $c['action'] = 'query';
      $c['error'] = $e->getMessage();
      $c['sql'] = $sql;
      echo json_encode($c);
	
  }	
 function insertchurch($c) {
  	ChromePhp::warn('Inserting '.$c->name.' to the database');
     $username = 'root';
     $password = 'DanaDenyse32';
     $dbname = 'ht';
     $host = '127.0.0.1';
  
 	               try {
 	   	           $cn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
    	           if(!$cn) {
                   $c['status'] = "failure";
                    $c['action'] = "Connect Error";
                    $c['error'] = "Unable to Connect to MYSSQL";
                    $c['sql'] = "";
                    ChromePhp($c['error']);
                    json_encode($c);
    	           } else {
 	               $sql = "CALL savegooglechurch(:cn,:ad,:ci,:st,:ph,:co,:we,:dm,:pl,:po,@cid)";
 	               $stmt = $cn->prepare($sql);
 	               ChromePhp::warn($sql);
 	               $stmt->bindParam(':cn',$c->name,PDO::PARAM_STR,50);
 	               $stmt->bindParam(':ad',$c->street,PDO::PARAM_STR,50);
 	               $stmt->bindParam(':ci',$c->city,PDO::PARAM_STR,50);
 	               $stmt->bindParam(':st',$c->province,PDO::PARAM_STR,50);
 	               $stmt->bindParam(':ph',$c->phone,PDO::PARAM_BOOL);
 	               $stmt->bindParam(':co',$c->country,PDO::PARAM_STR,50);
 	               $stmt->bindParam(':we',$c->website,PDO::PARAM_STR,80);
	               $stmt->bindParam(':dm',$c->domain,PDO::PARAM_STR,50);
 	               $stmt->bindParam(':pl',$c->placeid,PDO::PARAM_STR,50);
 	               $stmt->bindParam(':po',$c->postalcode,PDO::PARAM_STR,50);
                      $stmt->execute();
                    $stmt->closeCursor();
                    $r = $cn->query("select @cid as cid")->fetch(PDO::FETCH_ASSOC);
                    $c->id = $r['cid'];
                    $c->status = 'ok';
                    $c->action = 'insert';
                    $c->error = '';
                    $c->sql = $sql;
                   ChromePhp::warn('Insert Successful - New ID is '.$c->id);
                    echo json_encode($c);
                    setupratings($c->id,$c->placeid,$c->domain);
                    }
                 } catch (PDOException $e) {
                    $c->status = 'no';
                    $c->action = 'insert';
                    $c->error = $e->getMessage;
                    $c->sql = $sql;
                    echo json_encode($c);
                    ChromePhp::warn($e->getMessage);
                      }
         } 
         
function setupratings($churchid,$pid,$dom) {
ChromePhp::warn('Setting up Rating Matrix for: '.$churchid.' or '.$dom.' or '.$pid);	
 $user_name = 'root';
  $password = 'DanaDenyse32';
  $database = 'ht';
  $server = '127.0.0.1';
  $db_handle = mysql_connect($server,$user_name,$password);
  $db_found = mysql_select_db($database,$db_handle);
  if($db_found) {
         $sql = "select DefaultEventsCreated,
	       DefaultRatingsCreated,
	       ChurchID
         from ht.tblchurch
	     where ((ChurchID = ".$churchid.") or (GoogleMapPlaceID = '".$pid."') or (domain='".$dom."'))";
      $result = mysql_query($sql);
      ChromePhp::warn('SQL is '.$sql);
      if($dbfield = mysql_fetch_assoc($result)){
      	$defaultEvents = $dbfield['DefaultEventsCreated'];
      	$defaultRatings = $dbfield['DefaultRatingsCreated'];
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
                 $sql = "insert into ht.tblEvents(ChurchID,EventName,`interval`,rank,Recurring,`default`)
                 select ".$churchid.",EventName,`interval`,rank,Recurring,`default` from ht.tblDefaultEvents";
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
                     $sql = "update ht.tblchurch set DefaultEventsCreated = true, EventCount = ".$eventcount." where ChurchID = ".$churchid;
                     $retval = mysql_query($sql); 
                     if($retval) {
                     	ChromePhp::warn('Default Events Created Flag update was successful for ChurchID: '.$churchid);
                    ChromePhp::warn("Retrieving recently inserted events for ratings sync");
                     $sql = "select * from ht.tblEvents where ChurchID = ".$churchid;
                     ChromePhp::warn($sql);
                       $result = mysql_query($sql) or die(mysql_error());
                        for($i = 0; $events[$i] = mysql_fetch_assoc($result); $i++) ;
                         array_pop($events); 
                        $eventcount = sizeof($events); 
                        ChromePhp::warn("Recent Events Received are as follows");
                        foreach ($events as $event):
                        ChromePhp::warn($event['EventName']);
                        endforeach;
                        checkratings($events,$churchid,$pid,$dom);
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
                $userStub->status="ok";
                $usetStub->churchid = $churchid;
                echo json_encode($userStub);            	
               }
        	} else {
        	   $status['status'] = "no Go";
        	   $status['action'] = "Getting Default Events";
        	   $status['error'] = "DefaultEvents Flag is false and Church ID is Zero";
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
         foreach($events as $element): 
            ChromePhp::warn($element['EventName']);
          endforeach; 
          checkratings($events,$churchid,$pid,$dom);
         }
// End Default Events check      	
         console.log(json_encode($events));
                       
   		} else {

               $data = array('status' => 'no',
                             'action' => 'searching for church id for '.$dom,
                             'error' => mysql_error(),
                             'sql' => $sql);
                     ChromePhp::warn($data['error']);
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
            console.log(json_encode($sorry));
        }             
      mysql_close($db_handle);    
  }//$dbfound
  //
  function checkratings($e,$churchid,$p,$d) {
ChromePhp::warn('Checking Ratings');	
$user_name = 'root';
$password = 'DanaDenyse32';
$database = 'ht';
$server = '127.0.0.1';
$db_handle = mysql_connect($server,$user_name,$password);
$db_found = mysql_select_db($database,$db_handle);
if($db_found) {
  ChromePhp::warn('Checking ratings for Events');
  $status =  array('status' => '',
      	              'action' => '',
      	              'error' => '',
      	              'sql' => ''); 
  $sql = "select DefaultRatingsCreated from ht.tblchurch where (ChurchID = ".$churchid.") or GoogleMapPlaceId = '".$p."' or domain = '".$d."'";
  ChromePhp::warn($sql);
  $result = mysql_query($sql);
  ChromePhp::warn('SQL is '.$sql);
  if($dbfield = mysql_fetch_assoc($result)){
   	$defaultRatings = $dbfield['DefaultRatingsCreated'];
// check Default Events Flag
    if(empty($defaultRatings)) {
      ChromePhp::warn('DefaultRatingFlag is false for Church ID: '.$churchid);
      ChromePhp::warn('Events in Core are as follows.....');
      foreach($e as $value):
        ChromePhp::warn($value['EventName']." ID:".$value['EventID']);	
      endforeach;
      ChromePhp::warn('Creating Default Ratings for Events for '.$churchid); 
      foreach($e as $value):
      $sql = "insert into ht.tblEventRatings(ChurchID,EventID,Category,rank,DontTallyIfZero,datecreated) select ".$churchid.",".$value['EventID'].", Category, rank,DontTallyIfZero,now() from ht.tblDefaultRatings";
      ChromePhp::warn($sql);
      $retval = mysql_query($sql);
      if(!$retval) {
        ChromePhp::warn("Could not insert this query: Error:".mysql_error());
       }
       endforeach;
       $sql = "select * from ht.tblEventRatings where ChurchID = ".$churchid;
       ChromePhp::warn("Checking for recently inserted default ratings : ".$sql);
       $result = mysql_query($sql) or die(mysql_error());
       for($i = 0; $ratings[$i] = mysql_fetch_assoc($result); $i++) ;
       array_pop($ratings); 
       $ratingcount = sizeof($ratings); 
       ChromePhp::warn("Total Ratings for ".$churchid.": ".$ratingcount);
       if($ratingcount > 0) {
       	 ChromePhp::warn('Default Ratings just inserted are as follows:');
       	 foreach($ratings as $value):
       	  ChromePhp::warn($value['Category']);
       	  endforeach;
       	 ChromePhp::warn('Updating Default Ratings Created Flag for: '.$churchid);
         $sql = "update ht.tblchurch set DefaultRatingsCreated = true where ChurchID = ".$churchid;
         ChromePhp::warn($sql);
         $retval = mysql_query($sql); 
         if($retval) {  
           ChromePhp::warn('Default Ratings Created Flag update was successful for ChurchID: '.$churchid);
           console.log(json_encode($ratings));   
         } else {
              ChromePhp::warn("Unable to update Default Ratings Flag in Church ID: ".$churchid);                	
                   }
       } else {
          ChromePhp::warn("No Ratings found even after insert .. Clearing Flag");
          $sql = "update ht.tblchurch set DefaultRatingsCreated = false where ChurchID = ".$churchid;
          ChromePhp::warn($sql);
          $retval = mysql_query($sql);
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
         console.log(json_encode($ratings));
             
     } else {

               $data = array('status' => 'no',
                             'action' => 'searching for church id for '.$churchid,
                             'error' => mysql_error(),
                             'sql' => $sql);
                     console.log(json_encode($data));
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
            console.log(json_encode($sorry));             
      mysql_close($db_handle);
      }
   }//$dbfound
   
?>