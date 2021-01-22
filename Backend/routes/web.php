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
        $router->post('register', 'LoginController@register');                  // /api/auth/register
        $router->post('login', 'LoginController@login');                        // /api/auth/login
        $router->post('logout', 'LoginController@logout');                      // /api/auth/logout
    });

    $router->group(['prefix' => 'profile'], function () use ($router) {
        $router->get('', 'UserController@getAuthenticatedUser');                // /api/profile/
        $router->patch('', 'UserController@editAuthenticatedUser');             
        $router->delete('', 'UserController@deleteAuthenticatedUser');          // /api/profile/
        $router->patch('password', 'UserController@editAuthenticatedPassword'); // /api/profile/password
        $router->get('orders', 'UserController@getUserOrders');                 // /api/profile/orders
    });

    $router->group(['prefix' => 'stores'], function () use ($router) {
        $router->get('', 'StoreController@getStores');                          // /api/store
    });

    $router->group(['prefix' => 'order'], function () use ($router) {
        $router->post('', 'OrderController@createOrder');                       // /api/order
    });

    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->get('', 'ProductController@getProducts');                      // /api/products/
        $router->get('featured', 'ProductController@getFeaturedProducts');      // /api/products/featured
        $router->get('{id}', 'ProductController@getProductById');               // /api/products/{id}
        $router->post('list', 'ProductController@getProductsList');             // /api/products/list
    });

    // Owner routes
    $router->group(['prefix' => 'owner', 'middleware' => ['auth', 'role:1']], function () use ($router) {
        $router->get('orders', 'OrderController@getOrdersFiltered');            // /api/owner/orders
        $router->get('orders/{id}', 'OrderController@getOrderById');            // /api/owner/orders/{id}
        $router->patch('orders/{id}', 'OrderController@editOrderStatus');       // /api/owner/orders
    });

    // Admin routes
    $router->group(['prefix' => 'admin', 'middleware' => ['auth', 'role:2']], function () use ($router) {
        $router->get('users/all', 'UserController@getAllUsers');                // /api/admin/users/all
        $router->get('users/{id}', 'UserController@getUserById');               // /api/admin/users/{id}
        $router->delete('users/{id}', 'UserController@deleteUser');             // /api/admin/users/{id}
        $router->post('users', 'LoginController@createUserAdmin');              // /api/admin/users

        $router->get('orders', 'OrderController@getOrders');                    // /api/admin/orders
        $router->delete('orders/{id}', 'OrderController@deleteOrder');
        $router->patch('orders/{id}', 'OrderController@editOrderStatusAdmin');

        $router->patch('products/{id}', 'ProductController@editProduct');       // /api/admin/products/{id}
        $router->delete('products/{id}', 'ProductController@deleteProduct');

        $router->patch('stores/{id}', 'StoreController@editStore');             // /api/admin/stores/{id}
        $router->delete('stores/{id}', 'StoreController@deleteStore');

        $router->get('images/folders', 'ImageController@getFolders');           // /api/admin/images/folders
        $router->get('images/{folder}', 'ImageController@getFiles');            // /api/admin/images/{folder}
        $router->post('images/{folder}', 'ImageController@handleFileUpload');   
        $router->delete('images', 'ImageController@handleFileDelete');          // /api/admin/images
    });
});

$router->get('/{route:.*}', function () use ($router) {
    return view("vue-app"); // no route found, catch all and return Vue app
});
