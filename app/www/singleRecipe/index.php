<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageHead.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/Review.php";
require_once "components/Chat.php";
require_once "components/ErrorNotification.php";
require_once "lib/core/api.php";
require_once "lib/auth.php";
require_once "lib/recipes.php";

$db = Database::connectDefault();
$login = LoginSession::autoLogin($db);
$recipe = false;

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    global $db, $recipe;

    $recipeId = $req->getParam("recipeId");
    if (!is_string($recipeId)) {
        $res->redirect("/404/");
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

$ingredients = $recipe->getIngredients($db);
if($ingredients===false){
    $ingredients = [];
}

$tags = $recipe->getTags($db);
if($tags===false){
    $tags = [];
}

$reviews = $recipe->getReviews($db);
if($reviews===false){
    $reviews = [];
}

$rating = $recipe->getRating($db);
if($rating===false){
    $rating = "No Reviews Yet";
}

$instructions = $recipe->getSteps($db);
if($instructions===false){
    $instructions = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<?= PageHead("Recipe", [ "style.css" ]) ?>
<body>
    <?=  ErrorNotification() ?>
    <?= Navbar($login) ?>
    <?=  Chat() ?>
    <main>
        <section id="recipe-info" class="d-flex justify-content-center">
            <div class="mx-auto px-4 py-4">
                <a href="/" id="back-button" class="p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    Back
                </a>
                <div class="my-4 position-relative">
                    <img src="/api/recipes/image.php?recipeId=<?= $recipe->id ?>"
                        class="object-fit-cover w-100" alt="Students cooking together"/>
                    <?php 
                        if($login !== false && ($login->user->isAdmin || $recipe->userId === $login->user->id)){
                    ?>
                    <div class="d-flex control-buttons">
                        <a href="/createRecipe?recipeId=<?= $recipe->id ?>" title="edit recipe" class="d-flex justify-content-center align-items-center p-2">
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil flex-shrink-0" viewBox="-1 -1 18 18">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                            </svg>
                        </a>
                        <button id="remove_btn" class="d-flex justify-content-center align-items-center p-2 ms-2" type="button" title="remove recipe">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash flex-shrink-0" viewBox="-1 -1 18 18">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                            </svg>
                        </button>
                        <button id="save_btn_1" class="d-flex justify-content-center align-items-center p-2 ms-2" type="button" title="<?=  $recipe->isSavedFrom($db, $login->user)?"remove from saved":"save" ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="transparent" stroke="currentColor" class="bi bi-bookmark-fill flex-shrink-0" viewBox="-1 -1 18 18">
                                <path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2"/>
                            </svg>
                        </button>   
                    </div>
                    <?php 
                        }
                    ?>
                </div>
                <ul id="tagsUl" class="d-flex flex-wrap px-3 px-md-5">
                    <?php 
                        foreach($tags as $tag){

                    ?>
                    <li class="d-flex justify-content-center align-items-center px-3 mx-1 my-2 float-start">
                        <!-- <a href=""> -->
                        <?= $tag->name ?>
                        <!-- </a> -->
                    </li>
                    <?php 
                        }
                    ?>
                </ul>

                <header>
                    <h1 class="mb-4"><?= $recipe->title ?></h1>
                    <p class="d-flex flex-wrap"><?= $recipe->description ?></p>
                </header>

                <ul class="row d-flex mt-4 w-100 m-0 ps-0">
                    <li class="text-center col-6 col-lg-3">
                        <div class="p-2 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-dollar-sign w-8 h-8 mb-2" aria-hidden="true">
                                <line x1="12" x2="12" y1="2" y2="22"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            <h2>Cost</h2>
                            <span><?= $recipe->cost->name ?></span>
                        </div>
                    </li>
                    <li class="text-center col-6 col-lg-3">
                        <div class="p-2 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-clock-history mb-2" viewBox="0 0 16 16">
                                <path
                                    d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.985-.299l.219-.976q.576.129 1.126.342zm1.37.71a7 7 0 0 0-.439-.27l.493-.87a8 8 0 0 1 .979.654l-.615.789a7 7 0 0 0-.418-.302zm1.834 1.79a7 7 0 0 0-.653-.796l.724-.69q.406.429.747.91zm.744 1.352a7 7 0 0 0-.214-.468l.893-.45a8 8 0 0 1 .45 1.088l-.95.313a7 7 0 0 0-.179-.483m.53 2.507a7 7 0 0 0-.1-1.025l.985-.17q.1.58.116 1.17zm-.131 1.538q.05-.254.081-.51l.993.123a8 8 0 0 1-.23 1.155l-.964-.267q.069-.247.12-.501m-.952 2.379q.276-.436.486-.908l.914.405q-.24.54-.555 1.038zm-.964 1.205q.183-.183.35-.378l.758.653a8 8 0 0 1-.401.432z" />
                                <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                                <path
                                    d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                            </svg>
                            <h2>Prep Time</h2>
                            <span><?= $recipe->prepTime ?></span>
                        </div>
                    </li>
                    <li class="text-center col-6 col-lg-3">
                        <div class="p-2 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-users w-6 h-6 text-foreground mx-auto mb-2"
                                    aria-hidden="true">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                </svg>
                            <h2>Servings</h2>
                            <span><?= $recipe->servings ?></span>
                        </div>
                    </li>
                    <li class="text-center col-6 col-lg-3">
                        <div class="p-2 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star mb-2" viewBox="0 0 16 16">
                                <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.56.56 0 0 0-.163-.505L1.71 6.745l4.052-.576a.53.53 0 0 0 .393-.288L8 2.223l1.847 3.658a.53.53 0 0 0 .393.288l4.052.575-2.906 2.77a.56.56 0 0 0-.163.506l.694 3.957-3.686-1.894a.5.5 0 0 0-.461 0z"/>
                            </svg>
                            <h2>Rating</h2>
                            <span><?= $rating ?></span>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
        <div class="container-fluid">
            <div class="row d-flex">
                <aside class="col-md-4 px-3" id="aside">
                    <div class="p-4 mb-4">
                        <h2 class="mb-4">Ingredients</h2>
                        <ul>
                            <?php 
                                $i = 0;
                                foreach ($ingredients as $ingredient) { 
                                    ?>
                                <li class="d-flex my-3 align-items-center">
                                    <input id="ingredients<?= $i ?>" type="checkbox" class="me-2" />
                                    <label for="ingredients<?= $i ?>">
                                        <?= $ingredient->name." ".$ingredient->quantity ?>
                                    </label>
                                </li>
                            <?php
                                    $i += 1;
                                }
                            ?>
                        </ul>
                    </div>
                </aside>
                <div class="col-md-8">
                    <!--https://stackoverflow.com/questions/23610151/can-you-style-ordered-list-numbers-->
                    <section id="instruction">
                        <div class="p-4 mb-4">
                            <h2 class="mb-4">Instruction</h2>
                            <ol>
                                <?php 
                                    foreach($instructions as $instruction){
                                ?>
                                <li class="d-flex flex-start">
                                    <?= $instruction->instruction ?>
                                </li>
                                <?php                                    
                                    }
                                ?>
                            </ol>
                        </div>
                    </section>
                    <section id="reviews">
                        <div class="p-4 mb-4">
                            <h2>Reviews</h2>
                            <?php if ($login === false) { ?>
                            <div id="login-adv" class="p-3 mb-3 text-center">
                                <p class="mb-4">Want to leave a review?
                                </p>
                                <a href="/login/" class="py-2 px-3 m-3" >Log in </a>
                            </div>
                            <?php } else { ?>
                            <form action="/api/reviews/create.php" method="POST"  class="p-3 mb-3" id="review-form">
                                <fieldset class="mb-3">
                                    <legend>Your Rating</legend>
                                    <!-- <label class="mb-2" for="rating">Your Rating</label> -->
                                    <div class="stars">
                                        <input type="radio" name="rating" id="r1" value="1"/>
                                        <label for="r1" class="pe-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="-1 -1 18 18">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                            </svg>
                                            <span hidden>1 star</span>
                                        </label>
                                        <input type="radio" name="rating" id="r2" value="2"/>
                                        <label for="r2" class="pe-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="-1 -1 18 18">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                            </svg>
                                            <span hidden>2 star</span>
                                        </label>
                                        <input type="radio" name="rating" id="r3" value="3" checked="checked" hidden/>
                                        <label for="r3" class="pe-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="-1 -1 18 18">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                            </svg>
                                            <span hidden>3 star</span>
                                        </label>
                                        <input type="radio" name="rating" id="r4" value="4"/>
                                        <label for="r4" class="pe-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="-1 -1 18 18">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                            </svg>
                                            <span hidden>4 star</span>
                                        </label>
                                        <input type="radio" name="rating" id="r5" value="5" checked/>
                                        <label for="r5" class="pe-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="-1 -1 18 18">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                            </svg>
                                            <span hidden>5 star</span>
                                        </label>
                                    </div>
                                </fieldset>

                                <div class="mb-3">
                                    <label for="reviewText" hidden>Your Review</label>
                                    <textarea 
                                        name="body"
                                        id="reviewText"
                                        placeholder="Share your thoughts about this recipe..." 
                                        rows="3"
                                        class="p-2"
                                        required
                                    ></textarea>
                                </div>

                                <label for="submit_review" hidden>Submit Review</label>
                                <button id="submit_review" type="submit" class="btn btn-success">Submit Review</button>
                            </form>
                            <?php } 
                            ?>

                            <div id="review-template"class="d-none"> 
                                <?= Review("template", $login->user->id, $login->user->username, 1, "template", new DateTime(),  $login!==false ) ?>
                            </div>
                            <div id="reviews-box">
                            <div>
                            <?php 
                                $i = 0;
                                usort($reviews, function ($a, $b) use ($login){
                                    if($login === false){
                                        return $b->createdAt <=> $a->createdAt;
                                    }

                                    $aIsMine = $a->userId === $login->user->id;
                                    $bIsMine = $b->userId === $login->user->id;

                                    if ($aIsMine !== $bIsMine) {
                                        return $bIsMine <=> $aIsMine;
                                    }

                                    return $b->createdAt <=> $a->createdAt;
                                });

                                foreach($reviews as $review){
                                    $user = User::fromId($db,$review->userId);
                            ?>
                            <?=     Review($review->id, $review->userId, $user->username, $review->rating, $review->body, $review->createdAt, $login!==false and ($user->id === $login->user->id or $login->user->isAdmin)) ?>
                            <?php
                                    $i++; 
                                }
                            ?>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
    <?= Footer() ?>
    <script type="module" src="/js/bootstrap.js"></script>
    <script type="module" src="/js/chat.js"></script>
    <script type="module" src="/js/review.js"></script>
    <script type="module" src="main.js"></script>
</body>
</html>