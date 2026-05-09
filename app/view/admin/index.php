<?php require_once __DIR__ . "/../layout/header.php"; ?>

<div class="flex min-h-screen bg-slate-50">
    <?php require_once __DIR__ . "/../layout/sidebar.php"; ?>

    <main class="flex-1 p-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium uppercase tracking-wide text-emerald-600">Admin Dashboard</p>
                <h1 class="mt-1 text-3xl font-semibold tracking-tight text-slate-950">Business overview</h1>
            </div>
            <span class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm text-slate-600 shadow-sm">Welcome, Admin</span>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Users</p>
                        <h2 class="mt-2 text-3xl font-semibold text-slate-950"><?= $userCount ?></h2>
                    </div>
                    <span class="rounded-lg bg-blue-50 p-3 text-blue-600"><i class="fa-solid fa-user"></i></span>
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Foods</p>
                        <h2 class="mt-2 text-3xl font-semibold text-slate-950"><?= $productCount ?></h2>
                    </div>
                    <span class="rounded-lg bg-emerald-50 p-3 text-emerald-600"><i class="fa-solid fa-burger"></i></span>
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Orders</p>
                        <h2 class="mt-2 text-3xl font-semibold text-slate-950"><?= $orderCount ?></h2>
                    </div>
                    <span class="rounded-lg bg-amber-50 p-3 text-amber-600"><i class="fa-solid fa-cart-shopping"></i></span>
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Categories</p>
                        <h2 class="mt-2 text-3xl font-semibold text-slate-950"><?= $cateCount ?></h2>
                    </div>
                    <span class="rounded-lg bg-violet-50 p-3 text-violet-600"><i class="fa-solid fa-layer-group"></i></span>
                </div>
            </div>
        </div>

        <div class="mt-10 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-950">Recent Orders</h2>
            </div>

            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3">Order ID</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($orders as $index => $order): ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-medium text-slate-900">#<?= $order['id'] ?? $index + 1 ?></td>
                            <td class="px-6 py-4 text-slate-700"><?= $order['username'] ?></td>
                            <td class="px-6 py-4 font-medium text-slate-900">$<?= number_format((float)$order['total_price'], 2) ?></td>
                            <td class="px-6 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-semibold <?= ($order['status'] === 'done') ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'; ?>">
                                    <?= $order['status'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
