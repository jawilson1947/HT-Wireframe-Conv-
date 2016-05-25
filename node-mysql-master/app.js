//app.js
var mysql = require('mysql');
var connection = mysql.createConnection({host:'127.0.0.1', user:'root', password:'DanaDenyse32', database:'ht'});
connection.connect(function(err) {if(err) {console.error('Error connecting: '+ err.stack); return;});
console.log('Connected as id ' + connection.threadid);
connection.query('select lastname from ht.tblMember where UserID = 43', function(err,rows,fields) if(err) throw err);
console.log('Result is: ', rows[0].lastname);
connection.end();

