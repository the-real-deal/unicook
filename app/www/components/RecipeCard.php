<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/recipes.php";

function RecipeCard(string $elementId,
string $recipeId, 
string $recipeTitle, 
array $tags, 
int $timeRequired, 
string $cost,
string $imgID,
bool $saved=false,
bool $isLogged=false) {
    
    $imgageSrc = "";
?>
<div class="p-0 col-12 col-sm-6 col-lg-3 d-flex justify-content-center recipe-card" id="<?= $elementId ?>">
    <article class="my-2 pb-2">
        <div>
            <img class="card-img-top img-fluid" src="/assets/penne.jpg" alt="">
        </div>
        <div class="d-flex flex-column">
            <h3 class="order-2 recipe-card-title" data-recipe-id="<?= $recipeId?>" ><?= $recipeTitle ?></h3>
            <ul class="order-1 w-100">
                <!--
                <?php 
                    foreach($tags as $tag){
                ?>
                --><li><?= $tag ?></li><!--
                <?php 
                    }
                ?>
                -->
            </ul>
        </div>
        <div class="d-flex">
            <div class="pe-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-clock-history" viewBox="0 0 16 16">
                    <path
                        d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z" />
                    <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                    <path
                        d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                </svg>
                <span><?= $timeRequired ?> min</span>
            </div>
            <div class="pe-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-dollar-sign w-8 h-8" aria-hidden="true">
                    <line x1="12" x2="12" y1="2" y2="22"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
                <span><?= $cost ?></span>
            </div>
        </div>
        <a href="/singleRecipe?id=<?= $recipeId?>" class="recipeLink">More...</a>
        <?php 
            if($isLogged){
        ?>
        <button id="btn-<?= $elementId?>" class="d-flex justify-content-center align-items-center" onclick="saveRecipe('btn-<?= $elementId ?>','<?= $recipeId?>')" type="button" title="<?= $saved?"remove from saved":"save" ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="<?= $saved?"currentColor":"transparent" ?>" stroke="currentColor" class="bi bi-bookmark-fill flex-shrink-0" viewBox="-1 -1 18 18">
                <path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2"/>
            </svg>
        </button>
        <?php
            }
        ?>
    </article>
</div>
<?php } ?>
