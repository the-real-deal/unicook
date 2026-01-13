<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "components/PageOpening.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";

$id = $_GET['id'] ?? null;



PageOpening("Recipe",["style.css"]);
?>

<body>
    <?php 
    Navbar();
    ?>
        <main>
        <section id="recipe-info" class="d-flex justify-content-center">
            <div class="mx-auto px-4 py-4">
                <a href="/recipes" class="p-2"> &#8592; Back to Recipes</a>

                <div class="my-4 position-relative">
                    <img src="https://images.unsplash.com/photo-1676300184847-4ee4030409c0?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxwYXN0YSUyMGRpc2glMjBmb29kfGVufDF8fHx8MTc2MzA4MTc4MHww&ixlib=rb-4.1.0&q=80&w=1080"
                        class="object-fit-cover w-100" alt="Students cooking together" />
                    <form class="d-flex gap-3">
                        <input type="button" id="save_btn" hidden>
                        <label for="save_btn" class="d-flex align-items-center justify-content-center p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                            </svg>
                        </label>
                        <input type="button" id="remove_btn" hidden>
                        <label for="remove_btn" class="d-flex align-items-center justify-content-center p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                            </svg>
                        </label>
                        <input type="checkbox" id="modify_btn" hidden>
                        <label for="modify_btn" class="d-flex align-items-center justify-content-center p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="transparent" stroke="currentColor" class="bi bi-bookmark-fill" viewBox="-1 -1 18 18">
                                <path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2"/>
                            </svg>
                        </label>
                    </form>
                    
                </div>

                <div class="d-flex flex-wrap">
                    <ul>
                        <li class="d-flex justify-content-center align-items-center px-2 m-2 float-start">
                            <a>
                                Quick Meals
                            </a>
                        </li>
                        <li class="d-flex justify-content-center align-items-center px-2 m-2 float-start">
                            <a>
                                Easy
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h1 class="mb-4">Creamy Garlic Pasta</h1>
                    <p class="d-flex flex-wrap">A rich and creamy garlic pasta that's perfect for a quick
                        weeknight
                        dinner. Made with simple
                        pantry ingredients, this dish comes together in under 20 minutes!</p>
                </div>
                <div class="container-fluid">
                    <!-- https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/dl#wrapping_name-value_groups_in_div_elements -->
                    <dl class="row d-flex mt-4">
                        <div class="text-center col-6 col-lg-3">
                            <div class="p-2 mb-4">
                                <div>
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
                                </div>
                                <dt>Prep Time</dt>
                                <dd>20 min</dd>
                            </div>
                        </div>
                        <div class="text-center col-6 col-lg-3">
                            <div class="p-2 mb-4">
                                <div>
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
                                </div>
                                <dt>Prep Time</dt>
                                <dd>20 min</dd>
                            </div>
                        </div>
                        <div class="text-center col-6 col-lg-3">
                            <div class="p-2 mb-4">
                                <div>
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
                                </div>
                                <dt>Prep Time</dt>
                                <dd>20 min</dd>
                            </div>
                        </div>
                        <div class="text-center col-6 col-lg-3">
                            <div class="p-2 mb-4">
                                <div>
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
                                </div>
                                <dt>Prep Time</dt>
                                <dd>20 min</dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
        </section>
        <div class="container-fluid">
            <div class="row d-flex">
                <aside class="col-md-4 pe-4" id="aside">
                    <div class="p-4 mb-4">
                        <h2 class="mb-4">Ingredients</h2>
                        <ul>
                            <li class="d-flex flex-wrap my-3">
                                <input id="ingredients1" type="checkbox" class="me-2" />
                                <label for="ingredients1">
                                    200g sp aghetti
                                </label>
                            </li>
                            <li class="d-flex flex-wrap my-3">
                                <input id="ingredients1" type="checkbox" class="me-2" />
                                <label for="ingredients1">
                                    200g spaghetti
                                </label>
                            </li>
                            <li class="d-flex flex-wrap my-3">
                                <input id="ingredients1" type="checkbox" class="me-2" />
                                <label for="ingredients1">
                                    200g spaghetti
                                </label>
                            </li>
                            <li class="d-flex flex-wrap my-3">
                                <input id="ingredients1" type="checkbox" class="me-2" />
                                <label for="ingredients1">
                                    200g spaghetti
                                </label>
                            </li>
                        </ul>
                    </div>
                </aside>
                <div class="col-md-8">
                    <!--https://stackoverflow.com/questions/23610151/can-you-style-ordered-list-numbers-->
                    <section id="instruction">
                        <div class="p-4 mb-4">
                            <h2 class=" mb-4">Instruction</h2>
                            <ol>
                                <li class="d-flex flex-start">
                                    Cook pasta according to package directions. Reserve 1 cup pasta water before
                                    draining.
                                </li>
                                <li class="d-flex flex-start">
                                    Cook pasta according to package directions. Reserve 1 cup pasta water before
                                    draining.
                                </li>
                                <li class="d-flex flex-start">
                                    Cook pasta according to package directions. Reserve 1 cup pasta water before
                                    draining.
                                </li>
                                <li class="d-flex flex-start">
                                    Cook pasta according to package directions. Reserve 1 cup pasta water before
                                    draining.
                                </li>
                                <li class="d-flex flex-start">
                                    Cook pasta according to package directions. Reserve 1 cup pasta water before
                                    draining.
                                </li>
                            </ol>
                        </div>
                    </section>
                    <section id="reviews">
                        <div class="p-4 mb-4">
                            <div class="d-flex gap-2 mb-4 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chat-left" viewBox="0 0 16 16">
                                    <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                </svg>
                                <h2>Reviews</h2>
                            </div>
                            <article>
                                
                            </article>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
    <?php   
    Footer();
    ?>
</body>