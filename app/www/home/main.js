document.getElementById("home-search-form").addEventListener('submit', function (event) {
    event.preventDefault();
    window.location = "/recipes/?text=" + document.getElementById("search-home").value;
})