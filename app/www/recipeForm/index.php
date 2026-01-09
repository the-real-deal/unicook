<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "components/PageOpening.php";
require_once "components/Navbar.php";
require_once "components/Footer.php";

PageOpening("Create Recipe",["style.css"]);
?>

<body>
    <?php 
    Navbar();
    ?>
    <main>
        <form class="d-flex flex-column p-4 gap-3 mx-auto my-2">
            <label for="title">Titolo Ricetta *</label>
            <input type="text" id="title" name="title" class="p-2" placeholder="es. Ramen Facile" required />

            <label for="description">Descrizione *</label>
            <input type="text" id="description" class="p-2" placeholder="Descrivi la tua ricetta..." required />

            <label for="image_URL">Immagine (facoltativo)</label>
            <input type="file" id="image_URL" class="p-2" />

            <hr>
            <label for="tags">Tag</label>
            <select name="tags" id="tags" class="p-2">
                <option disabled selected value style="display:none"> -- seleziona un'opzione -- </option>
                <option value="italiana">Italiana</option>
                <option value="veloce">Veloce</option>
                <option value="vegetariana">Vegetariana</option>
                <option value="vegana">Vegana</option>
            </select>
            <ul id="tag-list">
            </ul>

            <hr>
            <div class="row">
                <div class="col-md-6 d-flex flex-column ps-2 py-2 gap-2">
                    <label for="difficulty">Difficoltà *</label>
                    <select name="difficulty" id="difficulty" class="p-2" required>
                        <option disabled selected value style="display:none"> -- seleziona un'opzione -- </option>
                        <option value="0">Facile</option>
                        <option value="1">Media</option>
                        <option value="2">Difficile</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex flex-column ps-2 py-2 gap-2">
                    <label for="prep_time">Tempo Preparazione *</label>
                    <select name="prep_time" id="prep_time" class="p-2" required>
                        <option disabled selected value style="display:none"> -- seleziona un'opzione -- </option>
                        <option value="0">Veloce</option>
                        <option value="1">Normale</option>
                        <option value="2">Lento</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex flex-column ps-2 py-2 gap-2">
                    <label for="cost">Costo *</label>
                    <select name="cost" id="cost" class="p-2" required>
                        <option disabled selected value style="display:none"> -- seleziona un'opzione -- </option>
                        <option value="0">Economico</option>
                        <option value="1">Normale</option>
                        <option value="2">Costoso</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex flex-column ps-2 py-2 gap-2">
                    <label for="serving_size">Porzioni *</label>
                    <input type="number" id="serving_size" name="serving_size" class="p-2" min="1" value="1" required />
                </div>
            </div>
            <hr>

            <div class="d-flex justify-content-between align-items-center">
                <label for="ingredients">Ingredienti *</label>
                <label for="add-ingredients-slot" hidden>Passaggi Preparazione *</label>
                <button type="button" id="add-ingredients-slot">Aggiungi
                    Ingrediente</button>
            </div>
            <ul id="ingredients">
            </ul>
            <hr>

            <div class="d-flex justify-content-between align-items-center">
                <label for="steps">Passaggi Preparazione *</label>
                <label for="add-step-slot" hidden>Aggiungi Passaggio</label>
                <button type="button" id="add-step-slot">Aggiungi Passaggio</button>
            </div>
            <ul id="steps">
            </ul>
        </form>
    </main>
    <?php 
    Footer();
    ?>
    <script type="module" src="main.js"></script>
</body>
