<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/services/AvatarService.class.php';
require_once __DIR__ . '/services/BookService.class.php';
require_once __DIR__ . '/services/UserbookService.class.php';
require_once __DIR__ . '/services/UserService.class.php';
require_once __DIR__ . '/dao/UserDao.class.php';

Flight::register('userDao', 'UserDao');
Flight::register('avatarService', 'AvatarService');
Flight::register('bookService', 'BookService');
Flight::register('userbookService', 'UserbookService');
Flight::register('userService', 'UserService');

Flight::map('error', function (Exception $ex) {
  // Handle error
  Flight::json(['message' => $ex->getMessage()], 500);
});

/* utility function for reading query parameters from URL */
Flight::map('query', function ($name, $default_value = NULL) {
  $request = Flight::request();
  $query_param = @$request->query->getData()[$name];
  $query_param = $query_param ? $query_param : $default_value;
  return urldecode($query_param);
});

// middleware method for login
Flight::route('/*', function () {
  // return TRUE;
  //perform JWT decode
  $path = Flight::request()->url;
  $publicPaths = array(
    '/login',
    '/sign_up',
    '/docs.json',
    '/publicbooks'
  );
  $publicPathsWithVariables = array(
    '/publicbooks/',
    '/publicauthors/',
    '/publicbooks_by_author/'
  );

  // check public routes with variables
  function is_public_path($publicPathsWithVariables, $path){
    foreach($publicPathsWithVariables as $p)
    {
        if (str_starts_with($path, $p)) {
            return true;
        }
    }
    return false;
  }

  if (in_array($path, $publicPaths) || is_public_path($publicPathsWithVariables, $path)) return TRUE; // exclude login route from middleware

  $headers = getallheaders();
  if (@!$headers['Authorization']) {
    Flight::json(["message" => "Authorization is missing"], 403);
    return FALSE;
  } else {
    try {
      $decoded = (array)JWT::decode($headers['Authorization'], new Key(Config::JWT_SECRET(), 'HS256'));
      Flight::set('user', $decoded);
      return TRUE;
    } catch (\Exception $e) {
      Flight::json(["message" => "Authorization token is not valid"], 403);
      return FALSE;
    }
  }
});

/* REST API documentation endpoint */
Flight::route('GET /docs.json', function () {
  $openapi = \OpenApi\scan('routes');
  header('Content-Type: application/json');
  echo $openapi->toJson();
});

require_once __DIR__ . '/routes/AvatarRoutes.php';
require_once __DIR__ . '/routes/BookRoutes.php';
require_once __DIR__ . '/routes/UserbookRoutes.php';
require_once __DIR__ . '/routes/UserRoutes.php';

Flight::start();
