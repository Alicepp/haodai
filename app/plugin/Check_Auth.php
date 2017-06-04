<?php
/**
 * Created by PhpStorm.
 * User: luosong
 * Date: 2016/10/14
 * Time: 14:18
 */

/**
 * User: shixiao@qianbao.com
 * Date: 2017/4/19
 */
/*
 * 检查token是否存在（不用检验合法性，每个接口都会检查）
 *  检查用户权限？
 * */

class Check_Auth
{

    public function begin($params = array())
    {
        /*
         * ******登录
         *  1、无token
         *     除登录页面均跳转登录页面，在登录页面获取token
         *
         *  2、有token
         *       先验证token是否有效（需登录的接口，java那边都会先验证token，这里调“用户信息”接口）
         *          有效：如果是登录页面，则跳转首页（其他登录相关接口（如图片、手机验证码）调用时token已经被验证过）
         *                其他页面：
             *              继续请求其他接口
             *                  正常返回
             *                  异常：①无登录信息，②多人登录，③ip变更，④无权限，①②直接跳转登录页面，③提示重新登录，跳转登录页面，④提示无权限
         *
         *          无效：
         *              除登录页面外均需跳转登录页面
         *
         * 登录成功
         *       没有更改初始密码的跳转“更改初始密码”页面（不能进行其他操作，“取消”按钮退出）；密码过期的，正常渲染首页，并提示改密码（“下次修改”可以进行其他操作）
         * */
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
        $ci = getCiInstance();
        
        if (!$ci->Base_model->getStaticToken()) {
            //获取token并存储，（目前存在cookie中）
            $token = $ci->Base_model->getAndSetToken();
            if (getRealUri() !== '/home/login/') {
                //直接跳转登录页
                redirect('/home/login');
            }
        } else if (getRealUri() === '/home/login/') {//有token，且是登录页面，先验证token
            $userInfo = $ci->UserAccount_model->getUserInfo(false);
            //$ci->_setSession('userInfo', $userInfo, 'global');
            //token 验证通过，跳转首页
            if ($userInfo) {
                redirect('/');
            }
        }

        $isNeedLogin = $ci->Base_model->isNeedLogin();
        //echo '++49++';var_dump($isNeedLogin);
        //需登录的页面
        if ($isNeedLogin['code'] === 1) {
            //获取登录用户信息,并存储
            //$userInfo = $ci->_getSession('userInfo', 'global');
            //if (!$userInfo) {
                $userInfo = $ci->UserAccount_model->getUserInfo();
            //   $ci->_setSession('userInfo', $userInfo, 'global');
           // }
            //如果未更改初始密码，跳转更改初始密码页面
            if ($userInfo['userFlag'] === 'I' && getRealUri() !== '/systemadmin/modifypassword/') {
                redirect('/systemAdmin/modifyPassword');
            }
            //echo '++58++';var_dump($userInfo);die;

            //获取一级菜单列表,并存储
            //$menuInfo = $ci->_getSession('firstLevelMenu', 'global');
            //if (!$menuInfo) {
                $menuInfo = $ci->Base_model->getMenuInfo();
            //    $ci->_setSession('firstLevelMenu', $menuInfo, 'global');
            //}
            //echo '++70++';var_dump($menuInfo);die;
        }
    }

}
