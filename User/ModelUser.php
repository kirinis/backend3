<?php

namespace User;

class ModelUser extends ModelBase
{

    public function __construct()
    {
        $this->table = "users";
        $this->addFilds = array("email", "pswd", "token");

        $this->funFildsCreate['pswd']['start'] = " MD5(";
        $this->funFildsCreate['pswd']['end'] = ") ";
    }

    public function getRestoreTime($data)
    {
        $sql = "SELECT restoreTime FROM " . $this->table . " WHERE token='" . $data['restoreEmail'] . "'";
        $res = MySQLi_DB::getInstance()->execute($sql);
        $row = $res->fetch_assoc();
        return $row;

    }

    public function restoreTime($data)
    {
        $sql = "UPDATE " . $this->table . " SET restoreTime = '" . $data['restoreTime'] . "' WHERE token = '" . $data['token'] . "'";
        $res = MySQLi_DB::getInstance()->execute($sql);

    }

    public function getToken($data)
    {
        $sql = "SELECT token, activemail FROM " . $this->table . " WHERE token='" . $data['token'] . "'";
        $res = MySQLi_DB::getInstance()->execute($sql);
        $row = $res->fetch_assoc();
        return $row;
    }

    public function getAll($data)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE email='" . $data['email'] . "'";
        $res = MySQLi_DB::getInstance()->execute($sql);
        $row = $res->fetch_assoc();
        return $row;
    }

    public function activEmail($data)
    {
        $sql = "UPDATE " . $this->table . " SET activemail='yes' WHERE token='" . $data['token'] . "'";
        $res = MySQLi_DB::getInstance()->execute($sql);
        return $res;
    }

    public function loginIn($data)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE email='" .
            $data['email'] . "' AND pswd=MD5('" . $data['pswd'] . "')";
        $res = MySQLi_DB::getInstance()->execute($sql);
        $row = $res->fetch_assoc();
        return $row;
    }

    public function restoreEmail($data)
    {
        $sql = "UPDATE " . $this->table . " SET pswd = MD5('" . $data['pswd'] . "') WHERE token='" . $data['restore'] . "'";
        $res = MySQLi_DB::getInstance()->execute($sql);

    }
}