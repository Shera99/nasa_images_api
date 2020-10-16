<?php 

require_once 'function.php';

$class = new NasaApi();

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
