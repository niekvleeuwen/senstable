let chart;

var getJSON = function(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';
    xhr.onload = function() {
      var status = xhr.status;
      if (status === 200) {
        callback(null, xhr.response);
      } else {
        callback(status, xhr.response);
      }
    };
    xhr.send();
};

getJSON('api/live/update/sensData.json',function(err, data) {
    if (err !== null) {
        alert('Something went wrong: ' + err);
    }
    var labels = data.amounts.map(function(e) {
        return e[0];
    });
    var data = data.amounts.map(function(e) {
        return e[1];
    });

    var ctx = document.getElementById('myChart').getContext('2d');
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label:"Sensor waarde",
                backgroundColor: 'rgb(129, 198, 2228)',
                borderColor: "#48BF84",
                fill: false,
                data: data
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
});

function updateGraph() {
    getJSON('api/live/update/sensData.json',function(err, data) {
        if (err !== null) {
            alert('Something went wrong: ' + err);
        }
        var labels = data.amounts.map(function(e) {
            return e[0];
        });
        var data = data.amounts.map(function(e) {
            return e[1];
        });
        chart.data.labels = labels;
        chart.data.datasets.forEach((dataset) => {
            dataset.data = data;
        });
        chart.update();
    });
}

window.setInterval(function(){
    updateGraph();
    console.log("Update graph");
}, 100);