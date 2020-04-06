const tbody = document.getElementById("table");

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
            const entry = document.createElement("tr");

            const name = document.createElement("td");
            name.textContent = sensor.name;

            const description = document.createElement("td");
            description.textContent = sensor.short_description;

            const serialNumber = document.createElement("td");
            serialNumber.textContent = sensor.serial_number;

            entry.appendChild(name);
            entry.appendChild(description);
            entry.appendChild(serialNumber);
            tbody.appendChild(entry);
            });
    }
}

request.send();