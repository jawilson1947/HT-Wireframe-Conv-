<html>
	<head>
		<title>Holy tomatoes Church Data Manager</title>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&signed_in=true"></script>
    <script type="text/javascript" src="jeasyui/jquery.min.js"></script>
   <script type="text/javascript" src="jeasyui/jquery.easyui.mobile.js"></script>
   <script type="text/javascript" src="jeasyui/easyui.min.js"></script>
   <script type="text/javascript" src="jeasyui/easyloader.js"></script>
   <script type="text/javascript" src="js/js.cookie.js"></script>
    <script type="text/javascript" src="js/htlib.js"></script>
 	<link rel="stylesheet" type="text/css" href="jeasyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="jeasyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="jeasyui/demo.css">
	</head>
	<body>
		<table id="dg" title="My Users" style="width:550px;height:250px"
        url="jeasyui/get_users.php"
        toolbar="#toolbar"
        fitColumns="true" singleSelect="true">
    <thead>
        <tr>
            <th field="firstname" width="50">First Name</th>
            <th field="lastname" width="50">Last Name</th>
            <th field="phone" width="50">Phone</th>
            <th field="email" width="50">Email</th>
        </tr>
    </thead>
</table>
<div id="toolbar">
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newItem()">New</a>
    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyItem()">Destroy</a>
</div
<script type="text/javascript">
	$(document).ready(function() {
$('#dg').datagrid({
    view: detailview,
    detailFormatter:function(index,row){
        return '<div class="ddv"></div>';
    },
    onExpandRow: function(index,row){
        var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
        ddv.panel({
            border:false,
            cache:true,
            href:'show_form.php?index='+index,
            onLoad:function(){
                $('#dg').datagrid('fixDetailRowHeight',index);
                $('#dg').datagrid('selectRow',index);
                $('#dg').datagrid('getRowDetail',index).find('form').form('load',row);
            }
        });
        $('#dg').datagrid('fixDetailRowHeight',index);
    }
  });		
});
</script>
	</body>
</html>