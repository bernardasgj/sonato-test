<?php
spl_autoload_register(function ($className) {
    $classPath = str_replace('\\', '/', $className);
    $file = __DIR__ . '/' . $classPath . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Session.php';

Session::start();

$router = new Router();

// UserController
$router->get('/', 'UserController@index');
$router->get('/login', 'UserController@login');
$router->get('/logout', 'UserController@logout');
$router->get('/user_search', 'UserController@searchUser');
$router->post('/login', 'UserController@login');
$router->get('/update_account', 'UserController@updateAccount');
$router->post('/update_account', 'UserController@updateAccount');
$router->get('/register', 'UserController@register');
$router->post('/register', 'UserController@register');

// PokeController
$router->get('/pokes', 'PokeController@index');
$router->post('/add_poke', 'PokeController@addPoke');
$router->get('/add_poke', 'PokeController@addPoke');
$router->get('/update_poke_popup', 'PokeController@updatePokePopup');
$router->post('/update_poke_popup', 'PokeController@updatePokePopup');
$router->get('/search_poke', 'PokeController@searchPokes');
$router->post('/search_poke', 'PokeController@searchPokes');

// FileController
$router->get('/csv_form_page', 'FileController@csvIndexPage');
$router->get('/json_form_page', 'FileController@jsonIndexPage');
$router->post('/upload_csv', 'FileController@uploadCsv');
$router->post('/upload_json_data', 'FileController@uploadJSON');

// Dispatch the request
$router->dispatch();
