const sens = document.getElementById("sens");

//create a request variable 
var request = new XMLHttpRequest();

//open a new connection
request.open("GET", "https://niekvanleeuwen.nl/senstable/api/sensors/get/", true);

request.onload = function() {
    //parse the object
    var data = JSON.parse(this.response);
    //handle errors
    if (request.status >= 200 && request.status < 400) {
        data.forEach(sensor => {
            const section = document.createElement("section");
            section.setAttribute("class", "pt-0");

            const container = document.createElement("div");
            container.setAttribute("class", "container");

            const row = document.createElement("div");
            row.setAttribute("class", "row");
            row.setAttribute("data-aos", "fade-up");

            const col1 = document.createElement("div");
            col1.setAttribute("class", "col-md-6");

            const a1 = document.createElement("a");
            a1.setAttribute("class", "fade-page");
            a1.setAttribute("href", "sensor.html?id=" + sensor.id);

            const img = document.createElement("img");
            img.setAttribute("src", "admin/" + sensor.diagram);
            img.setAttribute("class", "rounded shadow-3d hover-shadow-3d border mb-3 mb-md-0");

            a1.appendChild(img);
            col1.appendChild(a1);


            const col2 = document.createElement("div");
            col2.setAttribute("class", "col");

            const rowInCol2 = document.createElement("div");
            rowInCol2.setAttribute("class", "row justify-content-center");

            const colinCol2 = document.createElement("div");
            colinCol2.setAttribute("class", "col-xl-9 col-lg-10");

            const a2 = document.createElement("a");
            a2.setAttribute("class", "fade-up");
            a2.setAttribute("href", "sensor.html?id=" + sensor.id);

            const h3ina2 = document.createElement("h3");
            h3ina2.setAttribute("class", "h2");
            h3ina2.textContent = sensor.name;

            const pLead = document.createElement("p");
            pLead.setAttribute("class", "lead");
            pLead.textContent = sensor.short_description;

            a2.appendChild(h3ina2);
            colinCol2.appendChild(a2);
            colinCol2.appendChild(pLead)
            rowInCol2.appendChild(colinCol2);
            col2.appendChild(rowInCol2);
            row.appendChild(col1);
            row.appendChild(col2);
            container.appendChild(row);
            section.appendChild(container);
            sens.appendChild(section);
        });
    }
}

request.send();