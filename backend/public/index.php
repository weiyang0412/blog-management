<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\BodyParsingMiddleware;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Middleware\ErrorMiddleware;
use App\Controllers\UserController;
use App\Controllers\CourseController;
use App\Controllers\PostController;
use App\Controllers\ContactController;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../config.php';

$config = require __DIR__ . '/../config.php';

$db = new db($config['settings']['db']);

$app = AppFactory::create();

// Add Routing Middleware
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

// Add CORS Middleware
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Add Error Middleware
$app->addErrorMiddleware(true, true, true);

$userController = new UserController($db);
$courseController = new CourseController($db);
$postController = new PostController($db);
$contactController = new ContactController($db);

$app->get('/users', [$userController, 'getAllUsers']);
$app->get('/users/{id}', [$userController, 'getUserById']);
$app->post('/register', [$userController, 'createUser']);
$app->put('/users/{id}', [$userController, 'updateUser']);
$app->delete('/users/{id}', [$userController, 'deleteUser']);
$app->post('/login', [$userController, 'loginUser']);

$app->get('/courses', [$courseController, 'getCourses']);
$app->post('/registerCourse', [$courseController, 'createCourse']);
$app->put('/courses/{id}', [$courseController, 'updateCourse']);
$app->delete('/courses/{id}', [$courseController, 'deleteCourse']);

$app->get('/posts', [$postController, 'getPosts']);
$app->get('/posts/{id}', [$postController, 'getPostById']);
$app->post('/createPost', [$postController, 'createPost']);
$app->post('/posts/{id}', [$postController, 'updatePost']);
$app->delete('/posts/{id}', [$postController, 'deletePost']);

// Contact routes
$app->get('/contacts', [$contactController, 'getAllForms']);
$app->get('/contacts/{id}', [$contactController, 'getFormById']);
$app->post('/submitForm', [$contactController, 'submitForm']);
$app->put('/contacts/{id}', [$contactController, 'updateForm']);
$app->delete('/contacts/{id}', [$contactController, 'deleteForm']);

$app->run();
