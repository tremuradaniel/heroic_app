<?php

/*
* App Core Class 
* Creates URL & loads core controller 
* URL FOMRAT - /controller/methods/params 
*/

class Core {
    protected $currentController;
    protected $currentMethod;
    protected $params = [];

    public function __construct(){
        $url = $this->getUrl();
        if ($this->isHome($url)) return require_once('../app/views/home.php');

        // Look in controllers for first value

        // define the location of the file as if it was relative to 
        // public/index.php
        $pathToController = '../app/controllers/' . ucwords($url[0]) . '.php';
        if (file_exists($pathToController)) {
            // If exists, set as controller
            $this->currentController = ucwords($url[0]);
            // Unset 0 Index
            unset($url[0]);
        } else return require_once('../app/views/404.php');
        // Require the controller
        require_once '../app/controllers/' . $this->currentController . '.php';
        // Instantiate controller class
        $this->currentController = new $this->currentController;

        // Check for second part of url
        if (isset($url[1])) {
            // Check to see of method exists in controller
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                // Unset 1 Index
                unset($url[1]);
            }
        }
        
        // get params
        $this->params = $url ? array_values($url) : [];
        
        // Call a callback with array of params
        if (method_exists($this->currentController, $this->currentMethod)) 
            call_user_func_array(
                [ $this->currentController, $this->currentMethod ], 
                $this->params
            );
        else return require_once('../app/views/404.php');
    }
    
    /**
     * getUrl
     *
     * @return array
     */
    public function getUrl(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        } else return [];
    }
        
            
    /**
     * isHome
     *
     * @param  array $url
     * @return bool
     */
    private function isHome (Array $url) {
        return count($url) === 0;
    }

}
