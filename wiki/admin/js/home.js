function loadSensorTable(){
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
                var i_element = document.createElement('I');
                i_element.className = "fas fa-trash-alt delete-sensor";
                i_element.name = `delete-${sensor.id}`;
                i_element.onclick = function () {
                    removeSensor(i_element.name.replace("delete-", ""));
                };
                deleteSensor.appendChild(i_element);

                const editSensor = document.createElement("td");
                i_element = document.createElement('I');
                i_element.className = "fas fa-edit edit-sensor";
                i_element.name = `${sensor.id}`;
                i_element.onclick = function () {
                    getTableData(i_element.name);
                };
                editSensor.appendChild(i_element);

                entry.appendChild(id)
                entry.appendChild(name);
                entry.appendChild(description);
                entry.appendChild(serialNumber);
                entry.appendChild(dateAdded);
                entry.appendChild(deleteSensor);
                entry.appendChild(editSensor);
                tbody.appendChild(entry);
            });
        }
    }

    request.send();
}

function clearSensorTable(){
    var myTable = document.getElementById("sensorTable");
    var rowCount = myTable.rows.length;
    for (var x=rowCount-1; x>0; x--) {
        myTable.deleteRow(x);
    }
}

document.getElementById("add-sensor").addEventListener("click", () => {
    openForm();
});

function openForm() {
    document.getElementById("popup-form").style.display = "block";
}
function closeForm() {
    document.getElementById("popup-form").style.display = "none";
}

function removeSensor(sensorId){
    if(confirm(`Weet u zeker dat u deze sensor, #${sensorId}, wilt verwijderen?`)){
        var data = {
            "id": sensorId
        };
    
        var request = new XMLHttpRequest();
        request.open("POST", "https://niekvanleeuwen.nl/senstable/api/sensors/delete/", true);
        request.setRequestHeader('Content-type', 'application/json');
        request.send(JSON.stringify(data));
    
        request.onload = function() {
            var json = JSON.parse(request.responseText);
            showResponse(json);
        };
    }
}

function addSensor(){
    var request = new XMLHttpRequest();
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

    request.open("POST", "https://niekvanleeuwen.nl/senstable/api/sensors/add/", true);
    request.setRequestHeader('Content-type', 'application/json');
    request.send(JSON.stringify(data));

    request.onload = function() {
        var json = JSON.parse(request.responseText);
        showResponse(json);
    };
}

function showResponse(json){
    var lbl = document.getElementById('lbl-status');

    if(Object.keys(json).includes("result")){
        lbl.innerHTML = json["result"];
        lbl.style.color = "green";
    }   
    else{
        lbl.innerHTML = json["error"];
        lbl.style.color = "red";
    }

    clearSensorTable();
    loadSensorTable();
}

function getTableData(id){
    var request = new XMLHttpRequest();
    //open a new connection
    request.open("POST",  "https://niekvanleeuwen.nl/senstable/api/sensors/get/", true);
    request.setRequestHeader('Content-type', 'application/json');

    var data = {
        "id": id,
    };
    request.send(JSON.stringify(data));

    request.onload = function() {
        //parse the object
        var data = JSON.parse(this.responseText);
        console.log(data);        
        openEditForm();
        
        var hd = document.getElementById("edit-form-header");
        hd.innerHTML = `${hd.innerHTML}${data[0]['id']}`;
        console.log(hd.innerHTML);

        Object.keys(data[0]).forEach(function(key){
            if(document.getElementById(`sens-${key}-edit`) !== null)
                document.getElementById(`sens-${key}-edit`).value = data[0][key];
        }); 
    }
}

function saveSensor(){
    var request = new XMLHttpRequest();
    request.open("PUT",  "https://niekvanleeuwen.nl/senstable/api/sensors/update/", true);
    request.setRequestHeader('Content-type', 'application/json');

    var data = {
        "id": document.getElementById('edit-form-header').innerHTML.replace("Sensor bewerken: #", ""),
        "name": document.getElementById(`sens-name-edit`).value,
        "short_description": document.getElementById(`sens-short_description-edit`).value,
        "serial_number": document.getElementById(`sens-serial_number-edit`).value,
        "wiki": document.getElementById(`sens-wiki-edit`).value,
        "code": document.getElementById(`sens-code-edit`).value
    };
    console.log(data);
    request.send(JSON.stringify(data));

    request.onload = function() {
        var json = JSON.parse(request.responseText);
        showResponse(json);
        closeEditForm();

        var hd = document.getElementById("edit-form-header");
        hd.innerHTML = `Sensor bewerken: #`;
    }

}

function openEditForm() {
    document.getElementById("edit-popup-form").style.display = "block";
}

function closeEditForm() {
    document.getElementById("edit-popup-form").style.display = "none";
}