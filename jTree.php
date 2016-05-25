<?php
//jtree.php
//gets the user index record for ratings done'
include 'ChromePhp.php';
print_r('getuserindex.php');
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
//$userid = $pkg->id;
$userid = 43;
  $sql = 'select churchname,EventName,EventID,ChurchID,Score,EventDate,CommentID from ht.tblIndex where UserID = :ui order by churchname, EventDate desc'; 
   $myStats = array(); 
   $myChurch = array();
   $myEvent = array();                                                         
  $xsql = $cn->prepare($sql);
  $xsql->bindParam(':ui',$userid,PDO::PARAM_INT);
//  print_r('SQL : '.$sql);
  if($xsql->execute()) {
  	$data = $xsql->fetchALL(PDO::FETCH_ASSOC);
     print_r("Search is successful \n");
//print_r("Search is Successful \n");
     $rCount = 0;
     $cCount = 0;
     $churchbreak = 0;
     $eventbreak = 0;
     foreach($data as $d):
     if($rCount == 0) {
     	$churchbreak = $d['ChurchID'];
     	print_r($d['churchname']." \n");
     	$myChurch = array('churchid' => $d['ChurchID'],
     	                  'eventid' => $d['EventID'],
     	                  'commentid' => $d['CommentID'],
     	                  'churchname' => $d['churchname'],
     	                  'eventname' => $d['EventName'],
     	                  'eventdate' => $d['EventDate'],
     	                  'score' => $d['Score'],
     	                  'id' => $rCount + 1,
     	                  'events' => array());
      	                   $eCount = 0;
      	                   
     }
        if($d['ChurchID'] != $churchbreak && $rCount > 0) {
          array_push($myStats,$myChurch);	
          print_r($d['churchname']." \n");
//         print_r($d['churchname']);
     	 $myChurch = array('churchid' => $d['ChurchID'],
     	                  'eventid' => $d['EventID'],
     	                  'commentid' => $d['CommentID'],
     	                  'churchname' => $d['churchname'],
     	                  'eventname' => $d['EventName'],
     	                  'eventdate' => $d['EventDate'],
     	                  'score' => $d['Score'],
     	                  'id' => $rCount + 1,
     	                  'events' => array());
     	                  $cCount ++;
    	                  $churchbreak = $d['ChurchID'];
    	                  
                } else {
                	if($rCount > 0) {
                       $myEvent =array('churchid' => $d['ChurchID'],
     	                  'eventid' => $d['EventID'],
     	                  'commentid' => $d['CommentID'],
     	                  'churchname' => $d['churchname'],
     	                  'eventname' => $d['EventName'],
     	                  'eventdate' => $d['EventDate'],
     	                  'score' => $d['Score'],
     	                  'id' => $rCount + 1);
     	                   array_push($myChurch['events'],$myEvent);   
                    }
        }

      $rCount++ ;
     endforeach;
     array_push($myStats,$myChurch);
     echo json_encode($myStats);
   } else {
//      print_r('Query Unsuccessful');
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
  $c['error'] =  $e->getMessage();
  $c['sql'] = $sql;
//   print_r($c['error']);
  print_r($c['error']." \n");
   echo json_encode($c);
   die();
 }
 
?>
