// In your Javascript (external .js resource or <script> tag)
$(document).ready(function () {

    // Initialize autocompletes

    $('.select2').select2();
    select2AutocompleteClientName();
    select2AutocompleteProductName();
    select2AutocompleteProductNameSuppliersTableLoader();

});

function select2AutocompleteClientName() {
    $('.select2-autocomplete-client-name').select2({
        ajax: {
            url: '/api/autocomplete/clients_autocomplete.php',
            method: 'GET',
            dataType: 'json',
            data: (params) => ({ client_name: params.term }),
            delay: 400,
            width: 'resolve',
            theme: "classic",
            processResults: (data) => {
                return {
                    results: data.results.map((element, _, __) => ({ id: element.id, text: element.name }))
                };
            },
        }
    });
}

function select2AutocompleteProductName() {
    $('.select2-autocomplete-product-name').select2({
        ajax: {
            url: '/api/autocomplete/products_autocomplete.php',
            method: 'GET',
            dataType: 'json',
            data: (params) => ({ product_name: params.term }),
            delay: 400,
            width: 'resolve',
            theme: "classic",
            processResults: (data) => {
                return {
                    results: data.results.map((element, _, __) => ({ id: element.id, text: `${element.name} - R$ ${element.price.toFixed(2)}` }))
                };
            },
        }
    });
}

function select2AutocompleteProductNameSuppliersTableLoader() {
    $('.select2-autocomplete-product-name.list-product-suppliers').on('select2:select', function (e) {

        let selected = e.params.data;
        document.querySelector('#product-selection-quantity').value = 1

        fetch(`/api/suppliers/list_by_product.php?product_id=${selected.id}`).then(response => {

            clearProductSuppliersTable();

            const r = response.json().then(jsonList => {

                let targetTable = document.querySelector('[product-suppliers-list]');

                jsonList.forEach((supplier, _, __) => {
                    let tableData = document.createElement('td');
                    tableData.innerText = `${supplier.supplier_name}`;

                    let tableRow = document.createElement('tr');
                    tableRow.appendChild(tableData);

                    targetTable.appendChild(tableRow)
                })
            })
        })
    });
}

function clearProductSuppliersTable() {
    document.querySelector('[product-suppliers-list]').replaceChildren([]);
}

function addSelectedProductToCart() {
    let selectElement = document.querySelector('#product-selection');
    let selectedProduct = selectElement.options.item(selectElement.options.selectedIndex)
    let quantityElement = document.querySelector('#product-selection-quantity')
    let quantity = parseInt(quantityElement.value);

    //.options.selectedIndex
    if (selectedProduct.value == '') {
        return;
    }

    if (quantity == NaN || quantity < 1) {
        return;
    }

    let targetTable = document.querySelector('[product-sell-list]');
    let itemNumber = targetTable.childNodes.length;

    let tableRow = document.createElement('tr');
    tableRow.setAttribute("id", `product-cart-item-${itemNumber}`);

    let tableData = document.createElement('td');
    tableData.innerText = `${selectedProduct.innerText}`;
    tableData.setAttribute('product_id', selectedProduct.value);
    tableData.setAttribute('quantity', quantity);
    tableData.setAttribute('sell-itens', '');
    tableRow.appendChild(tableData);

    tableData = document.createElement('td');
    tableData.innerText = `${quantity}`;
    tableRow.appendChild(tableData);

    tableData = document.createElement('td');
    let removeBtn = document.createElement('button');

    removeBtn.innerText = "Remover";
    removeBtn.onclick = () => removeProductFromCart(`product-cart-item-${itemNumber}`);
    removeBtn.className = "btn btn-danger w-100"

    tableData.appendChild(removeBtn);
    tableRow.appendChild(tableData);

    targetTable.appendChild(tableRow);
    clearProductSuppliersTable();
    selectElement.value = '';
    quantityElement.value = 1;

    $('#product-selection').trigger('change');

}

function removeProductFromCart(itemId) {
    document.getElementById(itemId).remove();
}

function postalCodeFieldAutocomplete(event) {

    const mask = /^[0-9]{5}-[0-9]{3}$/;
    const numberValidator = /[0-9]{1}/
    const input = event.target;

    if (event.inputType == 'deleteContentBackward') {
        input.previous = input.value;
    }

    if (!numberValidator.test(event.data)) {
        input.value = input.previous
        return;
    }

    if (`${input.previous}`.length == 5) {
        input.value = input.previous + '-' + event.data;
    }

    input.previous = input.value;

    if (mask.test(input.value)) {
        postalCodeFieldAutocompleteApiRequest(input.value);
    }
}

function postalCodeFieldAutocompleteApiRequest(postalCode) {
    fetch(`https://viacep.com.br/ws/${postalCode}/json/`).then(response => {

        if (!response.ok) {
            let toClear = document.querySelectorAll('[address-form-data]')
            toClear.forEach(el => el.value = "")
        }

        const r = response.json().then(json => {
            document.querySelector("input[name='street']").value = json.logradouro;
            document.querySelector("input[name='district']").value = json.bairro;
            document.querySelector("input[name='city']").value = json.localidade;
            document.querySelector("input[name='state']").value = json.uf;
        })
    })
}

function saveSell() {

    let form = document.forms.item(0);
    let isComplete = form.reportValidity();

    if (!isComplete) {
        callModalBox('Verificar informações', 'Verifique as informações da venda pois algumas podem estar ausentes')
        return;
    }

    let formData = new FormData(form);
    let content = {}
    content.items = []
    formData.forEach((value, keyName, _) => content[keyName] = value);

    let itensTable = document.querySelectorAll('[sell-itens]');

    itensTable.forEach((element, _, __) => {
        content.items.push({
            product_id: element.getAttribute('product_id'),
            quantity: element.getAttribute('quantity')
        })
    });

    fetch('/api/sell/save.php', {
        headers: {
            'Content-Type': "application/json"
        },
        method: 'POST',
        body: JSON.stringify(content)
    }).then((response) => {
        console.log(response);

        if (response.ok) {
            callModalBox("Situação", 'Venda salva com sucesso', 'hide.bs.modal', () => { window.location.reload() })
        }
    })
}

function callModalBox(titleText, bodyText, event, eventFunction) {
    let title = document.getElementById('modal-title')
    let message = document.getElementById('modal-content')

    title.innerText = titleText
    message.innerText = bodyText

    let modal = document.getElementById('modal')
    let modalBsElement = new bootstrap.Modal(modal, {})

    modalBsElement.show();

    if (event) {
        let handler = (event) => {
            eventFunction();
            modal.removeEventListener(event, handler);
        }
        modal.addEventListener(event, handler)
    }
}