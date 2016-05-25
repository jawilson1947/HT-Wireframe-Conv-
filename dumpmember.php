<?php
//dumpmember.php
//exports ht.tblmember table to json for import to Mongo
$username = 'root';
$password = 'DanaDenyse32';
$dbname = 'ht';
$host = '127.0.0.1';
try {
   $myFile = "./htApp/files/members.json";
   $flatfile = "members.txt";
   $handle = fopen($flatfile,'w') or die("Cannot open file: ".$flatfile." \n");
   $arr_data = array(); // create empty array
//Get data from existing json file
   $jsondata = file_get_contents($myFile);
 // converts json data into array
   $arr_data = json_decode($jsondata, true);
   print_r("member.json file ready for use \n");
   date_default_timezone_set('America/Chicago');
   $today = date('Y-m-d H:i:s');
   print_r("Opening Data Connection \n");
   $db = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
   $row=$db->prepare("select UserID, 
    lastname, 
    firstname, 
    userpsw, 
    IFNULL(gender,'') as gender,
    IFNULL(age,0) as age,
    IFNULL(placeid,'') as placeid, 
    IFNULL(churchname,'') as churchname, 
    IFNULL(churchID,0) as churchid, 
    IFNULL(churchadmin,false) as churchadmin, 
    email from ht.tblMember order by lastname, firstname");  
   $row->execute();
   $memberJson = $arr_data;
   $json_data = array();
   $json_array = array('userid' =>0,
                           'lastname' => "",
                           'firstname' => "",
                           'userpsw' => "",
                           'email' => "",
                           'gender' => "",
                           'age' =>0,
                           'race' =>"",
                           'churchname' => "",
                           'churchid' =>0,
                           'churchadmin' =>false,
                           'placeid' => "",
                           'created_on' => $today,
                           'updated_at' =>$today);
   foreach($row as $rec){  
      $json_array['userid']=(int)$rec['UserID'];  
      $json_array['lastname']=$rec['lastname'];
      $json_array['firstname'] = $rec['firstname'];  
      $json_array['userpsw']=$rec['userpsw'];  
      $json_array['gender']=$rec['gender'];  
      $json_array['age']=(int)$rec['age'];  
      $json_array['churchname']=$rec['churchname'];  
      $json_array['churchid']=(int)$rec['churchid'];  
      $json_array['churchadmin']=(int)$rec['churchadmin'];  
      $json_array['placeid']=$rec['placeid'];
      $json_array['email'] = $rec['email']; 
      $memberJson['member']['bio'] = $json_array;
      print_r($rec['lastname']." ".$rec['firstname']." \n");
          
  // get the questions
      $xsql = "select churchname, T1.ChurchID as churchid, EventName, T1.EventID as eventid, T1.EventDate as eventdate, Score, RatingStack, Comment from ht.tblIndex T1 join ht.tblUserComments T2 on T2.CommentID = T1.CommentID where T1.UserID = ".$rec['UserID']." order by churchname, EventDate DESC";
      $rhRow = $db->prepare($xsql);
      $rhRow->execute();
      if($rhRow) {
      	  $memberJson['member']['ratings'] = array();
    	 $iCount = 0;
         $rhArr = array();
         foreach($rhRow as $ridx) {
            $iCount++ ;
            print_r($ridx['churchname']." \n");
            $rat = array('_index' => (int)$iCount,
                           'churchname' => $ridx['churchname'],
             	           'eventname' => $ridx['EventName'],
             	           'eventdate' => $ridx['eventdate'],
             	           'score' => (int)$ridx['Score'],
                           'daterated' =>  $today,
                           'userid' => (int)$rec['UserID'],
                           'churchid'=> (int)$ridx['churchid'],
                           'eventid' => (int)$ridx['eventid'],
             	           'comments' => $ridx['Comment'],
             	           'questions' => array());
             	           
            $ratingStack = $ridx['RatingStack'];
            $rids = explode("|", $ratingStack);
            foreach($rids as $ratingid) {
               if(!empty($ratingid)) {
                  $ratid = (int)$ratingid;
                  if($ratid > 0 ) {
                     $xrx = $db->prepare('Select Category as category, Score as score from ht.tblUserRating where RatingID = :ri');
                     $xrx->bindParam(':ri',$ratid,PDO::PARAM_INT);
                     $xrx->execute();
                     $r = $xrx->fetch(PDO::FETCH_ASSOC);
                     if($r) {
                     	$q = array('question' => $r['category'],
                     	           'score' => (int)$r['score']);
                       	array_push($rat['questions'],$q);
                     }
                  }   
                  $xrx->closeCursor();
               }   
            }
            array_push($memberJson['member']['ratings'],$rat);
         }    
      } 
      $memberdata = json_encode($memberJson);
      fwrite($handle, $memberdata." \n");
   }   
   fclose($handle);
   print_r("Operation is complete! \n");  
   }catch (PDOException $e) {
      print_r("Connection attempt Bombed \n");
      print_r($e->getMessage()." \n");
  }	
/*           $qsql = "select Category,Score from tblUserRating where UserID = ".(int)$rec['UserID']." and
             ChurchID = ".(int)$ridx['ChurchID']." and EventID = ".(int)$ridx['EventID']." order by rank";
            $qRow = $db->prepare($qsql);
            $qRow->execute();
            if($qRow) {
                foreach($qRow as $q) {
            	  $qu = Array('question' => $q['Category'],'score' => (int)$q['Score']);
             	  array_push($rat['questions'],$qu); 
       	     }
            $qRow->closeCursor();
            array_push($memberJson['member']['ratings'], $rat);
            print_r("    ------- ".$iCount." Rating Indexes found \n");
          }
        }
        $rhRow->closeCursor();
*/
 
?>
