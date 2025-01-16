// In your Javascript (external .js resource or <script> tag)
$(document).ready(function () {
    $('.select2').select2();
});

async function getCep(cep) {
    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
    if (!response.ok) {
        throw new Error(`Response status: ${response.status}`);
    }

    const r = await response.json();

    document.querySelector("#logradouro").value = r.logradouro;
    document.querySelector("#bairro").value = r.bairro;
    document.querySelector("#localidade").value = r.localidade;
    document.querySelector("#uf").value = r.uf;
}