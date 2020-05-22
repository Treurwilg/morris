<?php
namespace PhpClasses\Image;
class Thumbnail2 {
	protected $original;
	protected $originalwidth;
	protected $originalheight;
	protected $basename;
	protected $maxSize = 120;
	protected $imageType;
	protected $suffix = '_duim';
	protected $messages = [];
	
	public function __construct($image, $destination, $maxSize = 120, $suffix = '_duim') {
		if (is_file($image) && is_readable($image)) {
			$details = getimagesize($image);		
		} else {
			throw new \Exception("Systeem kan de $image niet openen.");		
		}
		if (!is_array($details)) {
			throw new \Exception("$image blijkt geen foto te zijn.");		
		} else {
			if ($details[0] == 0) {
				throw new \Exception("Kan de grootte van $image niet bepalen.");
			}		
			// check the MIME type
			if (!$this->checkType($details['mime'])) {
				throw new \Exception('Kan dit type bestand niet verwerken.');			
			}
			$this->original = $image;
			$this->originalwidth = $details[0];
			$this->originalheight = $details[1];
			$this->basename = pathinfo($image, PATHINFO_FILENAME);
			$this->setDestination($destination);
			$this->setMaxSize($maxSize);
			$this->setSuffix($suffix);
		}
	}
	
	public function test() {
		$ratio = $this->calculateRatio($this->originalwidth, $this->originalheight, $this->maxSize);
		$thumbwidth = round($this->originalwidth * $ratio);
		$thumbheight = round($this->originalheight * $ratio); 
		$details = <<<END
		<pre>
		File: $this->original
		Original width: $this->originalwidth
		Original height: $this->originalheight
		Base name: $this->basename
		Image type: $this->imageType
		Destination: $this->destination
		Max size: $this->maxSize
		Suffix: $this->suffix
		Thumb width: $thumbwidth
		Thumb height: $thumbheight
		</pre>
		END;
		echo $details;
		echo "__DIR__";
		if ($this->messages) {
				print_r($this->messages);			
		}
	}
	
	public function create() {
		$ratio = $this->calculateRatio($this->originalwidth, $this->originalheight, $this->maxSize);
		$thumbwidth = round($this->originalwidth * $ratio);
		$thumbheight = round($this->originalheight * $ratio); 	
		$resource = $this->createImageResource(); // resource for the original
		$thumb = imagecreatetruecolor($thumbwidth, $thumbheight); // resource for the thumbnail
		imagecopyresampled($thumb, $resource, 0,0,0,0, $thumbwidth, $thumbheight, $this->originalwidth, $this->originalheight);
		$newname = $this->basename . $this->suffix;
		switch ($this->imageType) {
			case 'jpeg':
				$newname .= '.jpg';
				$success = imagejpeg($thumb, $this->destination . $newname);
				break;	
			case 'png':
				$newname .= '.png';
				$success = imagejpeg($thumb, $this->destination . $newname);
				break;
			case 'gif':
				$newname .= '.gif';
				$success = imagejpeg($thumb, $this->destination . $newname);
				break;	
			case 'webp':
				$newname .= '.webp';
				$success = imagejpeg($thumb, $this->destination . $newname);
				break;
		}
		if ($success) {
			$this->messages[] = "$newname created successfully.";
		}	else {
			$this->messages[] = "Couldn't create a thumbnail for " . basename($this->original);		
		}
		imagedestroy($resource);
		imagedestroy($thumb);
		return $newname;
	}
	
	public function getMessages() {
		return $this->messages;	
	}
	
	protected function checkType($mime) {
		$mimetypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
		if (in_array($mime, $mimetypes)) {
			// extract the characters after '/'
			$this->imageType = substr($mime, strpos($mime, '/')+1);
			return true;
		}	
		return false;
	}
	
	protected function createImageResource() {
		switch($this->imageType) {	
			case 'jpeg':
				return imagecreatefromjpeg($this->original);
			case 'png':
				return imagecreatefrompng($this->original);
			case 'gif':
				return imagecreatefromgif($this->original);
			case 'webp': 
				return imagecreatefromwebp($this->original);
		}
	}
	
	protected function setDestination($destination) {
		if (is_dir($destination) && is_writable($destination)) {
			$this->destination = rtrim($destination, '/\\') . DIRECTORY_SEPARATOR;		
		}	else {
				throw new \Exception("Kan niet schrijven naar $destination.");		
		}
	}
	
	protected function setMaxSize($size) {
		if (is_numeric($size) && $size > 0) {
			$this->maxSize = $size;		
		}	else {
				throw new \Exception('The value for setMaxSize() must be a positive number.');	
		}
	}
	
	protected function setSuffix($suffix) {
		if (preg_match('/^\w+$/', $suffix)) {
			if (strpos($suffix, '_') !== 0 ) {
				$this->suffix = '_' . $suffix;			
			}	else {
				$this->suffix = $suffix;			
			}
		}	
	}
	
	protected function calculateRatio($width, $height, $maxSize) {
		if ($width <= $maxSize && $height <= $maxSize) {
			return 1;		
		}	elseif ($width > $height) {
			return $maxSize/$width;		
		}	else {
				return $maxSize/$height;		
		}
	}
}
?>