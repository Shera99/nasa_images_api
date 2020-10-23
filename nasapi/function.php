<?php 

	class NasaApi{

		private $date;
		private $file = 'json.txt';
		private $jsonData;

		public function __construct() {
			$this->date = date('Y-n-j');
			$this->jsonData = $this->readFile();

			if (!empty($this->jsonData['firstOpen'])) {
				if ($this->jsonData['date'] !== $this->date) {
					$this->nextOpen();
				}
			} else {
				$this->jsonData['firstOpen'] = "open";
				$this->firstOpen();
			}
			
			$this->jsonData['date'] = $this->date;
			$this->writeFile();
		}

		private function readFile() {
			$data = json_decode(file_get_contents($this->file), TRUE);
			return $data;
		}

		private function writeFile() {
			file_put_contents($this->file, json_encode($this->jsonData));
		}

		private function query($date) {
			$url = 'https://api.nasa.gov/mars-photos/api/v1/rovers/curiosity/photos?earth_date='.$date.'&api_key=8qJZd8AwTeDG7XycZwFbXQhHTK6VJBvuFpbwlqpt';
			$request = json_decode(file_get_contents($url));
			return $request;
		}

		private function firstOpen() {
			for ($i = 0; $i < 5; $i++) {
				$date1 = date('Y-n-j', strtotime("-".$i." day"));
				$result = $this->query($date1);

				if (count($result->photos) > 0) $this->copyImages($result->photos, $date1);
			}
		}

		private function nextOpen() {
			$result = $this->query($this->date);

			if (count($result->photos) > 0) $this->copyImages($result->photos, $this->date);
		}

		private function copyImages($data, $date) {
				$img = 'nasa_images/'.$data[0]->camera->name.'_'.$data[0]->camera->id.'_'.$data[0]->id.'_'.$date.'.jpg';
				copy($data[0]->img_src,$img);
		}
	}

?>
