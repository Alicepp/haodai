<?php
/**
 * 代付出款
 *
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PayForOthers_model extends Base_model
{


    protected function _init()
    {
        parent::_init();
    }


    /**
     * 商户证书上传
     *杨东辉 yangdh@qianbaoeco.com
     * 访问地址 开发 :
     *  excel 2007及以后（xlsx格式） 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
     *  excel 2003（xls格式） 'application/vnd.ms-excel'
     * @param $file
     * @return array
     */
    public function uploadPayForOthers($totalAmount,$totalCount,$file,$maxSize=500000)
    {
        //1.判断文件上传是否错误
        if ($file['error'] > 0) {
            showError();
        }
        //2.判断上传文件类型是否合法
        $mimes=['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel'];
        if (!in_array($file['type'],$mimes)) {
            showError('请上传excel文件(xls或xlsx格式)');
        }
        //3.判断上传文件大小是否超出允许值，此处暂定500KB
        if ($file['size'] > $maxSize) {
            showError('文件过大');
        }
        //4.判断是否是上传的文件，并移动文件
        if (is_uploaded_file($file['tmp_name'])) {
            $param['file'] = '@' . $file['tmp_name'];
            $param['totalAmount'] =$totalAmount;
            $param['totalCount'] = $totalCount;

            //$rst = $this->fetchPost('/payment/v1/moms/merchant/key/upload', $param);
            $rst=[
                'status'=>'20000107',
                'info'=>'上传成功',
                'message'=>'上传成功',
                'result'=>'上传成功'
            ];
            return $rst;
        } else {
            showError();
        }
    }

    //*************************************************************************************

    /*
     * 登录
     * @param string Y userName 用户名
     * @param string Y passWord 密码
     *
     * 密码错误次数超限是必传
     * @param string N validCode 验证码
     * @param string N uuid 验证码与用户名对应的id
     * @return json
     * */
    public function login($userName, $passWord, $imageCode = '')
    {
        $param['userName'] = $userName;
        $param['passWord'] = $passWord;

        $param['validCode'] = $imageCode;
        !empty($imageCode) && $param['uuid'] = $this->_getSession('uuid', '_CODEIMG');//获取session中是否存在uuid 默认为空

        $ret = $this->fetchPost('p/login', $param);
        if (API_HDB_SUCCESS == $ret['status']) {
            $this->writeLogin($ret['result']['mid'], $ret['result']['token'], self::SESSION_COOKIE, 1800);

            goRedirect(API_SUCCESS, '', '', '/home/index');
        } else {
            $message = $ret['message'];
            if (4629 === $ret['status'] && !empty($ret['result']) && array_key_exists('image', $ret['result'])) {//密码错误次数超过限定的3次
                !empty($param['uuid']) && $message = '用户名密码或验证码错误';
                empty($imageCode) && $message = '';

                $this->_setSession('uuid', $ret['result']['uuid'], '_CODEIMG');
                $ret['result']['image'] = 'data:image/jpg;base64,' . $ret['result']['image'];

                unset($ret['result']['uuid']);

                showJsonResult($ret['result'], $message, API_PWD_FAILURE_OVERRUN);
            }
            showJsonMsg(API_FAILURE, $ret['message']);
        }

    }

}
