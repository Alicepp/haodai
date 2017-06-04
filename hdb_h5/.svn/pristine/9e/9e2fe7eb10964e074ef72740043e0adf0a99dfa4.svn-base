// define(function(require, exports) {
   var currentTime=new Date().toLocaleString(),
       jsLogUrl="ws://dev-lib.f2e.li:2223",
       jsLog = new WebSocket(jsLogUrl);
   exports.jsLog=function(obj){
        var newObj={
            "cmd": "log",
            "data": {
                "subject": "haodaibao",
                "text":obj
            },
            "time": currentTime
        };
        this.send = function (message, callback) {
           this.waitForConnection(function () {
               jsLog.send(message);
               if (typeof callback !== 'undefined') {
                   callback();
               }
           }, 500);
        };
        this.waitForConnection = function (callback, interval) {
           if (jsLog.readyState === 1) {
               callback();
           } else {
               var that = this;
               setTimeout(function () {
                   that.waitForConnection(callback, interval);
               }, interval);
           }
        };
        this.send(JSON.stringify(newObj));
        jsLog.onerror=function(data){
           console.log(data)
        }
   }
// })
