<?php
require_once 'DB.php';
class UserModel
{
    private $name;
    private $email;
    private $pass;
    private $re_pass;
    private $_db = null;

    public function __construct() {
        $this->_db = DB::getInstence();
    }

    public function setData($name, $email, $pass, $re_pass)
    {
        $this->name = $name;
        $this->email = $email;
        $this->pass = $pass;
        $this->re_pass = $re_pass;
    }

    public function validForm () {
        if(strlen($this->name) < 3)
            return "Имя слишком короткое";
        elseif (strlen($this->email) < 3)
            return "email слишком короткий";
        elseif ((strpos($this->email,'@') == false)||(strpos($this->email,'.') == false))
            return "email введен с ошибкой";
        elseif (strlen($this->pass) < 3)
            return "Пароль не менее 3 символлов";
        elseif ($this->pass!==$this->re_pass)
            return "Пароли не совпадают";
        else return "ok";
    }

    public function addUser() {
        if (!empty($this->getUserByEmail($this->email))) return "такой пользователь уже существует";
        else {
            $sql = 'INSERT INTO users(name, email, pass, role) VALUES (:name, :email, :pass, :role)';
            $query = $this->_db->prepare($sql);
            $hash_pass = password_hash($this->pass, PASSWORD_DEFAULT);
            $query->execute([
                'name' => $this->name,
                'email' => $this->email,
                'pass' => $hash_pass,
                'role' => 'user'
            ]);
            $this->setAuth($this->email);
            return "Пользователь успешно добавлен";
        }
    }

    public function getUsers () {
        $result = $this->_db->query("SELECT * FROM `users` ORDER BY `id`");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUser () {
        $email = $_SESSION['login'];
        $result = $this->_db->query("SELECT * FROM `users` WHERE `email` = '$email'");
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail ($email) {
        $result = $this->_db->query("SELECT * FROM `users` WHERE `email` = '$email'");
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById ($id) {
        $result = $this->_db->query("SELECT * FROM `users` WHERE `id` = '$id'");
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function logOut () {
        $_SESSION['login']=null;
        header('location: /user/auth');
    }

    public function userAuth ($email, $pass) {
        $result = $this->getUserByEmail($email);
        if (!empty($result)) {
//            $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
            if(password_verify($pass, $result['pass'])) {
                $this->setAuth($result['email']);
            }
            else return "Неверный пароль";
        }
        else return "Пользователь не существует";
    }

    private function setAuth ($email) {
        $_SESSION['login'] = $email;
        header('location: /user/dashboard');
    }

}