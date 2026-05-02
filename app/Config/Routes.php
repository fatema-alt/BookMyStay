<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --------------------
// AUTH
// --------------------
$routes->get('register', 'Auth::register');
$routes->post('register/store', 'Auth::storeRegister');
$routes->get('login', 'Auth::login');
$routes->post('login/check', 'Auth::checkLogin');
$routes->get('logout', 'Auth::logout');


// --------------------
// FRONTEND
// --------------------
$routes->get('/', 'Home::index');
$routes->get('room/search', 'Room::search');
$routes->get('booking/create/(:num)', 'Booking::create/$1', ['filter' => 'auth']);
$routes->post('booking/store', 'Booking::store', ['filter' => 'auth']);
$routes->get('booking/success/(:any)', 'Booking::success/$1', ['filter' => 'auth']);
$routes->get('my-bookings', 'User::bookings', ['filter' => 'auth']);
$routes->get('booking/cancel/(:num)', 'User::cancelBooking/$1', ['filter' => 'auth']);

//payment
$routes->get('payment/pay/(:num)', 'Payment::pay/$1', ['filter' => 'auth']);
$routes->post('payment/success', 'Payment::success');
$routes->post('payment/fail', 'Payment::fail');
$routes->post('payment/cancel', 'Payment::cancel');
$routes->post('payment/ipn', 'Payment::ipn');

//roomView
$routes->get('review/create/(:num)', 'Review::create/$1', ['filter' => 'auth']);
$routes->post('review/store', 'Review::store', ['filter' => 'auth']);

//view booking
$routes->get('my-bookings/view/(:num)', 'User::bookingDetails/$1', ['filter' => 'auth']);

//profile view
$routes->get('profile', 'User::profile', ['filter' => 'auth']);
$routes->post('profile/update', 'User::updateProfile', ['filter' => 'auth']);

//notifications
$routes->get('notifications/read/(:num)', 'User::readNotification/$1', ['filter' => 'auth']);

//invoicPDF
$routes->get('invoice/download/(:num)', 'Invoice::download/$1', ['filter' => 'auth']);

// --------------------
// ADMIN PANEL
// --------------------
$routes->group('admin', ['filter' => 'admin'], function ($routes) {

    $routes->get('dashboard', 'Admin::dashboard');

    // bookings
    $routes->get('bookings', 'Admin::bookings');
    $routes->get('booking/approve/(:num)', 'Admin::approveBooking/$1');
    $routes->get('booking/cancel/(:num)', 'Admin::cancelBooking/$1');

    // room types
    $routes->get('room-types', 'Admin::roomTypes');
    $routes->get('room-types/create', 'Admin::createRoomType');
    $routes->post('room-types/store', 'Admin::storeRoomType');
    $routes->get('room-types/edit/(:num)', 'Admin::editRoomType/$1');
    $routes->post('room-types/update/(:num)', 'Admin::updateRoomType/$1');
    $routes->get('room-types/delete/(:num)', 'Admin::deleteRoomType/$1');

    // rooms
    $routes->get('rooms', 'Admin::rooms');
    $routes->get('rooms/create', 'Admin::createRoom');
    $routes->post('rooms/store', 'Admin::storeRoom');
    $routes->get('rooms/edit/(:num)', 'Admin::editRoom/$1');
    $routes->post('rooms/update/(:num)', 'Admin::updateRoom/$1');
    $routes->get('rooms/delete/(:num)', 'Admin::deleteRoom/$1');

    //review section
    $routes->get('reviews', 'Admin::reviews');
    $routes->get('review/approve/(:num)', 'Admin::approveReview/$1');
    $routes->get('review/delete/(:num)', 'Admin::deleteReview/$1');

    //payments
    $routes->get('payments', 'Admin::payments');

});