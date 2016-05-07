<?php

namespace Blocky\Fields;

use Blocky\Extension\SimpleExtension;
use Blocky\Extension\FieldTypeProvider;
use Blocky\Extension\TwigFilterProvider;
use Blocky\Extension\TwigFunctionProvider;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class FieldsExtension extends SimpleExtension implements FieldTypeProvider, TwigFilterProvider, TwigFunctionProvider
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

			$this->app['view']->addSnippet("BLOCKY::END_BODY", function($app) {
				return $app['view']->twig->render("@fields/_gallery_image_modal.twig");
			});
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
			new FieldType\ImageList(),
			new FieldType\Repeater(),
			new FieldType\Select(),
			new FieldType\Tag(),
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
	public function getTwigFunctions()
	{
		$self = $this;
		return [
			'call_provider' => function($a, $b, $provider, $args = []) use($self) {

				if(!is_string($provider))
					return $provider;

				if(strpos($provider, ".") !== false) {
					$providerParts = explode(".", $provider);
					list($service, $method) = $providerParts;
					return $self->app[$service]->$method();
				}

				$providerParts = explode("::", $provider);
				
				return call_user_func_array($providerParts, $args);
			}
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
			'isKeyContained' => function($a, $b, $array, $key, $value) {
				foreach($array as $a) {
					if($key == 'id') {
						if($a->getID() == $value)
							return true;
					} else {
						if($a->getValue($key) == $value)
							return true;
					}
				}

				return false;
			},
			'in_array' => function($a, $b, $array, $value) {
				return in_array($value, $array);
			},
			'is_array' => function($a, $b, $arr) {
				return is_array($arr);
			},
			'is_string' => function($a, $b, $arr) {
				return is_string($arr);
			},
			'is_integer' => function($a, $b, $arr) {
				return is_integer($arr);
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
	public function twigImageFilter($a, $b, $image, $options)
	{
		$this->addAsset("js", "@this/assets/js/lazyload.jquery.js");
		$this->addAsset("style", "@this/assets/css/lazyload.css");

		$options = array_merge([], [
			'size' => null,
			'watermark' => null,
			'class' => '',
			'lazyLoad' => true,
			'onlySrc' => false,
			'alt' => '',
			'title' => '',
			'quality' => null,
			'data' => []
		], $options);

		$filePath = null;
		$path = null;
		if(!is_array($image)) {
			$path = $this->app['path']['root'].$image;
			$filePath = $this->app['config']['host'].$image;
		} else {
			if(array_key_exists('path', $image)) {
				$filePath = $this->app['path']['files_url'].$image['path'];
				$path = $this->app['path']['files'].$image['path'];
				if(array_key_exists('watermark', $image)) {
					$options['watermark'] = $image['watermark'];
				}
				if(array_key_exists('alt', $image)) {
					$options['alt'] = $image['alt'];
				}
				if(array_key_exists('title', $image)) {
					$options['title'] = $image['title'];
				}
			} else {
				die("Not alrighty...");
			}
		}
		if(($path == null) || (!is_file($path)))
			return "";

		$watermarkPath = null;
		if($options['watermark']) {
			$watermarkPath = str_replace("@root", $this->app['path']['root'], $options['watermark']);
		}
		
		// todo: themes/default/assets/images.png|image([400,400])

		if($options['size']) {
			
			$options['quality'] = $options['quality']?$options['quality']:80;

			$resized_path = explode("/", $path);
			
			$file = $resized_path[count($resized_path)-1];
			$file_parts = explode(".", $file);
			$file_parts[0] .= "_".implode("_", $options['size']);
			
			$file_parts[0] .= "_".$options['quality'];

			if($watermarkPath) {
				$file_parts[0] .= "_wt";
			}

			$resizedName = implode(".", $file_parts);

			if(!file_exists( $this->app['path']['files']."/cache/".$resizedName)) {

				$imagick = new \Imagick($path);
				$this->resizeImage($imagick, $options['size'][0], $options['size'][1], \Imagick::FILTER_LANCZOS, $options['quality']/100, false, false);
				$imagick->setInterlaceScheme(\Imagick::INTERLACE_PLANE);

				if($watermarkPath) {
					$sprite = new \Imagick($watermarkPath);
					$imagick = $this->addWatermark($imagick, $sprite);
				}

				file_put_contents( $this->app['path']['files']."/cache/".$resizedName, $imagick->getImageBlob());
			}

			$filePath = $this->app['path']['files_url']."/cache/".$resizedName;
		} else {
			if($options['quality']) {

				$resized_path = explode("/", $path);
				
				$file = $resized_path[count($resized_path)-1];
				$file_parts = explode(".", $file);
				$file_parts[0] .= "_default_default";
				
				$file_parts[0] .= "_".$options['quality'];

				if($watermarkPath) {
					$file_parts[0] .= "_wt";
				}

				$resizedName = implode(".", $file_parts);

				if(!file_exists( $this->app['path']['files']."/cache/".$resizedName)) {

					$imagick = new \Imagick($path);
					$this->resizeImage($imagick, $imagick->getImageWidth(), $imagick->getImageHeight(), \Imagick::FILTER_LANCZOS, $options['quality']/100, false, false);
					$imagick->setInterlaceScheme(\Imagick::INTERLACE_PLANE);

					if($watermarkPath) {
						$sprite = new \Imagick($watermarkPath);
						$imagick = $this->addWatermark($imagick, $sprite);
					}

					file_put_contents( $this->app['path']['files']."/cache/".$resizedName, $imagick->getImageBlob());
				}

				$filePath = $this->app['path']['files_url']."/cache/".$resizedName;
			}
		}

		if($options['onlySrc'])
			return $filePath;

		$dataTags = '';
		foreach($options['data'] as $k => $v) {
			$dataTags .= ' data-'.$k.'="'.$v."'";
		}

		$dimensionTags = '';
		if($options['size']) {
			$dimensionTags = ' width="'.$options['size'][0].'px" height="'.$options['size'][1].'px" ';
		}

		$metaTags = 'alt="'.$options['alt'].'" title="'.$options['title'].'"';

		$output = '';
		if($options['lazyLoad']) {
			$output = '<img data-original="'.$filePath.'" class="lazy '.$options['class'].'" '.$metaTags.' '.$dimensionTags.' '.$dataTags.' /><noscript><img src="'.$filePath.'" class="'.$options['class'].'" '.$metaTags.' '.$dimensionTags.' '.$dataTags.' /></noscript>';
		} else {
			$output = '<img src="'.$filePath.'" class="'.$options['class'].'" '.$metaTags.' '.$dimensionTags.' '.$dataTags.' />';
		}

		return new \Twig_Markup($output, 'utf-8');
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