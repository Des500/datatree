<?php

    class User extends Controller
    {
        public function reg() {
            $data = [];
            if(isset($_POST['name'])) {
                $user = $this->Model('UserModel');
                $user->setData($_POST['name'], $_POST['email'], $_POST['pass'], $_POST['re_pass']);
                $isValid = $user->validForm();
                if($isValid==='ok') {
                    $data['message'] = $user->addUser();
                }
                else {
                    $data['message'] = $isValid;
                }
            }
            $this->view('user/reg', $data);
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
                $data['message'] = $user->userAuth($_POST['email'], $_POST['pass']);
            }
            $this->view('user/auth', $data);
        }
    }