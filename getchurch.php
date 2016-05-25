<?php
//if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
//getchurch.php
	
  include 'ChromePhp.php';
  ChromePhp::warn('getchurch.php');
  $username = 'root';
  $password = 'DanaDenyse32';
  $dbname = 'ht';
  $host = '127.0.0.1';
  $info = array_fill_keys(array('status', 'action', 'error', 'sql'), '');
 try {
 	    ChromePhp::warn("Opening Data Connection");
 	    $cn = new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
        $pkg = json_decode($_POST["info"]);
         $churchname = $pkg->name;

          $sql = "select churchname, domain, address,city, state as province,address,phone,zipcode as postalcode,ChurchID as id,country,website,DefaultEventsCreated as dvc,DefaultRatingsCreated as drc,EventRatingCount as erc,GoogleMapPlaceID as placeid,MemberCount as mc,'ok' as status from ht.tblchurch where lower(churchname) like '%".strtolower($churchname)."%' order by state,churchname";
          ChromePhp::warn($sql);        
            $stmt = $cn->prepare($sql);
          ChromePhp::warn('Query submitted');
            $stmt->execute(); 
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($results) {
            ChromePhp::warn('Count of dbfield is: '. $stmt->rowCount()); 
            echo json_encode($results);
          } else {
                     ChromePhp::warn($churchname.' not found');
                     array_push($info, array('status' => 'no',
                                             'action' => 'looking for '.$churchname,
                                             'error' => 'Not found',
                                             'sql' => $sql));
                  echo json_encode($info);
          }
   }catch (PDOException $e) {
      ChromePhp::warn('Query Unsuccessful');
                      $info = array_fill_keys(array('status', 'action', 'error', 'sql'), '');
                       array_push($info, array("status" => "bombed!",
                                               "action" => "church lookup",
                                               "error" => $e->getMessage(), 
                                               "sql" => $sql));
      echo json_encode($info);
	
  }	
 
?>