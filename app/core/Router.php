<?php
class Router {
    private $routes = [];

    public function get($path, $action) {
        $this->routes['GET'][$path] = $action;
    }

    public function post($path, $action) {
        $this->routes['POST'][$path] = $action;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $base = '/webdulich'; // thư mục gốc
        $path = str_replace($base, '', $uri);

        if (isset($this->routes[$method][$path])) {
            list($controller, $function) = explode('@', $this->routes[$method][$path]);

            // ✅ Kiểm tra xem có phải API controller không
            if (strpos($controller, 'ApiController') !== false) {
                require_once __DIR__ . "/../apicontrollers/$controller.php";
            } else {
                require_once __DIR__ . "/../controllers/$controller.php";
            }

            $obj = new $controller();
            $obj->$function();
        } else {
            echo "404 - Không tìm thấy đường dẫn!";
        }
    }
}

