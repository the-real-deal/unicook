<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageHead.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";

?>
<!DOCTYPE html>
<html lang="en">
<?= PageHead("Login", [ "/css/loginRegister.css" ]) ?>
<body>
    <?= Navbar() ?>
        <main>
            <div class="d-flex justify-content-center">
                <form id="loginForm" action="/api/auth/login.php" method="POST" class="w-100 mx-4 p-5">
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
                        <h1 class="text-2xl font-bold mt-4 mb-2">Welcome Back!</h1>
                        <p class="mb-6 text-center text-muted-foreground">Log in to save recipes and share your own</p>
                    </div>

                    <label for="email" class="mt-4 mb-1">Email</label>
                    <div class="d-flex align-items-center gap-2 p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-mail absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-muted-foreground flex-shrink-0"
                            aria-hidden="true">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        </svg>
                        <input type="email" id="email" name="email" placeholder="email" class="w-100" required />
                    </div>

                    <label for="password" class="mt-4 mb-1">Password</label>
                    <div class="d-flex align-items-center gap-2 p-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-lock absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-muted-foreground flex-shrink-0"
                            aria-hidden="true">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <input type="password" id="password" name="password" placeholder="password" class="w-100" required />
                    </div>

                    <label for="login" hidden>submit the login</label>
                    <input type="submit" id="login" class="w-100 my-4 py-3" value="Log In" />
                    <div class="d-flex justify-content-between align-items-center gap-4 ">
                        <a href="javascript:history.back()" class="text-decoration-none">&#8592; Back</a>
                        <a href="/register" class="text-decoration-none">Sign up &#8594;</a>
                    </div>

                </form>
            </div>
        </main>
    <?php 
    Footer();
    ?>
    <script type="module" src="/js/bootstrap.js"></script>
    <script type="module" src="main.js"></script>
</body>
</html>