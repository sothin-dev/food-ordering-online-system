<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function userNavClass($path, $currentPath)
{
    return $currentPath === $path
        ? 'bg-emerald-600 text-white shadow-sm'
        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950';
}
?>

<aside class="fixed left-0 top-[67px] flex h-[calc(100vh-67px)] w-72 flex-col justify-between border-r border-slate-200 bg-white/95 backdrop-blur">
    <div>
        <div class="border-b border-slate-200 px-6 py-5">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-600 text-white">
                    <i class="fa-solid fa-utensils"></i>
                </span>
                <div>
                    <h1 class="font-semibold text-slate-950">Food Order</h1>
                    <p class="text-xs text-slate-500">Your daily menu</p>
                </div>
            </div>
        </div>

        <nav class="space-y-1 p-4">
            <a href="/user/home" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition <?= userNavClass('/user/home', $currentPath) ?>">
                <i class="fa-solid fa-table-list w-5"></i>
                Menu
            </a>

            <a href="/user/cart?id=<?= $_SESSION['userid'] ?>" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition <?= userNavClass('/user/cart', $currentPath) ?>">
                <i class="fa-solid fa-cart-shopping w-5"></i>
                My Cart
            </a>

            <a href="/user/orders?id=<?= $_SESSION['userid'] ?>" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition <?= userNavClass('/user/orders', $currentPath) ?>">
                <i class="fa-solid fa-box-open w-5"></i>
                Order History
            </a>

            <a href="/user/profile?id=<?= $_SESSION['userid'] ?>" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition <?= userNavClass('/user/profile', $currentPath) ?>">
                <i class="fa-solid fa-circle-user w-5"></i>
                Profile
            </a>
        </nav>
    </div>

    <div class="border-t border-slate-200 p-4">
        <a href="/user/logout" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-red-600 transition hover:bg-red-50">
            <i class="fa-solid fa-right-from-bracket w-5"></i>
            Logout
        </a>
    </div>
</aside>
