<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageHead.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
?>
<!DOCTYPE html>
<html lang="en">
<?= PageHead("Page Not Found", [ "style.css" ], base: "/404/") ?>
<body>
    <?= Navbar() ?>
    <main class="container-fluid" id="error-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="error-content text-center">
                        <div class="error-icon mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.165 6.598C9.954 7.478 9.64 8.36 9 9c-.64.64-1.521.954-2.402 1.165A6 6 0 0 0 8 22c7.732 0 14-6.268 14-14a6 6 0 0 0-11.835-1.402Z"/>
                                <path d="M5.341 10.62a4 4 0 1 0 5.279-5.28"/>
                                <path d="M13 18h.01"/>
                            </svg>
                        </div>
                        <h1 class="display-1 fw-bold mb-3">404</h1>
                        <h2 class="h2 fw-semibold mb-4">Page Not Found</h2>
                        <p class="lead mb-5">Oops! The page you're looking for seems to have been eaten. Maybe it was too delicious to resist!</p>
                        
                        <div class="d-flex gap-3 justify-content-center flex-wrap mb-5">
                            <a href="/" class="btn btn-primary px-4 py-2">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?= Footer() ?>
    <script type="module" src="/js/bootstrap.js"></script>
</body>
</html>