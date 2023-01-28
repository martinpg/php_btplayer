<html>
<head>
	<style>
	@font-face {
	  font-family: Antonio_Light;
	  src: url(Antonio-Light.ttf);
	}
	
	::-webkit-scrollbar {
		background-color: transparent; 
		width: 20px;
	}
	
	::-webkit-scrollbar-thumb {
		background-image: linear-gradient(to right, rgba(110, 160, 80, 0.85), rgba(80, 110, 80, 1.0));
		border-radius: 70px 10px 10px 70px;
	}
	
	::-webkit-scrollbar-thumb:hover {
		background: rgba(100, 150, 80, 0.9); 
	}
	
	html {		
		cursor: none;
		pointer-events: none;
	}

	body {
		background-color: black;
		color: white;
		overflow-x: hidden;
		overflow-y: overlay;
		position: absolute;
		min-height: 100%;
		width: 100%;
		margin: 0;
		padding: 0;
		text-overflow: ellipsis;
	}
	
	td {		
		font-family: Antonio_Light;
		font-size: 9.28vh;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
	
	.folder {
		color: #6567a5;
		text-shadow: 2px 2px 0px rgba(0, 150, 150, 0.4);
	}
	
	.track {
		color: #658765;
		text-shadow: 2px 2px 0px rgba(0, 150, 0, 0.4);
	}
	</style>	
</head>
<body>
	<table style="width: 100%">
<?php
	$dir = "../www/pistas/";

	function listAllFiles($dir) {
	  $array = array_slice(scandir($dir), 2);
	  
	  foreach ($array as &$item) {
		$item = $dir . $item;
	  }
	  unset($item);
	  foreach ($array as $item) {
		if (is_dir($item)) {
		 $array = array_merge($array, listAllFiles($item . DIRECTORY_SEPARATOR));
		}
	  }
	  return $array;
	}

	$a = listAllFiles($dir);
	
	print "\t\t<tr id=\"1\" class=\"track\">\n\t\t\t<td>&nbsp;</td>\n\t\t</tr>\n";
	print "\t\t<tr id=\"2\" class=\"track\">\n\t\t\t<td>&nbsp;</td>\n\t\t</tr>\n";
	print "\t\t<tr id=\"3\" class=\"track\">\n\t\t\t<td>&nbsp;</td>\n\t\t</tr>\n";

	$index = 3;
	
	foreach ($a as &$item) {
		if (!is_dir($item)) {
			$item = str_replace('\\', '/', $item);
			$lower = strtolower($item);
			if (str_ends_with($lower,".wav") || str_ends_with($lower,".mp3") ) {
				$index++;
				$nameBegins = strrpos($item, "/") + 1;
				$nameEnds = strrpos($item, ".");
				print "\t\t<tr id=\"" . $index . "\" class=\"track\" onclick=\"playSelected('" . substr("{$item}", 6) . "')\">\n\t\t\t<td>" . substr("{$item}", $nameBegins, $nameEnds - $nameBegins) . "</td>\n\t\t</tr>\n";
			}
		}
	}

	$index++;
	print "\t\t<tr id=\"" . $index . "\" class=\"track\">\n\t\t\t<td>&nbsp;</td>\n\t\t</tr>\n";
	$index++;
	print "\t\t<tr id=\"" . $index . "\" class=\"track\">\n\t\t\t<td>&nbsp;</td>\n\t\t</tr>\n";
	$index++;
	print "\t\t<tr id=\"" . $index . "\" class=\"track\">\n\t\t\t<td>&nbsp;</td>\n\t\t</tr>\n";
	$index++;
	print "\t\t<tr id=\"" . $index . "\" class=\"track\">\n\t\t\t<td>&nbsp;<audio id=\"playa\"></audio></td>\n\t\t</tr>\n";
	
?>
	</table>
	<div id="progressBar1" style="z-index: 3; background-image: linear-gradient(rgba(100, 200, 120, 0.5), transparent); position: fixed; left: 0; top: 0; height: 40vh; width: 0px;"></div>
	<div id="progressBar2" style="z-index: 3; background-image: linear-gradient(transparent, rgba(100, 200, 120, 0.5)); position: fixed; left: 0; bottom: 0; height: 40vh; width: 0px;"></div>
	<script>
		var listPosition = 1;
		var prevListPosition = 2;
		var listCount = <?php echo $index ;?>;
		var playingIndex = 0;
		var prevPlayingIndex = 0;
		var playingTrack = 0;
		
		document.onkeydown = checkKey;
		
		document.getElementById('playa').ontimeupdate = updateProgressBar;

		document.getElementById(listPosition + 3).style.backgroundColor = "#324323";

		function playSelected(a) {
			document.getElementById('playa').src=a;
			document.getElementById('playa').play();
		}
		
		function checkKey(e) {
						
			e = e || window.event;

			if (e.keyCode == '38') { // Up
				e.preventDefault();
				if (listPosition > 1) {
					prevListPosition = listPosition;
					listPosition--;
					window.location.href = 'http://localhost#' + listPosition;
					document.getElementById(prevListPosition + 3).style.backgroundColor = "0";
					document.getElementById(listPosition + 3).style.backgroundColor = "#324323";
				}
			}
			else if (e.keyCode == '40') { // Down
				e.preventDefault();
				if (listPosition < (listCount - 7)) {
					prevListPosition = listPosition;
					listPosition++;
					window.location.href = 'http://localhost#' + listPosition;
					document.getElementById(prevListPosition + 3).style.backgroundColor = "0";
					document.getElementById(listPosition + 3).style.backgroundColor = "#324323";
				}
			}
			else if (e.keyCode == '33') { // Page Up
				e.preventDefault();
				if ((listPosition - 4) > 1) {
					prevListPosition = listPosition;
					listPosition -= 5;
					window.location.href = 'http://localhost#' + listPosition;
					document.getElementById(prevListPosition + 3).style.backgroundColor = "0";
					document.getElementById(listPosition + 3).style.backgroundColor = "#324323";
				} else {
					prevListPosition = listPosition;
					listPosition = 1;
					window.location.href = 'http://localhost#' + listPosition;
					document.getElementById(prevListPosition + 3).style.backgroundColor = "0";
					document.getElementById(listPosition + 3).style.backgroundColor = "#324323";
				}
			}
			else if (e.keyCode == '34') { // Page Down
				e.preventDefault();
				if ((listPosition + 4) < (listCount - 7)) {
					prevListPosition = listPosition;
					listPosition += 5;
					window.location.href = 'http://localhost#' + listPosition;
					document.getElementById(prevListPosition + 3).style.backgroundColor = "0";
					document.getElementById(listPosition + 3).style.backgroundColor = "#324323";
				} else {
					prevListPosition = listPosition;
					listPosition = listCount - 7;
					window.location.href = 'http://localhost#' + listPosition;
					document.getElementById(prevListPosition + 3).style.backgroundColor = "0";
					document.getElementById(listPosition + 3).style.backgroundColor = "#324323";
				}
			}
			else if (e.keyCode == '13') { // Enter
				e.preventDefault();
				prevListPosition = listPosition;
				document.getElementById(listPosition + 3).click();
				playingTrack = document.getElementById(listPosition + 3);
				playingTrack.style.color = "#46b846";
			}
			else if (e.keyCode == '27') { // Escape
				e.preventDefault();
				document.getElementById('playa').src="";
				var progressDiv1 = document.getElementById('progressBar1');
				progressDiv1.style.width = 0;
				var progressDiv2 = document.getElementById('progressBar2');
				progressDiv2.style.width = 0;
				playingTrack.style.color = "#658765";
			}
			else if (e.keyCode == '36') { // Home
				e.preventDefault();
				prevListPosition = listPosition;
				listPosition = 1;
				window.location.href = 'http://localhost#' + listPosition;				
				document.getElementById(prevListPosition + 3).style.backgroundColor = "0";
				document.getElementById(listPosition + 3).style.backgroundColor = "#324323";
			}
			else if (e.keyCode == '35') { // End
				e.preventDefault();
				prevListPosition = listPosition;
				listPosition = listCount - 7;
				window.location.href = 'http://localhost#' + listPosition;
				document.getElementById(prevListPosition + 3).style.backgroundColor = "0";
				document.getElementById(listPosition + 3).style.backgroundColor = "#324323";
			}
		}
		
		function updateProgressBar() {
			var barwidth;
			var player = document.getElementById('playa');
			barwidth = Math.trunc((player.currentTime / player.duration) * window.innerWidth);
			var progressDiv1 = document.getElementById('progressBar1');
			progressDiv1.style.width = barwidth;
			var progressDiv2 = document.getElementById('progressBar2');
			progressDiv2.style.width = barwidth;
		}
	</script>
</body>
</html>