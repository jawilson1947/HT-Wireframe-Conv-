//FilesizeWacther2.js
'use strict';

var fs = require('fs');
var util = require('util');
var EventEmitter = require('events').EventEmitter;

var FilesizeWatcher = function(path) {
   var self = this;

  self.callbacks = {};

  if(/^\//.test(path) === false {
   process.nextTick(function() {  
    self.callbacks['error']('Path does not start with a slash');
    });   
    return;
  }

  fs.stat(path, function(err, stats) {
   self.lastfilesize = stats.size;
   });

  self.interval = setInterval (
    function() {
      fs.stat(path, function(err, stats) {
        if (stats.size > self.lastfilesize) {
          self.callbacks['grew'](stats.size - self.laastfilesize);
          self.lastfilesize = stats.size;
        }
        if(stats.size > self.lastfilesize) {
        	self.callbacks['shrank'](self.lastfilesize - stats.size);
        	self.lastfilesize = stats.size;
        } 	
      }, 1000);
    });
  };

  FilesizeWatcher.prototype.on = function(eventType, callback) {
  this.callbacks[eventType] = callback;;
 };

  FilesizeWatcher.prototype.stop = function() {
  clearInterval(this.interval);
  };

  module.exports = FilesizeWatcher;

