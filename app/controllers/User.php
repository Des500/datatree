<?php

    class User extends Controller
    {
        public function reg() {
            $data = [];
            if(isset($_POST['name'])) {
                $user = $this->Model('UserModel');
                $user->setData($_POST['name'], $_POST['email'], $_POST['pass'], $_POST['re_pass']);
                $isValid = $user->validForm();
                if($isValid === 'ok')
                    if ($user->addUser()) {
                        NotifMessage::setStatus('success', 'Пользователь успешно добавлен');
                        $this->redirect('user/dashboard');
                        return true;
                    }
                    else
                        NotifMessage::setStatus('error', 'такой пользователь уже существует');
                else
                    NotifMessage::setStatus('error', $isValid);
            }
            $this->view('user/reg');
        }
        public function dashboard() {
            $user = $this->Model('UserModel');
            if(isset($_POST['exit_button'])) {
                $user->logOut();
                exit();
            }
            $data = $user->getUser();
            $this->view('user/dashboard', $data);
        }
        public function auth () {
            $data = [];
            if(isset($_POST['email'])) {
                $user = $this->Model('UserModel');
                $message = $user->userAuth($_POST['email'], $_POST['pass']);
                if ($message === 'ok') {
                    NotifMessage::setStatus('success', 'Вы авторизованы');
                    $this->redirect('user/dashboard');
                    return true;
                }
                else {
                    NotifMessage::setStatus('error', $message);
                }
            }
            $this->view('user/auth');
        }
    }