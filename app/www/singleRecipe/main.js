const reviewTextArea = document.getElementById('review-text-area');
if (reviewTextArea) {
    reviewTextArea.oninput = function () {
        this.style.height = "";
        this.style.height = (this.scrollHeight + 3) + 'px';
    };
}