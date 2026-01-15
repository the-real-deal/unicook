<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageOpening.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/RecipeCard.php";
require_once "components/CategoryCard.php";
require_once "components/SearchBar.php";
require_once "lib/auth.php";

PageOpening("Home",["style.css"]);
Database::connectDefault();
?>
<body>
    <?php Navbar(); ?>
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
                        <?php SearchBar("home-search")?>
                    </form>
                    <form>
                        <label for="btn_random" hidden>Random Recipe</label>
                        <input id="btn_random" type="submit" value="Random Recipe">
                    </form>
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
            
            <?php RecipeCard("1", "1", "Recipe Title#1", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            <?php RecipeCard("2", "2", "Recipe Title#2", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            <?php RecipeCard("3", "3", "Recipe Title#3", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            <?php RecipeCard("4", "4", "Recipe Title#4", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
        </section>

        <!-- Categories -->
        <section class="row">
            <h2>Hand-Picked Categories</h2>
            <p>Find recipes that fit your needs</p>
            <ul class="row text-center px-0 m-auto">
                <?php CategoryCard("1", "Vegan", 34, "vegan.svg") ?>
                <?php CategoryCard("2", "Night Snacks", 34, "moon.svg") ?>
                <?php CategoryCard("3", "International", 34, "international.svg") ?>
                <?php CategoryCard("4", "Few Ingredients", 34, "lowIngredients.svg") ?>
                <?php CategoryCard("5", "Quick Meals", 34, "quick.svg") ?>
                <?php CategoryCard("6", "Budget-Friendly", 34, "budget.svg") ?>
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

    <?php Footer();?>

    <script src="/js/recipeCard.js"></script>
</body>
