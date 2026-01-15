<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageOpening.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/RecipeCard.php";
require_once "components/SearchBar.php";
require_once "lib/auth.php";

PageOpening("Recipes",["style.css"]);

$tags = [
    "Vegan",
    "Sweet",
    "Sour",
    "Salty",
    "Spicy",
    "Umami",
    "Gluten-Free",
    "Organic",
    "Low Fat",
    "Low Sugar",
    "Dairy-Free",
    "Raw",
    "Fermented",
    "Grilled",
    "Pickled",
    "Smoked"
];
$totalRecipes = 13;
$resultNumber = 4;
?>

<body>
    <?php Navbar(); ?>
    <main class="container-fluid p-0 overflow-x-hidden" id="home-page">
        <header class="p-5">
            <h1>All Recipes</h1>
            <p>Discover <?php echo $totalRecipes?> delicious recipes for students</p>
            <div class="row">
                <form class="w-100">
                    <!-- SEARCH BAR -->
                    <?php SearchBar("recipes-search",50)?>
                    <div class="row p-4">
                        <div class="col-lg-4 my-3">
                            <select name="difficulty" id="dif" class="w-100 p-2 mx-2">
                                <option value="">Any Difficulty</option>
                                <option value="0">Easy</option>
                                <option value="1">Moderate</option>
                                <option value="2">Hard</option>
                            </select>
                        </div>
                        <div class="col-lg-4 my-3">
                            <select name="price" id="prc" class="w-100 p-2 mx-2">
                                <option value="">Any Price</option>
                                <option value="0">Cheap</option>
                                <option value="1">Medium</option>
                                <option value="2">Expensive</option>
                            </select>
                        </div>
                        <div class="col-lg-4 my-3">
                            <select name="time" id="time" class="w-100 p-2 mx-2">
                                <option value="">Any Time</option>
                                <option value="0">Quick</option>
                                <option value="1">Medium</option>
                                <option value="2">Long</option>
                            </select>
                        </div>
                        <ul class="col-12 d-flex mt-3 flex-wrap">
                            <?php 
                                $counter = 0;
                                foreach($tags as $tag){
                            ?>   
                            <li class="d-flex align-items-center ps-2 pe-3 py-1 me-3 mb-3">
                                <input type="checkbox" id="tag<?php echo $counter?>" name="tag<?php echo $counter?>" value="<?php echo  strtolower($tag)?>">
                                <label for="tag<?php echo $counter?>"><?php echo $tag?></label>
                            </li>
                            <?php 
                                    $counter++;
                                }
                            ?>
                        </ul>
                    </div>
                </form>
            </div>
        </header>
        <div>
            <section class="row">
                <h2>Results</h2>
                <p>Showing <?php echo $resultNumber ?> recipes</p>
                
                <?php RecipeCard("1", "1", "Recipe Title#1", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("2", "2", "Recipe Title#2", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("3", "3", "Recipe Title#3", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("4", "4", "Recipe Title#4", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("5", "5", "Recipe Title#1", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("6", "6", "Recipe Title#2", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("7", "7", "Recipe Title#3", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?php RecipeCard("8", "8", "Recipe Title#4", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            </section>
        </div>
    </main>
    <?php Footer();?>
    <script src="/js/recipeCard.js"></script>
</body>