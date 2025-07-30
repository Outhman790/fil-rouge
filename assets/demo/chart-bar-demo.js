// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily =
  '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#292b2c";

// Bar Chart Example
var ctx = document.getElementById("myBarChart");

// Fetch data from total-payments-of-month.inc.php
fetch("includes/total-payments-of-month.inc.php")
  .then((response) => response.json())
  .then((data) => {
    console.log(data);
    console.log("inside fetch bar demo");
    var myLineChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "Juillet",
          "August",
          "September",
          "October",
          "November",
          "December",
        ],
        datasets: [
          {
            label: "Revenue",
            backgroundColor: "rgba(2,117,216,1)",
            borderColor: "rgba(2,117,216,1)",
            data: Object.values(data), // Use the data received from PHP
          },
        ],
      },
      options: {
        scales: {
          xAxes: [
            {
              time: {
                unit: "month",
              },
              gridLines: {
                display: false,
              },
              ticks: {
                maxTicksLimit: 12,
              },
            },
          ],
          yAxes: [
            {
              ticks: {
                min: 0,
                max: 2000,
                maxTicksLimit: 5,
              },
              gridLines: {
                display: true,
              },
            },
          ],
        },
        legend: {
          display: false,
        },
      },
    });
  })
  .catch((error) => {
    console.log("Error fetching data:", error);
  });
