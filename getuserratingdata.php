<?php
//getuserratingdata.php
//gets the user index record for ratings done'
include 'ChromePhp.php';
ChromePhp::warn('getuserratingdata');
$username = 'root';
$password = 'DanaDenyse32';
$dbname = 'ht';
$host = '127.0.0.1';
$c = array( 'status' => '',
  'action' => '',
  'error' =>  '',
  'sql' => '');
try {
  $cn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
$pkg = json_decode($_POST["info"]);
$userid = $pkg->userid;
//$userid = 24;
  $sql = 'select T1.churchname,city,state,country,T1.ChurchID,T2.EventName,T2.EventID,Category,IFNULL(T3.Score,0) as score,T3.EventDate, 
          IFNULL(T2.Score,0) as totalscore,T3.RatingID from ht.tblchurch T1 join ht.tblIndex T2 on (T2.ChurchID = T1.ChurchID and T2.EventID = T2.EventID)
          join ht.tblUserRating T3 on T3.EventID = T2.EventID where T3.UserID = :ui order by T1.churchname,T2.EventName, T3.EventDate desc, T3.rank'; 
$myStats = array();        
  
ChromePhp::warn('UserID is '.$userid);
  
//print_r("Matrix Created \n");                                                                
  $xsql = $cn->prepare($sql);
  $xsql->bindParam(':ui',$userid,PDO::PARAM_INT);
  ChromePhp::warn('SQL : '.$sql);
  if($xsql->execute()) {
  	$data = $xsql->fetchALL(PDO::FETCH_ASSOC);
     ChromePhp::warn('Search is successful');
//print_r("Search is Successful \n");
     $rCount = 0;
     $churchbreak = $data[0]['ChurchID'];
     $eventbreak = $data[0]['EventID'];
     $namebreak = $data[0]['churchname'];
     $citybreak = $data[0]['city'];
     $statebreak = $data[0]['state'];
     $countrybreak = $data[0]['country'];
     $eventnamebreak = $data[0]['EventName'];
     $totalscorebreak = $data[0]['totalscore'];
     $eventdatebreak = $data[0]['EventDate'];
     print_r("First ChurchID: ".$churchbreak." \n");
     print_r("First EventID: ".$eventbreak." \n");
     ChromePhp::warn('Church Name: '.$namebreak.' **Event Name: '.$eventnamebreak); 
// set up the first church array
     $churchstats = array('churchid' => $churchbreak,
                          'churchname' => $namebreak,
                          'city' => $citybreak,
                          'state' => $statebreak,
                          'country' => $countrybreak,
                          'children' => array());
 // setup the first event stats                         
     $eventstats = array('churchid' => $churchbreak,
                         'eventid' => $eventbreak,
                         'eventname' => $eventnamebreak,
                         'eventdate' => $eventdatebreak,
                         'children' => array());
                                             
   $ratingstats = array('churchid' => 0,
                     'eventid' => 0,
                     'ratingid' => 0,
                     'category' => '',
                     'score' => 0);
    ChromePhp::warn('Matrix Created!');
    foreach($data as $d):
         $rCount++ ;  
         if($d['EventID'] != $eventbreak) {
         ChromePhp::warn('Church Name: '.$d['churchname'].' **Event Name: '.$d['EventName']);
         ChromePhp::warn('Record Count: '.$rCount);
//         print_r("Church Name: ".$d['churchname']." **Event Name: ".$d['EventName']." \n");
//         print_r("Record Count: ".$rCount." \n");
                     array_push($churchstats['children'],$eventstats);
                      $eventstats =  array('churchid' => $d['ChurchID'],
                     'eventname' => $d['EventName'],
                     'eventdate' => $d['EventDate'],
                     'totalscore' => $d['totalscore'],
                     'eventid' => $d['EventID'],
                     'children' => array());
                     $eventbreak = $d['EventID'];
                     
                     $ratingstats = array('churchid' => 0,
                           'eventid' => 0,
                            'ratingid' => 0,
                            'category' => '',
                            'score' => 0);
     }
                    
          if($d['ChurchID'] != $churchbreak) {        	         
                     array_push($myStats,$churchstats);
                     $churchstats = array('churchid' => $d['ChurchID'],
                     'churchname' => $d['churchname'],
                     'city' => $d['city'],
                     'state' => $d['state'],
                     'country' => $d['country'],
                     'children' => array());
                     $churchbreak = $d['ChurchID'];
          	
         }	                     
    	$r = array('churchid' => $d['ChurchID'],
     	                        'eventid' => $d['EventID'],
     	                        'ratingid' => $d['RatingID'],
     	                        'category' => $d['Category'],
     	                        'score' => $d['score']);
     	                        
      	  array_push($eventstats['children'], $r);
     	 
     endforeach;
     
     echo json_encode($myStats);
   } else {
      ChromePhp::warn('Query Unsuccessful');
//      print_r("Query Unsuccessful \n");
      $c['status'] = 'no';
      $c['action'] = 'query';
      $c['error'] = "!Not Found";
      $c['sql'] = $sql;
      echo json_encode($c);
  }
 
} catch (PDOException $e) {
  $c['status'] = 'no';
  $c['action'] = 'selecting rating/event items ';
  $c['error'] =  $e->getMessage;
  $c['sql'] = $sql;
   ChromePhp::warn($c['error']);
  print_r($c['error']." \n");
   echo json_encode($c);
   die();
 }
 
?>