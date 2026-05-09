<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

function adminNavClass($path, $currentPath)
{
    $active = strpos($currentPath, $path) !== false;
    return $active
        ? 'bg-emerald-500 text-white shadow-sm'
        : 'text-slate-300 hover:bg-slate-800 hover:text-white';
}
?>

<aside class="sticky top-[67px] h-[calc(100vh-67px)] w-72 shrink-0 border-r border-slate-800 bg-slate-950 text-slate-100">
    <div class="border-b border-slate-800 p-6">
        <div class="flex items-center gap-3">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-lg bg-emerald-500 text-white">
                <i class="fa-solid fa-chart-line"></i>
            </span>
            <div>
                <div class="font-semibold tracking-tight">Admin Panel</div>
                <p class="text-xs text-slate-400">Orders and inventory</p>
            </div>
        </div>
    </div>

    <nav class="space-y-1 p-4">
        <a href="/admin/home" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition <?= adminNavClass('/admin/home', $currentPath) ?>">
            <i class="fa-solid fa-house w-5"></i>
            Dashboard
        </a>

        <a href="/admin/users" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition <?= adminNavClass('/admin/users', $currentPath) ?>">
            <i class="fa-solid fa-user-group w-5"></i>
            Users
        </a>

        <a href="/admin/category" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition <?= adminNavClass('/admin/category', $currentPath) ?>">
            <i class="fa-solid fa-layer-group w-5"></i>
            Categories
        </a>

        <a href="/admin/product" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition <?= adminNavClass('/admin/product', $currentPath) ?>">
            <i class="fa-solid fa-burger w-5"></i>
            Products
        </a>

        <a href="/admin/order" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition <?= adminNavClass('/admin/order', $currentPath) ?>">
            <i class="fa-solid fa-receipt w-5"></i>
            Orders
        </a>
    </nav>

    <div class="absolute bottom-0 left-0 right-0 border-t border-slate-800 p-4">
        <a href="/user/logout" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-red-200 transition hover:bg-red-500 hover:text-white">
            <i class="fa-solid fa-right-from-bracket w-5"></i>
            Logout
        </a>
    </div>
</aside>
