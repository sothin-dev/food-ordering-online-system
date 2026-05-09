<?php

require_once __DIR__ . "/../app/controller/usersController.php";
require_once __DIR__ . "/../app/controller/homeController.php";
require_once __DIR__ . "/../app/controller/category.php";
require_once __DIR__ . "/../app/controller/orderController.php";
require_once __DIR__ . "/../app/controller/productController.php";
require_once __DIR__ . "/../app/controller/user/userHomeController.php";


class Route
{
    private static $routes = [];

    public static function get($uri, $action)
    {
        self::$routes['GET'][$uri] = $action;
    }

    public static function post($uri, $action)
    {
        self::$routes['POST'][$uri] = $action;
    }

    public static function delete($uri, $action)
    {
        self::$routes['DELETE'][$uri] = $action;
    }

    public static function dispatch($pdo)
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Method override (for DELETE, PUT)
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $requestMethod = strtoupper($_POST['_method']);
        }

        if (!isset(self::$routes[$requestMethod])) {
            echo "404 Not Found";
            return;
        }

        foreach (self::$routes[$requestMethod] as $route => $action) {

            // Convert /users/{id} → regex
            $pattern = preg_replace('#\{[\w]+\}#', '([\w-]+)', $route);
            $pattern = "#^$pattern$#";

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);

                $controllerName = $action[0];
                $method = $action[1];

                $controller = new $controllerName($pdo);
                call_user_func_array([$controller, $method], $matches);
                return;
            }
        }

        echo "404 Not Found";
    }
}

/// ===== Public Pages =====
Route::get('/', [UsersController::class, 'index']);

Route::get('/user/create', [UsersController::class, 'create']);
Route::post('/user/create', [UsersController::class, 'store']);

Route::get('/user/login', [UsersController::class, 'loginForm']);
Route::post('/user/login', [UsersController::class, 'login']);

Route::get('/user/logout', [UsersController::class, 'logout']);


// ===== Admin Dashboard =====
Route::get('/admin.home', [HomeController::class, 'index']);
Route::get('/admin/home', [HomeController::class, 'index']);


// ===== Admin Users =====
Route::get('/admin/users', [UsersController::class, 'index']);


// ===== Admin Category =====
Route::get('/admin/category', [categoryController::class, 'index']);
Route::get('/admin/category/create', [categoryController::class, 'create']);
Route::post('/admin/category/create', [categoryController::class, 'store']);
Route::get('/admin/category/edit', [categoryController::class, 'edit']);
Route::post('/admin/category/edit', [categoryController::class, 'update']);
Route::get('/admin/category/delete', [categoryController::class, 'delete']);


// ===== Admin Product =====
Route::get('/admin/product', [ProductController::class, 'index']);
Route::get('/admin/product/create', [ProductController::class, 'create']);
Route::post('/admin/product/create', [ProductController::class, 'store']);
Route::get('/admin/product/edit', [ProductController::class, 'edit']);
Route::post('/admin/product/edit', [ProductController::class, 'update']);
Route::get('/admin/product/show', [ProductController::class, 'show']);


// ===== Admin Orders =====
Route::get('/admin/order', [orderController::class, 'index']);
Route::get('/admin/order/show', [orderController::class, 'show']);




// ====== user home =======
Route::get('/user/home', [userHomeController::class, 'index']);
Route::get('/user/profile', [userHomeController::class, 'show']);
Route::get('/user/edit-profile', [userHomeController::class, 'edit']);
Route::post('/user/update-profile', [userHomeController::class, 'updateProfile']);
Route::get('/user/filter', [userHomeController::class, 'filterMenu']);
// cart action
Route::get('/user/cart', [userHomeController::class, 'showCart']);
// Route::post('/user/cart/add', [usersController::class, 'addProduct']);
// Route::post('/user/cart/add', [usersController::class, 'addProduct']);
Route::post('/user/cart/add', [userHomeController::class, 'addProduct']);
Route::post('/user/cart/remove', [userHomeController::class, 'removeFromCart']);
// cart
Route::post('/user/cart/increase', [userHomeController::class, 'increaseQty']);
Route::post('/user/cart/decrease', [userHomeController::class, 'decreaseQty']);

Route::get('/user/checkout', [userHomeController::class, 'checkout']);
Route::post('/user/order/place', [userHomeController::class, 'placeOrder']);
Route::get('/user/order/details', [userHomeController::class, 'showOrderDetail']);





Route::get('/user/orders', [userHomeController::class, 'showOrder']);
