document.addEventListener('DOMContentLoaded', function() {
    const boxes = document.querySelectorAll('.box');

    boxes.forEach(box => {
        box.addEventListener('click', function(event) {
            const subboxes = box.nextElementSibling;
            if (subboxes && subboxes.classList.contains('subboxes')) {
                event.preventDefault();
                toggleSubboxes(subboxes);
            }
        });
    });

    function toggleSubboxes(subbox) {
        if (subbox.style.maxHeight === '0px' || subbox.style.maxHeight === '') {
            subbox.style.maxHeight = '500px'; // Adjust this value based on the number of sub-boxes
            subbox.style.opacity = '1';
        } else {
            subbox.style.maxHeight = '0px';
            subbox.style.opacity = '0';
        }
    }
});
