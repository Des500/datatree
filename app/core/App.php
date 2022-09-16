<?php
    class App {
        protected $controller = "Tree";
        protected $method = "index";
        protected $params = [];

        function __destruct() {}

        function __construct() {
            // echo "Hello";
            $url = $this -> parseUrl();
            // print_r(ucfirst($url[0]).'.php');
            if(file_exists('app/controllers/'.ucfirst($url[0]).'.php')) {
                $this -> controller = ucfirst($url[0]);
                unset($url[0]);
            }
            require_once 'app/controllers/'.$this -> controller.'.php';
            // echo "$this->controller <br>";
            // print_r($url);
            $this->controller = new $this->controller;
            if(isset($url[1])) {
                if(method_exists($this->controller, $url[1])) {
                    $this->method = $url[1];
                    unset($url[1]);
                }
            }

            $this->params = $url ? array_values($url): [];
            call_user_func_array([$this->controller, $this->method], $this->params);

        }
        function parseUrl() {
            if (isset($_GET['url'])) {
                return explode('/',
                    filter_var(
                    rtrim($_GET['url'], '/'),
                        FILTER_UNSAFE_RAW
                )
                );
            }
        }
    }