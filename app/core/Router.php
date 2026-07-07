<?php
class Router {
    protected $currentController = 'SuperadminController';
    protected $currentMethod = 'index';
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
        $urlStr = '';
        if(isset($_GET['url'])) {
            $urlStr = $_GET['url'];
        } else {
            $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
            $basePath = parse_url(URLROOT, PHP_URL_PATH) ?? '';
            if (!empty($basePath) && strpos($uri, $basePath) === 0) {
                $uri = substr($uri, strlen($basePath));
            }
            $urlStr = $uri;
        }

        if(!empty($urlStr) && $urlStr !== '/') {
            $url = trim($urlStr, '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
