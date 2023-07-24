<?php
class Controller {
    protected function render($view, $data = []) {
        extract($data);

        $viewFile = __DIR__ . '/../app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            header("HTTP/1.0 404 Not Found");
            echo '404 Not Found';
        }
    }

    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
}
