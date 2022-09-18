<?php
    class Controller {
        function __construct() {}
        function __destruct() {}
        protected function Model($model) {
            require_once 'app/models/'.$model.'.php';
            return new $model;
        }

        protected function view($view, $data = []) {
            require_once 'app/views/' . $view . '.php';
        }
        protected function redirect($view) {
            header($view);
        }
    }