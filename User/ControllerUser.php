<?php

namespace User;

use User\ModelUser;

class ControllerUser extends BaseController
{

    private $content;
    private $error;


    public function getWiget()
    {
        if (isset($_SESSION['auth']) == 'true') {
            return $this->render("wiget-login.tpl.php");
        } else {
            return $this->render("wiget-guest.tpl.php");
        }
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getError()
    {
        return $this->error;
    }

    public function index()
    {

    }

    public function register()
    {
        $this->content = $this->render("register-form.tpl.php");
    }

    public function login()
    {
        $this->content = $this->render("login-form.tpl.php");
    }

    public function restore()
    {
        $this->content = $this->render('restore-email.tpl.php');
    }

    public function newPass()
    {
        $data = $_POST;
        $data['restore'] = $_GET['restore'];
        if ($data['pswd'] == $data['pswd2']) {
            $this->Model->restoreEmail($data);
            $this->content = '<div class="alert alert-warning text-center" role="alert">
                   Ваш пароль обновлен</div>';
        } else {
            $this->content = '<div class="alert alert-warning text-center" role="alert">
                   Пароли не совпадают</div>';
            $this->content .= $this->render('newPassForm.tpl.php');
        }

    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
    }

    public function favorite()
    {
        $this->content = $this->render('favorite.tpl.php');
    }

    public function resPass()
    {
        $data1 = $_GET;
        $time = $this->Model->getRestoreTime($data1);
        $t = time() - $time['restoreTime'];
        if ($t < 3600) {
            $this->content = $this->render('newPassForm.tpl.php');
        } else {
            $this->content = '<div class="alert alert-warning text-center" role="alert">
                   Прошло больше часа, вы не можете восстановить пароль по этой ссылке, запросите новую ссылку</div>';
        }
    }

    public function restoreEmail()
    {
        $data1 = $_POST;
        $all = $this->Model->getAll($data1);
        $all['restoreTime'] = time();
        $this->Model->restoreTime($all);
        $mail['email'] = $data1['email'];
        $mail['body'] = 'Для ввода нового пароля перейдите по ссылке, ссылка действует в течении часа: 
                <a href="http://' . $_SERVER['SERVER_NAME'] . ':81' . $_SERVER['PHP_SELF'] . '?restoreEmail=' . $all['token'] . '"><b>Восстановить</b></a>';
        $mail['subject'] = 'Восстановление пароля';
        $this->SendEmail($mail);
        $this->content = '<div class="alert alert-warning text-center" role="alert">
                   Письмо отправлено</div>';
    }


    public function loginIn()
    {
        $data1 = $_POST;
        $data = $this->Model->loginIn($data1);
        //     var_export($data);
        if ($data) {
            if ($data['activemail']) {
                $_SESSION['auth'] = 'true';
            } else {
                $this->content = '<div class="alert alert-warning text-center" role="alert">
                   Подтвердите ваш email!</div>';
            }
        } else {
            $this->content = '<div class="alert alert-warning text-center" role="alert">
                   НЕправильный логин или пароль!</div>';
        }
    }

    public function token()
    {
        $data1 = $_GET;
        $data = $this->Model->getToken($data1);
        if (!$data['activemail']) {
            $act = $this->Model->activEmail($data);
            $this->content = '<div class="alert alert-warning text-center" role="alert">
                   Спасибо за подтверждения Email-а</div>';
        } else {
            $this->content = '<div class="alert alert-warning text-center" role="alert">
                   Вы уже подтвердили Email. Спасибо!</div>';
        }
    }

    public function create()
    {
        // Логика

        $data1 = $_POST;


        if ($data1['pswd'] == $data1['pswd1']) {
            $data1['token'] = md5(uniqid($data1['email'], true));
            $data = $this->Model->Create($data1);
            $this->error = $data['errNum'] . $data['errText'];
            $mail['body'] = '<b>Ваш email: </b>' . $data1['email'] . '<br> <b>Ваш пароль: </b>' . $data1['pswd'] . '<br>'
                . 'Для подтверждения регистрации перейдите по ссылке: 
                <a href="http://' . $_SERVER['SERVER_NAME'] . ':81' . $_SERVER['PHP_SELF'] . '?token=' . $data1['token'] . '"><b>Подтверждение</b></a>';
            $mail['email'] = $data1['email'];
            $mail['subject'] = 'Подтверждение регистрации';
            $this->SendEmail($mail);
            $this->content = '<div class="alert alert-warning text-center" role="alert">
                   Письмо с подтверждением регистрации отправлено</div>';
        } else {
            $this->content = '<div class="alert alert-warning text-center" role="alert">
                   Пароли не совпадают!</div>';
            $this->content .= $this->render("register-form.tpl.php");
        }

    }


    private $Model;

    /*
     *  Одиночка
     */
    private function __construct()
    {
        $this->Model = new ModelUser();
    }

    private static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}