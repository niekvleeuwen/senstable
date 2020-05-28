let chart;
var dataArr = [];
var labels = [];
var sock = new WebSocket("ws://172.19.3.116:5000");  //replace this address with the one the node.js server prints out. 

sock.onmessage = function (event) {
    update(JSON.parse(event.data));
}
    
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
    }
});
    
function update(json){
    if(dataArr.length > 100){
       dataArr.shift()
       labels.shift()
    }
    labels.push(json["sensor"]["timeStamp"]);
    dataArr.push(json["sensor"]["value"]);
    chart.update();
}
