<?php 
	if (!empty($_COOKIE['IP']) && $_COOKIE['IP'] == $_SERVER["REMOTE_ADDR"]) {
		if ($_COOKIE['date'] != $date) {
			nextOpen();
		}
	} else {
		firstOpen();
	}

	function query($date) {
		$url = 'https://api.nasa.gov/mars-photos/api/v1/rovers/curiosity/photos?earth_date='.$date.'&api_key=8qJZd8AwTeDG7XycZwFbXQhHTK6VJBvuFpbwlqpt';
		$request = json_decode(file_get_contents($url));
		return $request;
	}

	function firstOpen() {
		$date = date('Y-n-j');
		$cookie_name = 'IP';
		$cookie_value = $_SERVER["REMOTE_ADDR"];

		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
		createCookieDate($date);

		for ($i = 0; $i < 5; $i++) {
			$date1 = date('Y-n-j', strtotime("-".$i." day"));
			$result = query($date1);
			if (count($result->photos) > 0) {
				copyImages($result->photos, 10, $date1);
				echo 'firstOpen';
			}
		}
	}

	function nextOpen() {
		$date = date('Y-n-j');
		$result = query($date);
		if (count($result->photos) > 0) {
			copyImages($result->photos, 5, $date);
			echo 'nextOpen';
		}
		createCookieDate($date);
	}

	function createCookieDate($date) {
		$cookie_name = 'date';
		$cookie_value = $date;
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
	}

	function copyImages($data, $n, $date) {
		for($i = 0; $i < $n; $i++) {
			$img = 'nasa_images/'.$data[$i]->camera->name.'_'.$data[$i]->camera->id.'_'.$data[$i]->id.'_'.$date.'.jpg';
			copy($data[$i]->img_src,$img);
		}
	}

?>
