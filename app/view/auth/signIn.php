<?php
require_once __DIR__ . "/../layout/header.php";
$message = isset($_GET['message']) ? $_GET['message'] : "";
?>

<main class="flex min-h-[calc(100vh-140px)] items-center justify-center px-5 py-12">
    <form action="/user/login" method="POST" class="w-full max-w-md rounded-lg border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-8 text-center">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-600 text-white">
                <i class="fa-solid fa-right-to-bracket"></i>
            </span>
            <h2 class="mt-4 text-2xl font-semibold tracking-tight text-slate-950">Welcome back</h2>
            <p class="mt-2 text-sm text-slate-500">Sign in to manage orders and continue shopping.</p>
        </div>

        <?php if ($message === 'logined') : ?>
            <p class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-center text-sm font-medium text-red-700">
                Your email is already registered.
            </p>
        <?php endif; ?>

        <div class="mb-4">
            <label for="useremail" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
            <input type="email"
                name="useremail"
                required
                placeholder="admin@example.com"
                class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
        </div>

        <div class="mb-6">
            <label for="userpassword" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
            <input type="password"
                name="userpassword"
                required
                placeholder="Enter your password"
                class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
            <i class="fa-solid fa-arrow-right-to-bracket"></i>
            Login
        </button>

        <a href="/user/create" class="mt-5 block text-center text-sm font-medium text-emerald-700 hover:underline">Create a new account</a>
    </form>
</main>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
