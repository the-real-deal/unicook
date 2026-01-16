<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageHead.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/RecipeCard.php";
require_once "components/CategoryCard.php";
require_once "components/SearchBar.php";
require_once "lib/auth.php";

$randomRecipeId="1";
Database::connectDefault();
?>
<!DOCTYPE html>
<html lang="en">
<?= PageHead("Home",["style.css"]) ?>
<body>
    <?= Navbar() ?>
    <main class="container-fluid p-0 overflow-x-hidden" id="home-page">
        <div><!-- Presentational Purposes Only -->
            <header class="row pt-5 px-5">
                <div class="col-md-12 col-lg-6 p-0 pt-4 pe-0 pe-lg-4">
                    <h1>Easy Recipes for Student Life</h1>
                    <p>Simple, affordable, and quick recipes designed for college students living away from home. Start
                        cooking with confidence
                        today!
                    </p>
                    <form class="w-100">
                        <!-- SEARCH BAR -->
                        <?= SearchBar("home-search") ?>
                    </form>
                    <a href="/singleRecipe?id=<?= $randomRecipeId ?>" class="px-2 py-1 d-inline-flex justify-content-center align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-shuffle w-4 h-4 me-2" aria-hidden="true">
                            <path d="m18 14 4 4-4 4"></path>
                            <path d="m18 2 4 4-4 4"></path>
                            <path d="M2 18h1.973a4 4 0 0 0 3.3-1.7l5.454-8.6a4 4 0 0 1 3.3-1.7H22"></path>
                            <path d="M2 6h1.972a4 4 0 0 1 3.6 2.2"></path>
                            <path d="M22 18h-6.041a4 4 0 0 1-3.3-1.8l-.359-.45"></path>
                        </svg>
                        Random Recipe
                    </a>
                </div>
                <div class="d-none d-lg-flex col-lg-6 justify-content-center align-content-center">
                    <div>
                        <img src="/assets/homepage.png"
                            alt="">
                    </div>
                </div>
            </header>
        </div>
        <section class="row">
            <h2>Featured Recipes</h2>
            <p>Handpicked favorites for busy students</p>
            
            <?= RecipeCard("1", "1", "Recipe Title#1", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            <?= RecipeCard("2", "2", "Recipe Title#2", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            <?= RecipeCard("3", "3", "Recipe Title#3", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            <?= RecipeCard("4", "4", "Recipe Title#4", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
        </section>

        <!-- Categories -->
        <section class="row">
            <h2>Hand-Picked Categories</h2>
            <p>Find recipes that fit your needs</p>
            <ul class="row text-center px-0 m-auto">
                <?= CategoryCard("1", "Vegan", 34, "vegan.svg") ?>
                <?= CategoryCard("2", "Night Snacks", 34, "moon.svg") ?>
                <?= CategoryCard("3", "International", 34, "international.svg") ?>
                <?= CategoryCard("4", "Few Ingredients", 34, "lowIngredients.svg") ?>
                <?= CategoryCard("5", "Pasta", 34, "pasta.svg") ?>
                <?= CategoryCard("6", "Dessert", 34, "dessert.svg") ?>
            </ul>
        </section>

        <section class="row">
            <div class="p-5 text-center">
                <h2 class="mb-3">Ready to Start Cooking?</h2>
                <p class="lead mb-4 w-75 mx-auto">
                    Join thousands of students who are eating better, saving money, and having fun in the kitchen.
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="/recipes" class="px-4 py-2">Browse Recipes</a>
                </div>
            </div>
        </section>
    </main>

    <?= Footer();?>

    <script type="module" src="/js/bootstrap.js"></script>
    <script src="/js/recipeCard.js"></script>
</body>
</html>
