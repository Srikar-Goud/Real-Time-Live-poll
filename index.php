<?php
session_start();
require_once 'config/database.php';
require_once 'config/app.php';
require_once 'core/Router.php';
require_once 'core/Auth.php';
require_once 'core/VotingEngine.php';

$router = new Router();

// Define routes
$router->get('/', 'PollController@index');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/polls', 'PollController@index');
$router->get('/polls/{id}', 'PollController@show');
$router->post('/vote', 'VoteController@store');
$router->get('/results/{id}', 'VoteController@results');
$router->get('/admin', 'AdminController@dashboard');
$router->get('/admin/polls', 'AdminController@polls');
$router->post('/admin/polls/create', 'AdminController@createPoll');
$router->get('/admin/polls/{id}/ips', 'AdminController@viewIPs');
$router->post('/admin/release-ip', 'AdminController@releaseIP');
$router->get('/admin/vote-history/{poll_id}', 'AdminController@voteHistory');

// AJAX endpoints
$router->post('/ajax/vote', 'VoteController@ajaxVote');
$router->get('/ajax/results/{id}', 'VoteController@ajaxResults');
$router->post('/ajax/release-ip', 'AdminController@ajaxReleaseIP');

$router->dispatch();
