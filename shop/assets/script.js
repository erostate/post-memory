
function searchProductInput(event) {
    if (event.key === "Enter") {
        searchProduct();
    }
}

function searchProduct() {
    const searchProductValue = document.getElementById('searchProductValue').value;

    if (searchProductValue === '' || searchProductValue === null) {
        alert('Please enter a product name');
        return;
    }
    alert('You are searching for: ' + searchProductValue);
}