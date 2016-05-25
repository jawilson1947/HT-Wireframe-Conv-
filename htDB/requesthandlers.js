//requesthandlers.js
//var exec = require("child_process").exec;
var querystring = require("querystring"),
    fs = require("fs"),
    formidable = require("formidable");
var newphoto = "";

function start(response) {
console.log("requesthandlers.start:Request handler 'start' was called");	
	
 var body = '<html>'+'<head>'+'<meta http-equiv="Content-Type" content="text/html; '+'charset=UTF-8" />'+'</head>'+'<body>'+
'<div style="border:1px solid red;">'+ '<form action="/upload" enctype="multipart/form-data" method="post">'+'<input type="file" name="upload"><br>'+
'<input type="text" name="user" size="20" placeholder="user id" value="">'+'<br><input type="submit" value="Upload file" />'+'</form></div>'+'</body>'+'</html>';

//response.writeHead(200, {"Content-Type": "type/html"});
response.write(body);
response.end();


/* console.log("Request handler 'start' was called.");

 exec("ls -lah", function(error, stdout, stderr) {
   response.writeHead(200, {"Content-Type": "text/plain"});
   response.end(stdout);
  });	
 
/* 
/*
 function sleep(milliSeconds) {
  var startTime = new Date().getTime();
  while (new Date().getTime() < startTime + milliSeconds);	
  }	
 
  sleep(10000);
  return "Hello Sleepy Head";
  */
 }

function upload(response, request) {
 console.log("Request handler 'upload' was called");
 
 var form = new formidable.IncomingForm();
 
 console.log("requesthandlers.upload: about to parse");
  form.parse(request, function(error, fields, files) {
   console.log("parsing done");	
   console.log("User Id: " + fields['user']);
   console.log("files are: " + files.upload.path);
   newphoto = "server/php/files/"+fields['user'] + ".jpg";
   console.log("requesthandlers.upload: New file name is: " + newphoto); 
   fs.rename(files.upload.path, newphoto, function(error) {
   	 if (error) {
   	 console.log("requesthandlers.upload.fs.rename Error encountered in requesthandlers.fs.rename function");	
   	   fs.unlink(files.upload.path);
   	   fs.rename(files.upload.path, newphoto);   	 	
   	 } 	
   });
   
   response.writeHead(200, {"Content-Type": "text/html"});
   response.write("<b>received image:</b><br>");
   response.end("<img src='/show' />"); 
 }); 
}

function show(response) {
 console.log("requesthandlers.show: request handler 'show' was called.");
 response.writeHead(200, {"Content-Type": "image/jpg"});
 fs.createReadStream(newphoto).pipe(response);	
}	
function photo(response) {
console.log("requesthandlers.photo:Request handler 'photo' was called");	
	
 var body = '<html>'+'<head>'+'<meta http-equiv="Content-Type" content="text/html; '+'charset=UTF-8" />'+'</head>'+'<body>'+
'<div style="border:1px solid red;">'+ '<form action="/upload" enctype="multipart/form-data" method="post">'+'<input type="file" name="upload"><br>'+
'<input type="text" name="user" size="20" placeholder="user id" value="">'+'<br><input type="submit" value="Upload file" />'+'</form></div>'+'</body>'+'</html>';

 response.end(body);
}
	
exports.start = start;
exports.upload = upload;
exports.show = show;
exports.photo = photo;	