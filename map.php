<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/common.php';

$url = 'http://' . $_SERVER['HTTP_HOST'].'/'.SITE_NAME.'/data.php';
?>
<!-- thanks to : http://bl.ocks.org/mbostock/899711 -->
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript" src="http://mbostock.github.com/d3/d3.js?1.29.1"></script>
    <style type="text/css">

html, body, #map {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;0
}

.stations, .stations svg {
  position: absolute;
}

.stations svg {
  width: 60px;
  height: 20px;
  padding-right: 100px;
  font: 10px sans-serif;
}

.stations circle {
  fill: green;
  stroke: black;
  stroke-width: 1px;
}

    </style>
  </head>
  <body>
    <div id="map"></div>
    <script type="text/javascript">

// Create the Google Map…
var map = new google.maps.Map(d3.select("#map").node(), {
  zoom: 6,
  center: new google.maps.LatLng(54.1578899,-3.9252587),
  mapTypeId: google.maps.MapTypeId.ROADMAP
});

// Load the station data. When the data comes back, create an overlay.
// accept data of format {"Glasgow":["Glasgow",-120.12,36.98,1],"London":...}
d3.json("http://localhost/server.php", function(data) {
  var overlay = new google.maps.OverlayView();

  // Add the container when the overlay is added to the map.
  overlay.onAdd = function() {
    var layer = d3.select(this.getPanes().overlayLayer).append("div")
        .attr("class", "stations");

    // Draw each marker as a separate SVG element.
    // We could use a single SVG, but what size would it have?
    overlay.draw = function() {
      var projection = this.getProjection(),
          padding = 10;

      var marker = layer.selectAll("svg")
          .data(d3.entries(data))
          .each(transform) // update existing markers
        .enter().append("svg:svg")
          .each(transform)
          .attr("class", "marker");

      // Add a circle.
      marker.append("svg:circle")
          .attr("r", 6)
          .attr("cx", padding)
          .attr("cy", padding);

      // Add a label.
      marker.append("svg:text")
          .attr("x", padding + 7)
          .attr("y", padding)
          .attr("dy", ".31em")
          .text(function(d) { return d.value[3]; });

      function transform(d) {
        d = new google.maps.LatLng(d.value[1], d.value[2]);
        d = projection.fromLatLngToDivPixel(d);
        return d3.select(this)
            .style("left", (d.x - padding) + "px")
            .style("top", (d.y - padding) + "px");
      }
    };
  };

  // Bind our overlay to the map…
  overlay.setMap(map);
});

    </script>
  </body>
</html>
