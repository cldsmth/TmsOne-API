<?php 
class SimpleImage {   
	var $image; 
	var $image_type; 

	function load($filename) {   
		$image_info = getimagesize($filename); $this->image_type = $image_info[2]; 
		if( $this->image_type == IMAGETYPE_JPEG ) {   
			$this->image = imagecreatefromjpeg($filename); } 
		elseif( $this->image_type == IMAGETYPE_GIF ) {   
			$this->image = imagecreatefromgif($filename); } 
		elseif( $this->image_type == IMAGETYPE_PNG ) {   
			$this->image = imagecreatefrompng($filename); } 
	} 

	function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {   
		if( $image_type == IMAGETYPE_JPEG ) { imagejpeg($this->image,$filename,$compression); } 
		elseif( $image_type == IMAGETYPE_GIF ) {   imagegif($this->image,$filename); } 
		elseif( $image_type == IMAGETYPE_PNG ) {   imagepng($this->image,$filename); } 

		if( $permissions != null) {   chmod($filename,$permissions); } 
	} 

	function output($image_type=IMAGETYPE_JPEG) {   
		if( $image_type == IMAGETYPE_JPEG ) { imagejpeg($this->image); } 
		elseif( $image_type == IMAGETYPE_GIF ) {   imagegif($this->image); } 
		elseif( $image_type == IMAGETYPE_PNG ) {   imagepng($this->image); } 
	} 

	function getWidth() {   
		return imagesx($this->image); 
	} 

	function getHeight() {   
		return imagesy($this->image); 
	} 

	function resizeToHeight($height) {   
		$ratio = $height / $this->getHeight(); $width = $this->getWidth() * $ratio; $this->resize($width,$height); 
	}   

	function resizeToWidth($width) { 
		$ratio = $width / $this->getWidth(); $height = $this->getheight() * $ratio; $this->resize($width,$height); 
	}   

	function scale($scale) { 
		$width = $this->getWidth() * $scale/100; $height = $this->getheight() * $scale/100; $this->resize($width,$height); 
	}   

	function resize($width,$height) { 
            $original_width= $this->getWidth();
            $original_height=  $this->getHeight();
            $original_aspect = $original_width / $original_height;
            $thumb_aspect = $width / $height;
            if($original_aspect >= $thumb_aspect) {
   // If image is wider than thumbnail (in aspect ratio sense)
   $new_height = $height;
   $new_width = $original_width / ($original_height / $height);
} else {
   // If the thumbnail is wider than the image
   $new_width = $width;
   $new_height = $original_height / ($original_width / $width);
}

//create square image
$img_dst=imagecreatetruecolor($new_width,$new_height);
imagecopyresampled($img_dst, $this->image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

//create thumbnail
		$new_image = imagecreatetruecolor($width, $height); 
                $background = imagecolorallocate($new_image, 255, 255, 255);
                imagefilledrectangle($new_image, 0, 0, $width, $height, $background);
                imagecopyresampled($new_image,
                   $img_dst,
                   0 - ($new_width - $width) / 2, // Center the image horizontally
                   0 - ($new_height - $height) / 2, // Center the image vertically
                   0, 0,
                   $new_width, $new_height,
                   $new_width, $new_height);
                //imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight()); 
                $this->image = $new_image; 
	}   
} 
?> 