//define(function(require,exports){
    /**
     * 格式化金额字符串
     * param str 数字字符串
     * return 1.00/1.20/1.21 不处理千分位
    */
    exports.num2 = function(numStr){
        var nStr=[],nStr1;
        nStr=String(numStr).split('.');
        if(nStr[1]===undefined){
            nStr1=nStr[0]+'.00'
        }else if(nStr[1].length==1){
            nStr1=nStr[0]+'.'+nStr[1].substring(0,1)+'0';                   
        }else{
            nStr1=nStr[0]+'.'+nStr[1].substring(0,2);
        }
        return nStr1;
    }

    /** 
     * 左补齐字符串 
     *  
     * @param nSize 要补齐的长度 
     * @param ch 要补齐的字符 
     * @return 
     */  
    String.prototype.padLeft = function(nSize, ch){  
        var len = 0;  
        var s = this ? this : "";  
        ch = ch ? ch : '0';// 默认补0  
      
        len = s.length;  
        while (len < nSize)
        {  
            s = ch + s;  
            len++;  
        }  
        return s;  
    } 

    /** 
     * 右补齐字符串 
     *  
     * @param nSize 要补齐的长度 
     * @param ch 要补齐的字符 
     * @return 
     */  
    String.prototype.padRight = function(nSize, ch)  
    {  
        var len = 0;  
        var s = this ? this : "";  
        ch = ch ? ch : '0';// 默认补0  
      
        len = s.length;  
        while (len < nSize)  
        {  
            s = s + ch;  
            len++;  
        }  
        return s;  
    } 

    /** 
     * 左移小数点位置（用于数学计算，相当于除以Math.pow(10,scale)） 
     *  
     * @param scale 要移位的刻度 
     * @return 
     */  
    String.prototype.movePointLeft = function(scale)  
    {  
        var s, s1, s2, ch, ps, sign;  
        ch = '.';  
        sign = '';  
        s = this ? this : "";  
      
        if (scale <= 0) return s;  
        ps = s.split('.');  
        s1 = ps[0] ? ps[0] : "";  
        s2 = ps[1] ? ps[1] : "";  
        if (s1.slice(0, 1) == '-')  
        {  
            s1 = s1.slice(1);  
            sign = '-';  
        }  
        if (s1.length <= scale)  
        {  
            ch = "0.";  
            s1 = s1.padLeft(scale);  
        }  
        return sign + s1.slice(0, -scale) + ch + s1.slice(-scale) + s2;  
    }
    /** 
     * 右移小数点位置（用于数学计算，相当于乘以Math.pow(10,scale)） 
     *  
     * @param scale  要移位的刻度 
     * @return 
     */  
    String.prototype.movePointRight = function(scale)  
    {  
        var s, s1, s2, ch, ps;  
        ch = '.';  
        s = this ? this : "";  
      
        if (scale <= 0) return s;  
        ps = s.split('.');  
        s1 = ps[0] ? ps[0] : "";  
        s2 = ps[1] ? ps[1] : "";  
        if (s2.length <= scale)  
        {  
            ch = '';  
            s2 = s2.padRight(scale);  
        }  
        return s1 + s2.slice(0, scale) + ch + s2.slice(scale, s2.length);  
    } 

    /** 
     * 移动小数点位置（用于数学计算，相当于（乘以/除以）Math.pow(10,scale)） 
     *  
     * @param scale  要移位的刻度（正数表示向右移；负数表示向左移动；0返回原值） 
     * @return 
    */  
    String.prototype.movePoint = function(scale){
        if (scale >= 0){
            return this.movePointRight(scale); 
        }else{  
            return this.movePointLeft(-scale);
        } 
    }

    // 详细计算方法
    exports.calFn = function(){
        // 取值
        var
        // 投资金额
            _borrowAmount = $('.jsBuyMoney').val(),
            // 年化收益
            _yearRate = $('.jsInterestRate').val(),
            // 投资期限
            _borrowDate = $('.jsBorrowExpired').val(),
            // 收益方式
            _incomeModule = $('.jsRepayType').val(),
            // 投资期限日月  （1-日  2-月）
            _date = $('.jsBorrowExpiredUnit').val();

        // 判断收益方式
        switch (_incomeModule) {
            // 每日返息到期还本(1)
            case "1":
                exports.everyDayFn({
                    // 投资金额
                    borrowAmount: _borrowAmount,
                    // 年化收益
                    yearRate: _yearRate,
                    // 投资期限
                    borrowDate: _borrowDate,
                    // 收益方式
                    incomeModule: _incomeModule,
                    // 投资期限月日
                    date: _date
                });
                break;
        }
    };

    // 按月等额本息
    exports.monthDebxFn = function(json) {
        // 新算法
        var benjin=json.borrowAmount,//出借金额
            lilvy=String(json.yearRate)/100, //年利率
            qixian=json.borrowDate,//借款期限
            lilvm,//月利率
            lilvm_out,//月利率输出值
            ybx,//月本息
            ybx_out,//月本息输出
            lxhj,//利息合计
            bxhj;//本息合计
            lilvm=lilvy/12;
            lilvm_out=(lilvy/12).toFixed(2);
            
            ybx=(benjin*lilvm*Math.pow(1+lilvm,qixian))/(Math.pow(1+lilvm,qixian)-1);
            ybx_out=ybx.toFixed(2);
            bxhj=(ybx_out*qixian).toFixed(2);
            lxhj=(bxhj-benjin).toFixed(2);

            
            // html
            $('.jsCalculators').html('<div class="modelTexta"><span class="con-lt cGray bg">本息合计（元）：</span><span class="con-rt cRed">'+bxhj+'</span></div><div class="modelTexta"><span class="con-lt cGray">利息合计（元）：</span><span class="con-rt cRed">'+lxhj+'</span></div><div class="modelTexta"><span class="con-lt cGray">月收本息（元）：</span><span class="con-rt cRed">'+ybx_out+'</span></div>');
            // 可投资金额页 预计首页收益
            $(".jsIncome").html(lxhj);
    };

    // 一次性还本息 新改的算法-和好贷宝一致
    exports.oneTimeFn = function(json) {
        var benjin=json.borrowAmount,//出借金额
            lilvy=String(json.yearRate).movePoint(4), //年利率
            qixian=json.borrowDate,//借款期限
            hkfsdw, //还款方式的单位
            lxhj,   //利息合计
            _dqlx,  //内部单期利息
            dqlx,   //单期利息
            rlx,  //为月准备的按天返息额度
            zhfy,   //最后月返还金额
            yfh,    //在月还款 选择日时 返回
            bxhj;   //本息合计  
        switch (json.date) {
            case "2": // 月
                // 新算法
                _dqlx = benjin*lilvy/12;
                dqlx = exports.num2((lilvy/12*benjin).toString().movePoint(-6));
                lxhj = exports.num2((dqlx*qixian));
                bxhj = Number(lxhj)+Number(benjin); 
                zhfy = Number(benjin)+Number(dqlx); 

                // html
                $('.jsCalculators').html('<div class="modelTexta"><span class="con-lt cGray bg">本息合计（元）：</span><span class="con-rt cRed">'+bxhj+'</span></div><div class="modelTexta"><span class="con-lt cGray">利息合计（元）：</span><span class="con-rt cRed">'+lxhj+'</span></div>');
                // 可投资金额页 预计首页收益
                $(".jsIncome").html(lxhj);
                break;
            case "1": // 日
                // 新算法
                _dqlx = benjin*lilvy/365;
                dqlx = exports.num2((benjin*lilvy/365).toString().movePoint(-6));
                lxhj = (((benjin*lilvy/365).toString().movePoint(-6))*(qixian-1)).toFixed(2);
                rlx = exports.num2((benjin*lilvy/365).toString().movePoint(-6));
                yfh = (rlx*30).toFixed(2);
                bxhj = Number(lxhj)+Number(benjin); 
                zhfy = Number(benjin)+Number(dqlx); 

                // html
                $('.jsCalculators').html('<div class="modelTexta"><span class="con-lt cGray bg">本息合计（元）：</span><span class="con-rt cRed">'+bxhj+'</span></div><div class="modelTexta"><span class="con-lt cGray">利息合计（元）：</span><span class="con-rt cRed">'+lxhj+'</span></div>');
                // 可投资金额页 预计首页收益
                $(".jsIncome").html(lxhj);
                break;
        }
    }

    // 按月付息,到期还本
    exports.monthlyFn = function(json) {
         var benjin=json.borrowAmount,//出借金额
            lilvy=String(json.yearRate).movePoint(4), //年利率
            qixian=json.borrowDate,//借款期限
            hkfsdw, //还款方式的单位
            lxhj,   //利息合计
            _dqlx,  //内部单期利息
            dqlx,   //单期利息  每月回收
            rlx,  //为月准备的按天返息额度
            zhfy,   //最后月返还金额
            yfh,    //在月还款 选择日时 返回
            bxhj;   //本息合计  
        switch (json.date) {
            case "2":  // 月
               // 新算法
                _dqlx = benjin*lilvy/12;
                dqlx = exports.num2((lilvy/12*benjin).toString().movePoint(-6));
                lxhj = exports.num2((dqlx*qixian));
                bxhj = Number(lxhj)+Number(benjin); 
                zhfy = Number(benjin)+Number(dqlx); 
                
                // html
                $('.jsCalculators').html('<div class="modelTexta"><span class="con-lt cGray bg">本息合计（元）：</span><span class="con-rt cRed">'+bxhj+'</span></div><div class="modelTexta"><span class="con-lt cGray">利息合计（元）：</span><span class="con-rt cRed">'+lxhj+'</span></div><div class="modelTexta"><span class="con-lt cGray">每月预期回收利息（元）：</span><span class="con-rt cRed">'+dqlx+'</span></div><div class="modelTexta"><span class="con-lt cGray">最后一月回收（元）：</span><span class="con-rt cRed">'+zhfy+'</span></div>');
                // 可投资金额页 预计首页收益
                $(".jsIncome").html(lxhj);
                break;
            case "1": // 日
                // 新算法
                _dqlx = benjin*lilvy/365;
                dqlx = exports.num2((benjin*lilvy/365).toString().movePoint(-6));
                lxhj = (((benjin*lilvy/365).toString().movePoint(-6))*(qixian-1)).toFixed(2);
                rlx = exports.num2((benjin*lilvy/365).toString().movePoint(-6));
                yfh = (rlx*30).toFixed(2);
                bxhj = Number(lxhj)+Number(benjin); 
                zhfy = Number(benjin)+Number(dqlx); 

                // html
                $('.jsCalculators').html('<div class="modelTexta"><span class="con-lt cGray bg">本息合计（元）：</span><span class="con-rt cRed">'+bxhj+'</span></div><div class="modelTexta"><span class="con-lt cGray">利息合计（元）：</span><span class="con-rt cRed">'+lxhj+'</span></div><div class="modelTexta"><span class="con-lt cGray">每月预期回收利息（元）：</span><span class="con-rt cRed">'+yfh+'</span></div>');
                // 可投资金额页 预计首页收益
                $(".jsIncome").html(lxhj);
                break;
        }
    }

    // 每日返息,到期还本
    exports.everyDayFn = function(json) {
        switch (json.date) {
            // json.date 2 是月
            case "2":
                // 日收益
                var _dayNum = exports.num2(json.borrowAmount * ((Number(json.yearRate)/100) / 365));
                // 非四舍五入
                // var _interest = _dayNum.toString().substring(0, _dayNum.toString().indexOf(".") + 3);
                // 预计月收入
                var _expected = (json.borrowAmount * ((Number(json.yearRate) / 100) / 365) * 30).toFixed(2);
                // 利息合计
                var _interest = (_dayNum * 30 * json.borrowDate).toFixed(2);
                // 本息合计
                var _thisAndInterest = exports.num2(Number(json.borrowAmount) + Number(_interest));

                // html
                $('.jsCalculators').html('<div class="modelTexta"><span class="con-lt cGray bg">本息合计（元）：</span><span class="con-rt cRed">'+Number(_thisAndInterest).toFixed(2)+'</span></div><div class="modelTexta"><span class="con-lt cGray">利息合计（元）：</span><span class="con-rt cRed">'+_interest+'</span></div><div class="modelTexta"><span class="con-lt cGray">每日预期回收利息（元）：</span><span class="con-rt cRed">'+_dayNum+'</span></div>');
                // 可投资金额页 预计首页收益
                $(".jsIncome").html(_interest);
                break;
            // json.date 2 是日
            case "1":
                // 新算法
                var benjin=json.borrowAmount,//出借金额
                lilvy=Number(json.yearRate), //年利率
                qixian=json.borrowDate,//借款期限

                rlx, //每日回收利息
                lxhj,//利息合计
                bxhj;//本息合计 
                        
                rlx=exports.num2((benjin*(lilvy/365)).toString().movePoint(-2));
                rlx_n=rlx.toString().movePoint(2);
                // bxhj=exports.num2(Number((rlx_n*qixian).toString().movePoint(-2))+benjin);          
                lxhj=exports.num2((rlx_n*qixian).toString().movePoint(-2));
                bxhj = Number(lxhj)+Number(benjin); 
               
                // html
                $(".jsIncome").html(lxhj); //利息
                $(".jsThisAndInterest").html(bxhj); //本息合计
                $(".jsDayNum").html(rlx); //日收益

                // html
                $('.jsCalculators').html('<div class="modelTexta"><span class="con-lt cGray bg">本息合计（元）：</span><span class="con-rt cRed">'+bxhj+'</span></div><div class="modelTexta"><span class="con-lt cGray">利息合计（元）：</span><span class="con-rt cRed">'+lxhj+'</span></div><div class="modelTexta"><span class="con-lt cGray">每日预期回收利息（元）：</span><span class="con-rt cRed">'+rlx+'</span></div>');
                // 可投资金额页 预计首页收益
                $(".jsIncome").html(lxhj);
                break;
        }
    }
    

    //乘法
    function accMul(arg1,arg2){  
        var m=0,s1=arg1.toString(),s2=arg2.toString();  
        try{m+=s1.split(".")[1].length}catch(e){}  
        try{m+=s2.split(".")[1].length}catch(e){}  
        return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m)  
    }

    //除法
    function accDiv(arg1,arg2){  
        var t1=0,t2=0,r1,r2;  
        try{t1=arg1.toString().split(".")[1].length}catch(e){}  
        try{t2=arg2.toString().split(".")[1].length}catch(e){}  
        with(Math){  
            r1=Number(arg1.toString().replace(".",""))  
            r2=Number(arg2.toString().replace(".",""))  
            return (r1/r2)*pow(10,t2-t1);  
        }  
    }
//})