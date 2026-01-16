<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageHead.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/RecipeCard.php";
require_once "components/SearchBar.php";
require_once "lib/auth.php";

class TagTmp{
    public string $id;
    public string $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }
}

$tags = [
    new TagTmp("1","Vegan"),
    new TagTmp("2","Night Snacks"),
    new TagTmp("3","International"),
    new TagTmp("4","Few Ingredients"),
    new TagTmp("5","Pasta"),
    new TagTmp("6","Dessert"),
    new TagTmp("7","Salty")
];

if(isset($_GET['tag']))
    $selectedTag = $_GET['tag'];
else
    $selectedTag = null;

$totalRecipes = 13;
$resultNumber = 4;

?>
<!DOCTYPE html>
<html lang="en">
<?= PageHead("Recipes", [ "style.css" ]) ?>
<body>
    <?= Navbar() ?>
    <main class="container-fluid p-0 overflow-x-hidden" id="home-page">
        <header class="p-5">
            <h1>All Recipes</h1>
            <p>Discover <?= $totalRecipes ?> delicious recipes for students</p>
            <div class="row">
                <form class="w-100">
                    <!-- SEARCH BAR -->
                    <?= SearchBar("recipes-search",50) ?>
                    <div class="row p-4">
                        <div class="col-lg-4 my-3">
                            <label for="dif" hidden>difficulty level selector</label>
                            <select name="difficulty" id="dif" class="w-100 p-2 mx-2">
                                <option value="">Any Difficulty</option>
                                <option value="0">Easy</option>
                                <option value="1">Moderate</option>
                                <option value="2">Hard</option>
                            </select>
                        </div>
                        <div class="col-lg-4 my-3">
                            <label for="prc" hidden>price level selector</label>
                            <select name="price" id="prc" class="w-100 p-2 mx-2">
                                <option value="">Any Price</option>
                                <option value="0">Cheap</option>
                                <option value="1">Medium</option>
                                <option value="2">Expensive</option>
                            </select>
                        </div>
                        <div class="col-lg-4 my-3">
                            <label for="time" hidden>time level selector</label>
                            <select name="time" id="time" class="w-100 p-2 mx-2">
                                <option value="">Any Time</option>
                                <option value="0">Quick</option>
                                <option value="1">Medium</option>
                                <option value="2">Long</option>
                            </select>
                        </div>
                        <ul class="col-12 d-flex mt-3 flex-wrap">
                            <?php
                                foreach($tags as $tag){
                            ?>   
                            <li class="d-flex align-items-center ps-2 pe-3 py-1 me-3 mb-3">
                                <input type="checkbox" 
                                       id="<?= $tag->id ?>" 
                                       name="<?= $tag->id ?>" 
                                       value="<?= strtolower($tag->name)?>" 
                                       <?php echo isset($selectedTag) && $selectedTag == $tag->id ? "checked" : ""; ?>>
                                <label for="<?= $tag->id ?>"><?= $tag->name ?></label>
                            </li>
                            <?php 
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
                <p>Showing <?= $resultNumber ?> recipes</p>
                
                <?= RecipeCard("1", "1", "Recipe Title#1", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?= RecipeCard("2", "2", "Recipe Title#2", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?= RecipeCard("3", "3", "Recipe Title#3", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?= RecipeCard("4", "4", "Recipe Title#4", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?= RecipeCard("5", "5", "Recipe Title#1", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?= RecipeCard("6", "6", "Recipe Title#2", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?= RecipeCard("7", "7", "Recipe Title#3", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
                <?= RecipeCard("8", "8", "Recipe Title#4", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            </section>
        </div>
    </main>
    <?= Footer();?>
    <script src="/js/bootstrap.js"></script>
    <script src="/js/recipeCard.js"></script>
</body>
</html>