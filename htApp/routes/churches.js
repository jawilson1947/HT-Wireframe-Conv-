//churches.js
var express = require('express');
var http = require('http');
var cors = require('cors');
var MongoClient = require('mongodb').MongoClient;
//var ObjectId = require('mongodb').ObjectID;
var util = require('util');
var ChurchTemplate = require('../files/churches.json');
var churches = express.Router();
var bodyParser = require('body-parser');
churches.options('*', cors());
var merge = require('merge'), original, cloned;
var trigger = require('mongo-triggers');
var ip = require('ip');
/* get root of /churches */
churches.get('/', function(req,res) {
 console.log('churches.js.get/ (root)');
 res.send({"go":"Panthers"});	
});
churches.get('/ip', cors(), function(req, res) {
   console.log('members.js.get/ip');
   res.setHeader('Content-Type', 'application/json');
   console.log('localhost ip: ' + ip.address());
   res.status(200).send({"ip":ip.address(),"status":"ok"});	
	
});
// get basic church info using churchid sent from client
churches.get('/getevents/:id', cors(), function(req, res) {
   res.setHeader('Content-Type', 'application/json');
   var churchid = parseInt(req.params.id);
   console.log('Looking for church events for id: ' + churchid);
   var dbstatus = {"status":"no","action":"Church data assignmen","error":"","sql":""}; 
   var assert = require('assert');
   var MongoUrl = 'mongodb://localhost/ht';
   var db = MongoClient.connect(MongoUrl, function(dberr, db) {
      assert.equal(null, dberr);
      if(dberr) {
         console.log('!!** Error opening database: ' + dberr);
         res.status(200).send([{"status":"dbError","error":dberr}]);
         return;
      }
      console.log('Database opened successfully');
      var ChurchData = db.collection('churches');    
      ChurchData.findOne({"profile.churchid": churchid}, {"events":1}, function(error, doc) {
         if(error) {
         	console.log('Error encountered while looking for ChurchID: ' + error);
            res.status(200).send([{"status":"Unable to pre-validate","error": error}]);
            db.close();
            return;
          }
          if (doc) { 
                console.log('First Event: ' + doc.events[0].eventname);
                dbstatus.status="ok";
                res.status(200).send(doc.events);
                db.close();
                return;
            } else {
             console.log('No record matching ChurchID: ' + churchid);	
             res.status(200).send([{"status":"no"}]);
             db.close();
             return;
            }
         });
     });
 });
churches.get('/getdata/:id', cors(), function(req, res) {
   res.setHeader('Content-Type', 'application/json');
   var churchid = parseInt(req.params.id);
   console.log('Looking for church data for id: ' + churchid);
   var dbstatus = {"status":"no","action":"Church data assignmen","error":"","sql":""};
   var assert = require('assert');
   var MongoUrl = 'mongodb://localhost/ht';
   var db = MongoClient.connect(MongoUrl, function(dberr, db) {
      assert.equal(null, dberr);
      if(dberr) {
         console.log('!!** Error opening database: ' + dberr);
         res.status(200).send({"status":"dbError","error":dberr});
         return;
      }
      console.log('Database opened successfully');
      var ChurchData = db.collection('churches');
      ChurchData.findOne({"profile.churchid": churchid}, function(error, doc) {
         if(error) {
         	console.log('Error encountered while looking for ChurchID: ' + error);
            res.status(200).send({"status":"Unable to pre-validate","error": error});
            db.close();
            return;
          }
          if (doc) {
                console.log(doc.profile.churchname + ' found');
                dbstatus.status="ok";
                dbstatus = merge( doc.profile, dbstatus)
                res.status(200).send(dbstatus);
                db.close();
                return;
            } else {
             console.log('No record matching ChurchID: ' + churchid);
             res.status(200).send({"status":"no"});
             db.close();
             return;
            }
         })
     });
 })
//handle churches inserts/updates
churches.post('/manage/register', cors(), function(req, res) {
    console.log("churches.js.router.post/manage"); 
    var church = req.body;
    console.log(church);
    res.setHeader('Content-Type', 'application/json');
    console.log('Incoming Domain is: ' + church.domain + ' - Church ID is '+ church.churchid); 
   // create the object
    var today = new Date();
    console.log('Current date is: ' + today);	
    var dbstatus = {"status":"no","action":"Church data assignmen","error":"","sql":""};	
//make sure email does not already exist
//************************
    console.log('Validating  Incoming Church Assignment');
    var assert = require('assert');
    var MongoUrl = 'mongodb://localhost/ht';
// fire in the hole!
// ensure that we are connected
    var db = MongoClient.connect(MongoUrl, function(dberr, db) {
        assert.equal(null, dberr);
        if(dberr) {
            console.log('!!** Error opening database: ' + dberr);
            res.status(200).send({"status":"dbError","error":dberr});
            return;
        }
        var ChurchData = db.collection('churches');    
        ChurchData.findOne({"profile.domain": church.domain}, function(error, doc) {
            if(error) {
                db.close();
                res.status(200).send({"status":"Unable to pre-validate","error": error});
                return;
            }
            if(doc) { 
                dbstatus.status="ok";
                console.log('Record for church domain: "' + church.domain + '" found in database');
                console.log(doc.profile);
                dbstatus = merge( doc.profile, dbstatus)
                console.log('Incoming ChurchID  ' + doc.profile.churchid );
                res.status(200).send(dbstatus);
                db.close();
            } else {
                ChurchTemplate.profile.domain = church.domain;
                ChurchTemplate.profile.googlemapplaceid = church.PlaceID;
                ChurchTemplate.profile.churchname = church.ChurchName;
                ChurchTemplate.profile.address = church.address;
                ChurchTemplate.profile.city = church.city;
                ChurchTemplate.profile.state = church.province;
                ChurchTemplate.profile.zipcode = church.postalcode;
                ChurchTemplate.profile.phone = church.phone;
                ChurchTemplate.profile.country = church.country;
                ChurchTemplate.profile.website = church.website;
                ChurchTemplate.profile.created_on = today;
                ChurchTemplate.profile.updated_at = today;
                console.log('Inserting new record for: '+ ChurchTemplate.profile.churchname);
                nextChurchID = 0;
                nextEventID = 0;
                nextCategoryID = 0;
                var ChurchData = db.collection('churches');
                ChurchData.findOneAndUpdate({"keycontrol.churchid": {$gt: 0}},
                    {$inc: {"keycontrol.churchid": 1}},
                    {new: false}, function (error, result) {
                    if (result) {
                        nextChurchID = result.value.keycontrol.churchid;
                        ChurchData.findOneAndUpdate({"keycontrol.eventid": {$gt: 0}},
                            {$inc: {"keycontrol.eventid": 5}},
                            {new: false}, function (error, result) {
                            if (result) {
                                nextEventID = result.value.keycontrol.eventid;
                                console.log('Next Event ID starts at: ' + nextEventID);
                                ChurchData.findOneAndUpdate({"keycontrol.categoryid": {$gt: 0}},
                                    {$inc: {"keycontrol.categoryid": 45}},
                                    {new: false}, function (error, result) {
                                    if (result) {
                                        nextCategoryID = result.value.keycontrol.categoryid;
                                        console.log('Next Category ID starts at: ' + nextCategoryID);
                                        ChurchTemplate.profile.churchid  = nextChurchID;
                                        console.log('Document new ChurchID returned: ' + ChurchTemplate.profile.churchid);
// stuff the new Church Document Events with new event ids
                                        for(i=0;i<ChurchTemplate.events.length;i++){
                                            ChurchTemplate.events[i].eventid = nextEventID;
                                                for(j=0;j<ChurchTemplate.events[i].questions.length;j++) {
                                                    ChurchTemplate.events[i].questions[j].categoryid = nextCategoryID;
                                                    nextCategoryID ++;
                                                }
                                                nextEventID++;
                                            }
                                            ChurchTemplate._id = require('mongodb').ObjectID();
                                            console.log('New Object ID: ' + ChurchTemplate._id);
                                            ChurchData.insertOne(ChurchTemplate, function(error, doc) {
                                            if(doc) {
                                                console.log('Data Insertion for ' + church.ChurchName + ' succesful!');
                                                dbstatus.status = "ok";
                                                dbstatus = merge(dbstatus, ChurchTemplate.profile);
                                                console.log('Sending json package back to client');
                                                res.status(200).send(dbstatus);
                                                db.close();
                                            } else {
                                                dbstatus.status = "Unable to insert " + ChurchTemplate.profile.churchname;
                                                dbstatus.action = "Add Church Record";
                                                dbstatus.error = error.message;
                                                res.status(200).send(dbstatus);
                                            }
                                        });
                                    } else {
                                        dbstatus.status = "Unable to Setup Default Categories for: " + ChurchTemplate.profile.churchname;
                                        res.status(200).send(dbstatus);
                                    }
                                });
                            } else {
                                dbstatus.status = "Unable to Setup Default Events for: " + ChurchTemplate.profile.churchname;
                                res.status(200).send(dbstatus);
                            }
                        });
                    } else {
                        dbstatus.status = "Unable to determine subsequent ID for: " + ChurchTemplate.profile.churchname;
                        res.status(200).send(dbstatus);
                    }
                });
            }
        });
    });
});
churches.get('/ratingstats/:id', cors(), function(req, res) {
    res.setHeader('Content-Type', 'application/json');
    var churchid = parseInt(req.params.id);
    console.log('Looking for church statistical data for id: ' + churchid);
    var dbstatus = {"status":"no","action":"Church data assignmen","error":"","sql":""};
    var churchstats = {"rcount": 0, "ecount": 0, "mcount": 0, "qcount": 0, "score": 0, "status":"","error":""};
    var assert = require('assert');
    var MongoUrl = 'mongodb://localhost/ht';
    var db = MongoClient.connect(MongoUrl, function(dberr, db) {
        assert.equal(null, dberr);
        if (dberr) {
            console.log('!!** Error opening database: ' + dberr);
            res.status(200).send({"status": "dbError", "error": dberr});
            return;
        }
        var MemberData = db.collection('members');
// find number of  persons who have rated events for this church
        MemberData.aggregate([{$match: {"member.ratings.churchid": churchid}},
            {$unwind: "$member.ratings"},
            {$match: {"member.ratings.churchid":churchid}},
            {$group: {_id: "$member.ratings.userid", count:{$sum: "$member.ratings.score"}}}],function(err,result) {
            if (result) {
                var score = 0;
                churchstats.mcount = result.length;
                console.log('Member Count is:' + churchstats.mcount);
                for (i = 0; i < result.length; i++) {
                    score += result[i].count;
                }
                // get the active event count and number of ratings for this church
                MemberData.aggregate([{$match: {"member.ratings.churchid": churchid}},
                    {$unwind: "$member.ratings"},
                    {$match: {"member.ratings.churchid": churchid}},
                    {$group: {_id: "$member.ratings.eventid", count: {$sum:1}}}], function(err,result){
                    if(result) {
                        churchstats.ecount=result.length;
                        for (i=0; i < result.length; i++) {
                            churchstats.rcount+=result[i].count;
                        }
                        console.log('Rating Count is: ' + churchstats.rcount);
                        if (churchstats.rcount > 0) {
                            churchstats.score=score / churchstats.rcount;
                        } else {
                            churchstats.score = 0;
                        }
                        console.log('Average score: ' + churchstats.score);
                        // get number of questions surveyed for this church
                        MemberData.aggregate([{$match: {"member.ratings.churchid": churchid}},
                            {$unwind: "$member.ratings"},
                            {$match: {"member.ratings.churchid": churchid}},
                            {$unwind: "$member.ratings.questions"}], function(err,result) {
                            if(result) {
                                churchstats.qcount = result.length;
                                console.log("Questions survey count: "+ churchstats.qcount);
                            } else {
                                console.log('Error calculating Question Count: '+ err.message);
                                churchstatus.error = err.message;
                                res.status(200).send(churchstats);
                                db.close();
                                return;
                            }
                            churchstats.status="ok";
                            db.close();
                            res.status(200).send(churchstats);
                            return;
                        });
                    }else {
                        console.log('Error retrieving Event Stats: Error: '+ err.message);
                        churchstats.error = err.message;
                        db.close();
                        res.status(200).send(churchstats);
                        return;
                    }
               });
            } else {
                console.log('Member Count error; ' + err.message);
                churchstats.error = err.message;
                db.close();
                res.status(200).send(churchstats);
                return;
            }
        });
    });
});

module.exports = churches;
