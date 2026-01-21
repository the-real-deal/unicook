<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageHead.php";
require_once "components/ErrorNotification.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/FileInput.php";
require_once "components/Chat.php";
require_once "lib/core/api.php";
require_once "lib/auth.php";
require_once "lib/tags.php";
require_once "lib/utils.php";

$db = Database::connectDefault();
$login = LoginSession::autoLoginOrRedirect($db);
$recipe = false;

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    global $db, $recipe;

    $recipeId = $req->getParam("recipeId");
    if (is_array($recipeId)) {
        $res->redirect("/404/");
    } else if ($recipeId === null) {
        return;
    }

    try {
        $recipe = Recipe::fromId($db, $recipeId);
        if($recipe === false){
            $res->redirect("/404/");
        }
    } catch (InvalidArgumentException $e) {
        $res->redirect("/404/");
    }
});

$server->respond();

?>
<!DOCTYPE html>
<html lang="en">
<?= PageHead("Form", [ "style.css" ]) ?>
<body>
    <?= ErrorNotification() ?>
    <?= Navbar($login) ?>
    <?= Chat() ?>
    <main>
        <a href="/" id="back-button" class="p-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
            </svg>
            Back
        </a>
        <form id="recipeForm" action="/api/recipes/<?= $recipe === false ? "create.php" : "update.php" ?>" method="POST"
            class="d-flex flex-column p-4 gap-3 mx-auto my-2">
            <label for="title">Title *</label>
            <input type="text" minlength="1" maxlength="50" id="title" name="title" class="p-2" placeholder="e.g. Easy Ramen" required />

            <label for="description">Description *</label>
            <textarea minlength="1" maxlength="250" id="description" name="description" class="p-2" placeholder="Describe your recipe..." required></textarea>

            <label for="image">Image *</label>
            <?= FileInput("image", FileType::Image, required: $recipe === false) ?>

            <hr>
            <label for="tags">Tags</label>
            <select id="tags" class="p-2">
                <option disabled selected value> -- select an option -- </option>
                <?php
                $tags = Tag::getAllTags($db);
                if ($tags === false) {
                    $tags = [];
                }
                foreach ($tags as $t) {
                ?>
                    <option value="<?= $t->id ?>"><?= $t->name ?></option>
                <?php } ?>
            </select>
            <ul id="tag-list">
            </ul>

            <hr>
            <div class="row">
                <div class="col-md-6 d-flex flex-column ps-2 py-2 gap-2">
                    <label for="difficulty">Difficulty *</label>
                    <select name="difficulty" id="difficulty" class="p-2" required>
                        <option disabled selected value> -- select an option -- </option>
                        <?php
                        $difficulties = RecipeDifficulty::cases();
                        foreach ($difficulties as $d) {
                        ?>
                            <option value="<?= $d->value ?>"><?= $d->name ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6 d-flex flex-column ps-2 py-2 gap-2">
                    <label for="prepTime">Preparation Time (minutes) *</label>
                    <input type="number" min="5" max="300" id="prepTime" name="prepTime" class="p-2" value="5" required />
                </div>
                <div class="col-md-6 d-flex flex-column ps-2 py-2 gap-2">
                    <label for="cost">Cost *</label>
                    <select name="cost" id="cost" class="p-2" required>
                        <option disabled selected value> -- select an option -- </option>
                        <?php
                        $costs = RecipeCost::cases();
                        foreach ($costs as $c) {
                        ?>
                            <option value="<?= $c->value ?>"><?= $c->name ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6 d-flex flex-column ps-2 py-2 gap-2">
                    <label for="servings">Servings *</label>
                    <input type="number" min="1" max="10" id="servings" name="servings" class="p-2" value="1" required />
                </div>
            </div>
            <hr>

            <div class="d-flex justify-content-between align-items-center">
                <span>Ingredients *</span>
                <label for="add-ingredients-slot" hidden>Preparation Steps *</label>
                <button type="button" id="add-ingredients-slot">Add Ingredient</button>
            </div>
            <ul id="ingredients">
                
            </ul>
            <hr>

            <div class="d-flex justify-content-between align-items-center">
                <span>Preparation Steps *</span>
                <label for="add-step-slot" hidden>Add Step</label>
                <button type="button" id="add-step-slot">Add Step</button>
            </div>
            <ul id="steps">
            </ul>
            <hr>
            <input type="submit" value="Publish"/>
        </form>
    </main>
    <?php 
    Footer();
    ?>
    <script type="module" src="/js/bootstrap.js"></script>
    <script type="module" src="/js/chat.js"></script>
    <script type="module" src="main.js"></script>
</body>
</html>
