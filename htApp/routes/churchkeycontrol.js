/**
 * churchkeycontrol.js
 * class which derives all key controls required for a new record in the churches collection
 * next <churchid> to assign
 * next <eventids> to assign
 * next (categoryids> to assign
 * Created by jawilson on 3/3/16.
 */
var express = require('express');
var http = require('http');
var cors = require('cors');
var MongoClient = require('mongodb').MongoClient;
//var ObjectId = require('mongodb').ObjectID;
var util = require('util');
var ChurchTemplate = require('../files/churches.json');
var churches = express.Router();

var churchkeycontrol = function() {
    churchkeycontrol.prototype.getChurchID = function() {
        var assert = require('assert');
        var MongoUrl = 'mongodb://localhost/ht';
// fire in the hole!
// ensure that we are connected
        var db = MongoClient.connect(MongoUrl, function (dberr, db) {
            assert.equal(null, dberr);
            if (dberr) {
                console.log('!!**ChurchKeyControl.getChurchID**!!  Error opening database: ' + dberr);
                db.close();
                return 0;
            }
            var ChurchData = db.collection('churches');
            ChurchData.findOneAndUpdate({"keycontrol.churchid": {$gt: 0}},
                {$inc: {"keycontrol.churchid": 1}},
                {new: false}, function (error, result) {
                    if (result) {
                        return result.value.keycontrol.churchid;
                        db.close()
                    } else {
                        return 0;
                        db.close();
                    }
                });
        });
    }
    churchkeycontrol.prototype.getEventID = function() {
        var assert = require('assert');
        var MongoUrl = 'mongodb://localhost/ht';
// fire in the hole!
// ensure that we are connected
        var db = MongoClient.connect(MongoUrl, function (dberr, db) {
            assert.equal(null, dberr);
            if (dberr) {
                console.log('!!**ChurchKeyControl.getEventID**!!  Error opening database: ' + dberr);
                db.close();
                return 0;
            }
            var ChurchData = db.collection('churches');
            ChurchData.findOneAndUpdate({"keycontrol.eventid": {$gt: 0}},
                {$inc: {"keycontrol.eventid": 5}},
                {new: false}, function (error, result) {
                    if (result) {
                        return result.value.keycontrol.eventid;
                        db.close()
                    } else {
                        return 0;
                        db.close();
                    }
                });
        });

    };
    churchkeycontrol.prototype.getCategoryID = function() {
        var assert = require('assert');
        var MongoUrl = 'mongodb://localhost/ht';
// fire in the hole!
// ensure that we are connected
        var db = MongoClient.connect(MongoUrl, function (dberr, db) {
            assert.equal(null, dberr);
            if (dberr) {
                console.log('!!**ChurchKeyControl**!!  Error opening database: ' + dberr);
                db.close();
                return 0;
            }
            var ChurchData = db.collection('churches');
            ChurchData.findOneAndUpdate({"keycontrol.categoryid": {$gt: 0}},
                {$inc: {"keycontrol.categoryid": 45}},
                {new: false}, function (error, result) {
                    if (result) {
                        return result.value.keycontrol.categoryid;
                        db.close()
                    } else {
                        return 0;
                        db.close();
                    }
                });
        });
    }
}