//app.js
var express = require('express');
var path = require('path');
var favicon = require('serve-favicon');
var logger = require('morgan');
var cookieParser = require('cookie-parser');
var bodyParser = require('body-parser');

// Connect to MongoDB
var  MongoClient  = require('mongodb').MongoClient;
var assert = require('assert');
var MongoUrl = 'mongodb://localhost/ht';
// fire in the hole!
var db = MongoClient.connect(MongoUrl, function(err, db) {
// ensure that we are connected
  assert.equal(null, err);
  if(err) {
     return console(err);
  } else {
    console.log('Connected to MongoDB - HT Database successfully');
  } 
  db.close();
});
// make our database accessible to our router
var app = express();

app.use(favicon(__dirname + '/public/images/favicon.ico'));

app.use(function(req, res, next) {
   req.db = db;
   next();
});

var routes = require('./routes/index');
var members = require('./routes/members');
var churches = require('./routes/churches');

// view engine setup
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');

// uncomment after placing your favicon in /public
//app.use(favicon(path.join(__dirname, 'public', 'favicon.ico')));
app.use(logger('dev'));
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));

app.use('/', routes);
app.use('/members', members);
app.use('/churches', churches);

// catch 404 and forward to error handler
app.use(function(req, res, next) {
  var err = new Error('Oops! Not Found');
  err.status = 404;
  next(err);
})

// error handlers

// development error handler
// will print stacktrace
if (app.get('env') === 'development') {
  app.use(function(err, req, res, next) {
    res.status(err.status || 500);
    res.render('Dev Stack Error', {
      message: err.message,
      error: err
    });
  });
}

// production error handler
// no stacktraces leaked to user
app.use(function(err, req, res, next) {
  res.status(err.status || 500);
  res.render(req.Path, {
    message: err.message,
    error: {}
  });
}).listen(8080);
console.log('HT Server listening on port 8080');

module.exports = app;
