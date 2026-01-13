<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "components/PageOpening.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";

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
                    <div>
                        <form>
                            <input type="checkbox" id="save_btn" hidden>
                            <label for="save_btn" class="d-flex align-items-center justify-content-center p-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-bookmark w-5 h-5" aria-hidden="true">
                                    <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"></path>
                                </svg>
                            </label>
                        </form>
                    </div>
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
                    <section id="student-tips">
                        <div class="p-4 mb-4">
                            <div class="d-flex gap-2 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left" viewBox="0 0 16 16">
                                    <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                </svg>
                                <h2>Review</h2>
                            </div>    
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