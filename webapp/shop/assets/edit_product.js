// MISC
function customProduct(btn, productId) {
    
}


// CUSTOMING
let currentDraggedElement = null;

let customizationList = {};

function addText() {
    const plaqueContainer = document.getElementById('plaqueContainer');
    const newText = document.createElement('div');
    newText.className = 'draggable-text';
    newText.draggable = true;
    newText.innerHTML = '<span>Votre texte ici</span>';

    newText.ondragstart = dragStart;
    newText.ondragend = dragEnd;
    newText.onclick = function() {
        textInfo(newText);
    };
    newText.ondblclick = enableEdit;

    const deleteButton = document.createElement('button');
    deleteButton.className = 'delete-button';
    deleteButton.innerHTML = '&times;';
    deleteButton.onclick = () => {
        plaqueContainer.removeChild(newText);
    };

    const infoButton = document.createElement('button');
    infoButton.className = 'info-button';
    infoButton.innerHTML = '<i class="fas fa-info"></i>';
    infoButton.onclick = () => {
        textInfo(newText);
    };

    newText.appendChild(deleteButton);
    newText.appendChild(infoButton);
    plaqueContainer.appendChild(newText);

    // Position the new text at the center
    const rect = plaqueContainer.getBoundingClientRect();
    newText.style.left = `${rect.width / 2 - newText.offsetWidth / 2}px`;
    newText.style.top = `${rect.height / 2 - newText.offsetHeight / 2}px`;
}

function addDate() {
    const plaqueContainer = document.getElementById('plaqueContainer');
    const newDate = document.createElement('div');
    newDate.className = 'draggable-text';
    newDate.draggable = true;
    newDate.innerHTML = '<span>' + new Date().toLocaleDateString() + '</span>';

    newDate.ondragstart = dragStart;
    newDate.ondragend = dragEnd;
    newDate.onclick = function() {
        textInfo(newText);
    };
    newDate.ondblclick = enableEdit;

    const deleteButton = document.createElement('button');
    deleteButton.className = 'delete-button';
    deleteButton.innerHTML = '&times;';
    deleteButton.onclick = () => {
        plaqueContainer.removeChild(newDate);
    };

    const infoButton = document.createElement('button');
    infoButton.className = 'info-button';
    infoButton.innerHTML = '<i class="fas fa-info"></i>';
    infoButton.onclick = () => {
        // let currentSize = parseFloat(window.getComputedStyle(newDate, null).getPropertyValue('font-size'));
        // newDate.style.fontSize = (currentSize + 2) + 'px';
        textInfo(newText);
    };

    newDate.appendChild(deleteButton);
    newDate.appendChild(infoButton);
    plaqueContainer.appendChild(newDate);

    // Position the new date at the center
    const rect = plaqueContainer.getBoundingClientRect();
    newDate.style.left = `${rect.width / 2 - newDate.offsetWidth / 2}px`;
    newDate.style.top = `${rect.height / 2 - newDate.offsetHeight / 2}px`;
}

function textInfo(container) {
    console.log(container);

    const optControls = document.getElementsByClassName('opt-controls')[0];
    const currentText = document.getElementById('currentText');

    var id = 0;
    if (container.id) {
        id = container.id.split('-')[1];
    } else {
        id = Math.floor(Math.random() * 1000);
    }
    
    if (getElementInCustomizationList(id)) {
        const customization = getElementInCustomizationList(id);
        document.getElementById('textSize').value = customization.size;
        document.getElementById('textColor').value = customization.color;
        document.querySelector('input[data-value="' + customization.weight + '"]').checked = true;
    } else {
        document.getElementById('textSize').value = 20;
        document.getElementById('textColor').value = '#000000';
        document.querySelector('input[data-value="light"]').checked = true;

        customizationList[id] = {
            size: 20,
            color: '#000000',
            weight: 'light'
        };
    }

    setTimeout(() => {
        optControls.style.display = 'flex';
    }, 100);

    container.style.border = '2px solid purple';
    if (!container.id) {
        container.id = 'text-' + id;
    }

    currentText.value = id;
}

function optChange(type, element) {
    const currentText = document.getElementById('currentText').value;
    const textElement = document.getElementById('text-' + currentText).getElementsByTagName('span')[0];

    switch (type) {
        case 'size':
            textElement.style.fontSize = element.value + 'px';
            updateElementInCustomizationList(currentText, 'size', element.value);
            break;
        case 'color':
            textElement.style.color = element.value;
            updateElementInCustomizationList(currentText, 'color', element.value);
            break;
        case 'weight':
            const dataValue = element.getAttribute('data-value');
            updateElementInCustomizationList(currentText, 'weight', dataValue);
            if (dataValue == 'light') {
                textElement.style.fontWeight = '300';
            } else if (dataValue == 'normal') {
                textElement.style.fontWeight = '600';
            } else if (dataValue == 'bold') {
                textElement.style.fontWeight = '900';
            }
            break;
    }
}

function enableEdit(event) {
    const target = event.target;
    target.contentEditable = true;
    target.focus();
    target.onblur = disableEdit;
    if (target.innerText == 'Votre texte ici') {
        target.innerText = '';
        console.log('qzgqgz')
    }
}

function disableEdit(event) {
    const target = event.target;
    target.contentEditable = false;
    if (target.innerText.trim() == '') {
        target.innerText = 'Votre texte ici';
    }
}

function dragStart(event) {
    currentDraggedElement = event.target;
    event.dataTransfer.setData('text/plain', null);
    const style = window.getComputedStyle(event.target, null);
    event.dataTransfer.setData('text/plain',
        (parseInt(style.getPropertyValue('left'), 10) - event.clientX) + ',' +
        (parseInt(style.getPropertyValue('top'), 10) - event.clientY));
}

function dragEnd(event) {
    const offset = event.dataTransfer.getData('text/plain').split(',');
    const left = (event.clientX + parseInt(offset[0], 10));
    const top = (event.clientY + parseInt(offset[1], 10));

    currentDraggedElement = event.target;
    currentDraggedElement.style.left = `${left}px`;
    currentDraggedElement.style.top = `${top}px`;
    currentDraggedElement = null;
}

function getElementInCustomizationList(id) {
    return customizationList[id];
}
function updateElementInCustomizationList(id, type, customization) {
    customizationList[id][type] = customization;
}

document.addEventListener('DOMContentLoaded', () => {
    const plaqueContainer = document.getElementById('plaqueContainer');
    plaqueContainer.ondragover = event => event.preventDefault();
    plaqueContainer.ondrop = event => {
        event.preventDefault();
        if (currentDraggedElement) {
            const offset = event.dataTransfer.getData('text/plain').split(',');
            const left = (event.clientX + parseInt(offset[0], 10));
            const top = (event.clientY + parseInt(offset[1], 10));

            currentDraggedElement.style.left = `${left}px`;
            currentDraggedElement.style.top = `${top}px`;
            currentDraggedElement = null;
        }
    };
    plaqueContainer.onclick = () => {
        const optControls = document.getElementsByClassName('opt-controls')[0];
        if (optControls.style.display == 'flex') {
            optControls.style.display = 'none';
            const draggableText = document.getElementsByClassName('draggable-text');
            for (let item of draggableText) {
                item.style.border = 'none';
            }
        }
    };
});