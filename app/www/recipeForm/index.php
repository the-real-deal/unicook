<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageHead.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/FileInput.php";
?>
<!DOCTYPE html>
<html lang="en">
<?= PageHead("Form", ["style.css"]) ?>
<body>
    <?=  Navbar() ?>
    <main>
        <form class="d-flex flex-column p-4 gap-3 mx-auto my-2">
            <label for="title">Recipe Title *</label>
            <input type="text" id="title" name="title" class="p-2" placeholder="e.g. Easy Ramen" required />

            <label for="description">Description *</label>
            <input type="text" id="description" class="p-2" placeholder="Describe your recipe..." required />

            <label for="image">Image (optional)</label>
            <?= FileInput("image", FileType::Image) ?>

            <hr>
            <label for="tags">Tags</label>
            <select name="tags" id="tags" class="p-2">
                <option disabled selected value style="display:none"> -- select an option -- </option>
                <option value="italiana">Italian</option>
                <option value="veloce">Quick</option>
                <option value="vegetariana">Vegetarian</option>
                <option value="vegana">Vegan</option>
            </select>
            <ul id="tag-list">
            </ul>

            <hr>
            <div class="row">
                <div class="col-md-6 d-flex flex-column ps-2 py-2 gap-2">
                    <label for="difficulty">Difficulty *</label>
                    <select name="difficulty" id="difficulty" class="p-2" required>
                        <option disabled selected value style="display:none"> -- select an option -- </option>
                        <option value="0">Easy</option>
                        <option value="1">Medium</option>
                        <option value="2">Hard</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex flex-column ps-2 py-2 gap-2">
                    <label for="prepTime">Preparation Time *</label>
                    <select name="prepTime" id="prepTime" class="p-2" required>
                        <option disabled selected value style="display:none"> -- select an option -- </option>
                        <option value="0">Fast</option>
                        <option value="1">Normal</option>
                        <option value="2">Slow</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex flex-column ps-2 py-2 gap-2">
                    <label for="cost">Cost *</label>
                    <select name="cost" id="cost" class="p-2" required>
                        <option disabled selected value style="display:none"> -- select an option -- </option>
                        <option value="0">Cheap</option>
                        <option value="1">Average</option>
                        <option value="2">Expensive</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex flex-column ps-2 py-2 gap-2">
                    <label for="servings">Servings *</label>
                    <input type="number" id="servings" name="servings" class="p-2" min="1" value="1" required />
                </div>
            </div>
            <hr>

            <div class="d-flex justify-content-between align-items-center">
                <span>Ingredients *</span>
                <label for="add-ingredients-slot" hidden>Preparation Steps *</label>
                <button type="button" id="add-ingredients-slot">Add Ingredient</button>
            </div>
            <ul id="ingredients">
                <li class="d-flex justify-content-between mb-2 align-items-center gap-2">
                    <label for="ingredientQuantity-2" class="d-none" hidden="">enter the quantity of the next ingredient</label>
                    <input id="ingredientQuantity-2" type="text" name="ingredientsQuantity[]" class="p-2 col-md-2" placeholder="Quantity">
                    <label for="ingredientName-2" class="d-none" hidden="">enter the next ingredient</label>
                    <input id="ingredientName-2" type="text" name="ingredientsName[]" class="p-2 flex-grow-1" placeholder="Add ingredient">
                    <button type="button" class="p-2 flex-shrink-0" onclick="this.parentElement.remove()">&#128473;</button>
                </li>
            </ul>
            <hr>

            <div class="d-flex justify-content-between align-items-center">
                <span>Preparation Steps *</span>
                <label for="add-step-slot" hidden>Add Step</label>
                <button type="button" id="add-step-slot">Add Step</button>
            </div>
            <ul id="steps">
                <li class="d-flex justify-content-between mb-2 align-items-center gap-2">
                    <label for="step-1" class="d-none" hidden="">enter the recipe step</label>
                    <textarea id="step-1" name="steps[]" class="p-2 flex-grow-1" placeholder="Describe the step" style="resize: none;"></textarea>
                    <button type="button" class="p-2 flex-shrink-0" onclick="this.parentElement.remove()">&#128473;</button>
                </li>
            </ul>
            <hr>
            <input type="submit" value="Publish"></input>
        </form>
    </main>
    <?php 
    Footer();
    ?>
    <script type="module" src="/js/bootstrap.js"></script>
    <script type="module" src="main.js"></script>
</body>
</html>
