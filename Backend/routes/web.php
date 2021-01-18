<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('register', 'LoginController@register');  // /api/auth/register
        $router->post('login', 'LoginController@login');        // /api/auth/login
        $router->post('logout', 'LoginController@logout');      // /api/auth/logout
    });

    $router->group(['prefix' => 'profile'], function () use ($router) {
        $router->get('', 'UserController@getAuthenticatedUser');                // /api/profile/
        $router->patch('', 'UserController@editAuthenticatedUser');             
        $router->patch('password', 'UserController@editAuthenticatedPassword'); // /api/profile/password
        $router->delete('', 'UserController@deleteAuthenticatedUser');          // /api/profile/
        $router->get('orders', 'UserController@getUserOrders');                 // /api/profile/orders
    });

    $router->group(['prefix' => 'stores'], function () use ($router) {
        $router->get('', 'StoreController@getStores');      // /api/store/
    });

    $router->group(['prefix' => 'order'], function () use ($router) {
        $router->post('', 'OrderController@createOrder');    // /api/store/
    });

    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->get('', 'ProductController@getProducts');                  // /api/products/
        $router->get('featured', 'ProductController@getFeaturedProducts');  // /api/products/featured
        $router->get('{id}', 'ProductController@getProductById');           // /api/products/{id}
        $router->post('list', 'ProductController@getProductsList');         // /api/products/list
    });

    // Owner routes
    $router->group(['prefix' => 'owner', 'middleware' => ['auth', 'role:1']], function () use ($router) {
        $router->get('orders', 'OrderController@getOrdersFiltered');        // /api/owner/orders
        $router->get('orders/{id}', 'OrderController@getOrderById');        // /api/owner/orders/{id}
        $router->patch('orders/{id}', 'OrderController@editOrderStatus');   // /api/owner/orders
    });

    // Admin routes
    $router->group(['prefix' => 'admin', 'middleware' => ['auth', 'role:2']], function () use ($router) {
        $router->get('users/all', 'UserController@getAllUsers');    // /api/admin/users/all
        $router->get('users/{id}', 'UserController@getUserById');   // /api/admin/users/{id}

        $router->get('orders', 'OrderController@getOrders');         // /api/admin/orders
        $router->delete('orders/{id}', 'OrderController@deleteOrder');
        $router->patch('orders/{id}', 'OrderController@editOrderStatusAdmin');

        $router->patch('products/{id}', 'ProductController@editProduct');
        $router->delete('products/{id}', 'ProductController@deleteProduct');
    });
});

// TODO: remove in production, here for CORS
$router->options('/{route:.*}', function () {
    return response(['status' => 'success']);;
});

$router->get('/{route:.*}', function () use ($router) {
    return view("vue-app"); // no route found, catch all and return Vue app
});
