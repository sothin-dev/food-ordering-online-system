<?php

require_once __DIR__ . "/../../model/order.php";
require_once __DIR__ . "/../../model/product.php";
require_once __DIR__ . "/../../model/user.php";
require_once __DIR__ . "/../../model/order.php";

class userHomeController
{

    private $cateModel;
    private $productModel;
    private $userModel;
    private $orderModel;

    public function __construct()
    {
        $this->cateModel = new cateModel;
        $this->productModel = new ProductModel;
        $this->userModel = new UserModel;
        $this->orderModel = new orderModel;
    }

    public function index()
    {
        // Pagination settings
        $limit = 12; // number of products per page
        $page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $start = ($page - 1) * $limit;

        // Get categories (for sidebar or quick actions)
        $numOfCate = $this->cateModel->countCategory();
        $categories = $this->cateModel->getAllCate(0, $numOfCate);

        // Get featured products with pagination
        $totalProducts = $this->productModel->countProducts();
        $totalPages = ceil($totalProducts / $limit);
        $featured = $this->productModel->getAll($start, $limit);

        require_once __DIR__ . "/../../view/user/index.php";
    }

    // show user infor by id
    public function show()
    {
        $id = $_GET['id'];
        $user = $this->userModel->findUserById($id);
        require_once __DIR__ . "/../../view/user/profile.php";
    }

    // bring user to edit form 
    public function edit()
    {
        $id = $_SESSION['userid'];
        $user = $this->userModel->findUserById($id);
        require_once __DIR__ . "/../../view/user/edit-profile.php";
    }

    // update user infor
    public function updateProfile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = $_SESSION['userid'];
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $role = htmlspecialchars($_POST['role']);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Password validation
        if ($password && $password !== $confirmPassword) {
            echo json_encode(['status' => 'error', 'message' => 'Passwords do not match']);
            exit;
        }

        // Image upload
        $imageData = null;
        $imageType = null;

        if (!empty($_FILES['profile']['tmp_name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($_FILES['profile']['type'], $allowed)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid image type']);
                exit;
            }
            if ($_FILES['profile']['size'] > 2000000) {
                echo json_encode(['status' => 'error', 'message' => 'Image too large']);
                exit;
            }
            $imageData = file_get_contents($_FILES['profile']['tmp_name']);
            $imageType = $_FILES['profile']['type'];
        }

        // Call model to update user
        $result = $this->userModel->edit($id, $username, $email, $role, $password, $imageData, $imageType);

        if ($result) {
            // Update session
            $_SESSION['username'] = $username;
            $_SESSION['useremail'] = $email;
            $_SESSION['role'] = $role;
            if ($imageData) {
                $_SESSION['profile'] = $imageData;
                $_SESSION['profile_type'] = $imageType;
            }
            echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
        }
    }

    public function filterMenu()
    {
        $cateId = isset($_GET['cateId']) ? (int)$_GET['cateId'] : 0;

        $products = $this->productModel->filterByCate($cateId);

        $output = '';
        if ($products) {
            foreach ($products as $item) {
                $output .= '<div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">';
                $output .= '<img src="data:image/jpeg;base64,' . base64_encode($item['image']) . '" class="h-48 w-full object-cover bg-slate-100" alt="' . htmlspecialchars($item['name']) . ' Image">';
                $output .= '<div class="p-4">';
                $output .= '<h4 class="text-lg font-semibold text-slate-950">' . htmlspecialchars($item['name']) . '</h4>';
                $output .= '<p class="mt-1 font-semibold text-emerald-600">$' . number_format((float)$item['price'], 2) . '</p>';
                $output .= '<form class="addToCart"><input type="hidden" name="product_id" value="' . $item['id'] . '">';
                $output .= '<button type="submit" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700"><i class="fa-solid fa-plus"></i>Add to Cart</button></form>';
                $output .= '</div></div>';
            }
        } else {
            $output = '<p class="col-span-full rounded-lg border border-slate-200 bg-white p-6 text-slate-500">No products found in this category.</p>';
        }

        echo $output;
    }

    public function showCart()
    {
        // session_start(); // make sure session is started
        $userId = $_GET['id'] ?? null;

        if ($userId) {
            $products = [];
            $totalPrice = 0;

            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $productId => $quantity) {
                    $product = $this->productModel->find($productId);

                    if ($product) {
                        // Attach quantity and subtotal to the product
                        $product['quantity'] = $quantity;
                        $product['subtotal'] = $quantity * $product['price'];

                        $totalPrice += $product['subtotal'];

                        $products[] = $product;
                    }
                }
            }

            require_once __DIR__ . "/../../view/user/cart.php";
        } else {
            header("location: /user/create");
            exit();
        }
    }

    public function increaseQty()
    {
        // session_start();

        $id = $_POST['product_id'];

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]++;
        }

        echo json_encode(["status" => "success"]);
    }

    public function decreaseQty()
    {
        // session_start();

        $id = $_POST['product_id'];

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]--;

            if ($_SESSION['cart'][$id] <= 0) {
                unset($_SESSION['cart'][$id]);
            }
        }

        echo json_encode(["status" => "success"]);
    }



    public function removeFromCart()
    {
        // session_start();

        if (isset($_POST['product_id'])) {
            $productId = intval($_POST['product_id']);

            if (isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]); // remove the product
            }

            // Redirect back to cart page
            header("Location: /user/cart?id=" . ($_SESSION['userid'] ?? ''));
            exit();
        } else {
            echo "Product ID missing!";
        }
    }

    public function addProduct()
    {
        // session_start();

        if (!isset($_POST['product_id'])) {
            echo json_encode(["status" => "error", "message" => "Product ID missing"]);
            return;
        }

        $product_id = intval($_POST['product_id']);

        // Create cart if not exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add product
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = 1;
        } else {
            $_SESSION['cart'][$product_id]++;
        }

        echo json_encode([
            "status" => "success",
            "cart_count" => array_sum($_SESSION['cart'])
        ]);
    }



    // go to checkout page and show sammary order
    public function checkout()
    {
        $products = [];
        $totalPrice = 0;

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $product = $this->productModel->find($productId);

                if ($product) {
                    // Attach quantity and subtotal to the product
                    $product['quantity'] = $quantity;
                    $product['subtotal'] = $quantity * $product['price'];

                    $totalPrice += $product['subtotal'];

                    $products[] = $product;
                }
            }
        }

        require_once __DIR__ . "/../../view/user/checkout.php";
    }

    // start order
    public function placeOrder()
    {
        $userId = $_SESSION['userid'];
        if (!empty($_SESSION['cart']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $totalPrice = $_POST['total_price'];
            $userId = $_SESSION['userid'];
            $status = $_POST['status'];

            $lastOrderId = $this->orderModel->createOrder($userId, $totalPrice, $status);

            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $product = $this->productModel->find($productId);

                if ($product) {
                    // Attach quantity and subtotal to the product
                    // $product['subtotal'] = $quantity * $product['price'];

                    $this->orderModel->createOrderItems($lastOrderId, $productId, $quantity);
                }
            }
        }
        unset($_SESSION['cart']);
        header("location: /user/orders?id={$_SESSION['userid']}");
        exit();
    }


    public function showOrder()
    {
        $userId = $_GET['id'] ?? null;
        if (!$userId) {
            header("/user/create");
            exit();
        }

        $orders = $this->orderModel->findOrderByUserId($userId);

        require_once __DIR__ . "/../../view/user/order.php";
    }

    public function showOrderDetail()
    {
        // session_start();
        $userId = $_SESSION['userid'];
        $orderId = $_GET['id'] ?? null;

        if (!$orderId) {
            header("Location: /user/orders?id=$userId");
            exit();
        }

        $orderItems = $this->orderModel->findOrderWithItems($orderId, $userId);

        if (!$orderItems) {
            header("Location: /user/orders?id=$userId");
            exit();
        }

        // Calculate total price
        $totalPrice = 0;
        foreach ($orderItems as $item) {
            $totalPrice += $item['subtotal'];
        }

        require_once __DIR__ . "/../../view/user/detailCart.php";
    }
}
