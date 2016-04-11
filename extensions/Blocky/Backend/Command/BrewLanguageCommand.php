<?php

namespace Blocky\Backend\Command;

use Blocky\CLI\SimpleCommand;
use Blocky\Config\YamlWrapper;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class BrewLanguageCommand extends SimpleCommand
{

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function exec($params)
	{
		$dirs = $this->getExtensionLocalisationDirs();
		$translations = [];

		// first we use each localisations dir's proto.yml to fill any other languge.yml keys 
		// and in the same run, we populate each localisation's translation to an array
		foreach($dirs as $dir) {
			if(file_exists($dir."/proto.yml")) {
				$proto = YamlWrapper::parse($dir."/proto.yml");

				$otherLanguagePaths = glob($dir."/*.yml");
				foreach($otherLanguagePaths as $path) {
					$file = str_replace($dir."/", "", $path);

					if($file != "proto.yml") {

						$langugeConfig = YamlWrapper::parse($dir."/".$file);

						// fulfill keys from proto in every localisation which are not present in current langugeConfig
						foreach($proto as $k => $v) {
							if(!array_key_exists($k, $langugeConfig)) {
								$langugeConfig[$k] = $v;
							}
						}

						// populate global translations array
						$fileParts = explode(".", $file);
						$locale = array_shift($fileParts);

						if(!array_key_exists($locale, $translations))
							$translations[$locale] = [];

						$translations[$locale] = array_merge([], $translations[$locale], $langugeConfig);

						//save language config
						YamlWrapper::emit($dir."/".$file, $langugeConfig);
					}
				}
			}
		}

		// now we store global translations under app/translations in both .yml and .po, and create .mo
		@mkdir($this->app['path']['root']."/app/translations");

		
		foreach($translations as $locale => $content) {
			@mkdir($this->app['path']['root']."/app/translations/".$locale);
			@mkdir($this->app['path']['root']."/app/translations/".$locale."/LC_MESSAGES");
			YamlWrapper::emit($this->app['path']['root']."/app/translations/".$locale."/LC_MESSAGES/blocky.yml", $content);

			$poContent = [];

			foreach($content as $k => $v) {
				// we dont store keys which does not have content is a specific locale, so later on we can determin by the key, which translation is missing
				if($v && strlen($v) > 0) {
					$poContent[] = 'msgid "'.str_replace("\"","\\\"", $k).'"';
					$poContent[] = 'msgstr "'.str_replace("\"","\\\"", $v).'"';
				}
			}

			file_put_contents($this->app['path']['root']."/app/translations/".$locale."/LC_MESSAGES/blocky.po", implode("\r\n", $poContent));

			self::phpmo_convert($this->app['path']['root']."/app/translations/".$locale."/LC_MESSAGES/blocky.po", $this->app['path']['root']."/app/translations/".$locale."/LC_MESSAGES/blocky.mo");
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getExtensionLocalisationDirs()
	{
		$paths = [];

		$objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->app['path']['extensions']), \RecursiveIteratorIterator::SELF_FIRST);
		foreach($objects as $name => $object){
			$name = str_replace(["/", "\\"], "/", $name);
		    $parts = explode("/", $name);

		    if($parts[count($parts)-1] == "localisations")
		    	$paths[] = $name;
		}

		return $paths;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getName()
	{
		return "BrewLanguage";
	}

	// used resources
	/**
	 * php.mo 0.1 by Joss Crowcroft (http://www.josscrowcroft.com)
	 * 
	 * Converts gettext translation '.po' files to binary '.mo' files in PHP.
	 * 
	 * Usage: 
	 * <?php require('php-mo.php'); phpmo_convert( 'input.po', [ 'output.mo' ] ); ?>
	 * 
	 * NB:
	 * - If no $output_file specified, output filename is same as $input_file (but .mo)
	 * - Returns true/false for success/failure
	 * - No warranty, but if it breaks, please let me know
	 * 
	 * More info:
	 * https://github.com/josscrowcroft/php.mo
	 * 
	 * Based on php-msgfmt by Matthias Bauer (Copyright � 2007), a command-line PHP tool
	 * for converting .po files to .mo.
	 * (http://wordpress-soc-2007.googlecode.com/svn/trunk/moeffju/php-msgfmt/msgfmt.php)
	 * 
	 * License: GPL v3 http://www.opensource.org/licenses/gpl-3.0.html
	 */

	/**
	 * The main .po to .mo function
	 */
	static function phpmo_convert($input, $output = false) {
		if ( !$output )
			$output = str_replace( '.po', '.mo', $input );

		$hash = self::phpmo_parse_po_file( $input );
		if ( $hash === false ) {
			return false;
		} else {
			self::phpmo_write_mo_file( $hash, $output );
			return true;
		}
	}

	static function phpmo_clean_helper($x) {
		if (is_array($x)) {
			foreach ($x as $k => $v) {
				$x[$k] = self::phpmo_clean_helper($v);
			}
		} else {
			if ($x[0] == '"')
				$x = substr($x, 1, -1);
			$x = str_replace("\"\n\"", '', $x);
			$x = str_replace('$', '\\$', $x);
		}
		return $x;
	}

	/* Parse gettext .po files. */
	/* @link http://www.gnu.org/software/gettext/manual/gettext.html#PO-Files */
	static function phpmo_parse_po_file($in) {
		// read .po file
		$fh = fopen($in, 'r');
		if ($fh === false) {
			// Could not open file resource
			return false;
		}

		// results array
		$hash = array ();
		// temporary array
		$temp = array ();
		// state
		$state = null;
		$fuzzy = false;

		// iterate over lines
		while(($line = fgets($fh, 65536)) !== false) {
			$line = trim($line);
			if ($line === '')
				continue;

			list ($key, $data) = preg_split('/\s/', $line, 2);
			
			switch ($key) {
				case '#,' : // flag...
					$fuzzy = in_array('fuzzy', preg_split('/,\s*/', $data));
				case '#' : // translator-comments
				case '#.' : // extracted-comments
				case '#:' : // reference...
				case '#|' : // msgid previous-untranslated-string
					// start a new entry
					if (sizeof($temp) && array_key_exists('msgid', $temp) && array_key_exists('msgstr', $temp)) {
						if (!$fuzzy)
							$hash[] = $temp;
						$temp = array ();
						$state = null;
						$fuzzy = false;
					}
					break;
				case 'msgctxt' :
					// context
				case 'msgid' :
					// untranslated-string
				case 'msgid_plural' :
					// untranslated-string-plural
					$state = $key;
					$temp[$state] = $data;
					break;
				case 'msgstr' :
					// translated-string
					$state = 'msgstr';
					$temp[$state][] = $data;
					break;
				default :
					if (strpos($key, 'msgstr[') !== FALSE) {
						// translated-string-case-n
						$state = 'msgstr';
						$temp[$state][] = $data;
					} else {
						// continued lines
						switch ($state) {
							case 'msgctxt' :
							case 'msgid' :
							case 'msgid_plural' :
								$temp[$state] .= "\n" . $line;
								break;
							case 'msgstr' :
								$temp[$state][sizeof($temp[$state]) - 1] .= "\n" . $line;
								break;
							default :
								// parse error
								fclose($fh);
								return FALSE;
						}
					}
					break;
			}
		}
		fclose($fh);
		
		// add final entry
		if ($state == 'msgstr')
			$hash[] = $temp;

		// Cleanup data, merge multiline entries, reindex hash for ksort
		$temp = $hash;
		$hash = array ();
		foreach ($temp as $entry) {
			foreach ($entry as & $v) {
				$v = self::phpmo_clean_helper($v);
				if ($v === FALSE) {
					// parse error
					return FALSE;
				}
			}
			$hash[$entry['msgid']] = $entry;
		}

		return $hash;
	}

	/* Write a GNU gettext style machine object. */
	/* @link http://www.gnu.org/software/gettext/manual/gettext.html#MO-Files */
	static function phpmo_write_mo_file($hash, $out) {
		// sort by msgid
		ksort($hash, SORT_STRING);
		// our mo file data
		$mo = '';
		// header data
		$offsets = array ();
		$ids = '';
		$strings = '';

		foreach ($hash as $entry) {
			$id = $entry['msgid'];
			if (isset ($entry['msgid_plural']))
				$id .= "\x00" . $entry['msgid_plural'];
			// context is merged into id, separated by EOT (\x04)
			if (array_key_exists('msgctxt', $entry))
				$id = $entry['msgctxt'] . "\x04" . $id;
			// plural msgstrs are NUL-separated
			$str = implode("\x00", $entry['msgstr']);
			// keep track of offsets
			$offsets[] = array (
				strlen($ids
			), strlen($id), strlen($strings), strlen($str));
			// plural msgids are not stored (?)
			$ids .= $id . "\x00";
			$strings .= $str . "\x00";
		}

		// keys start after the header (7 words) + index tables ($#hash * 4 words)
		$key_start = 7 * 4 + sizeof($hash) * 4 * 4;
		// values start right after the keys
		$value_start = $key_start +strlen($ids);
		// first all key offsets, then all value offsets
		$key_offsets = array ();
		$value_offsets = array ();
		// calculate
		foreach ($offsets as $v) {
			list ($o1, $l1, $o2, $l2) = $v;
			$key_offsets[] = $l1;
			$key_offsets[] = $o1 + $key_start;
			$value_offsets[] = $l2;
			$value_offsets[] = $o2 + $value_start;
		}
		$offsets = array_merge($key_offsets, $value_offsets);

		// write header
		$mo .= pack('Iiiiiii', 0x950412de, // magic number
		0, // version
		sizeof($hash), // number of entries in the catalog
		7 * 4, // key index offset
		7 * 4 + sizeof($hash) * 8, // value index offset,
		0, // hashtable size (unused, thus 0)
		$key_start // hashtable offset
		);
		// offsets
		foreach ($offsets as $offset)
			$mo .= pack('i', $offset);
		// ids
		$mo .= $ids;
		// strings
		$mo .= $strings;

		file_put_contents($out, $mo);
	}
} // END class BrewLangaugeCommand