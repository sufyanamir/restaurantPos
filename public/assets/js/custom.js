
window.onload = function() {

  var chart = new CanvasJS.Chart("chartContainer", {
      animationEnabled: true,
      theme: "light2", // "light1", "light2", "dark1", "dark2"
      title: {
          text: "Scuba Diving Revenue"
      },
      axisY: {
          title: "Revenue"
      },
      data: [{
          type: "column",
          showInLegend: true,
          legendMarkerColor: "grey",
          legendText: "MMbbl = one million barrels",
          dataPoints: [{
                  y: 300878,
                  label: "company1"
              },
              {
                  y: 266455,
                  label: "Company2"
              },
              {
                  y: 169709,
                  label: "Company3"
              },
              {
                  y: 158400,
                  label: "Company4"
              },
              {
                  y: 142503,
                  label: "Company5"
              },
              {
                  y: 101500,
                  label: "Company6"
              },
              {
                  y: 97800,
                  label: "Company7"
              },
              {
                  y: 80000,
                  label: "Company8"
              }
          ]
      }]
  });
  chart.render();

}

//header open close
function openNav() {
    document.getElementById("mySidebar").style.width = "200px";
    document.getElementById("main-panel").style.marginLeft = "200px";
    document.getElementById("main-panel").style.borderRadius = "30px 0 0 30px";
    document.getElementById("closebtn").style.display = "block";
    document.getElementById("openbtn").style.display = "none";
    // document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
    document.getElementById("main-panel").style.marginLeft = "0";
    document.getElementById("main-panel").style.borderRadius = "0";
    document.getElementById("closebtn").style.display = "none";
    document.getElementById("openbtn").style.display = "block";
}
//header open close




// data Table
    new DataTable('#myTable');
// data Table