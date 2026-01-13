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
                    <section id="student-tips">
                        <div class="p-4 mb-4">
                            <div class="d-flex gap-2  mb-4">
                                <svg fill="#000000" width="34px" height="34px" viewBox="0 0 24.00 24.00"
                                    xmlns="http://www.w3.org/2000/svg" stroke="#000000"
                                    stroke-width="0.00024000000000000003">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M12,9a3.982,3.982,0,0,0-2.96,6.666A7,7,0,0,0,5,22a1,1,0,0,0,1,1H18a1,1,0,0,0,1-1,7,7,0,0,0-4.04-6.334A3.982,3.982,0,0,0,12,9Zm0,2a2,2,0,1,1-2,2A2,2,0,0,1,12,11Zm4.9,10H7.1a5,5,0,0,1,9.8,0ZM12,7a1,1,0,0,1-1-1V2a1,1,0,0,1,2,0V6A1,1,0,0,1,12,7Zm4.6,1.9A1,1,0,0,1,15.89,7.2l2.828-2.829a1,1,0,1,1,1.414,1.414L17.3,8.611A1,1,0,0,1,16.6,8.9ZM8.11,8.611a1,1,0,0,1-1.414,0L3.868,5.782A1,1,0,0,1,5.282,4.368L8.11,7.2A1,1,0,0,1,8.11,8.611ZM23,13a1,1,0,0,1-1,1H18.5a1,1,0,0,1,0-2H22A1,1,0,0,1,23,13ZM1,13a1,1,0,0,1,1-1H5.5a1,1,0,0,1,0,2H2A1,1,0,0,1,1,13Z">
                                        </path>
                                    </g>
                                </svg>
                                <h2>Student Tips</h2>
                            </div>
                            <ul>
                                <li>
                                    Use any pasta shape you have on hand - they all work great!
                                </li>
                                <li>
                                    Use any pasta shape you have on hand - they all work great!
                                </li>
                                <li>
                                    Use any pasta shape you have on hand - they all work great!
                                </li>
                            </ul>
                        </div>
                    </section>
                    <section id="nutrition-info">
                        <div class="p-4 mb-4">
                            <div class="d-flex gap-2 mb-4">
                                <svg width="34px" height="34px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" stroke="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M4 11.9998H8L9.5 8.99976L11.5 13.9998L13 11.9998H15M12 6.42958C12.4844 5.46436 13.4683 4.72543 14.2187 4.35927C16.1094 3.43671 17.9832 3.91202 19.5355 5.46436C21.4881 7.41698 21.4881 10.5828 19.5355 12.5354L12.7071 19.3639C12.3166 19.7544 11.6834 19.7544 11.2929 19.3639L4.46447 12.5354C2.51184 10.5828 2.51184 7.41698 4.46447 5.46436C6.0168 3.91202 7.89056 3.43671 9.78125 4.35927C10.5317 4.72543 11.5156 5.46436 12 6.42958Z"
                                            stroke="#000000" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </g>
                                </svg>
                                <h2>Nutrition Info</h2>
                            </div>

                            <dl class="row d-flex">
                                <div class="text-center col-6 col-lg-3 mb-4 mb-lg-0">
                                    <div class="px-2">
                                        <dt>Prep Time</dt>
                                        <dd>20 min</dd>
                                    </div>
                                </div>
                                <div class="text-center col-6 col-lg-3 mb-4 mb-lg-0">
                                    <div class="px-2">
                                        <dt>Prep Time</dt>
                                        <dd>20 min</dd>
                                    </div>
                                </div>
                                <div class="text-center col-6 col-lg-3 mb-lg-0">
                                    <div class="px-2">
                                        <dt>Prep Time</dt>
                                        <dd>20 min</dd>
                                    </div>
                                </div>
                                <div class="text-center col-6 col-lg-3 mb-lg-0">
                                    <div class="px-2">
                                        <dt>Prep Time</dt>
                                        <dd>20 min</dd>
                                    </div>
                                </div>
                            </dl>
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