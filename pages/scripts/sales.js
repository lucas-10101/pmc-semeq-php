window.addEventListener("load", () => {
    listSales();
});

function listSales(pageNumber) {
    fetch("/api/sell/list.php", {
        method: "GET",
    }).then((result) => {
        if (result.ok) {
            result.json().then((jsonData) => {


                let target = document.getElementById('salesList')
                target.innerHTML = "";

                jsonData.forEach(element => {
                    target.innerHTML += createSaleDomElementFromJsonObject(element)
                });

            });
        }
    });
}

function createSaleDomElementFromJsonObject(jsonObject) {
    let saleId = jsonObject.saleId;
    let collapseId = `collapse-${saleId}`;
    let saleDate = jsonObject.saleDate;
    let saleTotal = parseFloat(jsonObject.total).toFixed(2);
    let clientName = jsonObject.clientName;
    let sellerName = jsonObject.sellerName;

    let addressFirstLine = `Cidade: ${jsonObject.addressCity}/${jsonObject.addressState} - ${jsonObject.addressPostalCode}`;
    let addressSecondLine = `${jsonObject.addressStreetNumber}, ${jsonObject.addressStreet} - ${jsonObject.addressDistrict}`;
    let addressThirdLine = `${jsonObject.addressComplement}`;

    let productsTemplate = "";

    jsonObject.items.forEach((element) => {
        let totalProduct = (parseFloat(element.unitPrice) * parseInt(element.quantity)).toFixed(2);

        productsTemplate += `
        <tr>
            <td>${element.productName}</td>
            <td>${element.quantity} Un</td>
            <td>R$ ${parseFloat(element.unitPrice).toFixed(2)}</td>
            <td>R$ ${totalProduct}</td>
        </tr>
    `;
    });

    let htmlTemplate = `
    <div class="accordion-item border rounded border-black">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                Venda nº ${saleId} - Data da venda: ${saleDate} - Total: R$ ${saleTotal}
            </button>
        </h2>
        <div id="${collapseId}" class="accordion-collapse collapse" data-bs-parent="#salesList">
            <div class="accordion-body pt-5">
                <h4 class="text-start m-2 p-1 rounded">Cliente: ${clientName}</h4>
                <h4 class="text-start m-2 p-1 rounded">Vendedor: ${sellerName}</h4>
                <hr>
                <h2 class="text-center m-2 p-1 rounded">Produtos</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Valor Unitário</th>
                            <th>Total Item</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${productsTemplate}
                    </tbody>
                </table>
                <div class="container-fluid rounded bg-body-secondary p-2">
                    <h2 class="text-center m-2 p-1 rounded">Entrega</h2>
                    <h4 class="text-start"> ${addressFirstLine}</h4>
                    <h4 class="text-start"> ${addressSecondLine}</h4>
                    <h4 class="text-start"> ${addressThirdLine}</h4>
                </div>
            </div>
        </div>
    </div>
    `;
    return htmlTemplate;
}
