function isNumber(str) {
    return str.match(/^[1-9]\d*$/) !== null;
}


function calculate(event) {
    event.preventDefault();
    let result = document.getElementById("result");
    let priceElement = document.getElementById("product").value;
    let countElement = document.getElementById("quantity").value;
    let croue = document.getElementById("product");
    let c;
    if (priceElement === "") {
        c = "Выберите продукцию!";
    } else if (!isNumber(countElement)) {
        c = "Введите целое число!";
    } else {
        c = parseInt(priceElement) * parseInt(countElement);
    }
    forest.innerHTML = parseInt(priceElement);
    result.innerHTML = c;
  croue[0].addEventListener("change", function(event) {
    let w = event.target;
    let fw = document.getElementById("grew");
    console.log(w.value);
    if (w.value == "3") {
      grew.style.display = "none";
    }
    else {
      grew.style.display = "block";
    }
  });
  
  let r = document.querySelectorAll(".grew input[type=terw]");
  r.forEach(function(terw) {
    radio.addEventListener("change", function(event) {
      let r = event.target;
      console.log(r.value);
    });    
  });
}


window.addEventListener("DOMContentLoaded", function () {
    document.getElementById("btn").addEventListener("click", calculate);
});
