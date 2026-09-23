<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Core\Router;
use Core\Session;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// Start Session
$session = new Session();

// Global Input Sanitization
$_GET = \Core\Session::sanitize($_GET);
$_POST = \Core\Session::sanitize($_POST);

$router = new Router();

// Define Routes
$router->get('/', function() {
    header("Location: /dashboard");
    exit;
});

// Auth
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Dashboard
$router->get('/dashboard', 'DashboardController@master');
$router->get('/dashboard/completed', 'DashboardController@completed');

// Projects
$router->get('/project/create', 'ProjectController@create');
$router->post('/project/store', 'ProjectController@store');
$router->get('/project/{id}', 'ProjectController@show');
$router->post('/project/{id}/complete', 'ProjectController@complete');


// Status and MOM
$router->post('/project/{id}/toggle-status', 'ProjectController@toggleStatus');
$router->post('/project/{id}/mom', 'ProjectController@addMom');

// Steps
$router->post('/project/{id}/step/{step_id}/toggle', 'StepController@toggle');

// Subtasks
$router->post('/project/{id}/step/{stepId}/subtask', 'SubTaskController@add');
$router->post('/project/{id}/subtask/{subtaskId}/toggle', 'SubTaskController@toggle');
$router->post('/project/{id}/subtask/{subtaskId}/delete', 'SubTaskController@delete');

// Team
$router->post('/project/{id}/team/add', 'TeamController@add');
$router->post('/project/{id}/team/{teamId}/toggle-access', 'TeamController@toggleAccess');
$router->post('/project/{id}/team/{teamId}/remove', 'TeamController@remove');

// Reports
$router->get('/project/{id}/report', 'ReportController@show');
$router->get('/project/{id}/report/pdf', 'ReportController@pdf');

// Admin
$router->get('/admin/users', 'AdminController@users');
$router->post('/admin/users/add', 'AdminController@addUser');
$router->post('/admin/users/delete/{id}', 'AdminController@deleteUser');
$router->get('/admin/steps', 'AdminController@steps');
$router->post('/admin/steps/add', 'AdminController@addStep');
$router->post('/admin/steps/delete/{id}', 'AdminController@deleteStep');

// Dispatch
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Server-side CSRF validation for all POST requests
if ($method === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    $sessionToken = Session::get('csrf_token');
    if (empty($token) || empty($sessionToken) || !hash_equals($sessionToken, $token)) {
        Session::flash('error', 'Invalid CSRF token or session expired.');
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/login'));
        exit;
    }
}

$router->dispatch($uri, $method);

// Clear flash messages
Session::clearFlash();


