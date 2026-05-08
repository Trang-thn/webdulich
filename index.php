<?php
session_start();
require_once __DIR__ . "/app/core/Router.php";

$router = new Router();
//thuan mvc
// Admin
$router->get('/user/login','AdminController@loginForm');
$router->post('/login','AdminController@login');
$router->get('/dashboard','AdminController@dashboard');
$router->post('/user/register','AdminController@register');
$router->get('/logout','AdminController@logout');
$router->get('/profile','AdminController@profile');
$router->get('/home', 'AdminController@home');
$router->get('/admin','AdminController@admin');
// User
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
// Home
$router->get('/', 'HomeController@index');
$router->get('/search', 'HomeController@search');
// Tour
$router->get('/tour','TourController@index');
$router->get('/detail','TourController@detail');
$router->get('/tour/manage','TourController@manage');
// Booking
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
// Comment
$router->get('/comment/admin', 'CommentController@admin');
$router->post('/comment/add', 'CommentController@add');
$router->post('/comment/delete', 'CommentController@delete');
$router->post('/comment/deleteAdmin', 'CommentController@deleteAdmin');
$router->get('/comment/list', 'CommentController@list');
$router->get('/comment/search', 'CommentController@searchByTour');
$router->post('/comment/approveAdmin','CommentController@approveAdmin');


// API routes
// Admin
$router->post('/api/admin/login', 'AdminApiController@login');
$router->post('/api/admin/register', 'AdminApiController@register');
$router->get('/api/admin/dashboard', 'AdminApiController@dashboard');
$router->get('/api/admin/profile', 'AdminApiController@profile');
// User
$router->get('/api/users/list', 'UserApiController@list');
$router->get('/api/users/detail', 'UserApiController@detail');
$router->post('/api/users/add', 'UserApiController@add');
$router->post('/api/users/update', 'UserApiController@update');
$router->post('/api/users/delete', 'UserApiController@delete');
$router->get('/api/users/check', 'UserApiController@checkUsername');
// Tour
$router->get('/api/tours', 'TourApiController@list');
$router->get('/api/tours/detail', 'TourApiController@detail');
$router->get('/api/tours/manage', 'TourApiController@manage');
// Home
$router->get('/api/home', 'HomeApiController@index');
$router->get('/api/home/search', 'HomeApiController@search');
// Booking
$router->post('/api/bookings/create', 'BookingApiController@create');
$router->post('/api/bookings/update', 'BookingApiController@update');
$router->post('/api/bookings/cancel', 'BookingApiController@cancel');
$router->get('/api/bookings/user', 'BookingApiController@userHistory');
$router->get('/api/bookings/manage', 'BookingApiController@manage');
$router->get('/api/bookings/detail', 'BookingApiController@detail');

// Comment
$router->get('/api/comments', 'CommentApiController@listAll');
$router->get('/api/comments/byTour', 'CommentApiController@listByTour');
$router->post('/api/comments/add', 'CommentApiController@add');
$router->post('/api/comments/delete', 'CommentApiController@deleteAdmin');
$router->post('/api/comments/approve', 'CommentApiController@approveAdmin');

$router->run();
