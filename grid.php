<html lang="en">
<head>
    <title id="Description">GRID.HTML</title>
    <meta name="description" content="This sample demonstrates how we can bind jQWidgets TreeGrid widget to JSON Data by using jQWidgets DataAdapter plugin.">
    <link rel="stylesheet" href="jq/jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="jq/scripts/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="jq/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="jq/jqwidgets/jqxdata.js"></script>
    <script type="text/javascript" src="jq/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="jq/jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="jq/jqwidgets/jqxdatatable.js"></script>
    <script type="text/javascript" src="jq/jqwidgets/jqxtreegrid.js"></script>
 
     <script type="text/javascript" src="jq/jqwidgets/jqxlistbox.js"></script> 
    <script type="text/javascript" src="jq/jqwidgets/jqxdropdownlist.js"></script> 
    <script type="text/javascript" src="jq/jqwidgets/jqxtooltip.js"></script> 
    <script type="text/javascript" src="jq/jqwidgets/jqxinput.js"></script> 
    <script type="text/javascript" src="js/js.cookie.js"></script>
    <script type="text/javascript" src="jq/scripts/demos.js"></script>
  <script type="text/javascript">
 	
function SetUpGrid(members) {          
            var source =
            {
                dataType: "json",
                dataFields: [
                    { name: 'churchid', type: 'number' },
                    { name: 'churchname', type: 'string' },
                    { name: 'city', type: 'string' },
                    { name: 'state', type: 'string' },
                    { name: 'country', type: 'string' }
                 ],
                hierarchy:
                {
                    keyDataField: { name: 'churchname' },
                    parentDataField: { name: 'churchid' }
                },
                id: 'eventid',
                localData: members
            };
            var dataAdapter = new $.jqx.dataAdapter(source);
            // create Tree Grid
            $("#treeGrid").jqxTreeGrid(
            {
                width: 560,
                source: dataAdapter,
                sortable: true,
                pageable: true,
                editable: true,
                showToolbar: true,
                altRows: true,
                columnsResize: true,
                sortable: true,
                filterable: true,
                filterMode: 'advanced',
                ready: function()
                {
                    $("#treeGrid").jqxTreeGrid('expandRow', '1');
                },
                 pagerButtonsCount: 8,
                toolbarHeight: 35,
                renderToolbar: function(toolBar)
                {
                    var toTheme = function (className) {
                        if (theme == "") return className;
                        return className + " " + className + "-" + theme;
                    }

                    // appends buttons to the status bar.
                    var container = $("<div style='overflow: hidden; position: relative; height: 100%; width: 100%;'></div>");
                    var buttonTemplate = "<div style='float: left; padding: 3px; margin: 2px;'><div style='margin: 4px; width: 16px; height: 16px;'></div></div>";
                     var editButton = $(buttonTemplate);
                    var deleteButton = $(buttonTemplate);
                    var cancelButton = $(buttonTemplate);
                    var updateButton = $(buttonTemplate);
                    container.append(editButton);
                    container.append(deleteButton);
                    container.append(cancelButton);
                    container.append(updateButton);

                    toolBar.append(container);
                     editButton.jqxButton({ cursor: "pointer", disabled: true, enableDefault: false,  height: 25, width: 25 });
                    editButton.find('div:first').addClass(toTheme('jqx-icon-edit'));
                    editButton.jqxTooltip({ position: 'bottom', content: "Edit"});

                    deleteButton.jqxButton({ cursor: "pointer", disabled: true, enableDefault: false,  height: 25, width: 25 });
                    deleteButton.find('div:first').addClass(toTheme('jqx-icon-delete'));
                    deleteButton.jqxTooltip({ position: 'bottom', content: "Delete"});

                    updateButton.jqxButton({ cursor: "pointer", disabled: true, enableDefault: false,  height: 25, width: 25 });
                    updateButton.find('div:first').addClass(toTheme('jqx-icon-save'));
                    updateButton.jqxTooltip({ position: 'bottom', content: "Save Changes"});

                    cancelButton.jqxButton({ cursor: "pointer", disabled: true, enableDefault: false,  height: 25, width: 25 });
                    cancelButton.find('div:first').addClass(toTheme('jqx-icon-cancel'));
                    cancelButton.jqxTooltip({ position: 'bottom', content: "Cancel"});

                    var updateButtons = function (action) {
                        switch (action) {
                            case "Select":
                                deleteButton.jqxButton({ disabled: false });
                                editButton.jqxButton({ disabled: false });
                                cancelButton.jqxButton({ disabled: true });
                                updateButton.jqxButton({ disabled: true });
                                break;
                            case "Unselect":
                                deleteButton.jqxButton({ disabled: true });
                                editButton.jqxButton({ disabled: true });
                                cancelButton.jqxButton({ disabled: true });
                                updateButton.jqxButton({ disabled: true });
                                break;
                            case "Edit":
                                deleteButton.jqxButton({ disabled: true });
                                editButton.jqxButton({ disabled: true });
                                cancelButton.jqxButton({ disabled: false });
                                updateButton.jqxButton({ disabled: false });
                                break;
                            case "End Edit":
                                deleteButton.jqxButton({ disabled: false });
                                editButton.jqxButton({ disabled: false });
                                cancelButton.jqxButton({ disabled: true });
                                updateButton.jqxButton({ disabled: true });
                                break;

                        }
                    }

                    var rowKey = null;
                    $("#treeGrid").on('rowSelect', function (event) {
                        var args = event.args;
                        rowKey = args.key;
                        updateButtons('Select');
                    });
                    $("#treeGrid").on('rowUnselect', function (event) {
                        updateButtons('Unselect');
                    });
                    $("#treeGrid").on('rowEndEdit', function (event) {
                        updateButtons('End Edit');
                    });
                    $("#treeGrid").on('rowBeginEdit', function (event) {
                        updateButtons('Edit');
                    });
                    cancelButton.click(function (event) {
                        if (!cancelButton.jqxButton('disabled')) {
                            // cancel changes.
                            $("#treeGrid").jqxTreeGrid('endRowEdit', rowKey, true);
                        }
                    });

                    updateButton.click(function (event) {
                        if (!updateButton.jqxButton('disabled')) {
                            // save changes.
                            $("#treeGrid").jqxTreeGrid('endRowEdit', rowKey, false);
                        }
                    });

                    editButton.click(function () {
                        if (!editButton.jqxButton('disabled')) {
                            $("#treeGrid").jqxTreeGrid('beginRowEdit', rowKey);
                            updateButtons('edit');

                        }
                    });
                    deleteButton.click(function () {
                        if (!deleteButton.jqxButton('disabled')) {
                            var selection = $("#treeGrid").jqxTreeGrid('getSelection');
                            if (selection.length > 1) {
                                var keys = new Array();
                                for (var i = 0; i < selection.length; i++) {
                                    keys.push($("#treeGrid").jqxTreeGrid('getKey', selection[i]));
                                }
                                $("#treeGrid").jqxTreeGrid('deleteRow', keys);
                            }
                            else {
                                $("#treeGrid").jqxTreeGrid('deleteRow', rowKey);
                            }
                            updateButtons('delete');

                        }
                    });
                },
               columns: [
                  { text: 'Church Name', columnGroup: 'Name', dataField: 'churchname', width: '50%' },
                  { text: 'City', columnGroup: 'Name', dataField: 'city', width: '30%' },
                  { text: 'Province', columnGroup: 'Name', dataField: 'state', width: '10%' },
                  { text: 'Country', columnGroup: 'Name', dataField: 'country', width: '10%' }
                ],
                columnGroups: [
                  { text: person + "'s Event Ratings", name: 'Name' }
                ]
            });
                 $( document ).ajaxComplete(function( event, xhr, settings ) {
                    console.log(settings.url  + ' has completed');
                    console.log( "<i>Triggered ajaxComplete handler. The result is " + xhr.responseText +"</i>");
 
                }); 
             };
     </script>
</head>
<body class='default' id='user'>
   <script type="text/javascript">
        $(document).ready(function () { 
        var members;	
         var online = Cookies.get('peaches');
         if(typeof(online) !="undefined") {
            userStub = $.parseJSON(online);                
            person = userStub.firstname + ' ' + userStub.lastname;
            userid = userStub.id;
         } 
      });      
 </script>
<div id="treeGrid"></div>'
 <form id="frm">
  <input type="hidden" id="data" value="
 <?php
   
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
//$pkg = json_decode($_POST["info"]);
 $pkg = json_decode($_COOKIES["peaches"]);
$userid = $pkg->userid;
//$userid = 24;
  $sql = 'select T1.churchname,city,state,country,T1.ChurchID,T2.EventName,T2.EventID,Category,IFNULL(T3.Score,0) as score,T3.EventDate, 
          IFNULL(T2.Score,0) as totalscore,T3.RatingID from ht.tblchurch T1 join ht.tblIndex T2 on T2.ChurchID = T1.ChurchID 
          join ht.tblUserRating T3 on T3.EventID = T2.EventID where T3.UserID = :ui order by T2.EventName, T3.EventDate desc, T3.rank'; 
$myStats = array();        
  
ChromePhp::warn('UserID is '.$userid);
/*
$churchstats = array('churchid' => 0,
                     'churchname' => '',
                     'city' => '',
                     'state' => '',
                     'country' => '',
                     'children' => array());
$eventstats =  array('churchid' => 0,
                     'eventname' => '',
                     'eventdate' => '',
                     'totalscore' =>'',
                     'eventid' =>0,
                     'children' => array()); 
$ratingstats = array('churchid' => 0,
                     'eventid' => 0,
                     'ratingid' => 0,
                     'category' => '',
                     'score' => 0);
                                    */                          
ChromePhp::warn('Matrix Created!');
  
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
//     print_r("First ChurchID: ".$churchbreak." \n");
//     print_r("First EventID: ".$eventbreak." \n");
       ChromePhp::warn('Church Name: '.$namebreak.' **Event Name: '.$eventnamebreak);
     
    foreach($data as $d):
         $rCount++ ;                       
         if($d['EventID'] != $eventbreak) {
         ChromePhp::warn('Church Name: '.$d['churchname'].' **Event Name: '.$d['EventName']);
         ChromePhp::warn('Record Count: '.$rCount);
         print_r("Church Name: ".$d['churchname']." **Event Name: ".$d['EventName']." \n");
         print_r("Record Count: ".$rCount." \n");
                     $churchstats = array('churchid' => $churchbreak,
                                          'churchname'  => $namebreak,
                                          'city' => $citybreak,
                                          'state' => $statebreak,
                                          'country' => $countrybreak,
                                          'children' => array());
                     $eventstats =  array('churchid' => $churchbreak,
                     'eventname' => $eventnamebreak,
                     'eventdate' => $eventdatebreak,
                     'totalscore' => $totalscorebreak,
                     'eventid' => $eventbreak,
                     'children' => array());
                     array_push($eventstats['children'],$ratingstats); 
                     array_push($churchstats['children'],$eventstats);
                     array_push($myStats,$churchstats); 

$churchstats = array('churchid' => 0,
                     'churchname' => '',
                     'city' => '',
                     'state' => '',
                     'country' => '',
                     'children' => array());
$eventstats =  array('churchid' => 0,
                     'eventname' => '',
                     'eventdate' => '',
                     'totalscore' =>'',
                     'eventid' =>0,
                     'children' => array()); 
$ratingstats = array('churchid' => 0,
                     'eventid' => 0,
                     'ratingid' => 0,
                     'category' => '',
                     'score' => 0);
                     $eventbreak = $d['EventID'];
            	     $churchbreak = $d['ChurchID'];
                     $namebreak = $d['churchname'];
                     $citybreak = $d['city'];
                     $statebreak = $d['state'];
                     $countrybreak = $d['country'];
                     $eventnamebreak = $d['EventName'];
                     $totalscorebreak = $d['totalscore'];
                     $eventdatebreak = $d['EventDate'];
        }
                    
     	$r = array('churchid' => $d['ChurchID'],
     	                        'eventid' => $d['EventID'],
     	                        'ratingid' => $d['RatingID'],
     	                        'category' => $d['Category'],
     	                        'score' => $d['score']);
     	                        
      	  array_push($ratingstats, $r);
     	 
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
//  print_r($c['error']." \n");
   echo json_encode($c);
   die();
 }
 ?>" >
 </form>
 </body>
</html>
