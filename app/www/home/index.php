<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "components/PageOpening.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";
require_once "components/RecipeCard.php";

PageOpening("Home",["style.css"]);
?>
<body>
    <?php Navbar(); ?>
    <main class="container-fluid p-0 overflow-x-hidden" id="home-page">
        <div><!-- Presentational Purposes Only -->
            <header class="row pt-5 px-5">
                <div class="col-md-12 col-lg-6 p-0 pt-4 pe-0 pe-lg-4">
                    <h1>Easy Recipes for Student Life</h1>
                    <p>Simple, affordable, and quick recipes designed for college students living away from home. Start
                        cooking with confidence
                        today!
                    </p>
                    <form class="w-100">
                        <!-- SEARCH BAR -->
                        <div class=" d-flex align-items-center mb-5 w-100">
                            <label for="search_bar" hidden>Text input for search purpose</label>
                            <input id="search_bar" type="search" placeholder="Search Recipes..."
                                class="me-2 w-75" />
                            <label for="btn_search" hidden>Button to start the search</label>
                            <input id="btn_search" type="submit" class="px-4 w-auto" value="Search" />
                        </div>
                        <div>

                        </div>
                    </form>
                    <form>
                        <label for="btn_random" hidden>Random Recipe</label>
                        <input id="btn_random" type="submit" value="Random Recipe" class="py-1">
                    </form>
                </div>
                <div class="d-sm-none d-lg-flex col-lg-6 justify-content-center align-content-center">
                    <div>
                        <img src="https://images.unsplash.com/photo-1671725501632-3980b640f420?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjb2xsZWdlJTIwc3R1ZGVudCUyMGNvb2tpbmclMjBraXRjaGVufGVufDF8fHx8MTc2MzEyMzM5NXww&ixlib=rb-4.1.0&q=80&w=1080"
                            alt="Student cooking">
                    </div>
                </div>
            </header>
        </div>
        <section class="row">
            <h2>Featured Recipes</h2>
            <p>Handpicked favorites for busy students</p>
            
            <?php RecipeCard("1", "Recipe Title#1", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            <?php RecipeCard("2", "Recipe Title#2", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            <?php RecipeCard("3", "Recipe Title#3", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
            <?php RecipeCard("4", "Recipe Title#4", ["Tag#1", "Tag#2", "Tag#3"], 20, "Medium") ?>
        </section>

        <!-- Categories -->
        <section>
            <h2>Popular Categories</h2>
            <p>Find recipes that fit your needs</p>
            <ul class="row text-center px-0">
                <li class="col-sm-6 col-md-4 col-lg-2 category-card py-1">
                    <!-- href="http://www.google.it" -->
                    <a class="my-1">
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- Vegan -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-leaf w-8 h-8" aria-hidden="true">
                                <path
                                    d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z">
                                </path>
                                <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"></path>
                            </svg>
                        </div>
                        Vegan
                        <p>34 Recipes</p>
                    </a>
                </li>
                <li class="col-sm-6 col-md-4 col-lg-2 category-card py-1">
                    <a class="my-1">
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- Moon -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-moon w-8 h-8" aria-hidden="true">
                                <path
                                    d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401">
                                </path>
                            </svg>
                        </div>
                        Night Snacks
                        <p>34 Recipes</p>
                    </a>
                </li>
                <li class="col-sm-6 col-md-4 col-lg-2 category-card py-1">
                    <a class="my-1">
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- International -->
                            <svg xmlns="http://www.w3.org/2000/svg" height viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-globe w-8 h-8" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                                <path d="M2 12h20"></path>
                            </svg>
                        </div>
                        International
                        <p>34 Recipes</p>
                    </a>
                </li>
                <li class="col-sm-6 col-md-4 col-lg-2 category-card py-1">
                    <a class="my-1">
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- Low Ingredients -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-cookie w-8 h-8" aria-hidden="true">
                                <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"></path>
                                <path d="M8.5 8.5v.01"></path>
                                <path d="M16 15.5v.01"></path>
                                <path d="M12 12v.01"></path>
                                <path d="M11 17v.01"></path>
                                <path d="M7 14v.01"></path>
                            </svg>
                        </div>
                        Few Ingredients
                        <p>34 Recipes</p>
                    </a>
                </li>
                <li class="col-sm-6 col-md-4 col-lg-2 category-card py-1">
                    <a class="my-1">
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- Quick  -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-zap w-8 h-8" aria-hidden="true">
                                <path
                                    d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z">
                                </path>
                            </svg>
                        </div>
                        Quick Meals
                        <p>34 Recipes</p>
                    </a>
                </li>
                <li class="col-sm-6 col-md-4 col-lg-2 category-card py-1">
                    <a class="my-1">
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- Budget -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-dollar-sign w-8 h-8" aria-hidden="true">
                                <line x1="12" x2="12" y1="2" y2="22"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        Budget-Friendly
                        <p>34 Recipes</p>
                    </a>
                </li>
            </ul>
        </section>
    </main>

    <?php Footer();?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
