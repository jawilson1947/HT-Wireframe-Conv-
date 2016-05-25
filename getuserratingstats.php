<?php
//getuserratingstats.php
//gets the user index record for ratings done'
include 'ChromePhp.php';
ChromePhp::warn('getuserratingstats.php');
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
   $userid = $pkg->id;
   $mychurchid = $pkg->churchid;
//$userid = 24;
  $sql = 'select id,T1.ChurchID,EventID,T2.churchname,EventName,EventDate,CommentID,Score as totalscore,RatingStack,city,state,country from ht.tblIndex T1 join ht.tblchurch T2 on T2.ChurchID = T1.ChurchID where T1.UserID = :ui order by T2.churchname,EventDate desc'; 
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
    
     $citybreak = checkstate($data[0]['city'],$data[0]['state']);
     $statebreak = $data[0]['state'];
     $countrybreak = $data[0]['country'];
     $eventnamebreak = $data[0]['EventName'];
     $totalscorebreak = $data[0]['totalscore'];
     $eventdatebreak = $data[0]['EventDate'];
     
//     print_r("First ChurchID: ".$churchbreak." \n");
//     print_r("First EventID: ".$eventbreak." \n");
     ChromePhp::warn('Church Name: '.$namebreak.' **Event Name: '.$eventnamebreak); 
// set up the first church array
     $churchstats = array('churchid' => $churchbreak,
                          'churchname' => $namebreak,
                          'city' => $citybreak,
                          'state' => $statebreak,
                          'country' => $countrybreak,
                          'expanded' => false,
                          'children' => array());
//    ChromePhp::warn('Matrix Created!');
    $churchbreak = $data[0]['ChurchID'];
    $churchname = $data[0]['churchname'];
    $city = checkstate($data[0]['city'],$data[0]['state']);;
    $state = $data[0]['state'];
    $country = $data[0]['country'];
    foreach($data as $d):
         $rCount++ ;  
         ChromePhp::warn('Church Name: '.$d['churchname'].' **Event Name: '.$d['EventName']);
         ChromePhp::warn('Record Count: '.$rCount);
//         print_r("Church Name: ".$d['churchname']." **Event Name: ".$d['EventName']." \n");
//         print_r("Record Count: ".$rCount." \n");
            if($churchbreak != $d['ChurchID']) {
             array_push($myStats, $churchstats);	
             $churchbreak = $d['ChurchID'];
             
             $churchstats = array('churchid' => $d['ChurchID'],
                                 'churchname' => $d['churchname'],
                                 'city' => checkstate($d['city'],$d['state']),
                                 'country' => $d['country'],
                                 'expanded' => true,
                                 'children' => array()); 	
            }	
            	
                     $eventstats =  array('churchid' => $d['ChurchID'],
                     'eventname' => $d['EventName'],
                     'eventdate' => $d['EventDate'],
                     'totalscore' => $d['totalscore'],
                     'eventid' => $d['EventID'],
                     'id' => $d['id'],
                     'ratings_expanded' => false,
                     'children' => array());
                     $ratingStack = $d['RatingStack'];
//                     print_r("Rating Stack: ".$ratingStack." \n");
                     $rids = explode("|", $ratingStack);
                     foreach($rids as $ratingid):
                      if(!empty($ratingid)) {
                        $ratid = (int)$ratingid;
                       if($ratid > 0 ) {
//                         print_r("Rating ID: ".$ratid." \n");
                       $xrx = $cn->prepare('Select ChurchID, EventID as eventid,RatingID as ratingid, Category as category, Score as score,
                        rank, VisitorOnly as visitor, MemberOnly as member from ht.tblUserRating where RatingID = :ri');
                       $xrx->bindParam(':ri',$ratid,PDO::PARAM_INT);
                       $xrx->execute();
                       $r = $xrx->fetch(PDO::FETCH_ASSOC);
                       if($r) {
//                       	print_r("RatingID: ".$ratingid." found \n"); 
                         $oktopush = true;
                         if($r['visitor'] == true) {
                           if($r['ChurchID'] == $mychurchid) {
                           	 $oktopush = false;
                           }
                         }
                         if($r['member'] == true) {
                           if($r['ChurchID'] != $mychurchid) {
                             $oktopush = false;	
                           }	
                         }
                         if($oktopush == true) {	
                       	   array_push($eventstats['children'],$r);
                         }
                       }
                       $xrx->closeCursor();
                       }
                      }
                      endforeach;
                      array_push($churchstats['children'],$eventstats); 
     endforeach;
     array_push($myStats, $churchstats);
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
//  print_r($c['error']." \n");
   echo json_encode($c);
   die();
 }
function checkstate($c,$s) {
	ChromePhp::warn('City: '.$c.' State: '.$s);
   if(!empty($c) && !empty($s)) {
     return $c.', '.$s;	
 } else {
  return $c;	
 }			
}	
	 
?>
