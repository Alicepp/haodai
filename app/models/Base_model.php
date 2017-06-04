<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Base_model extends qb_Model
{

    //不需登录，绝大部分需要登录
    //均转变为小写
    private static $unLogin = [
        'home' => ['login', 'sendphoneverifycode', 'getmcryptkey', 'dologin', 'logout', 'getmcryptkey', 'verifycode'],  //登录，获取手机验证码，获取加密因子，执行登录，退出登录，获得加密因子，图片验证码
    ];
    protected $_host = RESTHUB_URL;
    public static $token = '';

    protected function _init()
    {
        self::$token = $this->SIGN_TOKEN;
    }

    public function getStaticToken()
    {
        return self::$token;
    }

    /**
     * 获取token
     *  杨东辉 yangdh@qianbaoeco.com
     * 开发 : https://dev-apis.qianbao.com/payment/v1/moms/util/getToken
     */
    public function getAndSetToken()
    {
        $rst = $this->fetchPost('/payment/v1/moms/util/getToken');
        $this->writeLogin('', $rst['result']);
        self::$token = $rst['result'];
        //$this->readLogin(self::LOGIN_COOKIE);
        return $rst['result'];
    }


    //访问的地址是否需要登录
    public static function isNeedLogin()
    {
        $ci = getCiInstance();
        $class = strtolower($ci->router->directory . $ci->router->class);
        $method = strtolower($ci->router->method);
        $unLogin = self::$unLogin;
        //echo '++24++';var_dump($class,$method);
        if (isset($unLogin[$class]) && in_array($method, $unLogin[$class])) {
            $ci->_setSession('userInfo', '', 'global');
            $ci->_setSession('firstLevelMenu', '', 'global');
            return ['code' => -1, 'msg' => 'need not login'];
        }
        return ['code' => 1, 'msg' => 'need login'];
    }

    /**
     * //根据token，获取菜单列表
     *   杨东辉 yangdh@qianbaoeco.com
     * 访问地址    开发 : https://dev-apis.qianbao.com/payment/v1/moms/util/getPermissonMenu
     * @param string $parentId 父级菜单id
     * @return array
     */
    public function getMenuInfo($parentId = '', $sessionType = 'session')
    {
        $menu = [];
        $key = $parentId ? $parentId : 'firstLevelMenu';

        if ($sessionType === self::SESSION_SESSION) {
            $menu = $this->_getSession($key, 'global');
        }
        if ($menu) {
            return $menu;
        }

        $param['url'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $param['parentId'] = $parentId; //如果为空, 表示获取1级菜单

        //获取菜单列表
        $rst = $this->fetchPost('/payment/v1/moms/util/getPermissonMenu', $param);

        if (is_file($file = APPPATH . 'config/' . 'menu.php'))
            require $file;

        $menuMap = !$parentId ? $firstLevelMenu : (isset($secondLevelMenu[$parentId]) ? $secondLevelMenu[$parentId] : []);

        if (API_SUCCESS == $rst['status']) {
            foreach ($menuMap as $v) {  //保证菜单顺序
                foreach ($rst['result'] as $vv) {
                    if ($v['id'] === $vv['id']) {
                        $menu[] = $v;
                    }
                }
            }
        }
        if ($sessionType === self::SESSION_SESSION) {
            $this->_setSession($key, $menu, 'global');
        }
        return $menu;
    }

    /**
     * 校验连接权限 (登录用户是否有访问某连接的权限)
     *  杨东辉 yangdh@qianbaoeco.com
     * 开发 : 开发 : https://dev-apis.qianbao.com/payment/v1/moms/util/checkPermission
     * @param $urlPath
     * @return bool
     */
    public function checkPathPermission($urlPath)
    {
        if (!$urlPath) {
            return false;
        }
        return $rst = $this->fetchPost('/payment/v1/moms/util/checkPermission', ['urlPath' => $urlPath]);
    }

    /*
     * 请求之前 设置header头
     * 数据加密
     * */
    protected function fetchBefore($url, $data)
    {
        $header = ['ipAddress:' . $this->input->ip_address(), 'token: ' . self::$token];
        //$header = ['ipAddress:127.0.0.1', 'token:8FA42803AB5C4DD993577021155713F0'];

        $this->setRequestHeader($header);
        return $data;
    }

    protected function fetchFinish($data)
    {
        //echo '++99++';var_dump($data);die;
        if (!$data || !isset($data['status'])) {
            showError();
        }
        switch ($data['status']) {
            case API_SUCCESS:  //成功
                return $data;
                break;
            case '43580107'://无法读取用户登录信息
            case '43581107'://不能多人同时登陆一个账号
            case '43582107'://IP地址已变更, 重新登录
                if (getRealUri() !== '/home/login/') {
                    //goRedirect(API_FAILURE, '/home/login', $data['message']);
                    if (isAjax()) {
                        showJsonMsg(API_FAILURE, $data['message']);
                    } else {
                        redirect('/home/login');
                    }
                    break;
                } else {
                    return false;
                }
            case 502://服务器异常
            case 404://接口地址没有请求到
            case 4510://数据异常
                showError();
                break;
            case '43890107'://参数校验错误
                showError('参数校验错误');
                break;
            default:
                //case '43583107'://用户无此菜单权限
                //case '43815107'://手机验证码输入错误
                showError($data['message']);
        }
    }

}
