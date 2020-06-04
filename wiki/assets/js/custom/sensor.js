const title = document.getElementById("sens-title");
const title2 = document.getElementById("sens-title-2");
const shortDescription = document.getElementById("sens-short-description");
const description = document.getElementById("sens-description");
const code = document.getElementById("sens-code");
const img = document.getElementById("sens-image");
const sens = document.getElementById("sens");
const pauseButton = document.getElementById("pauseButton");
const url = "https://niekvanleeuwen.nl/senstable/api/sensors/get/";
let request = new XMLHttpRequest();
let chart;
let dataArr = [];
let labels = [];
let sock = new WebSocket("ws://185.224.91.138:5050"); //replace this address with the one the node.js server prints out. 
let graphPaused = false;

// get the paramater given in the url
let parts = window.location.search.substr(1).split("&");
let get = {};
for (let i = 0; i < parts.length; i++) {
    let temp = parts[i].split("=");
    get[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
}
const id = get['id'];
const data = JSON.stringify({ "id": id });
const jsonData = {
    "client": {
        "id": id
    }
}

// this function is called when the socket is connected
sock.onopen = function (event) {
    buildChart();
    // send the id from the sensor to the socket
    sock.send(JSON.stringify(jsonData));
}

// // this function is called on every messaged received from the server
sock.onmessage = function (event) {
    // update the graph with the new data
    if (graphPaused == false) {
        update(JSON.parse(event.data));
    }
}

// this function updates the graph
function update(json) {
    if (dataArr.length > 100) {
        dataArr.shift()
        labels.shift()
    }
    labels.push(json["sensor"]["timeStamp"]);
    dataArr.push(json["sensor"]["value"]);
    chart.update();
}

function pauseGraph() {
    // check if the graph is paused
    if (graphPaused) {
        pauseButton.textContent = "Pause";
        graphPaused = false;
        // set overlay to false
        chart.options.tooltips.enabled = false;
    } else {
        pauseButton.textContent = "Resume";
        graphPaused = true;
        // set overlay to true
        chart.options.tooltips.enabled = true;
    }
}

function buildChart() {
    var ctx = document.getElementById('myChart').getContext('2d');
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: "Sensor waarde",
                backgroundColor: 'rgb(129, 198, 2228)',
                borderColor: "#48BF84",
                fill: false,
                data: dataArr
            }]
        },
        options: {
            responsive: 'true',
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            tooltips: {
                enabled: false
            },
        }
    });
}

request.open("POST", url, true);
request.setRequestHeader("Content-Type", "application/json");

request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
        //parse the object
        var data = JSON.parse(request.response);
        //handle errors
        if (request.status >= 200 && request.status < 400) {
            data.forEach(sensor => {
                title.textContent = title2.textContent = sensor.name;
                shortDescription.textContent = sensor.short_description;
                description.textContent = sensor.wiki;
                code.innerHTML = "\n" + sensor.code;
                img.setAttribute("src", "admin/" + sensor.diagram);
                // Rerun Prism syntax highlighting on the current page
                Prism.highlightAll();
            });
        }
    }
};


if (id != null) {
    request.send(data);
} else {
    title.textContent = "Deze sensor bestaat niet!";
}