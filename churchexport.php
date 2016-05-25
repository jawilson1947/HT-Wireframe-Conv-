<?php
//churchexport.php
//exports ht.tblchurchtable to json for import to Mongo
  $username = 'root';
  $password = 'DanaDenyse32';
  $dbname = 'ht';
  $host = '127.0.0.1';
try {
$myfile = "./htApp/files/churches.json";
$flatfile = "churches.txt";
$handle = fopen($flatfile, 'w') or die("Cannot open ".$flatfile." \n");
$arr_data = array();
$jsondata = file_get_contents($myfile);
$arr_data = json_decode($jsondata, true);
print_r("church.json file ready for use \n");
date_default_timezone_set('America/Chicago');
$today = date('Y-m-d H:i:s');
print_r("Opening Data Connection \n");
$db = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
$row=$db->prepare("select ChurchID as churchid, 
 churchname, 
 domain, 
 address, 
 IFNULL(city,'') as city,
 IFNULL(zipcode,'') as zipcode,
 IFNULL(state,'') as state, 
 IFNULL(phone,'') as phone, 
 IFNULL(country,'') as country, 
 IFNULL(GoogleMapPlaceID,'') as googlemapplaceid, 
 IFNULL(email,'') as email,
 IFNULL(DefaultEventsCreated,false) as defaulteventscreated,
 IFNULL(DefaultRatingsCreated,false) as defaultratingscreated,
 IFNULL(website,'') as website,
 datecreated
 from ht.tblchurch
 where DefaultEventsCreated is true");  
$row->execute();
$json_data=array();
$json_stats = array();
$json_events = array();
$totalcount = 0;
foreach($row as $rec){  
  $totalcount++;
  $churchJson = $arr_data;
  $json_array['churchid']=(int)$rec['churchid'];
  $json_array['domain'] = $rec['domain'];  
  $json_array['churchname']=$rec['churchname'];
  $json_array['address'] = $rec['address'];  
  $json_array['city']=$rec['city'];  
  $json_array['state']=$rec['state'];  
  $json_array['zipcode']=$rec['zipcode'];  
  $json_array['phone']=$rec['phone'];  
  $json_array['country']=$rec['country'];
  $json_array['website']=$rec['website'];
  $json_array['contactemail'] = $rec['email'];  
  $json_array['googlemapplaceid']=$rec['googlemapplaceid'];  
  $json_array["created_on"] = $rec['datecreated'];
  $json_array["updated_at"] = $today;
  $json_stats['hasdefaultevents']=(int)$rec['defaulteventscreated'];
  $json_stats["membercount"] = 0;
  $json_stats["surveysample"] =  0;
  $json_stats["averageeventscore"] =  0;
  $json_stats["membersurveyscore"] = 0;
  $json_stats["peersurveyscore"] = 0;
  $churchJson['profile'] = $json_array;
  $churchJson['stats'] = $json_stats;
 // get events
  $esql = "select EventID,EventName,Recurring,rank,startdate,enddate,`interval`,IFNULL(lastratingdate,now()) as lastratingdate,
            IFNULL(AverageRating,0) as average,IFNULL(RatingCount,0) as rc,
            `Online`, Enabled, hyperlink, `default` from ht.tblEvents where ChurchID = ".$rec['churchid']." order by EventID";	
  $evRow = $db->prepare($esql);
  $evRow->execute();
  $eCount = 0;
  $json_events = array();
  foreach($evRow as $e) {
     $eCount ++;		
     $_events = array('_index' => (int)$eCount,
                                  'eventid' => (int)$e['EventID'],
                                  'eventname' => $e['EventName'],
                                  'rank' => (int)$e['rank'],
                                  'interval' => (int)$e['interval'],
                                  'default' => (int)$e['default'],
                                  'startdate' => $e['startdate'],
                                  'enddate' => $e['enddate'],
                                  'lastrating_at' => $e['lastratingdate'],
                                  'created_on' => $today,
                                  'averagescore' => (int)$e['average'],
                                  'ratingscount' => (int)$e['rc'],
                                  'onlineevent' => (int)$e['Online'],
                                  'hyperlink' => $e['hyperlink'],
                                  'enabled' => (int)$e['Enabled'],
                                  'questions' => array());
     $qsql = "select Category, CategoryID, datecreated,DontTallyIfZero,rank,VisitorOnly,MemberOnly from ht.tblEventRatings
               where ChurchID = ".$rec['churchid']." and EventID = ".$e['EventID'];
     $qRow = $db->prepare($qsql);
     $qRow->execute();
     $qCount = 0 ;
     foreach($qRow as $q) {         
        $qCount++;
        $qArr = array('question' => $q['Category'],
        	                  '_index' => (int)$qCount,
         	                  'dtz' => (int)$q['DontTallyIfZero'],
         	                  'visitoronly' => (int)$q['VisitorOnly'],
         	                  'memberonly' => (int)$q['MemberOnly'],
         	                  'rank' => (int)$q['rank'],
         	                  'datecreated' => $q['datecreated'],
         	                  'categoryid' => (int)$q['CategoryID']);
        array_push($_events['questions'],$qArr);
     }
     $qRow->closeCursor();
     array_push($json_events, $_events);    
  }
  $evRow->closeCursor();
  $churchJson['events'] = $json_events;
  $churchdata = json_encode($churchJson); 
  print_r($rec['churchname']." ".$rec['city']."  - ".$eCount." events found. \n");
  fwrite($handle, $churchdata." \n");
 }
 fclose($handle); 
 print_r($totalcount." Church Records Exported \n");
 die();   
}catch (PDOException $e) {
   print_r("Connection attempt Bombed \n");
   print_r($e->getMessage()." \n");
}	
?>