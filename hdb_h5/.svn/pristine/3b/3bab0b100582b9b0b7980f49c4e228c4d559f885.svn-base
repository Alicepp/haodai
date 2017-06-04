/**
 * Created by lxm on 16/10/31.
 */
// define(function(require, exports) {
    exports.judgeArg = function () {
        var aArray = arguments[0], i = 0, aNewArg = [];
        for (; i < aArray.length; i++) {
            if (typeof aArray[i] == 'undefined' || typeof aArray[i] == 'number') continue;
            if (typeof aArray[i] == 'string') aNewArg[0] = aArray[i];
            if (typeof aArray[i] == 'boolean') aNewArg[1] = aArray[i];
            if (typeof aArray[i] == 'object') aNewArg[2] = aArray[i];
            if (typeof aArray[i] == 'function') aNewArg[3] = aArray[i];
        };
        return aNewArg;
    }
// })