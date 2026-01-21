document.getElementById("home-search-form").addEventListener('submit', function (event) {
    event.preventDefault()
    const uri = encodeURI("/recipes/?text=" + document.getElementById("search-home").value)
    window.location = uri
})