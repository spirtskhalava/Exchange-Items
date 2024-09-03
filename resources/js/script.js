document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.clickable-image').forEach(function (image) {
        image.addEventListener('click', function (event) {
            console.log("event", event);
            var imageUrl = this.src;
            document.getElementById('modal-image').src = imageUrl;
        });
    });
});