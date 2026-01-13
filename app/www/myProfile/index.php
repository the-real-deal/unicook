<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "components/PageOpening.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/FileInput.php";
require_once "components/RecipeCard.php";

PageOpening("My Profile", ["style.css"]);
?>

<body>
    <?php 
    Navbar();
    ?>
<main>
    <section id="profile-section" class="container my-5 mx-auto">
        <div class="row align-items-center justify-content-center p-5">
            <div class="col-12 col-md-4 text-center mb-3">
                <img 
                    src="https://ui-avatars.com/api/?name=Ludovico+Spitaleri&size=256"
                    class="rounded-circle img-fluid shadow"
                    alt="Ludovico Spitaleri"
                    style="max-width:180px;"
                >
            </div>
            <div class="col-12 col-md-8 text-center text-md-start">
                <h3 class="mb-1 fw-bold">Ludovico Spitaleri</h3>
                <p class="fw-semibold mb-0">sugnubestia@gmail.com</p>
                <form class="mt-4 d-flex align-items-center justify-content-center justify-content-md-start gap-2 mx-auto mx-md-0">
                    <label for="image_URL" >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="CurrentColor">
                        <rect x="0" fill="none" width="24" height="24"/>
                        <g>
                        <path d="M23 4v2h-3v3h-2V6h-3V4h3V1h2v3h3zm-8.5 7c.828 0 1.5-.672 1.5-1.5S15.328 8 14.5 8 13 8.672 13 9.5s.672 1.5 1.5 1.5zm3.5 3.234l-.513-.57c-.794-.885-2.18-.885-2.976 0l-.655.73L9 9l-3 3.333V6h7V4H6c-1.105 0-2 .895-2 2v12c0 1.105.895 2 2 2h12c1.105 0 2-.895 2-2v-7h-2v3.234z"/>
                        </g>
                        <script xmlns="" id="vsc-settings-data">{}</script><script xmlns=""/></svg>
                        Add Image
                    </label>
                    <!-- <input type="image" id="image_URL" class="p-2" /> -->
                    <?php FileInput("image_URL", FileType::Image, hidden: true);?>
                </form>
                <button id="logout-button" class="mt-4 d-flex align-items-center justify-content-center justify-content-md-start gap-2 mx-auto mx-md-0 p-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" stroke="CurrentColor">
                    <path d="M12.9999 2C10.2385 2 7.99991 4.23858 7.99991 7C7.99991 7.55228 8.44762 8 8.99991 8C9.55219 8 9.99991 7.55228 9.99991 7C9.99991 5.34315 11.3431 4 12.9999 4H16.9999C18.6568 4 19.9999 5.34315 19.9999 7V17C19.9999 18.6569 18.6568 20 16.9999 20H12.9999C11.3431 20 9.99991 18.6569 9.99991 17C9.99991 16.4477 9.55219 16 8.99991 16C8.44762 16 7.99991 16.4477 7.99991 17C7.99991 19.7614 10.2385 22 12.9999 22H16.9999C19.7613 22 21.9999 19.7614 21.9999 17V7C21.9999 4.23858 19.7613 2 16.9999 2H12.9999Z" fill="#000000"/>
                    <path d="M13.9999 11C14.5522 11 14.9999 11.4477 14.9999 12C14.9999 12.5523 14.5522 13 13.9999 13V11Z" fill="#000000"/>
                    <path d="M5.71783 11C5.80685 10.8902 5.89214 10.7837 5.97282 10.682C6.21831 10.3723 6.42615 10.1004 6.57291 9.90549C6.64636 9.80795 6.70468 9.72946 6.74495 9.67492L6.79152 9.61162L6.804 9.59454L6.80842 9.58848C6.80846 9.58842 6.80892 9.58778 5.99991 9L6.80842 9.58848C7.13304 9.14167 7.0345 8.51561 6.58769 8.19098C6.14091 7.86637 5.51558 7.9654 5.19094 8.41215L5.18812 8.41602L5.17788 8.43002L5.13612 8.48679C5.09918 8.53682 5.04456 8.61033 4.97516 8.7025C4.83623 8.88702 4.63874 9.14542 4.40567 9.43937C3.93443 10.0337 3.33759 10.7481 2.7928 11.2929L2.08569 12L2.7928 12.7071C3.33759 13.2519 3.93443 13.9663 4.40567 14.5606C4.63874 14.8546 4.83623 15.113 4.97516 15.2975C5.04456 15.3897 5.09918 15.4632 5.13612 15.5132L5.17788 15.57L5.18812 15.584L5.19045 15.5872C5.51509 16.0339 6.14091 16.1336 6.58769 15.809C7.0345 15.4844 7.13355 14.859 6.80892 14.4122L5.99991 15C6.80892 14.4122 6.80897 14.4123 6.80892 14.4122L6.804 14.4055L6.79152 14.3884L6.74495 14.3251C6.70468 14.2705 6.64636 14.1921 6.57291 14.0945C6.42615 13.8996 6.21831 13.6277 5.97282 13.318C5.89214 13.2163 5.80685 13.1098 5.71783 13H13.9999V11H5.71783Z" fill="#000000"/>
                    <script xmlns="" id="vsc-settings-data">{}</script><script xmlns=""/></svg>
                    Log out
                </button>
            </div>
        </div>
        <hr/>
        <div class="container mb-2 mx-auto p-4">
            <h3 class="col-12 col-md-4 text-center mb-3"><strong>My Recipes</strong></h3>
            <div class="row">
                <?php RecipeCard("1", "1", "Recipe Title#1", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("2", "2", "Recipe Title#2", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("3", "3", "Recipe Title#3", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("4", "4", "Recipe Title#4", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            </div>
        </div>
        <div class="container-fluid mb-2 mx-auto p-4">
            <h3 class="col-12 col-md-4 text-center mb-3"><strong>Saved Recipes</strong></h3>
            <div class="row ">
                <?php RecipeCard("5", "1", "Recipe Title#1", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("6", "2", "Recipe Title#2", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("7", "3", "Recipe Title#3", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("8", "4", "Recipe Title#4", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            </div>
        </div>
    </section>
</main> 
    <?php Footer();?>
    <script type="module" src="main.js"></script>
    <script src="/js/recipeCard.js"></script>
</body>
