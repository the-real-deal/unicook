document.getElementById('review-text-area').oninput = function () {
    this.style.height = "";
    this.style.height = (this.scrollHeight + 3) + 'px';
};