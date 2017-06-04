    // ##====请求fastclick
    // @require ../../lib/fastclick
    exports.ft=function(className){
        if(className!="document.body"){
            var thisDiv=document.getElementsByClassName(className);
            if(thisDiv.length>0){
                for(var i=0;i<thisDiv.length;i++){
                    FastClick.attach(document.getElementsByClassName(className)[i]);
                }
            }
        }else {
            FastClick.attach(className);
        }
    }