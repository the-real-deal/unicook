<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageHead.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/RecipeCard.php";
require_once "components/SearchBar.php";
require_once "components/ErrorNotification.php";
require_once "components/Chat.php";
require_once "lib/auth.php";
require_once "lib/tags.php";

$db = Database::connectDefault();
$login = LoginSession::autoLogin($db);

$_isLogged = false;

if ($login !== false) {
    $_isLogged = true;
}

$tags = Tag::getAllTags($db);

if(isset($_GET['tag']))
    $selectedTag = $_GET['tag'];
else
    $selectedTag = null;

if(isset($_GET['text']))
    $searchText = $_GET['text'];
else
    $searchText = null;

$totalRecipes = 13;
$resultNumber = 4;

?>
<!DOCTYPE html>
<html lang="en">
<?= PageHead("Recipes", [ "style.css" ]) ?>
<body>
    <?=  ErrorNotification() ?>
    <?= Navbar($login) ?>
    <?=  Chat() ?>
    <main class="container-fluid p-0 overflow-x-hidden" id="home-page">
        <header class="p-5">
            <h1>All Recipes</h1>
            <p>Discover <?= $totalRecipes ?> delicious recipes for students</p>
            <div class="row">
                <form id="search-form" class="row" action="/api/recipes/search.php?" method="GET">
                    <div class="col-9">
                        <?= SearchBar("recipes", 100, isset($searchText) ? $searchText : "", false) ?>
                    </div>
                    <a data-bs-toggle="collapse" href="#collapseExample" role="button" class="col-2">
                        Filter 
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter-left" viewBox="0 0 16 16">
                            <path d="M2 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m0-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/>
                        </svg>
                    </a>
                    <div class="collapse mt-3" id="collapseExample" >
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
                                    if($tags !== false){
                                        foreach($tags as $tag){
                                ?>   
                                <li class="d-flex align-items-center ps-2 pe-3 py-1 me-3 mb-3">
                                    <input type="checkbox" 
                                        id="<?= $tag->id ?>" 
                                        name="tagIds[]"
                                        value="<?= $tag->id?>" 
                                        <?= isset($selectedTag) && $selectedTag == $tag->id ? "checked" : ""; ?>>
                                    <label for="<?= $tag->id ?>"><?= $tag->name ?></label>
                                </li>
                                <?php 
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                        
                    </div>
                </form>
            </div>
        </header>
        <div>
            <section id="recipe-result" class="row">
                <h2>Results</h2>
                <p>Showing <span id="recipeCount">3</span> recipes</p>
                <div id="recipe-template">
                    <?= RecipeCard("{template}", "{template}", "{template}", [], 20, "{template}", isLogged:$_isLogged) ?>
                </div>
                <div id="recipe-container" class="row">

                </div>
            </section>
        </div>
    </main>
    <?= Footer();?>

    <script type="module" src="/js/bootstrap.js"></script>
    <script src="/js/recipeCard.js"></script>
    <script type="module"  src="main.js"></script>
    <script type="module" src="/js/chat.js"></script>
</body>
</html>