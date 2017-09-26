<?php

namespace src\libs;

/**
 * Simple lang class
 * It detects user language, loads appropiate lang file and provides helper method for getting text in appropiate language
 *
 * @author Comandeer
 * @copyright  (c) 2014 Comandeer
 * @license MIT
 * @version 1.0.0
 */
class Lang
{
	/**
	 * Path to lang files
	 * It should be moved to config class!
	 * @var string
	 * @static
	 */
	public static $path;

	/**
	 * Current language
	 * @var string
	 * @static
	 */
	protected static $lang=DEFAULT_LANGUAGE;

	/**
	 * Loaded lang files
	 * @var array
	 * @static
	 */
	protected static $langTables=[];

	/**
	 * Detect user language
	 * Based on http://www.forumweb.pl/porady-i-tutoriale-www/xhtml-jak-ulepszyc-kod-strony/272327#272327
	 * @static
	 * @param $header   string   string in Accept-Language format to fetch language from
	 * @return array
	 */
	public static function detectLang($header) {
            echo $header;
		$acceptLangs=[];

		$header=explode(',',strtolower(str_replace(' ','',$header)));

		foreach($header as $a)
		{
			//default q is 1
			$q=1;
			if(strpos($a,';q=')!==FALSE)
				list($a,$q)=explode(';q=',$a);
			$acceptLangs[$a]=(float)$q;
		}
		
		arsort($acceptLangs,SORT_NUMERIC);
		return $acceptLangs;
	}

	/**
	 * Set current language
	 * Yea, I know that self::$lang could be modified without it, but let's do it in nice way
	 * @static
	 * @param $lang   string   language string
	 * @return boolean
	 */
	public static function setLang($string) {
		if($lang=self::loadLang($string))
		{
			self::$lang=$string;
			self::$langTables[$string]=$lang;
			return true;
		}
		return false;
	}

	/**
	 * Get current language
	 * @static
	 * @return string
	 */
	public static function getLang() {
		return self::$lang;
	}

	/**
	 * Translate given text
	 * @static
	 * @param $text   string   text to translate
	 * @param $toReplace   array   associative array of values to replace in returning string
	 * @return string
	 */
	public static function translate($text,$toReplace=[]){
		if(!isset($langTables[self::$lang]))
			$langTables[self::$lang]=self::loadLang(self::$lang);
		return strtr(isset($langTables[self::$lang][$text])?$langTables[self::$lang][$text]:$text,$toReplace);
	}

	/**
	 * Loads lang file
	 * @static
	 * @param $lang   string   language string
	 * @return array | NULL
	 */
	protected static function loadLang($lang){
		$lang=mb_strtolower(str_replace('_','-',basename($lang)));

		//get the main lang (not dialect)
		$mainLang=explode('-',$lang)[0];

		//if there is no dialect specified, repeat mainLang to form lang-dialect form
		if(strpos('-',$lang)===false)
			$lang=$mainLang.'-'.$mainLang;

		//first check existence of dialect
		if(file_exists(self::$path.'_'.$mainLang.'.php'))
			return require(self::$path.'_'.$mainLang.'.php');
		//if app came here, it means there's no such language
		return NULL;
	}
}
?>