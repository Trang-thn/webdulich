<?php
session_start();
require_once __DIR__ . "/app/core/Router.php";

$router = new Router();

// Admin routes
$router->get('/user/login','AdminController@loginForm');
$router->post('/login','AdminController@login');
$router->get('/dashboard','AdminController@dashboard');
$router->post('/user/register','AdminController@register');
$router->get('/logout','AdminController@logout');
$router->get('/profile','AdminController@profile');
$router->get('/home', 'AdminController@home');
$router->get('/admin','AdminController@admin');

// User routes
$router->get('/user/register','UserController@registerForm'); 
$router->post('/user/register','UserController@register');    
$router->get('/user/edit-profile', 'UserController@editProfile');
$router->post('/user/update-profile', 'UserController@updateProfile');
$router->get('/user/manage', 'UserController@manage');
$router->get('/user/add', 'UserController@add');
$router->post('/user/add', 'UserController@add');
$router->get('/user/edit', 'UserController@editForm');
$router->post('/user/update', 'UserController@update');
$router->post('/user/delete', 'UserController@delete');
$router->get('/user/import', 'UserController@import');
$router->post('/user/import', 'UserController@import');
$router->get('/user/export', 'UserController@export');

// Home routes
$router->get('/', 'HomeController@index');
$router->get('/search', 'HomeController@search');

// Tour routes
$router->get('/tour','TourController@index');
$router->get('/detail','TourController@detail');
$router->get('/tour/manage','TourController@manage');

// Booking routes
$router->get('/booking/form', 'BookingController@form');
$router->post('/booking/create', 'BookingController@createBooking');
$router->post('/booking/update', 'BookingController@updateBooking');
$router->post('/booking/cancel', 'BookingController@cancelBooking');
$router->post('/booking/cancelhome', 'BookingController@cancelAndGoHome');
$router->post('/booking/delete', 'BookingController@delete');
$router->get('/booking/history', 'BookingController@history');
$router->get('/booking/userHistory', 'BookingController@userHistory');
$router->get('/booking/manage', 'BookingController@manage');
$router->get('/booking/export', 'BookingController@export');
$router->get('/booking/edit', 'BookingController@editForm');
$router->get('/booking/detail', 'BookingController@detail');
$router->get('/booking/success', 'BookingController@success');
$router->get('/booking/successEdit', 'BookingController@successEdit');

// Comment routes
$router->get('/comment/admin', 'CommentController@admin');
$router->post('/comment/add', 'CommentController@add');
$router->post('/comment/delete', 'CommentController@delete');
$router->post('/comment/deleteAdmin', 'CommentController@deleteAdmin');
$router->get('/comment/list', 'CommentController@list'); 
$router->get('/comment/search', 'CommentController@searchByTour');
$router->post('/comment/approveAdmin','CommentController@approveAdmin');

$router->run();
