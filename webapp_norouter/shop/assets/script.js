
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

function changeCountProduct(pId, action) {
    const productCount = document.getElementById('count-p-' + pId);
    if (action === 'add') {
        productCount.value = parseInt(productCount.value) + 1;
    } else if (action === 'remove') {
        if (productCount.value > 1) {
            productCount.value = parseInt(productCount.value) - 1;
        }
    } else if (action === 'delete') {
        // TODO: Delete product from cart
        productCount.value = 0;
    }
}