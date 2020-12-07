<?php
namespace SynPic\Library;
class MakePic
{
	
	public function loadPic($pic, &$picdata=null, &$type=null) {
		
		$picdata = getimagesize($pic);

		switch ($picdata['mime']) {

			case 'image/gif':
			
				$type = 'gltf';

				return imagecreatefromgif($pic);

			case 'image/png':
				
				$type = 'png';

				return imagecreatefrompng($pic);

			case 'image/jpeg':
			
				$type = 'jpeg';

				return imagecreatefromjpeg($pic);

		}
		
	}
	
	
	
	public function addText($dst_im, $dest, $text, $fontSize=18, $angle=0, $tx='bottom', $ty='right', $color='21,124,175', $picbz=80) {
		
		if( empty( $dst_im = $this->loadPic($dst_im, $dst_info, $dst_type) ) ) return;
		
		$color = explode(',', $color);
		
        $color = ImageColorAllocate($dst_im, $color[0], $color[1], $color[2]);
		
		$font_msyh = 'stzhongs.ttf';
		
        $fontWidth = imagefontwidth($fontSize);//获取文字宽度
		
		$fontheight = imagefontheight($fontSize);//获取文字宽度
		
        $textWidth = $fontWidth * mb_strlen($text);
		
		if(is_string($tx)) switch($tx) {
			
			case 'left':
				
				$tx = 0;
				
				break;
				
			case 'center':
			
				$tx = ($dst_info[0] - $textWidth) / 2;
				
				break;
				
			case 'right':
				
				$tx = $dst_info[0] - $textWidth;
				
				break;
				
			default:
				
				$tx = (float) $tx;
				
		}
		
		if(is_string($ty)) switch($ty) {
			
			case 'top':
				
				$ty = 0;
				
				break;
				
			case 'center':
			
				$ty = ($dst_info[1] - $fontheight) / 2;
				
				break;
				
			case 'right':
				
				$ty = $dst_info[1] - $fontheight;
				
				break;
				
			default:
				
				$ty = (float) $ty;
				
		}

        imagettftext($dst_im, $fontSize, $angle, $tx, $ty, $color, $font_msyh, $text);

		switch ($dst_type) {

			case 'gif':

				imagegif($dst_im, $dest);

				break;

			case 'png':

				imagepng($dst_im, $dest);

				break;

			case 'jpeg':

				imagejpeg($dst_im, $dest, $picbz);

				break;

		}
		
		imagedestroy($dst_im);
		
		return $dest;
		
	}
	
	
	
	/**
	* 说明:合成图片
	* @param string $dst_im 目标图片
	* @param string $src_im 水印图片
	* @param string $dest 合成图片输出路径
	* @param int|string $dst_x 目标图像开始 x坐标(支持left center right)
	* @param int|string $dst_y 目标图像开始 y坐标(支持top center bottom)
	* @param int|string $src_x 水印图像开始 x坐标(支持百分比)
	* @param int|string $src_y 水印图像开始 y坐标(支持百分比)
	* @param int|string $src_w 拷贝的宽度(支持百分比)
	* @param int|string $src_h 拷贝的高度(支持百分比)
	* @param int $pct 透明度(0 ~ 100)
	* @param int $picbz jpeg类型清晰度(1~100)数值越大越清晰
	*/
	public function compositeImg($dst_im, $src_im, $dest, $dst_x=0, $dst_y=0, $src_x=0, $src_y=0, $src_w='100%', $src_h='100%', $pct=100, $picbz=80) {
		
		if( empty( $src_im = $this->loadPic($src_im, $src_info, $src_type) ) ) return;
		
		if( empty( $dst_im = $this->loadPic($dst_im, $dst_info, $dst_type) ) ) return;
		
		$this->conSize($dst_info, $src_info, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
		
		imagecopymerge($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct);
		
		switch ($dst_type) {

			case 'gif':

				imagegif($dst_im, $dest);

				break;

			case 'png':

				imagepng($dst_im, $dest);

				break;

			case 'jpeg':

				imagejpeg($dst_im, $dest, $picbz);

				break;

		}
		
		imagedestroy($dst_im);
		
		imagedestroy($src_im);
		
		return $dest;
		
	}
	
	
	
	public function conSize($dst_info, $src_info, &$dst_x, &$dst_y, &$src_x, &$src_y, &$src_w, &$src_h) {
		
		if( is_string($dst_x) ) {
			
			switch($dst_x) {
				
				case 'left':
					
					$dst_x = 0;
					
					break;
					
				case 'center':
					
					$dst_x = ($dst_info[0]-$src_info[0]) / 2;
					
					break;
					
				case 'right':
				
					$dst_x = $dst_info[0] - $src_info[0];
					
					break;
					
				default: 
					
					$dst_x = (float) $dst_x;
				
			}
			
		}
		
		if( is_string($dst_y) ) {
			
			switch($dst_y) {
				
				case 'top':
					
					$dst_y = 0;
					
					break;
					
				case 'center':
					
					$dst_y = ($dst_info[1]-$src_info[1]) / 2;
					
					break;
					
				case 'bottom':
				
					$dst_y = $dst_info[1] - $src_info[1];
					
					break;
					
				default: 
					
					$dst_y = (float) $dst_y;
				
			}
			
		}
		
		if( is_string($src_x) ) {
			
			if(substr($src_x, -1) == '%') {
				
				$src_x = $src_info[0] * (float) substr($src_x, 0, -1) / 100;
				
			} else {
				
				$src_x = (float) $src_x;
				
			}
			
		}
		
		if( is_string($src_y) ) {
			
			if(substr($src_y, -1) == '%') {
				
				$src_y = $src_info[1] * (float) substr($src_y, 0, -1) / 100;
				
			} else {
				
				$src_y = (float) $src_y;
				
			}
			
		}
		
		if( is_string($src_w) ) {
			
			if(substr($src_w, -1) == '%') {
				
				$src_w = $src_info[0] * (float) substr($src_w, 0, -1) / 100;
				
			} else {
				
				$src_w = (float) $src_w;
				
			}
			
		}
		
		if( $src_w > $src_info[0] - $src_x ) $src_w = $src_info[0] - $src_x;
		
		if( is_string($src_h) ) {
			
			if(substr($src_h, -1) == '%') {
				
				$src_h = $src_info[1] * (float) substr($src_h, 0, -1) / 100;
				
			} else {
				
				$src_h = (float) $src_h;
				
			}
			
		}
		
		if( $src_h > $src_info[1] - $src_y ) $src_h = $src_info[1] - $src_x;
		
	}
	
	
	
	
	/**
	* 说明:专门缩放处理
	* @param string $filename 原来的文件名
	* @param string $dest 保存的路径
	* @param int $width 宽
	* @param int $height 高
	* @param string $pictype 图片缩放模式(thumb拉伸,cut等比剪掉,other自适应)
	* @param int $picbz 图片质量
	* @return boolean
	*/
	public function resize ($filename, $dest, $width, $height, $pictype = "", $picbz = 88) {

		$picdata = getimagesize($filename);

		switch ($picdata['mime']) {

			case 'image/gif':

				$type = "gif";

				$img = imagecreatefromgif($filename);

				break;

			case 'image/png':

				$type = "png";

				$img = imagecreatefrompng($filename);

				break;

			case 'image/jpeg':

				$type = "jpg";

				$img = imagecreatefromjpeg($filename);

				break;

			default:

				return false;

				break;

		}

		@ini_set("memory_limit", 134217728);

		$org_width = $picdata[0];

		$org_height = $picdata[1];

		$xoffset = 0;

		$yoffset = 0;

		if ($pictype == "thumb") { //伸拉缩放

			$xoffset = 0;

			$yoffset = 0;

		} elseif ($pictype == "cut") { // 我自已写个等比裁剪

			if ($org_width / $width > $org_height / $height) {

				$xoffset = ($org_width - ($org_height / $height * $width)) / 2;

				$org_width = round($org_width - $xoffset * 2);

			} elseif ($org_height / $height > $org_width / $width) {

				$yoffset = ($org_height - ($org_width / $width * $height)) / 2;

				$org_height = round($org_height - $yoffset * 2);

			}

		} else { // 比例缩放

			if ($org_width / $width < $org_height / $height) {

				$new_height = $height;

				$new_width = $org_width / $org_height * $height;

			} else {

				$new_width = $width;

				$new_height = $org_height / $org_width * $width;

			}

			if( $new_width > $org_width || $new_height > $org_height ) {

                $width = $org_width;

                $height = $org_height;

            } else {

                $width = round($new_width);

                $height = round($new_height);

            }

		}

		$img_n = imagecreatetruecolor($width, $height);

		if ($type == "gif" or $type == "png") {

			imagecolortransparent($img_n, imagecolorallocatealpha($img_n, 0, 0, 0, 127));

			imagealphablending($img_n, false);

			imagesavealpha($img_n, true);

		}

		imagecopyresampled($img_n, $img, 0, 0, $xoffset, $yoffset, $width, $height, $org_width, $org_height);

		switch ($type) {

			case 'gif':

				imagegif($img_n, $dest);

				break;

			case 'png':

				imagepng($img_n, $dest);

				break;

			case 'jpg':

				imagejpeg($img_n, $dest, $picbz);

				break;

		}

		return array($width, $height);

	}
	
	
	
	public function pianyitupian ($filename, $dest, $width, $height, $pictype = "", $picbz = 88) {
	
		$picdata = getimagesize($filename);
	
		switch ($picdata['mime']) {
	
			case 'image/gif':
	
				$type = "gif";
	
				$img = imagecreatefromgif($filename);
	
				break;
	
			case 'image/png':
	
				$type = "png";
	
				$img = imagecreatefrompng($filename);
	
				break;
	
			case 'image/jpeg':
	
				$type = "jpg";
	
				$img = imagecreatefromjpeg($filename);
	
				break;
	
			default:
	
				return false;
	
				break;
	
		}
	
		@ini_set("memory_limit", 134217728);
	
		$org_width = $picdata[0];
	
		$org_height = $picdata[1];
	
		$xoffset = 0;
	
		$yoffset = 0;
	
		if ($pictype == "thumb") { //伸拉缩放
	
			$xoffset = 0;
	
			$yoffset = 0;
	
		} elseif ($pictype == "cut") { // 我自已写个等比裁剪
	
			if ($org_width / $width > $org_height / $height) {
	
				$xoffset = ($org_width - ($org_height / $height * $width)) / 2;
	
				$org_width = round($org_width - $xoffset * 2);
	
			} elseif ($org_height / $height > $org_width / $width) {
	
				$yoffset = ($org_height - ($org_width / $width * $height)) / 2;
	
				$org_height = round($org_height - $yoffset * 2);
	
			}
	
		} else { // 比例缩放
	
			if ($org_width / $width < $org_height / $height) {
	
				$new_height = $height;
	
				$new_width = $org_width / $org_height * $height;
	
			} else {
	
				$new_width = $width;
	
				$new_height = $org_height / $org_width * $width;
	
			}
	
			if( $new_width > $org_width || $new_height > $org_height ) {
	
	            $width = $org_width;
	
	            $height = $org_height;
	
	        } else {
	
	            $width = round($new_width);
	
	            $height = round($new_height);
	
	        }
	
		}
	
		$img_n = imagecreatetruecolor($width * 2, $height * 2);
		
		$color = imagecolorAllocate($img_n,200,200,200);   //分配一个灰色
		
		imagefill($img_n, 0, 0, $color); 
	
		if ($type == "gif" or $type == "png") {
	
			imagecolortransparent($img_n, imagecolorallocatealpha($img_n, 0, 0, 0, 127));
	
			imagealphablending($img_n, false);
	
			imagesavealpha($img_n, true);
	
		}
	
		imagecopyresampled($img_n, $img, 850, 0, $xoffset, $yoffset, $width, $height, $org_width, $org_height);
	
		switch ($type) {
	
			case 'gif':
	
				imagegif($img_n, $dest);
	
				break;
	
			case 'png':
	
				imagepng($img_n, $dest);
	
				break;
	
			case 'jpg':
	
				imagejpeg($img_n, $dest, $picbz);
	
				break;
	
		}
	
		return array($width, $height);
	
	}


}
