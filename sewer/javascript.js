function isNumber(str) {
    return str.match(/^[1-9]\d*$/) !== null;
}


function calculate(event) {
    event.preventDefault();

    let result = document.getElementById("result");
    let forest = document.getElementById("forest");
    let priceElement = document.getElementById("product").value;
    let countElement = document.getElementById("quantity").value;

    let c;

    if (priceElement === "") {
        c = "Выберите продукцию!";
    } else if (!isNumber(countElement)) {
        c = "Введите целое число!";
    } else {
        c = parseInt(priceElement) * parseInt(countElement);
    }
    forest.innerHTML = forest;
    result.innerHTML = c;
}


window.addEventListener("DOMContentLoaded", function () {
    document.getElementById("btn").addEventListener("click", calculate);
});
