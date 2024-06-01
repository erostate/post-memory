function togglePassw(btn) {
    var input = btn.previousElementSibling;

    if (input.type === "password") {
        input.type = "text";
        btn.innerHTML = "<i class=\"fa-solid fa-eye-slash\"></i>";
    } else {
        input.type = "password";
        btn.innerHTML = "<i class=\"fa-solid fa-eye\"></i>";
    }
}

function checkSamePassw(inp) {
    var passw = document.getElementById("password");
    var passw2 = inp;

    if (passw.value !== passw2.value) {
        passw2.setCustomValidity("Les mots de passe ne correspondent pas.");
    } else {
        passw2.setCustomValidity("");
    }

}

function checkStrength(password) {
    password = password.value;

    let strong_password = document.getElementById("strong_password");
    let passwordStrength = document.getElementById("password-strength");
    let lowUpperCase = document.getElementsByClassName("low-upper-case")[0];
    let number = document.getElementsByClassName("one-number")[0];
    let specialChar = document.getElementsByClassName("one-special-char")[0];
    let eightChar = document.getElementsByClassName("eight-character")[0];

    let strength = 0;

    //If password contains both lower and uppercase characters
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
        strength += 1;
        lowUpperCase.innerHTML = `
            <i class="fa fa-check" aria-hidden="true"></i>
            &nbsp;Minuscule &amp; Majuscule
        `;
    } else {
        lowUpperCase.innerHTML = `
            <i class="fa fa-circle" aria-hidden="true"></i>
            &nbsp;Minuscule &amp; Majuscule
        `;
    }
    //If it has numbers and characters
    if (password.match(/([0-9])/)) {
        strength += 1;
        number.innerHTML = `
            <i class="fa fa-check" aria-hidden="true"></i>
            &nbsp;Nombre (0-9)
        `;
    } else {
        number.innerHTML = `
            <i class="fa fa-circle" aria-hidden="true"></i>
            &nbsp;Nombre (0-9)
        `;
    }
    //If it has one special character
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
        strength += 1;
        specialChar.innerHTML = `
            <i class="fa fa-check" aria-hidden="true"></i>
            &nbsp;Caractère spécial (!@#$%^&*)
        `;
    } else {
        specialChar.innerHTML = `
            <i class="fa fa-circle" aria-hidden="true"></i>
            &nbsp;Caractère spécial (!@#$%^&*)
        `;
    }
    //If password is greater than 7
    if (password.length > 7) {
        strength += 1;
        eightChar.innerHTML = `
            <i class="fa fa-check" aria-hidden="true"></i>
            &nbsp;Au moins 8 caractères
        `;
    } else {
        eightChar.innerHTML = `
            <i class="fa fa-circle" aria-hidden="true"></i>
            &nbsp;Au moins 8 caractères
        `;  
    }

    console.log(passwordStrength);

    if (strength < 2) {
        passwordStrength.classList.remove('progress-bar-warning');
        passwordStrength.classList.remove('progress-bar-success');
        if (password.length <= 0) {
            passwordStrength.style = 'width: 0%';
            passwordStrength.classList.remove('progress-bar-danger');
        } else {
            passwordStrength.style = 'width: 10%';
            passwordStrength.classList.add('progress-bar-danger');
        }
        
        strong_password.value = 'false';
    } else if (strength == 3) {
        passwordStrength.classList.remove('progress-bar-success');
        passwordStrength.classList.remove('progress-bar-danger');
        passwordStrength.classList.add('progress-bar-warning');
        passwordStrength.style = 'width: 60%';

        strong_password.value = 'false';
    } else if (strength == 4) {
        passwordStrength.classList.remove('progress-bar-warning');
        passwordStrength.classList.remove('progress-bar-danger');
        passwordStrength.classList.add('progress-bar-success');
        passwordStrength.style = 'width: 100%';

        strong_password.value = 'true';
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    const phoneInput = document.getElementById('company_phone');

    phoneInput.addEventListener('input', function (e) {
        let input = e.target.value.replace(/\D/g, '');
        if (input.length > 10) input = input.substring(0, 10);

        if (input.length === 10) {
            input = input.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
        } else if (input.length <= 9) {
            input = input.replace(/(\d{1})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
        }

        e.target.value = input;
    });

    phoneInput.addEventListener('focus', function (e) {
        if (e.target.value === '') {
            // e.target.value = '_ __ __ __ __';
        }
    });

    phoneInput.addEventListener('blur', function (e) {
        if (e.target.value === '_ __ __ __ __') {
            e.target.value = '';
        }
    });

    phoneInput.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace') {
            if (e.target.value.length === 1) {
                e.target.value = '_ __ __ __ __';
                e.preventDefault();
            }
        }
    });
});

function geoApi(type, inp) {
    if (type == 'searchCommune') {
        const company_zip = inp.value;
        const company_city = document.getElementById('company_city');
        const company_address = document.getElementById('company_address');
        if (company_zip.length < 5) {
            company_city.disabled = true;
            company_address.disabled = true;
            return;
        }
        $.ajax({
            url: 'https://geo.api.gouv.fr/communes?codePostal=' + company_zip,
            type: 'GET',
            success: function(response) {
                console.log(response);
                company_city.disabled = false;
                company_address.disabled = false;
                for (let i = 0; i < response.length; i++) {
                    const commune = response[i];
                    company_city.innerHTML += `<option value="${commune.nom}">${commune.nom}</option>`;
                }
            },
            error: function() {
                company_city.disabled = true;
                company_address.disabled = true;
            }
        });
    } else if (type == 'searchAddress') {
        const company_address = inp.value;
        const company_city = document.getElementById('company_city');
        const company_zip = document.getElementById('company_zip');
        if (company_address.length < 3) {
            return;
        }
        $.ajax({
            url: 'https://api-adresse.data.gouv.fr/search/?q=' + company_address + '&city=' + company_city.value + '&postcode=' + company_zip.value,
            type: 'GET',
            success: function(response) {
                const companyAddressList = document.getElementById('company_address_list');
                companyAddressList.innerHTML = '';
                for (let i = 0; i < response.features.length; i++) {
                    const address = response.features[i];
                    companyAddressList.innerHTML += `<li onclick="selectAddress(this)">${address.properties.name}</li>`;
                }
            },
            error: function() {
                console.log('Error');
            }
        });
    }
    checkDataContent(inp);
}

function addressList(action) {
    const companyAddressList = document.getElementById('company_address_list');
    if (action == 'open') {
        companyAddressList.style.display = 'block';
    } else if (action == 'close') {
        companyAddressList.style.display = 'none';
    }
}
// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('#company_address')) {
        addressList('close');
    }
}

function selectAddress(li) {
    const company_address = document.getElementById('company_address');
    company_address.value = li.innerText;
    document.getElementById('company_address_list').innerHTML = '';
}

function checkDataContent(elem) {
    if (elem.value.length > 0) {
        elem.style.borderBottom = '1px solid rgb(0, 255, 0)';
    } else {
        elem.style.borderBottom = '1px solid rgb(255, 0, 0)';
    }
}