<?php

namespace Blocky\Fields;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\FieldTypeProvider;
use Blocky\Extension\TwigFilterProvider;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class FieldsExtension extends SimpleExtension implements FieldTypeProvider, TwigFilterProvider
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function boot()
	{
		if($this->app['site'] == "backend") {
			$this->addAsset('js', '@this/assets/js/dropzone.js', 600);
			$this->addAsset('js', '@this/assets/js/ckeditor/ckeditor.js', 600);
			$this->addAsset('js', '@this/assets/js/ckeditor/adapters/jquery.js', 600);

			$this->addAsset('js', '@this/assets/js/handsontable.min.js', 600);
			$this->addAsset('js', '@this/assets/js/select2.full.min.js', 600);
			$this->addAsset('js', '@this/assets/js/select2.hu.js', 600);
			$this->addAsset('js', '@this/assets/js/fieldIniter.js', 601);
			$this->addAsset('style', '@this/assets/css/dropzone.css');
			$this->addAsset('style', '@this/assets/css/handsontable.min.css');
			$this->addAsset('style', '@this/assets/css/select2.min.css');
			//$this->addAsset('style', '@this/assets/js/ckeditor/.css');
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getFieldTypes()
	{
		return [
			new FieldType\Text(),
			new FieldType\Slug(),
			new FieldType\Image(),
			new FieldType\Repeater(),
			new FieldType\Select(),
			new FieldType\Grid(),
			new FieldType\Html(),
			new FieldType\Password(),
			new FieldType\Random(),
			new FieldType\Timestamp()
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getTwigFilters()
	{
		return [
			'json_decode' => function($a, $b, $c) {
				if(strlen($c) == 0) return null;
				return json_decode($c, 1);
			},
			'json_encode' => function($a, $b, $c) {
				return json_encode($c);
			},
			'image' => [$this, 'twigImageFilter'],
			'isIDContained' => function($a, $b, $array, $value) {
				foreach($array as $a) {
						if($a->getID() == $value)
							return true;
				}

				return false;
			},
			'in_array' => function($a, $b, $array, $value) {
				return in_array($value, $array);
			}
		];
	}

	public function resizeImage(&$imagick, $width, $height, $filterType, $blur, $bestFit, $cropZoom) {
	    $imagick->setImageCompressionQuality(100);

		$imagick->setimageopacity(1.0);

	    $heightRatio = $height/$imagick->getImageHeight();


	    if( ($imagick->getImageWidth()*$heightRatio) < $width ) {
	        $coorit = $width;
	        $width = $imagick->getImageWidth()*$heightRatio;
	    }

	    $imagick->resizeImage($width, $height, $filterType, $blur, $bestFit);

	    $cropWidth = $imagick->getImageWidth();
	    $cropHeight = $imagick->getImageHeight();

	    if ($cropZoom) {
	        $newWidth = $cropWidth / 2;
	        $newHeight = $cropHeight / 2;

	        $imagick->cropimage(
	            $newWidth,
	            $newHeight,
	            ($cropWidth - $newWidth) / 2,
	            ($cropHeight - $newHeight) / 2
	        );

	        $imagick->scaleimage(
	            $imagick->getImageWidth() * 4,
	            $imagick->getImageHeight() * 4
	        );
	    }


		return $imagick;
	}

	public function addWatermark($image, $sprite) {

		$imageWidth = $image->getImageWidth();
		$imageHeight = $image->getImageHeight();

		// scale the sprite. 0 means 'scale height
		// proportionally'. Let's make it 1/3 of image width
		$sprite->scaleImage($imageWidth / 3, 0);

		$spriteWidth = $sprite->getImageWidth();
		$spriteHeight = $sprite->getImageHeight();

		// Calculate coordinates of top left corner
		// of the sprite inside of the image
	        // "- 10" stands for offset to image border
		$left = $imageWidth - $spriteWidth - 10;
		$top = $imageHeight - $spriteHeight - 10;

		$image->compositeImage($sprite,
				\Imagick::COMPOSITE_DEFAULT,
				$left, $top);

		return $image; // return if you want to output
	        // it or write the result to another file

	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function twigImageFilter($a, $b, $image, $size = null, $watermark = null)
	{
		$path = null;
		$isImageField = true;

		if(!is_array($image)) {
			$isImageField = false;

			$path = $this->app['path']['root'].$image;
		} else {
			if(array_key_exists('path', $image)) {
				$path = $this->app['path']['files'].$image['path'];
				if(array_key_exists('watermark', $image)) {
					$watermark = $image['watermark'];
				}
			}
		}
		if(($path == null) || (!is_file($path)))
			return "";

		if($watermark) {
			$watermark = str_replace("@root", $this->app['path']['root'], $watermark);
		}
		
		// todo: themes/default/assets/images.png|image([400,400])

		if($size) {
			$resized_path = explode("/", $path);
			
			$file = $resized_path[count($resized_path)-1];
			$file_parts = explode(".", $file);
			$file_parts[0] .= "_".implode("_", $size);
			
			if($watermark) {
				$file_parts[0] .= "_wt";
			}

			$resizedName = implode(".", $file_parts);
			/*
			$resized_path[count($resized_path)-1] = implode(".", $file_parts);
			$resized_path = implode("/", $resized_path);
			*/

			if(!file_exists( $this->app['path']['files']."/cache/".$resizedName /*$resized_path*/)) {
				$imagick = new \Imagick($path);
				$this->resizeImage($imagick, $size[0], $size[1], \Imagick::FILTER_LANCZOS, 0.7, false, false);
				$imagick->setInterlaceScheme(\Imagick::INTERLACE_PLANE);
				if($watermark) {
					$sprite = new \Imagick($watermark);
					$imagick = $this->addWatermark($imagick, $sprite);
				}
				file_put_contents( $this->app['path']['files']."/cache/".$resizedName /*$resized_path*/, $imagick->getImageBlob());
			}

			return $this->app['path']['files_url']."/cache/".$resizedName;
			if($isImageField) {
				return str_replace($this->app['path']['files'], $this->app['path']['files_url'], $resized_path);
			}

			return str_replace($this->app['path']['root'], $this->app['config']['host'], $resized_path);
		}

		return $this->app['path']['files_url'].$image['path'];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getViewNamespaces()
	{
		return [
			'fields' => __DIR__."/views"
		];
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return "Blocky::FieldsExtension";
	}
} // END class FrontendExtension