function get_products() {
    return [
        {
            name: "Салфетки",
            price: 384
        },
        {
            name: "Чайник",
            options: [
                {
                    name: "Самовывоз",
                    price: 0
                },
                {
                    name: "Доставка на дом",
                    price: 100
                },
                {
                    name: "Дополнительная эксплуатация",
                    price: 300
                },
                {
                    name: "Доставка на дом с дополнительной эксплуатацией",
                    price: 500
                }
            ],
            price: 4171
        },
        {
            name: "Наушники",
            price: 819,
            properties: [
                {
                    name: "Помощь в запуске гаджета",
                    price: 100
                },
                {
                    name: "Дополнительная симкарта",
                    price: 300
                },
                {
                    name: "Гарантия на 1 год",
                    price: 0
                }
            ]
        },
        {
            name: "Батончик",
            price: 770
        },
        {
            name: "Смартчасы",
            options: [
                {
                    name: "Самовывоз",
                    price: 0
                },
                {
                    name: "Доставка на дом",
                    price: 100
                },
                {
                    name: "Дополнительная эксплуатация",
                    price: 300
                },
                {
                    name: "Доставка на дом с дополнительной эксплуатацией",
                    price: 500
                },
            ],
            price: 1721,
            properties: [
                {
                    name: "Помощь в запуске гаджета",
                    price: 100
                },
                {
                    name: "Дополнительная симкарта",
                    price: 300
                },
                {
                    name: "Гарантия на 1 год",
                    price: 500
                }
            ]
        }
    ];
}


function insertProperty(container, index, property) {
    let label = document.createElement("label");
    label.setAttribute("for", `property-${index}`);
    label.innerHTML = property.name;

    let input = document.createElement("input");
    input.type = "checkbox";
    input.name = `property-${index}`;
    input.id = `property-${index}`;

    container.appendChild(input);
    container.appendChild(label);
}


function createProperties(indexProduct) {
    let propertiesContainer = document.getElementById("properties");
    propertiesContainer.innerHTML = "";

    let currentProduct = get_products()[indexProduct];
    if (currentProduct.properties) {
        currentProduct.properties.forEach(function (property, index) {
            insertProperty(propertiesContainer, index, property);
        });
    }
}


function insertOption(container, index, option) {
    let element = document.createElement("option");
    element.value = index;
    element.innerHTML = option.name;

    container.appendChild(element);
}


function createOptions(indexProduct) {
    let optionsContainer = document.getElementById("options");
    optionsContainer.innerHTML = "";

    let currentProduct = get_products()[indexProduct];
    if (currentProduct.options) {
        let select = document.createElement("select");
        select.name = "option";
        insertOption(select, NaN, {name: "-", price: 0});

        currentProduct.options.forEach(function (option, index) {
            insertOption(select, index, option);
        });
        optionsContainer.appendChild(select);
    }
}


function choiceProduct(event) {
    let indexProduct = parseInt(event.target.value);

    createOptions(indexProduct);
    createProperties(indexProduct);
}


function insertProduct(container, index, product) {
    let label = document.createElement("label");
    label.setAttribute("for", `product-${index}`);
    label.innerHTML = product.name;

    let input = document.createElement("input");
    input.type = "radio";
    input.name = "product";
    input.value = index;
    input.id = `product-${index}`;

    container.appendChild(input);
    container.appendChild(label);
}


function isNumber(str) {
    return str.match(/^[1-9]\d*$/) !== null;
}


function calculate() {
    let product = document.querySelector(
        "#products input[type=radio]:checked"
    );
    if (!product) {
        return;
    }
    product = get_products()[parseInt(product.value)];

    let price = product.price;
    if (product.options) {
        let option = parseInt(document.querySelector("#options select").value);
        if (!Number.isNaN(option)) {
            price += product.options[option].price;
        }
    }

    if (product.properties) {
        let properties = document.querySelectorAll(
            "#properties input[type=checkbox]"
        );

        properties.forEach(function (property, index) {
            if (property.checked) {
                price += product.properties[index].price;
            }
        });
    }

    let content;
    let quantity = document.getElementById("quantity").value;
    if (!isNumber(quantity)) {
        content = "Enter a natural number!";
    } else {
        content = price * parseInt(quantity);
    }

    document.getElementById("result").innerHTML = content;
}


window.addEventListener("DOMContentLoaded", function () {
    let productContainer = document.getElementById("products");
    get_products().forEach(function (product, index) {
        insertProduct(productContainer, index, product);
    });
    productContainer.addEventListener("change", choiceProduct);

    document.querySelector("form").addEventListener("change", calculate);
});
