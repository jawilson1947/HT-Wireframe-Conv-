var express = require('express');
var mongoose = require('mongoose');




var app = express();

app.get('/', function(req,res) {
 res.status(200).send('Working');
 });

app.listen(3000);
console.log('Api is running on Port 3000');