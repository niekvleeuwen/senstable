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

            const id = document.createElement("td");
            id.textContent = sensor.id;

            const name = document.createElement("td");
            name.textContent = sensor.name;

            const description = document.createElement("td");
            description.textContent = sensor.short_description;

            const serialNumber = document.createElement("td");
            serialNumber.textContent = sensor.serial_number;

            const dateAdded = document.createElement("td");
            dateAdded.textContent = sensor.date_added;

            const deleteSensor = document.createElement("td");
            var btn = document.createElement('input');
            btn.type = "button";
            btn.className = "btn btn-delete mb-1 h-25";
            btn.value = "Delete";
            btn.name = `btn-delete-${sensor.id}`
            deleteSensor.appendChild(btn);

            entry.appendChild(id)
            entry.appendChild(name);
            entry.appendChild(description);
            entry.appendChild(serialNumber);
            entry.appendChild(dateAdded);
            entry.appendChild(deleteSensor);
            tbody.appendChild(entry);
        });
    }
}

request.send();


document.getElementById("add-sensor").addEventListener("click", () => {
    openForm();
});

function openForm() {
    document.getElementById("popup-form").style.display = "block";
}
function closeForm() {
    document.getElementById("popup-form").style.display = "none";
}

function addSensor(){
    var name = document.getElementById("sensName").value;
    var serialNumber = document.getElementById("sensSerialNumber").value;
    var file = document.getElementById("fileToUpload").value;
    var shortDescription = document.getElementById("sensShortDescription").value;
    var wiki = document.getElementById("sensWiki").value;
    var code = document.getElementById("sensCode").value;

    // returns only the filename
    if (file) {
        var startIndex = (file.indexOf('\\') >= 0 ? file.lastIndexOf('\\') : file.lastIndexOf('/'));
        var filename = file.substring(startIndex);
        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
            filename = filename.substring(1);
        }
        file = filename;
    }

    var data = {
        "name": name,
        "short_description": shortDescription,
        "serial_number": serialNumber,
        "diagram": `img/sensors/${file}`,
        "wiki": wiki,
        "code": code
    };
    console.log(data);
    request.open("POST", "https://niekvanleeuwen.nl/senstable/api/sensors/add/", true);
    request.setRequestHeader('Content-type', 'application/json');
    request.send(JSON.stringify(data));
}

