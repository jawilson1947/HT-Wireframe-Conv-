<?php
//getgridstats.php
//gets the user index record for ratings done'
//include 'ChromePhp.php';
//ChromePhp::warn('getuserratingstats.php');
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
//   $pkg = json_decode($_POST["info"]);
$arr =  array('id' => 24,
              'firstname' => 'Carlton',
              'lastname' => 'Byrd',
              'churchid' => 28092);
              
$pkg = json_decode(json_encode($arr));              
$userid = $pkg->id;
$mychurchid = $pkg->churchid;
$sql = 'select id,T1.ChurchID,EventID,T2.churchname,EventName,EventDate,CommentID,Score as totalscore,RatingStack,city,state,country from ht.tblIndex T1 join ht.tblchurch T2 on T2.ChurchID = T1.ChurchID where T1.UserID = :ui order by T2.churchname,EventDate desc'; 
$myStats = array('total' => 0,
                 'person' => $pkg->firstname.' '.$pkg->lastname,
                 'rows' => array(),
                 'footer' => array('name' => $pkg->firstname.' '.$pkg->lastname.' church event rating history',
                                   'Events' => 0,
                                   'Categories' => 0,
                                   'Churches' => 0,
                                   'TotalRows' => 0,
                                   'iconCls' => 'icon-sum'));        
  
//ChromePhp::warn('UserID is '.$userid);
  
print_r("UserID: ".$userid." \n");                                                                
  $xsql = $cn->prepare($sql);
  $xsql->bindParam(':ui',$userid,PDO::PARAM_INT);
//  ChromePhp::warn('SQL : '.$sql);
  if($xsql->execute()) {
  	$data = $xsql->fetchALL(PDO::FETCH_ASSOC);
//     ChromePhp::warn('Search is successful');
print_r("Search is Successful \n");
     $rCount = 0;
//     ChromePhp::warn('Church Name: '.$namebreak.' **Event Name: '.$eventnamebreak); 
     $churchbreak = $data[0]['ChurchID'];
     $churchpointer = 1;
     $eventbreak = $data[0]['EventID'];
     $eventpointer = 2;
     $eCount = 1;
     $cCount = 1;
     $qCount = 0;
     $json = array("id" => 1,
              "_parentid" => 0,
              "churchname" => $data[0]['churchname'],
              "city" => checkstate($data[0]['city'],$data[0]['state']),
              "country" => $data[0]['country'],
              "eventname" =>"",
              "eventdate" => "",
              "avgscore" => 0,
              "category" => "",
              "score" => 0,
             "churchid" => $data[0]['ChurchID'],
              "indexid" => $data[0]['id'],
              "eventid" => 0,
              "ratingid" => 0,
              "icon-Cls" => "",
              "state" => "closed",
              "type" => "church");
              array_push($myStats['rows'], $json);
              $json['eventname'] = $data[0]['EventName'];
              $json['eventdate'] = $data[0]['EventDate'];
              $json['avgscore'] = $data[0]['totalscore'];
              $json['_parentid'] = 1;
              $json['churchid'] = $data[0]['ChurchID'];
              $json['eventid'] = $data[0]['EventID'];
              $json['type'] = "event";
              $json['state'] = "closed";
              $json['indexid'] = $data[0]['id'];
              $json['id'] = 2;
              array_push($myStats['rows'],$json);
              $rCount = 3;
      foreach($data as $d):
 
//         ChromePhp::warn('Church Name: '.$d['churchname'].' **Event Name: '.$d['EventName']);
//         ChromePhp::warn('Record Count: '.$rCount);
         print_r("Church Name: ".$d['churchname']." **Event Name: ".$d['EventName']." \n");
         print_r("Record Count: ".$rCount." \n");
         
            if($churchbreak != $d['ChurchID']) {
                $json['_parentid'] = 0;
                $json['churchname'] = $d['churchname'];
                $json['city'] = checkstate($d['city'],$d['state']);
                $json['country'] = $d['country'];
                $json['churchid'] = $d['ChurchID'];
                $json['type'] = "church";
                $json['state'] = 'closed';
                $json['id'] = $rCount;
                array_push($myStats['rows'],$json);
                $churchpointer = $rCount;
            	$churchbreak = $d['ChurchID'];
             	$rCount++ ;
             	$cCount++ ;
           }
            if($eventbreak !=$d['EventID']) {      	
                $json['_parentid'] = $churchpointer;
                $json['eventname'] = $d['EventName'];
                $json['eventdate'] = $d['EventDate'];
                $json['avgscore'] = $d['totalscore'];
                $json['eventid'] = $d['EventID'];
                $json['type'] = "event";
                $json['state'] = 'closed';
                $json['id'] = $rCount;
                $json['indexid'] = $d['id'];
                $json['churchname'] = '';
                array_push($myStats['rows'],$json);
                $eventbreak = $d['EventID'];
                $eventpointer = $rCount;
                $rCount++ ;
                $eCount++ ;
               }        
                     $ratingStack = $d['RatingStack'];
                     print_r("Rating Stack: ".$ratingStack." \n");
                     $rids = explode("|", $ratingStack);
                     foreach($rids as $ratingid):
                      if(!empty($ratingid)) {
                        $ratid = (int)$ratingid;
                       if($ratid > 0 ) {
                        print_r("Rating ID: ".$ratid." \n");
                       $xrx = $cn->prepare('Select ChurchID, EventID as eventid,RatingID as ratingid, Category as category, Score as score,
                        rank, VisitorOnly as visitor, MemberOnly as member from ht.tblUserRating where RatingID = :ri');
                       $xrx->bindParam(':ri',$ratid,PDO::PARAM_INT);
                       $xrx->execute();
                       $r = $xrx->fetch(PDO::FETCH_ASSOC);
                       if($r) {
                       	print_r("RatingID: ".$ratingid." found \n"); 
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
                         	$json['_parentid'] = $eventpointer;
                         	$json['category'] = $r['category'];
                         	$json['eventid'] = $r['eventid'];
                         	$json['score'] = $r['score'];
                         	$json['type'] = "rating";
                         	$json['id'] = $rCount;
                         	$json['ratingid'] = $ratid;
                         	$json['indexid'] = $d['id'];
                         	$json['state'] = '';
                         	$json['eventname'] = '';
                         	array_push($myStats['rows'],$json);
                         	$qCount++ ;
                         	$rCount++ ;
                         }
                       }
                       $xrx->closeCursor();
                       }
                      }
                      endforeach;

     endforeach;
     $mStats['total'] = $eCount;
     $myStats['footer']['Events'] = $eCount;
     $myStats['footer']['Categories'] = $qCount;
     $myStats['footer']['Churches'] = $cCount;
     $myStats['footer']['TotalRows'] = $rCount;
     echo json_encode($myStats);
   } else {
//      ChromePhp::warn('Query Unsuccessful');
      print_r("Query Unsuccessful \n");
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
//   ChromePhp::warn($c['error']);
 print_r($c['error']." \n");
   echo json_encode($c);
   die();
 }
function checkstate($c,$s) {
//	ChromePhp::warn('City: '.$c.' State: '.$s);
   if(!empty($c) && !empty($s)) {
     return $c.', '.$s;	
 } else {
  return $c;	
 }			
}	
	 
?>
