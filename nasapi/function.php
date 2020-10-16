<?php 

	class NasaApi{

		private $date;
		private $cookie_name;
		private $cookie_value;

		public function __construct() {
			$this->date = date('Y-n-j');
			
			if (!empty($_COOKIE['IP']) && $_COOKIE['IP'] == $_SERVER["REMOTE_ADDR"]) {
				if ($_COOKIE['date'] != $this->date) {
					$this->nextOpen();
				}
			} else {
				$this->cookie_name = 'IP';
				$this->cookie_value = $_SERVER["REMOTE_ADDR"];
				$this->firstOpen();
			}
		}

		private function query($date) {
			$url = 'https://api.nasa.gov/mars-photos/api/v1/rovers/curiosity/photos?earth_date='.$date.'&api_key=8qJZd8AwTeDG7XycZwFbXQhHTK6VJBvuFpbwlqpt';
			$request = json_decode(file_get_contents($url));
			return $request;
		}

		private function firstOpen() {
			setcookie($this->cookie_name, $this->cookie_value, time() + (86400 * 30), "/");
			$this->createCookieDate($this->date);

			for ($i = 0; $i < 5; $i++) {
				$date1 = date('Y-n-j', strtotime("-".$i." day"));
				$result = $this->query($date1);
				if (count($result->photos) > 0) {
					$this->copyImages($result->photos, 2, $date1);
					echo 'firstOpen';
				}
			}
		}

		private function nextOpen() {
			$result = $this->query($this->date);
			if (count($result->photos) > 0) {
				$this->copyImages($result->photos, 1, $this->date);
				echo 'nextOpen';
			}
			$this->createCookieDate($this->date);
		}

		private function createCookieDate($date) {
			$this->cookie_name = 'date';
			$this->cookie_value = $date;
			setcookie($this->cookie_name, $this->cookie_value, time() + (86400 * 30), "/");
		}

		private function copyImages($data, $n, $date) {
			for($i = 0; $i < $n; $i++) {
				$img = 'nasa_images/'.$data[$i]->camera->name.'_'.$data[$i]->camera->id.'_'.$data[$i]->id.'_'.$date.'.jpg';
				copy($data[$i]->img_src,$img);
			}
		}
	}

?>
