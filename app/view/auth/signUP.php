<?php
require_once __DIR__ . "/../layout/header.php";
?>

<div class="flex min-h-[calc(100vh-140px)] items-center justify-center px-5 py-12">
    <div class="w-full max-w-lg rounded-lg border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-8 text-center">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-600 text-white">
                <i class="fa-solid fa-user-plus"></i>
            </span>
            <h2 class="mt-4 text-2xl font-semibold tracking-tight text-slate-950">Create account</h2>
            <p class="mt-2 text-sm text-slate-500">Join the ordering system and start building your cart.</p>
        </div>

        <!-- Message Container -->
        <p id="message" class="text-center font-semibold mb-4"></p>

        <form id="form" enctype="multipart/form-data">

            <!-- Username -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-medium text-slate-700">Username</label>
                <input
                    type="text"
                    name="username"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                    placeholder="Enter username">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input
                    type="email"
                    name="email"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                    placeholder="Enter email"
                    id="email">
                <p class="text-red-600 text-xs invisible" id="erroremail">Email must contain @</p>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                    placeholder="Enter password"
                    id="password">
                <p id="errorPs" class="text-red-600 text-xs invisible">Password must be at least 8 characters</p>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-medium text-slate-700">Confirm Password</label>
                <input
                    type="password"
                    name="confirm_password"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                    placeholder="Confirm password"
                    id="confirmpw">
                <p id="errorPasCf" class="text-red-600 text-xs invisible">Passwords do not match</p>
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-medium text-slate-700">Role</label>
                <select
                    name="role"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100">
                    <option value="">Select role</option>
                    <option value="admin">Admin</option>
                    <option value="user" selected>User</option>
                </select>
            </div>

            <!-- Profile Image -->
            <div class="mb-6">
                <label class="mb-2 block text-sm font-medium text-slate-700">Profile Image</label>

                <!-- Preview -->
                <div class="mb-2">
                    <img id="profilePreview" src="" alt="Profile Preview" class="h-24 w-24 rounded-lg border border-slate-200 object-cover hidden">
                </div>

                <!-- File Input -->
                <input
                    type="file"
                    name="profile"
                    accept="image/*"
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-slate-700 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                    id="profileInput">
            </div>


            <!-- Submit Button -->
            <button
                type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                <i class="fa-solid fa-check"></i>
                Save User
            </button>

            <a href="/user/login" class="mt-5 block text-center text-sm font-medium text-emerald-700 hover:underline">Already have an account? Login</a>

        </form>
    </div>
</div>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {

        // Profile image preview
        $('#profileInput').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#profilePreview').attr('src', e.target.result).removeClass('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                $('#profilePreview').attr('src', '').addClass('hidden');
            }
        });

        // Form submit
        $('#form').on('submit', function(e) {
            e.preventDefault();

            // Clear previous messages
            $('#message').text('').removeClass('text-red-600 text-green-600');
            $('#erroremail, #errorPs, #errorPasCf').addClass('invisible');

            // Front-end validation (trimmed)
            let email = $('#email').val().trim();
            let password = $('#password').val();
            let confirmPassword = $('#confirmpw').val();
            let valid = true;

            if (!email.includes('@')) {
                $('#erroremail').removeClass('invisible');
                valid = false;
            }
            if (password.length < 8) {
                $('#errorPs').removeClass('invisible');
                valid = false;
            }
            if (password !== confirmPassword) {
                $('#errorPasCf').removeClass('invisible');
                valid = false;
            }

            if (!valid) return; // stop submit if invalid

            // AJAX submit
            let formData = new FormData(this);

            $.ajax({
                url: '/user/create',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    try {
                        let res = JSON.parse(response);
                        if (res.status === 'success') {
                            $('#message').text(res.message).addClass('text-green-600');
                            $('#form')[0].reset();
                            $('#profilePreview').attr('src', '').addClass('hidden'); // hide preview after reset
                        } else {
                            $('#message').text(res.message).addClass('text-red-600');
                        }
                    } catch (e) {
                        $('#message').text('Unexpected error.').addClass('text-red-600');
                    }
                },
                error: function() {
                    $('#message').text('AJAX error.').addClass('text-red-600');
                }
            });

        });

    });
</script>
<?php
require_once __DIR__ . "/../layout/footer.php";
?>
