<?php

class URL {
	
	static function host () {
		return $_SERVER['HTTP_HOST'];
	}
	
	static function script () {
		return $_SERVER['SCRIPT_NAME'];
	}
	
	static function query () {
		return $_SERVER['QUERY_STRING'];
	}
	
	static function uri () {
		return substr($_SERVER['REQUEST_URI'], 1);
	}
	
	static function ssl () {
		return $_SERVER['HTTPS'] == 'on' ? true : false; //check against server port instead?
	}
	
	static function segments ($part = null) {
		$uri = explode("/", self::uri());
		if (is_numeric($part) && !is_null($part))
			return $uri[$part];
		else
			foreach($uri as $key => $value)
				if ($value == '') 
					unset($uri[$key]);
					
			return array_values($uri);
	}
	
	static function contains ($var) {
		$parts = self::segments();
		if(in_array($var, $parts)) 
			return true;
		else
			return false;
	}
	
	static function like ($var) {
		$uri = self::uri();
		if(strpos($uri, $var))
			return true;
		else
			return false;
	}
	
	static function first () {
		return self::segments(0);
	}
	
	static function last () {
		$x = count(self::segments()) - 1;
		return self::segments($x);
	}

	static function reverse ($string = false) {
		$reversed = array_reverse(self::segments());
		if ($string)
			return implode("/", $reversed);
		else
			return $reversed;
	}
	
	static function build ($array, $prefix = '?', $suffix = null) {
		return $prefix . http_build_query($array) . $suffix;
	}
	
	static function parse ($var, $key = null)  {
		$parsed = parse_url($var);
		if(is_null($key))
			return $parsed;
		else 
			return $parsed[$key];
	}
	
	static function slugify($var, $replace="-") {
		if(is_string($var)) {
			if (function_exists('iconv')) {
	        	$var = @iconv('UTF-8', 'ASCII//TRANSLIT', $var);
	        }
	    } elseif (is_array($var)) {
	    	$var = implode("/", $var);
	    }
    	$var = preg_replace('/\W+/', $replace, $var);
    	$var = strtolower(trim($var, $replace));
    	return $var;
	}
	
	static function convert ($str = null, $keys = false, $remove = true) {
		if(is_null($str)) {
			$str = self::query();	
		}		
		parse_str($str, $output);
		$uri = array();
		foreach($output as $key => $value) {
			if($key == 'url' && $remove) continue;
				$uri[] = $keys ? $key : $value;
		}
		return strtolower(implode("/", $uri));
	}
	
	static function encode ($str) {
		return htmlentities(urlencode($str));
	}
	
	static function decode ($str) {
		return htmlspecialchars(urldecode($str));
	}
	
}