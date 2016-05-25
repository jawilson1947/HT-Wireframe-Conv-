<?php
//getuserratingindex.php
//gets the user index record for ratings done'
include 'ChromePhp.php';
//ChromePhp::warn('getuserratingindex');
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
// $pkg = json_decode($_POST["info"]);
// $userid = $pkg->userid;
$userid = 24;
  $sql = 'select T1.churchname,city,state,country,T1.ChurchID,T2.EventName,T2.EventID,Category,IFNULL(T3.Score,0) as score,T3.EventDate, 
          IFNULL(T2.Score,0) as totalscore,T3.RatingID from ht.tblchurch T1 join ht.tblIndex T2 on T2.ChurchID = T1.ChurchID 
          join ht.tblUserRating T3 on T3.EventID = T2.EventID where T3.UserID = :ui order by T1.churchname,T2.EventName, T3.EventDate desc, T3.rank'; 
  $myStats = array('churchid' => 0,
                     'churchname' => '',
                     'city' => '',
                     'state' => '',
                     'country' => '',
                     'children' => array('churchid' => 0,
                                         'eventname' => '',
                                         'eventdate' => '',
                                         'totalscore' =>'',
                                         'eventid' =>0,
                                         'children' => array('eventid' => 0,
                                                             'ratingid' =>0,
                                                             'category' => '',
                                                             'score' => 0)
                                                             )
                                                     );        
  
//ChromePhp:warn('Matrix Created!');  
// print_r("Matrix Created \n");                                                                
  $xsql = $cn->prepare($sql);
  $xsql->bindParam(':ui',$userid,PDO::PARAM_INT);
//  ChromePhp::warn('SQL : '.$sql);
  if($xsql->execute()) {
  	$data = $xsql->fetchALL(PDO::FETCH_ASSOC);
//     ChromePhp::warn('Search is successful');
print_r("Search is Successful \n");
     $rCount = 0;
     $cCount = -1;
     $eCount = -1;
     $sCount = 0;
     $churchbreak = 0;
     $eventbreak = 0;
    foreach($data as $d):
 /*    if($rCount == 0) {
     	$churchbreak = $d['ChurchID'];
     	$eventbreak = $d['EventID'];
     	$myStats[0]['churchid'] = $churchbreak;
     	$myStats[0]['churchname'] = $d['churchname'];
     	$myStats[0]['city'] = $d['city'];
     	$myStats[0]['state'] = $d['state'];
     	$myStats[0]['country'] = $d['country'];
     	$myStats[0]['children'][0]['churchid'] = $d['ChurchID'];
     	$myStats[0]['children'][0]['eventname'] = $d['EventName'];
     	$myStats[0]['children'][0]['eventdate'] = $d['EventDate'];
     	$myStats[0]['children'][0]['totalscore'] = $d['totalscore'];
     	$myStats[0]['children'][0]['eventid'] = $d['EventID'];
      }
      */
        if($d['ChurchID'] != $churchbreak) {
//            ChromePhp::warn('**Church Name: '.$d['churchname'].' Event Name: '.$d['EventName']);
//            ChromePhp::warn('Record Count: '.$rCount);
//            print_r("**Church Name: ".$d['churchname']." Event Name: ".$d['EventName']." \n");
//            print_r("Record Count: ".$rCount." \n");
            $cCount++ ;
        	$sCount = 0;
        	$ecount = 0;
        	$churchbreak = $d['ChurchID'];
        	$myStats[$cCount]['churchid'] = $churchbreak;
     	    $myStats[$cCount]['churchname'] = $d['churchname'];
     	    $myStats[$cCount]['city'] = $d['city'];
     	    $myStats[$cCount]['state'] = $d['state'];
     	    $myStats[$cCount]['country'] = $d['country'];
            $eventbreak = $d['EventID'];
            $myStats[$cCount]['children'][$eCount]['churchid'] = $d['ChurchID'];
            $myStats[$cCount]['children'][$eCount]['eventname'] = $d['EventName'];
            $myStats[$cCount]['children'][$eCount]['eventdate'] = $d['EventDate'];
            $myStats[$cCount]['children'][$eCount]['totalscore'] = $d['totalscore'];
            $myStats[$cCount]['children'][$eCount]['eventid'] = $eventbreak;

        }
        
        if($d['EventID'] != $eventbreak) {
//         ChromePhp::warn('Church Name: '.$d['churchname'].' **Event Name: '.$d['EventName']);
//         ChromePhp::warn('Record Count: '.$rCount);
//         print_r("Church Name: ".$d['churchname']." **Event Name: ".$d['EventName']." \n");
//         print_r("Record Count: ".$rCount." \n");
           $myStats[$cCount]['children'][$eCount]['churchid'] = $d['ChurchID'];
            $myStats[$cCount]['children'][$eCount]['eventname'] = $d['EventName'];
            $myStats[$cCount]['children'][$eCount]['eventdate'] = $d['EventDate'];
            $myStats[$cCount]['children'][$eCount]['totalscore'] = $d['totalscore'];
            $myStats[$cCount]['children'][$eCount]['eventid'] = $eventbreak;
         $eCount++ ;
         $sCount = 0;
         }
     	$myStats[$cCount]['children'][$eCount]['children'][$sCount]['eventid'] = $d['EventID'];
     	$myStats[$cCount]['children'][$eCount]['children'][$sCount]['ratingid'] = $d['RatingID'];
     	$myStats[$cCount]['children'][$eCount]['children'][$sCount]['category'] = $d['Category'];
     	$myStats[$cCount]['children'][$eCount]['children'][$sCount]['score'] = $d['score'];
       $sCount++ ;
       $rCount++ ;
     endforeach;
     echo json_encode($myStats);
   } else {
//      ChromePhp::warn('Query Unsuccessful');
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
//   ChromePhp::warn($c['error']);
//  print_r($c['error']." \n");
   echo json_encode($c);
   die();
 }
 
?>