<?php 
//$date = Date('Y-n-j');
$date = '2020-10-4';
$url = 'https://api.nasa.gov/mars-photos/api/v1/rovers/curiosity/photos?earth_date='.$date.'&api_key=8qJZd8AwTeDG7XycZwFbXQhHTK6VJBvuFpbwlqpt';

$request = json_decode(file_get_contents($url));

if (!empty($_COOKIE['IP']) && $_COOKIE['IP'] == $_SERVER["REMOTE_ADDR"]) {
	if ($_COOKIE['date'] != $date) {
		for($i = 0; $i < 5; $i++) {
			$img = 'nasa_images/'.$request->photos[$i]->camera->name.'_'.$request->photos[$i]->camera->id.'_'.$request->photos[$i]->id.'.jpg';
			copy($request->photos[$i]->img_src,$img);
		}
		createCookieDate($date);
	}
} else {
	$cookie_name = 'IP';
	$cookie_value = $_SERVER["REMOTE_ADDR"];
	setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
	createCookieDate($date);
	for($i = 0; $i < 10; $i++) {
			$img = 'nasa_images/'.$request->photos[$i]->camera->name.'_'.$request->photos[$i]->camera->id.'_'.$request->photos[$i]->id.'.jpg';
			copy($request->photos[$i]->img_src,$img);
		}
	}

	function createCookieDate($date) {
		$cookie_name = 'date';
		$cookie_value = $date;
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" type="text/css" href="slick-1.8.1/slick/slick.css"/>
	<link rel="stylesheet" type="text/css" href="slick-1.8.1/slick/slick-theme.css"/>
	<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
	<div class="card-slider">

		<?php 
			$handle = opendir(dirname(realpath(__FILE__)).'/nasa_images/');
			while($file = readdir($handle)){
			  if($file !== '.' && $file !== '..'){
					echo '
						<div class="card">
					    <img src="nasa_images/'.$file.'">
					  </div>
					';
				}
			}
		?>
	  
	</div>
</body>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="slick-1.8.1/slick/slick.min.js"></script>
<script>
	$(document).ready(function() {
	  $('.card-slider').slick({
	    dots: false,
	    arrows: true,
	    slidesToShow: 4,
	    infinite: true,
	    slidesToScroll: 4,
	    responsive: [
	      {
	        breakpoint: 800,
	        settings: {
	          slidesToShow: 3
	        }
	      },
	      {
	        breakpoint: 600,
	        settings: {
	          slidesToShow: 2
	        }
	      }
	    ]
	  });
	});
</script>
</html>