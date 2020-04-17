const sens = document.getElementById("sens");

// get the paramater given in the url
let parts = window.location.search.substr(1).split("&");
let get = {};
for (let i = 0; i < parts.length; i++) {
    let temp = parts[i].split("=");
    get[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
}
const id = get['id'];

const title = document.getElementById("sens-title");
const shortDescription = document.getElementById("sens-short-description");
const description = document.getElementById("sens-description");
const code = document.getElementById("sens-code");

//create a request variable 
var request = new XMLHttpRequest();
var url = "https://niekvanleeuwen.nl/senstable/api/sensors/get/";
var data = JSON.stringify({"id": id});

request.open("POST", url, true);
request.setRequestHeader("Content-Type", "application/json");

request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
        //parse the object
        var data = JSON.parse(request.response);
        console.log(data);
        //handle errors
        if (request.status >= 200 && request.status < 400) {
            data.forEach(sensor => {
                title.textContent = sensor.name;
                shortDescription.textContent = sensor.short_description;
                description.textContent = sensor.wiki;
                code.textContent = sensor.code;
            });
        }
    }
};

if(id != null){
    request.send(data);
}else{
    title.textContent = "Deze sensor bestaat niet!";
}
