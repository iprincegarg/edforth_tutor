<?php
class Router {
    protected $currentController = 'SuperadminController';
    protected $currentMethod = 'login';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // Default to superadmin if empty, or map appropriately
        if(isset($url[0])) {
            $controllerName = ucwords($url[0]) . 'Controller';
            if(file_exists('../app/controllers/' . $controllerName . '.php')) {
                $this->currentController = $controllerName;
                unset($url[0]);
            }
        }

        // Require the controller
        $controllerFile = '../app/controllers/' . $this->currentController . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $this->currentController = new $this->currentController;
            
            // Check for method
            if(isset($url[1])) {
                if(method_exists($this->currentController, $url[1])) {
                    $this->currentMethod = $url[1];
                    unset($url[1]);
                }
            }

            // Get params
            $this->params = $url ? array_values($url) : [];

            // Call a callback with array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        } else {
            die("Controller not found.");
        }
    }

    public function getUrl() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
