<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageHead.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/RecipeCard.php";
require_once "components/CategoryCard.php";
require_once "components/SearchBar.php";
require_once "components/Chat.php";
require_once "lib/auth.php";
require_once "lib/recipes.php";

$db = Database::connectDefault();
$login = LoginSession::autoLogin($db);

$randomRecipeId=Recipe::getRandom($db);
Database::connectDefault();

$isLogged = false;
if($login !== false){
    $isLogged = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<?= PageHead("Home",["style.css"]) ?>
<body>
    <?= Navbar($login);?>
    <?= Chat(); ?>
    <main class="container-fluid p-0 overflow-x-hidden" id="home-page">
        <div><!-- Presentational Purposes Only -->
            <header class="row pt-5 px-5">
                <div class="col-md-12 col-lg-6 p-0 pt-4 pe-0 pe-lg-4">
                    <h1>Easy Recipes for Student Life</h1>
                    <p>Simple, affordable, and quick recipes designed for college students living away from home. Start
                        cooking with confidence
                        today!
                    </p>
                    <form class="w-100" id="home-search-form">
                        <?= SearchBar("home") ?>
                    </form>
                    <a href="/singleRecipe?recipeId=<?= $randomRecipeId->id ?>" class="mt-5 px-3 py-1 d-inline-flex justify-content-center align-items-center">
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

            <?php 
                $recipes = Recipe::getBest($db, 4);
                if($recipes === false){
            ?>
                error
            <?php 
                }else{
                    $i = 0;
                    if($isLogged){
                        foreach($recipes as $recipe){
                            RecipeCard("home-".$i, $recipe->id, $recipe->title, array_map(fn($t)=>$t->name ,$recipe->getTags($db)), $recipe->prepTime, $recipe->cost->name, $recipe->isSavedFrom($db, $login->user), true);
                            $i++;
                        }
                    } else{
                        foreach($recipes as $recipe){
                            RecipeCard("home-".$i, $recipe->id, $recipe->title, array_map(fn($t)=>$t->name ,$recipe->getTags($db)), $recipe->prepTime, $recipe->cost->name, isLogged:false);
                            $i++;
                        }
                    }
                }       
            ?>
        </section>

        <!-- Categories -->
        <section class="row">
            <h2>Hand-Picked Categories</h2>
            <p>Find recipes that fit your needs</p>
            <ul class="row text-center px-0 m-auto">
                <?= CategoryCard("9b8a7c6d-5e4f-3a2b-1c0d-9e8f7a6b5c4d", "Vegan", count(Tag::fromId($db, "9b8a7c6d-5e4f-3a2b-1c0d-9e8f7a6b5c4d")->getRecipes($db)), "vegan.svg") ?>
                <?= CategoryCard("fd01cc1f-6f1a-4d01-bd38-4ec70349840c", "Night Snacks", count(Tag::fromId($db, "fd01cc1f-6f1a-4d01-bd38-4ec70349840c")->getRecipes($db)), "moon.svg") ?>
                <?= CategoryCard("61655a70-6f83-45cd-bf00-2ac8a9789e0c", "International", count(Tag::fromId($db, "61655a70-6f83-45cd-bf00-2ac8a9789e0c")->getRecipes($db)), "international.svg") ?>
                <?= CategoryCard("c9bf9e57-1685-4c89-bafb-ff5af830be8a", "Few Ingredients", count(Tag::fromId($db, "c9bf9e57-1685-4c89-bafb-ff5af830be8a")->getRecipes($db)), "lowIngredients.svg") ?>
                <?= CategoryCard("7c9e6679-7425-40de-944b-e07fc1f90ae7", "Pasta", count(Tag::fromId($db, "7c9e6679-7425-40de-944b-e07fc1f90ae7")->getRecipes($db)), "pasta.svg") ?>
                <?= CategoryCard("3d6f91f4-6e2e-4e5f-8d9a-1c3b5e7f9d2a", "Dessert", count(Tag::fromId($db, "3d6f91f4-6e2e-4e5f-8d9a-1c3b5e7f9d2a")->getRecipes($db)), "dessert.svg") ?>
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
    <script type="module" src="main.js"></script>
    <script type="module" src="/js/chat.js"></script>
</body>
</html>
