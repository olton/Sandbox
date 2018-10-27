<?php


namespace Models;


use Classes\Model;

class UserModel extends Model {
    public function UserByName($name){
        $h = $this->Select("select id, name, email, created, updated, last_logged from user where name = " . $this->_e($name));
        if ($this->Rows($h) === 0) {
            return false;
        }
        $user = $this->FetchArray($h);
        return $user;
    }

    public function User($id){
        $h = $this->Select("select id, name, email, created, updated, last_logged from user where id = " . $this->_e($id));
        if ($this->Rows($h) === 0) {
            return false;
        }
        $user = $this->FetchArray($h);
        return $user;
    }

    public function CheckAuth($name, $pass) {
        $h = $this->Select("select id from user where (name = " . $this->_e($name) . " or email = " . $this->_e($name) . ") and password = " . $this->_e($pass));
        if ($this->Rows($h) === 0) {
            return false;
        }
        $user = $this->FetchResult($h, 0, 0);
        return $user;
    }

    public function NameExists($name) {
        $h = $this->Select("select id from user where name = " . $this->_e($name));
        return $this->Rows($h) > 0;
    }

    public function EmailExists($email) {
        $h = $this->Select("select id from user where email = " . $this->_e($email));
        return $this->Rows($h) > 0;
    }

    public function Save($id, $name, $email){
        $data = [
            "name" => $name,
            "email" => $email
        ];
        if ($id == -1) {
            $data['created'] = date("Y-m-d H:i:s");
            $h = $this->Insert("user", $data, true, false, true);
        } else {
            $h = $this->Update("user",$data, "id = " . $this->_e($id), true);
        }

        return $id == -1 ? $this->ID() : $this->Rows($h);
    }

    public function SetPassword($id, $password){
        $data = [
            "password" => $password
        ];
        $h = $this->Update("user", $data, "id = " . $this->_e($id), true);
        return $this->Rows($h);
    }

    public function Logged($id){
        $data = [
            "last_logged" => date("Y-m-d H:i:s")
        ];
        $h = $this->Update("user", $data, "id = " . $this->_e($id), true);
        return $this->Rows($h);
    }


}