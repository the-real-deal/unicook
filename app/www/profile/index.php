<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "components/PageHead.php";
require_once "components/ErrorNotification.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/FileInput.php";
require_once "components/RecipeCard.php";
require_once "lib/core/api.php";
require_once "lib/core/db.php";
require_once "lib/users.php";

$user = false;

$server = new ApiServer();
$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    global $user;

    $userId = $req->getParam("userId");
    if ($userId === null) {
        $res->redirect("/404/");
    }

    $db = Database::connectDefault();
    try {
        $user = User::fromId($db, $userId);
        if ($user === false) {
            $res->redirect("/404/");
        }
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});
$server->respond();
?>
<!DOCTYPE html>
<html lang="en">
<?= PageHead("Profile", [ "style.css" ]) ?>
<body>
    <?= ErrorNotification() ?>
    <?= Navbar() ?>
    <main>
        <section id="profile-section" class="container my-5 mx-auto">
            <div class="row align-items-center justify-content-center p-5">
                <div class="col-12 col-md-4 text-center mb-3">
                    <img id="avatarImage" alt="profile picture">
                    <button id="delete-image-button">&#128473;</button>
                </div>
                <div class="col-12 col-md-8 text-center text-md-start">
                    <h3 class="mb-1 fw-bold">
                        <?php
                        echo $user->username;
                        if ($user->isAdmin) {
                        ?>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" width="35px" height="35px" class="mx-1 pb-1" viewBox="0 0 1920 1920">
                            <path d="M983.727 5.421 1723.04 353.62c19.765 9.374 32.414 29.252 32.414 51.162v601.525c0 489.6-424.207 719.774-733.779 887.943l-34.899 18.975c-8.47 4.517-17.731 6.889-27.105 6.889-9.262 0-18.523-2.372-26.993-6.89l-34.9-18.974C588.095 1726.08 164 1495.906 164 1006.306V404.78c0-21.91 12.65-41.788 32.414-51.162L935.727 5.42c15.134-7.228 32.866-7.228 48 0ZM757.088 383.322c-176.075 0-319.285 143.323-319.285 319.398 0 176.075 143.21 319.285 319.285 319.285 1.92 0 3.84 0 5.76-.113l58.504 58.503h83.689v116.781h116.781v83.803l91.595 91.482h313.412V1059.05l-350.57-350.682c.114-1.807.114-3.727.114-5.647 0-176.075-143.21-319.398-319.285-319.398Zm0 112.942c113.732 0 206.344 92.724 205.327 216.62l-3.953 37.271 355.426 355.652v153.713h-153.713l-25.412-25.299v-149.986h-116.78v-116.78H868.108l-63.812-63.7-47.209 5.309c-113.732 0-206.344-92.5-206.344-206.344 0-113.732 92.612-206.456 206.344-206.456Zm4.98 124.98c-46.757 0-84.705 37.948-84.705 84.706s37.948 84.706 84.706 84.706c46.757 0 84.706-37.948 84.706-84.706s-37.949-84.706-84.706-84.706Z" fill-rule="evenodd"/>
                        </svg>
                        <?php
                        }
                        ?> 
                    </h3>
                    <p class="fw-semibold mb-0"><?= $user->email?></p>
                    <p><small>created at <?= $user->createdAt->format('d/m/Y')?>.</small></p>
                    <form id="imageForm" action="/api/users/image/upload.php" method="POST"
                        class="mt-4 d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-start gap-2 mx-auto mx-md-0">
                        <label for="image">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="CurrentColor">
                                <rect x="0" fill="none" width="24" height="24"/>
                                <g>
                                    <path d="M23 4v2h-3v3h-2V6h-3V4h3V1h2v3h3zm-8.5 7c.828 0 1.5-.672 1.5-1.5S15.328 8 14.5 8 13 8.672 13 9.5s.672 1.5 1.5 1.5zm3.5 3.234l-.513-.57c-.794-.885-2.18-.885-2.976 0l-.655.73L9 9l-3 3.333V6h7V4H6c-1.105 0-2 .895-2 2v12c0 1.105.895 2 2 2h12c1.105 0 2-.895 2-2v-7h-2v3.234z"/>
                                </g>
                            </svg>
                            Change Profile Picture
                        </label>
                        <?= FileInput("image", FileType::Image, hidden: true) ?>
                        <input id="upload-button" type="submit" value="Upload new image" />
                        
                    </form>
                    <button id="logout-button" class="mt-4 d-flex align-items-center justify-content-center justify-content-md-start gap-2 mx-auto mx-md-0 ps-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" stroke="CurrentColor">
                        <path d="M12.9999 2C10.2385 2 7.99991 4.23858 7.99991 7C7.99991 7.55228 8.44762 8 8.99991 8C9.55219 8 9.99991 7.55228 9.99991 7C9.99991 5.34315 11.3431 4 12.9999 4H16.9999C18.6568 4 19.9999 5.34315 19.9999 7V17C19.9999 18.6569 18.6568 20 16.9999 20H12.9999C11.3431 20 9.99991 18.6569 9.99991 17C9.99991 16.4477 9.55219 16 8.99991 16C8.44762 16 7.99991 16.4477 7.99991 17C7.99991 19.7614 10.2385 22 12.9999 22H16.9999C19.7613 22 21.9999 19.7614 21.9999 17V7C21.9999 4.23858 19.7613 2 16.9999 2H12.9999Z" fill="#000000"/>
                        <path d="M13.9999 11C14.5522 11 14.9999 11.4477 14.9999 12C14.9999 12.5523 14.5522 13 13.9999 13V11Z" fill="#000000"/>
                        <path d="M5.71783 11C5.80685 10.8902 5.89214 10.7837 5.97282 10.682C6.21831 10.3723 6.42615 10.1004 6.57291 9.90549C6.64636 9.80795 6.70468 9.72946 6.74495 9.67492L6.79152 9.61162L6.804 9.59454L6.80842 9.58848C6.80846 9.58842 6.80892 9.58778 5.99991 9L6.80842 9.58848C7.13304 9.14167 7.0345 8.51561 6.58769 8.19098C6.14091 7.86637 5.51558 7.9654 5.19094 8.41215L5.18812 8.41602L5.17788 8.43002L5.13612 8.48679C5.09918 8.53682 5.04456 8.61033 4.97516 8.7025C4.83623 8.88702 4.63874 9.14542 4.40567 9.43937C3.93443 10.0337 3.33759 10.7481 2.7928 11.2929L2.08569 12L2.7928 12.7071C3.33759 13.2519 3.93443 13.9663 4.40567 14.5606C4.63874 14.8546 4.83623 15.113 4.97516 15.2975C5.04456 15.3897 5.09918 15.4632 5.13612 15.5132L5.17788 15.57L5.18812 15.584L5.19045 15.5872C5.51509 16.0339 6.14091 16.1336 6.58769 15.809C7.0345 15.4844 7.13355 14.859 6.80892 14.4122L5.99991 15C6.80892 14.4122 6.80897 14.4123 6.80892 14.4122L6.804 14.4055L6.79152 14.3884L6.74495 14.3251C6.70468 14.2705 6.64636 14.1921 6.57291 14.0945C6.42615 13.8996 6.21831 13.6277 5.97282 13.318C5.89214 13.2163 5.80685 13.1098 5.71783 13H13.9999V11H5.71783Z" fill="#000000"/>
                        </svg>
                        Log out
                    </button>
                </div>
            </div>
        </section>
        <section id="recipes-section" class="container my-5 mx-auto">
            <div class="container-fluid mb-2 mx-auto p-4">
                <h3 class="col-12 col-md-4 text-center mb-3"><strong>Published Recipes</strong></h3>
                <div class="row">
                    <?= RecipeCard("1", "1", "Recipe Title#1", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                    <?= RecipeCard("2", "2", "Recipe Title#2", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                    <?= RecipeCard("3", "3", "Recipe Title#3", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                    <?= RecipeCard("4", "4", "Recipe Title#4", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                </div>
            </div>
            <div class="container-fluid  mb-2 mx-auto p-4">
                <h3 class="col-12 col-md-4 text-center mb-3"><strong>Saved Recipes</strong></h3>
                <div class="row ">
                    <?= RecipeCard("5", "1", "Recipe Title#1", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                    <?= RecipeCard("6", "2", "Recipe Title#2", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                    <?= RecipeCard("7", "3", "Recipe Title#3", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                    <?= RecipeCard("8", "4", "Recipe Title#4", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                </div>
                
            </div>
        </section>
    </main> 
    <?= Footer() ?>
    <script type="module" src="/js/bootstrap.js"></script>
    <script src="/js/recipeCard.js"></script>
    <script type="module" src="main.js"></script>
</body>
</html>
