
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPRGGo7A17XdEbilkquouWwuZ7dNY9diM&callback=initialize"
    async defer></script>
<style type="text/css">
	#map{
		width:230px;
		height:200px;
		position:absolute;
		cursor:pointer;
		z-index: 1000003;
	}

	.map-content{
		min-width:230px;
		height:60px;
	}
	.map-canvas {
	    background: #ffffff none repeat scroll 0 0;
	    height: 480px;
	    width: 996px;
	}

	.map_popup {
		box-shadow: 0 0 5px;
		background: #ffffff none repeat scroll 0 0;
	    height: 480px;
	    width: 996px;
	    position: fixed;
	    top: 200px;
	    margin-left:-6px;
	    padding:4px;
	    z-index: 1000004;
	}
	.map-canvas-thumb {
	    background: #ffffff none repeat scroll 0 0;
	    height: 200px;
	    width: 226px;
	}
	.map_popup_thumb {
		background: #ffffff none repeat scroll 0 0;
	    height: 200px;
	    width: 226px;
	    position: absolute;
	    z-index: 1000001;
	}
    .gray_bg{
        position: fixed;
        top: 0;
        bottom: 0;
        display:none;
        z-index: 1000003;
        background-color: #000;
        opacity: 0.7;

    }

</style>
<div id="map_popup" class="map_popup"  style="display:none">
	<div id="map-canvas" class="map-canvas"></div>
	<div class="close" style=" background:url('images/close_btn.png') no-repeat center; display:none; position:absolute; left:987px; top:-13px; width:31px; height:31px;"></div>
</div>

<div class="" style="background:#ffffff; width:235px;">
	<div id="map_container" style="display:block; height:210px; cursor:pointer; position:relative; width:235px; padding-top:5px;">
	    <div id="map" class="map">

		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
        $( "body" ).prepend('<div class="gray_bg"></div>');
        $('.gray_bg').hide();
        $('#map_popup').show();
        $('#map_popup').removeClass("map_popup");
        $('#map_popup').addClass( "map_popup_thumb" );
        var position = $('#map_container').position();
        $('#map_popup').css('top',position.top);
        $('#map-canvas').removeClass("map-canvas" );
        $('#map-canvas').addClass("map-canvas-thumb");


	$("#map").click(function(){
		$('.gray_bg').show();

		$('#map_popup').removeClass( "map_popup_thumb");
		$('#map_popup').addClass( "map_popup");

		$('#map-canvas').removeClass( "map-canvas-thumb");
		$('#map-canvas').addClass( "map-canvas");
		$('#map_popup').show();
		initialize();
        $('#map_popup').css("top",200);
		$(".close").show();
	});
	$('.gray_bg').css('width',$(window).width()+'px');
	$('.gray_bg').css('height',$(window).height()+'px');
	$(".gray_bg").click(function(){
		$(this).hide();
        $(".close").hide();
		$('#map_popup').removeClass("map_popup");
		$('#map_popup').addClass( "map_popup_thumb" );
		var position = $('#map_container').position();
		$('#map_popup').css('top',position.top);
		$('#map-canvas').removeClass("map-canvas" );
		$('#map-canvas').addClass("map-canvas-thumb");
		initialize();

	});

	$(".close").click(
		function(){
			$(".gray_bg").hide();
			$('#map_popup').removeClass("map_popup");
			$('#map_popup').addClass( "map_popup_thumb" );
			var position = $('#map_container').position();
			$('#map_popup').css('top',position.top);
			$('#map-canvas').removeClass("map-canvas" );
			$('#map-canvas').addClass("map-canvas-thumb");
			initialize();
			$(".close").hide();
		}
	);

	function zoomCurrent(lat,lng, pos)
	{
		var mapOptions = {
			zoom: 15,
			center: new google.maps.LatLng(lat, lng),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}

		var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
		setMarkers(map, beaches, pos);
	}

	function initialize() {
	  var mapOptions = {
		zoom: 15,
		center: new google.maps.LatLng(<?=$settings['hotel_coordinates']?>),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	  }


	  var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
	  setMarkers(map, beaches,'-1');

	}

	/**
	 * Data for the markers consisting of a name, a LatLng and a zIndex for
	 * the order in which these markers should display on top of each
	 * other.
	 */


	var beaches = [
	  ["<?=$settings['address']?>", <?=$settings['hotel_coordinates']?>, 0,''],
	];


	function setMarkers(map, locations, position) {
	  // Add markers to the map



	  // Marker sizes are expressed as a Size of X,Y
	  // where the origin of the image (0,0) is located
	  // in the top left of the image.

	  // Origins, anchor positions and coordinates of the marker
	  // increase in the X direction to the right and in
	  // the Y direction down.
	  var image = {
		url: 'images/pin.png',
		// This marker is 20 pixels wide by 32 pixels tall.
		size: new google.maps.Size(80, 63),
		// The origin for this image is 0,0.
		origin: new google.maps.Point(0,0),
		// The anchor for this image is the base of the flagpole at 0,32.
		anchor: new google.maps.Point(20, 40)
	  };
	  // Shapes define the clickable region of the icon.
	  // The type defines an HTML &lt;area&gt; element 'poly' which
	  // traces out a polygon as a series of X,Y points. The final
	  // coordinate closes the poly by connecting to the first
	  // coordinate.
	  var shape = {
		  coord: [1, 1, 1, 40, 40, 40, 40, 1],
		  type: 'poly'
	  };

	  var infowindow = new google.maps.InfoWindow();



	  for (var i = 0; i < locations.length; i++) {
		var beach = locations[i];
		var myLatLng = new google.maps.LatLng(beach[1], beach[2]);
		var content = '<div class="map-content" ><h3>'+beach[0]+'</h3></div>';
		var marker = new google.maps.Marker({
			position: myLatLng,
			map: map,
			icon: image,
			shape: shape,
			title: beach[0],
			zIndex: beach[3]
		});
	  google.maps.event.addListener(marker, 'click', (function(marker, content) {
		return function() {
			infowindow.setContent(content);
			infowindow.open(map, marker);
		}
	  })(marker, content));

	  if (position!=-1 && position==i) {
		infowindow.setContent(content);
		infowindow.open(map, marker);
	  }


	  google.maps.event.addListener(marker, 'dblclick', function() {
		map.setZoom(5);
		map.panTo(this.getPosition());
	  });

	  }

	}

	google.maps.event.addDomListener(window, 'load', initialize);
	})
</script>
