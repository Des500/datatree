<?php
    class Controller {
        function __construct() {}
        function __destruct() {}
        protected function Model($model) {
            require_once 'app/models/'.$model.'.php';
            return new $model;
        }

        protected function view($view, $data = []) {
            // echo '../app/views/' . $view . '.php';
            require_once 'app/views/' . $view . '.php';
        }
        protected function redirect($view) {
            // echo '../app/views/' . $view . '.php';
            header($view);
//            require_once 'app/views/' . $view . '.php';
        }
    }