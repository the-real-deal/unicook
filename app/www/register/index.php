<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageHead.php";
require_once "components/ErrorNotification.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "lib/auth.php";

$db = Database::connectDefault();
$login = LoginSession::autoLogin($db);
?>
<!DOCTYPE html>
<html lang="en">
<?= PageHead("Register", [ "/css/loginRegister.css" ]) ?>
<body>
    <?= ErrorNotification() ?>
    <?= Navbar($login) ?>
    <main>
        <div class="d-flex justify-content-center">
            <form id="registerForm" action="/api/auth/register.php" method="POST" class="w-100 mx-4 p-5">
                <div class="d-flex align-items-center justify-content-center mx-auto" id="logoApp">
                    <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none"
                        stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chef-hat flex-shrink-0" aria-hidden="true">
                        <path
                            d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z">
                        </path>
                        <path d="M6 17h12"></path>
                    </svg>
                </div>
                <div class="text-center">
                    <h1 class="text-2xl font-bold mt-4 mb-2">Create Account</h1>
                    <p class="mb-6 text-center">Join Unicook to share and discover recipes</p>
                </div>

                <label for="username" class="mt-4 mb-1">Username</label>
                <div class="d-flex align-items-center gap-2 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-user flex-shrink-0" aria-hidden="true">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <input type="text" id="username" name="username" placeholder="username" class="w-100" required />
                </div>

                <label for="email" class="mt-4 mb-1">Email</label>
                <div class="d-flex align-items-center gap-2 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-mail flex-shrink-0" aria-hidden="true">
                        <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                    </svg>
                    <input type="email" id="email" name="email" placeholder="email" class="w-100" required />
                </div>

                <label for="password" class="mt-4 mb-1">Password</label>
                <div class="d-flex align-items-center gap-2 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-lock flex-shrink-0" aria-hidden="true">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" id="password" name="password" placeholder="password" class="w-100" required />
                </div>

                <label for="confirmPassword" class="mt-4 mb-1">Confirm Password</label>
                <div class="d-flex align-items-center gap-2 p-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-lock flex-shrink-0" aria-hidden="true">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="confirm password" class="w-100" required />
                </div>

                <label for="register" hidden>submit the registration</label>
                <input type="submit" id="register" class="w-100 my-4 py-3" value="Sign Up" />
                <div class="d-flex justify-content-between align-items-center gap-4">
                    <a href="javascript:history.back()" class="text-decoration-none">&#8592; Back</a>
                    <a href="/login/" class="text-decoration-none">Log in &#8594;</a>
                </div>

            </form>
        </div>
    </main>
    <?= Footer() ?>
    <script type="module" src="/js/bootstrap.js"></script>
    <script type="module" src="main.js"></script>
</body>
</html>