<?php
require_once __DIR__ . "/../layout/header.php";
?>

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <?php require_once __DIR__ . "/../layout/sidebaruser.php"; ?>

    <!-- Main Content -->
    <main class="ml-72 flex-1 bg-slate-50 p-8">

        <!-- Welcome Section -->
        <div class="mb-8 flex flex-col gap-4 rounded-lg border border-slate-200 bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-medium uppercase tracking-wide text-emerald-600">Today's Menu</p>
                <h2 class="mt-1 text-3xl font-semibold tracking-tight text-slate-950">Welcome, <?= $_SESSION['username'] ?? 'User' ?></h2>
                <p class="mt-2 text-slate-500">Browse categories, add items to cart, and checkout quickly.</p>
            </div>
            <a href="/user/cart?id=<?= $_SESSION['userid'] ?>" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                <i class="fa-solid fa-cart-shopping"></i>
                View Cart
            </a>
        </div>

        <!-- Quick Actions / Categories -->
        <div class="mb-8 flex flex-wrap gap-3">
            <a href="#"
                data-cateid="0"
                class="category-filter inline-flex items-center justify-center rounded-full border border-emerald-200 bg-emerald-50 px-5 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">

                <span>
                    All
                </span>
            </a>
            <?php foreach ($categories as $category): ?>
                <a href="#"
                    data-cateid="<?= $category['id'] ?>"
                    class="category-filter inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">

                    <span>
                        <?= $category['name'] ?>
                    </span>
                </a>

            <?php endforeach; ?>
        </div>

        <!-- Featured Menu Section -->
        <div id="menuContainer">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-2xl font-semibold text-slate-950">Featured Menu</h3>
                <span class="text-sm text-slate-500"><?= $totalProducts ?> items</span>
            </div>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" id="products">
                <?php foreach ($featured as $item): ?>
                    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                        <img
                            src="data:image/jpeg;base64,<?= base64_encode($item['image']) ?>"
                            class="h-48 w-full object-cover bg-slate-100"
                            alt="<?= $item['name'] ?> Image">
                        <div class="p-4">
                            <h4 class="text-lg font-semibold text-slate-950"><?= $item['name'] ?></h4>
                            <p class="mt-1 font-semibold text-emerald-600">$<?= number_format((float)$item['price'], 2) ?></p>
                            <form id="addToCart" class="addToCart">
                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">

                                <button
                                    type="submit"
                                    class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                    <i class="fa-solid fa-plus"></i>
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-8 flex justify-center gap-2">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>"
                            class="rounded-lg px-3 py-2 text-sm font-semibold <?= ($i == $page) ? 'bg-emerald-600 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // filter
        const categoryLinks = document.querySelectorAll(".category-filter");
        const productsDiv = document.getElementById("products");

        categoryLinks.forEach(link => {
            link.addEventListener("click", function(e) {
                e.preventDefault();
                const cateId = this.dataset.cateid;

                fetch(`/user/filter?cateId=${cateId}`)
                    .then(response => response.text())
                    .then(html => {
                        productsDiv.innerHTML = html;
                    });
            });
        });


        productsDiv.addEventListener("submit", function(e) {
            const form = e.target.closest(".addToCart");
            if (!form) return;

            e.preventDefault();

            fetch("/user/cart/add", {
                    method: "POST",
                    body: new FormData(form)
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                });
        });

    });
</script>

<?php
require_once __DIR__ . "/../layout/footer.php";
?>
