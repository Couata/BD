document.addEventListener("DOMContentLoaded", function() {
    var message = document.getElementById("message");
    if (message) {
        setTimeout(function() {
            message.style.display = "none";
        }, 3000); 
    }
});