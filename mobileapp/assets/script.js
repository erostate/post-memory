document.addEventListener('DOMContentLoaded', function () {
    // Get all the elements with the class "faq-header"
    var faqHeaders = document.querySelectorAll('.faq-header');

    // Loop through the elements
    faqHeaders.forEach(function (header) {
        header.addEventListener('click', function () {
            // Remove the class "active" from all the elements with the class "faq-card"
            document.querySelectorAll('.faq-card').forEach(function (card) {
                card.classList.remove('active');
            });

            // Add the class "active" to the closest element with the class "faq-card"
            header.closest('.faq-card').classList.add('active');
        });
    });
});


// FUNCTION
function seeMoreImage() {
    openModal('allImageModal');
}


// MODAL
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}
