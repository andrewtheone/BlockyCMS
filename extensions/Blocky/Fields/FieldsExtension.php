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
			new FieldType\Random()
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

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function twigImageFilter($a, $b, $image, $size = null)
	{
		if((!$image) || (count($image) == 0)) {
			return "";
		}

		if($size) {
			$path = $this->app['path']['files'].$image['path'];
			$resized_path = explode("/", $path);
			
			$file = $resized_path[count($resized_path)-1];
			$file_parts = explode(".", $file);
			$file_parts[0] .= "_".implode("_", $size);
			$resized_path[count($resized_path)-1] = implode(".", $file_parts);
			$resized_path = implode("/", $resized_path);

			if(!file_exists($resized_path)) {
				$imagick = new \Imagick($path);
				$this->resizeImage($imagick, $size[0], $size[1], \Imagick::FILTER_LANCZOS, 0.7, false, false);

				file_put_contents($resized_path, $imagick->getImageBlob());
			}

			return str_replace($this->app['path']['files'], $this->app['path']['files_url'], $resized_path);
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