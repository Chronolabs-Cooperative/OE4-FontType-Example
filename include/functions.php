<?php
/**
 * OE4 File Type Format Generation Example
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://syd.au.snails.email
 * @license         ACADEMIC APL 2 (https://sourceforge.net/u/chronolabscoop/wiki/Academic%20Public%20License%2C%20version%202.0/)
 * @license         GNU GPL 3 (http://www.gnu.org/licenses/gpl.html)
 * @package         oe4-fonttype
 * @since           1.0.1
 * @author          Dr. Simon Antony Roberts <simon@snails.email>
 * @version         1.0.1
 * @description		This is part of the font file type OE4 File Format Generation Example
 * @link            http://internetfounder.wordpress.com
 * @link            https://github.com/Chronolabs-Cooperative/0E4-FontType-Example
 * @link            https://sourceforge.net/p/chronolabs-cooperative
 * @link            https://facebook.com/ChronolabsCoop
 * @link            https://twitter.com/ChronolabsCoop
 *
 */


if (!function_exists('deleteWhenNotice'))
{
    function deleteWhenNotice($unixtime = 0)
    {
        if ($unixtime < time() - OE4_DELETE_WARNING)
        {
            return "<br /><font style='color: rgb(197,0,0); font-size: 0.7563256em;'>Deleting: " . displaySeconds($unixtime + OE4_DELETE_WHEN - time()) . "</font>";
        }
    }
}

if (!function_exists('displaySeconds'))
{
    function displaySeconds($seconds = 0)
    {
        $result = '';
        if ($seconds / (3600 * 24) > 1)
        {
            $days = $seconds / (3600 * 24);
            $parts = explode('.', $days);
            if ($parts[0]>1)
                $result = $parts[0] . ' days';
            else 
                $result = $parts[0] . ' day';
            $seconds = doubleval('0.'.$parts[1]) * (3600 * 24);
        }
        if ($seconds / 3600 > 1)
        {
            $days = $seconds / 3600;
            $parts = explode('.', $days);
            if ($parts[0]>1)
                $result = (strlen($result)>0?' ':'') . $parts[0] . ' hrs';
            else
                $result = (strlen($result)>0?' ':'') . $parts[0] . ' hr';
            $seconds = doubleval('0.'.$parts[1]) * (3600);
        }
        if ($seconds / (60*60) > 1)
        {
            $days = $seconds / (60*60);
            $parts = explode('.', $days);
            if ($parts[0]>1)
                $result = (strlen($result)>0?' ':'') . $parts[0] . ' mins';
            else
                $result = (strlen($result)>0?' ':'') . $parts[0] . ' min';
            $seconds = doubleval('0.'.$parts[1]) * (60*60);
        }
        if ($seconds / (60) > 1)
        {
            $days = $seconds / (60);
            $parts = explode('.', $days);
            if ($parts[0]>1)
                $result = (strlen($result)>0?' ':'') . $parts[0] . ' secs';
            else
                $result = (strlen($result)>0?' ':'') . $parts[0] . ' sec';
            $seconds = doubleval('0.'.$parts[1]) * (60);
        }
        return $result;
    }
}

if (!function_exists('image2ascii'))
{
    /**
     * Creates ASCII Art from Image
     *
     * @param string $file
     * @param integer $asciiwidth
     * 
     * return string
     * 
     */
    function image2ascii($file, $asciiwidth =  100, $scale = 10)
    {
        $ascii = '';
        $tmp = imagecreatefromstring(file_get_contents($file));
        $scl = imagescale($tmp, $asciiwidth * $scale);
        unset($tmp);
        if (is_file($file.'.png'))
            unlink($file.'.png');
        imagepng($scl, $file.'.png');
        unset($scl);
        $img = imagecreatefromstring(file_get_contents($file.'.png'));
        list($width, $height) = getimagesize($file.'.png');
        $chars = array_reverse(array(' ','\'','.',':','|','H','%','@','#'));
        $c_count = count($chars);
        for($y = 0; $y <= $height - $scale - 1; $y += $scale) {
            for($x = 0; $x <= $width - ($scale / 2) - 1; $x += ($scale / 2)) {
                $rgb = imagecolorat($img, $x, $y);
                $r = (($rgb >> 16) & 0xFF);
                $g = (($rgb >> 8) & 0xFF);
                $b = ($rgb & 0xFF);
                $sat = ($r + $g + $b) / (255 * 3);
                $ascii .= $chars[ (int)( $sat * ($c_count - 1) ) ];
            }
            $ascii .= PHP_EOL;
        }
        return $ascii;
    }
}


if (!function_exists("checkEmail")) {
    /**
     * checkEmail()
     *
     * @param mixed $email
     * @param mixed $antispam
     * @return bool|mixed
     */
    function checkEmail($email, $antispam = false)
    {
        if (!$email || !preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email)) {
            return false;
        }
        $email_array      = explode('@', $email);
        $local_array      = explode('.', $email_array[0]);
        $local_arrayCount = count($local_array);
        for ($i = 0; $i < $local_arrayCount; ++$i) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/\=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/\=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
            $domain_array = explode('.', $email_array[1]);
            if (count($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < count($domain_array); ++$i) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }
        if ($antispam) {
            $email = str_replace('@', ' at ', $email);
            $email = str_replace('.', ' dot ', $email);
        }
        
        return $email;
    }
}

if (!function_exists("redirect")) {
    /**
     * Redirect HTML Display
     *
     * @param string $uri
     * @param integer $seconds
     * @param string $message
     *
     */
    function redirect($uri = '', $seconds = 9, $message = '')
    {
        $GLOBALS['url'] = $uri;
        $GLOBALS['time'] = $seconds;
        $GLOBALS['message'] = $message;
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'redirect.php';
        exit(-1000);
    }
}


if (!function_exists("getURIData")) {
    
    /* function getURIData()
     *
     * 	Get a supporting domain system for the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    function getURIData($uri = '', $timeout = 25, $connectout = 25, $post = array(), $headers = array())
    {
        if (!function_exists("curl_init"))
        {
            die("Install PHP Curl Extension ie: $ sudo apt-get install php-curl -y");
        }
        $GLOBALS['php-curl'][md5($uri)] = array();
        if (!$btt = curl_init($uri)) {
            return false;
        }
        if (count($post)==0 || empty($post))
            curl_setopt($btt, CURLOPT_POST, false);
        else {
            $uploadfile = false;
            foreach($post as $field => $value)
                if (substr($value , 0, 1) == '@' && !file_exists(substr($value , 1, strlen($value) - 1)))
                    unset($post[$field]);
                else
                    $uploadfile = true;
            curl_setopt($btt, CURLOPT_POST, true);
            curl_setopt($btt, CURLOPT_POSTFIELDS, http_build_query($post));
                        
            if (!empty($headers))
                foreach($headers as $key => $value)
                    if ($uploadfile==true && substr($value, 0, strlen('Content-Type:')) == 'Content-Type:')
                        unset($headers[$key]);
            if ($uploadfile==true)
                $headers[]  = 'Content-Type: multipart/form-data';
        }
        if (count($headers)!=0 || !empty($headers))
        {
            curl_setopt($btt, CURLOPT_HEADER, implode("\n", $headers));
        }
        curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $connectout);
        curl_setopt($btt, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($btt, CURLOPT_VERBOSE, false);
        curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($btt);
        $GLOBALS['php-curl'][md5($uri)]['http']['posts'] = $post;
        $GLOBALS['php-curl'][md5($uri)]['http']['headers'] = $headers;
        $GLOBALS['php-curl'][md5($uri)]['http']['code'] = curl_getinfo($btt, CURLINFO_HTTP_CODE);
        $GLOBALS['php-curl'][md5($uri)]['header']['size'] = curl_getinfo($btt, CURLINFO_HEADER_SIZE);
        $GLOBALS['php-curl'][md5($uri)]['header']['value'] = curl_getinfo($btt, CURLINFO_HEADER_OUT);
        $GLOBALS['php-curl'][md5($uri)]['size']['download'] = curl_getinfo($btt, CURLINFO_SIZE_DOWNLOAD);
        $GLOBALS['php-curl'][md5($uri)]['size']['upload'] = curl_getinfo($btt, CURLINFO_SIZE_UPLOAD);
        $GLOBALS['php-curl'][md5($uri)]['content']['length']['download'] = curl_getinfo($btt, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        $GLOBALS['php-curl'][md5($uri)]['content']['length']['upload'] = curl_getinfo($btt, CURLINFO_CONTENT_LENGTH_UPLOAD);
        $GLOBALS['php-curl'][md5($uri)]['content']['type'] = curl_getinfo($btt, CURLINFO_CONTENT_TYPE);
        curl_close($btt);
        return $data;
    }
}


if (!function_exists("writeRawFile")) {
    /**
     *
     * @param string $file
     * @param string $data
     */
    function writeRawFile($file = '', $data = '')
    {
        $lineBreak = "\n";
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $lineBreak = "\r\n";
        }
        if (!is_dir(dirname($file)))
            mkdir(dirname($file), 0777, true);
            if (is_file($file))
                unlink($file);
                $data = str_replace("\n", $lineBreak, $data);
                $ff = fopen($file, 'w');
                fwrite($ff, $data, strlen($data));
                fclose($ff);
    }
}


if (!function_exists("getDirListAsArray")) {
	function getDirListAsArray($dirname)
	{
		$ignored = array(
				'cvs' ,
				'_darcs');
		$list = array();
		if (substr($dirname, - 1) != '/') {
			$dirname .= '/';
		}
		if ($handle = opendir($dirname)) {
			while ($file = readdir($handle)) {
				if (substr($file, 0, 1) == '.' || in_array(strtolower($file), $ignored))
					continue;
					if (is_dir($dirname . $file)) {
						$list[$file] = $file;
					}
			}
			closedir($handle);
			asort($list);
			reset($list);
		}

		return $list;
	}
}

if (!function_exists("getFileListAsArray")) {
	function getFileListAsArray($dirname, $prefix = '')
	{
		$filelist = array();
		if (substr($dirname, - 1) == '/') {
			$dirname = substr($dirname, 0, - 1);
		}
		if (is_dir($dirname) && $handle = opendir($dirname)) {
			while (false !== ($file = readdir($handle))) {
				if (! preg_match('/^[\.]{1,2}$/', $file) && is_file($dirname . '/' . $file)) {
					$file = $prefix . $file;
					$filelist[$file] = $file;
				}
			}
			closedir($handle);
			asort($filelist);
			reset($filelist);
		}

		return $filelist;
	}
}

if (!function_exists("getFontsListAsArray")) {
    /**
     * Get a font files listing for a single path no recursive
     *
     * @param string $dirname
     * @param string $prefix
     *
     * @return array
     */
    function getFontsListAsArray($dirname, $prefix = '')
    {
        $formats = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'font-converted.diz'));
        $filelist = array();
        
        if ($handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle))) {
                foreach($formats as $format)
                    if (substr(strtolower($file), strlen($file)-strlen(".".$format)) == strtolower(".".$format)) {
                        $file = $prefix . $file;
                        $filelist[$file] = array('file'=>$file, 'type'=>$format);
                    }
            }
            closedir($handle);
        }
        return $filelist;
    }
}

if (!function_exists('formatElement')) {
    
    function formatElement($element = '')
    {
        $result = '';
        for($u=0;$u<strlen($element);$u++)
        {
            if ((substr($element, $u, 1) == strtoupper(substr($element, $u, 1))) && (substr($element, $u+1, 1) != strtoupper(substr($element, $u+1, 1))))
                $result .= ' ';
            elseif ((substr($element, $u, 1) == strtoupper(substr($element, $u, 1))) && (substr($element, $u-1, 1) != strtoupper(substr($element, $u-1, 1))))
                $result .= ' ';
            elseif (is_numeric(substr($element, $u, 1)) && is_string(substr($element, $u-1, 1)) && (substr($element, $u-1, 1) != strtoupper(substr($element, $u-1, 1))))
                $result .= ' ';
            $result .= substr($element, $u, 1);
            if (is_numeric(substr($element, $u, 1)) && is_string(substr($element, $u+1, 1)) && (substr($element, $u+1, 1) != strtoupper(substr($element, $u+1, 1))))
                $result .= ' ';
        }
        $result = ucwords($result);
        return str_replace(' ', '-', $result);
    }
}

if (!function_exists('formatName')) {
    
    function formatName($element = '')
    {
        $result = '';
        for($u=0;$u<strlen($element);$u++)
        {
            if ((substr($element, $u, 1) == strtoupper(substr($element, $u, 1))) && (substr($element, $u+1, 1) != strtoupper(substr($element, $u+1, 1))))
                $result .= ' ';
            elseif ((substr($element, $u, 1) == strtoupper(substr($element, $u, 1))) && (substr($element, $u-1, 1) != strtoupper(substr($element, $u-1, 1))))
                $result .= ' ';
            elseif (is_numeric(substr($element, $u, 1)) && is_string(substr($element, $u-1, 1)) && (substr($element, $u-1, 1) != strtoupper(substr($element, $u-1, 1))))
                $result .= ' ';
            $result .= substr($element, $u, 1);
            if (is_numeric(substr($element, $u, 1)) && is_string(substr($element, $u+1, 1)) && (substr($element, $u+1, 1) != strtoupper(substr($element, $u+1, 1))))
                $result .= ' ';
        }
        return ucwords($result);
    }
}

if (!function_exists("xml2array")) {
    /**
     * Function to convert XML to Array in PHP
     *
     * @param unknown $contents
     * @param number $get_attributes
     * @param string $priority
     */
    function xml2array($contents, $get_attributes=1, $priority = 'tag') {
        if(!$contents) return array();
        
        if(!function_exists('xml_parser_create')) {
            return array();
        }
        
        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);
        
        if(!$xml_values) return;//Hmm...
        
        //Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();
        
        $current = &$xml_array; //Refference
        
        //Go through the tags.
        $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
        foreach($xml_values as $data) {
            unset($attributes,$value);//Remove existing values, or there will be trouble
            
            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data);//We could use the array by itself, but this cooler.
            
            $result = array();
            $attributes_data = array();
            
            if(isset($value)) {
                if($priority == 'tag') $result = $value;
                else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
            }
            
            //Set the attributes too.
            if(isset($attributes) and $get_attributes) {
                foreach($attributes as $attr => $val) {
                    if($priority == 'tag') $attributes_data[$attr] = $val;
                    else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }
            
            //See tag status and do the needed.
            if($type == "open") {//The starting of the tag '<tag>'
                $parent[$level-1] = &$current;
                if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    
                    $current = &$current[$tag];
                    
                } else { //There was another element with the same tag name
                    
                    if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                        $repeated_tag_index[$tag.'_'.$level]++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag.'_'.$level] = 2;
                        
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }
                        
                    }
                    $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                    $current = &$current[$tag][$last_item_index];
                }
                
            } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if(!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;
                    
                } else { //If taken, put all things inside a list(array)
                    if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...
                        
                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                        
                        if($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag.'_'.$level]++;
                        
                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag.'_'.$level] = 1;
                        if($priority == 'tag' and $get_attributes) {
                            if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                                
                                $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                                unset($current[$tag.'_attr']);
                            }
                            
                            if($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                    }
                }
                
            } elseif($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level-1];
            }
        }
        
        return($xml_array);
    }
}

if (!function_exists("putRawFile")) {
    /**
     * Saves a Raw File to the Filebase
     *
     * @param string $file
     * @param string $data
     *
     * @return boolean
     */
    function putRawFile($file = '', $data = '')
    {
        $lineBreak = "\n";
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $lineBreak = "\r\n";
        }
        if (!is_dir(dirname($file)))
            if (strpos(' '.$file, FONTS_CACHE))
                mkdirSecure(dirname($file), 0777, true);
                else
                    mkdir(dirname($file), 0777, true);
                    elseif (strpos(' '.$file, FONTS_CACHE) && !file_exists(FONTS_CACHE . DIRECTORY_SEPARATOR . '.htaccess'))
                    SaveToFile(FONTS_CACHE . DIRECTORY_SEPARATOR . '.htaccess', "<Files ~ \"^.*$\">\n\tdeny from all\n</Files>");
                    if (is_file($file))
                        unlink($file);
                        return SaveToFile($file, $data);
    }
}

if (!function_exists("getHTMLForm")) {
    /**
     * Get the HTML Forms for the API
     *
     * @param unknown_type $mode
     * @param unknown_type $clause
     * @param unknown_type $output
     * @param unknown_type $version
     *
     * @return string
     */
    function getHTMLForm($mode = '', $key = '', $data = array())
    {
        $ua = substr(sha1($_SERVER['HTTP_USER_AGENT']), mt_rand(0,32), 7);
        $form = array();
        switch ($mode)
        {
            case "uploads":
                $form[] = "<form name=\"" . $ua . "\" method=\"POST\" enctype=\"multipart/form-data\" action=\"" . OE4_URL . "/upload.php\">";
                $form[] = "\t<table class='font-uploader' id='font-uploader' style='vertical-align: top !important; min-width: 98%;'>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td colspan='3'>";
                $form[] = "\t\t\t\t<label for='".$ua."'>Font to convert:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t\t<input type='file' name='" . $ua . "' id='" . $ua ."'><br/>";
                $form[] = "\t\t\t\t<div style='margin-left:42px; font-size: 71.99%; margin-top: 7px; padding: 11px;'>";
                $form[] = "\t\t\t\t\t ~~ <strong>Maximum Upload Size Is: <em style='color:rgb(255,100,123); font-weight: bold; font-size: 132.6502%;'>" . ini_get('upload_max_filesize') . "!!!</em></strong><br/>";
                $formats = file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'font-converted.diz'); sort($formats);
                $form[] = "\t\t\t\t\t ~~ <strong>Font File Formats Supported: <em style='color:rgb(15,70 43); font-weight: bold; font-size: 81.6502%;'>*." . str_replace("\n" , "", implode(" *.", array_unique($formats))) . "</em></strong>!<br/>";
                $form[] = "\t\t\t\t</div>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td colspan='3'>";
                $form[] = "\t\t\t\t<label for='logo'>Converter's Logo:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t\t<input type='file' name='logo' id='logo'><br/>";
                $form[] = "\t\t\t\t<div style='margin-left:42px; font-size: 71.99%; margin-top: 7px; padding: 11px;'>";
                $form[] = "\t\t\t\t\t ~~ <strong>Maximum Upload Size Is: <em style='color:rgb(255,100,123); font-weight: bold; font-size: 132.6502%;'>" . ini_get('upload_max_filesize') . "!!!</em></strong><br/>";
                $formats = file(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'images-accepted.diz'); sort($formats);
                $form[] = "\t\t\t\t\t ~~ <strong>Image Formats Supported: <em style='color:rgb(15,70 43); font-weight: bold; font-size: 81.6502%;'>*." . str_replace("\n" , "", implode(" *.", array_unique($formats))) . "</em></strong>!<br/>";
                $form[] = "\t\t\t\t</div>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='name'>Converter's Name:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<input type='textbox' name='name' id='name' maxlen='198' size='41' value='".OE4_COMPANY."' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='email'>Converter's eMail:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<input type='textbox' name='email' id='email' maxlen='198' size='41' value='".OE4_EMAIL."' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 320px;'>";
                $form[] = "\t\t\t\t<label for='url'>Converter's URL:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>";
                $form[] = "\t\t\t\t<input type='textbox' name='url' id='url' maxlen='198' size='41' value='".OE4_COMPANY_URL."' /><br/>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td>&nbsp;</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
                $form[] = "\t\t\t\t<input type='hidden' name='field' value='" . (empty($ua)?'':$ua) ."'>";
                $form[] = "\t\t\t\t<input type='submit' value='Upload File' name='submit' style='padding:11px; font-size:122%;'>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
                $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t</table>";
                $form[] = "</form>";
                break;
            case "convert":
                
                $charsets = array_map('str_getcsv', file(OE4_CHARSETS_CSV));
                $licenses = json_decode(file_get_contents(OE4_LICENSES_JSON), true);
                
                $form[] = "<form name=\"" . $ua . "\" method=\"POST\" enctype=\"multipart/form-data\" action=\"" . OE4_URL . '/convert.php?key='.$key."\">";
                $form[] = "\t<table class='oe4-convert' id='oe4-convert' style='vertical-align: top !important; min-width: 98%;'>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 276px; vertical-align: top; valign: top;'>";
                $form[] = "\t\t\t\t<label for='charsets'>Character Sets:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='border-bottom: 4px solid #0a0a0a; padding-bottom: 13px;'>";
                foreach($charsets as $row => $values) {
                    $form[] = "\t\t\t\t<div style='margin: 3px; text-align: center; float: left; width: auto; padding: 7px; border-bottom: 1px dotted #010101; border-left: 2px dotted #212121;'>";
                    $form[] = "\t\t\t\t\t<input type='checkbox' name='charsets[".$values[0]."]' value='".$values[0]."'".(strpos(' '.$values[0],'utf8')?" checked='checked'":"") ."/>&nbsp;" . $values[1] . "&nbsp;(".$values[0] .")";
                    $form[] = "\t\t\t\t</div>";
                }
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td style='width: 276px; vertical-align: top; valign: top;'>";
                $form[] = "\t\t\t\t<label for='licenses'>License Font Coverage By:&nbsp;<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold'>*</font></label>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t\t<td style='padding-top: 23px;'>";
                foreach($licenses as $key => $values) {
                    $form[] = "\t\t\t\t<div style='margin: 3px; text-align: center; float: left; width: auto; padding: 7px; border-bottom: 1px dotted #010101; border-left: 2px dotted #212121;'>";
                    $form[] = "\t\t\t\t\t<input type='checkbox' name='licenses[".$key."]' value='".$key."'/>&nbsp;".$values['title'] . "&nbsp;(".$values['code'] .")";
                    $form[] = "\t\t\t\t</div>";
                }
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td colspan='3' style='padding-left:64px;'>";
                $form[] = "\t\t\t\t<input type='hidden' name='op' value='convert'>";
                $form[] = "\t\t\t\t<input type='submit' value='Convert to *.EO4' name='submit' style='padding:11px; font-size:111%;'>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t\t\t<td colspan='3' style='padding-top: 8px; padding-bottom: 14px; padding-right:35px; text-align: right;'>";
                $form[] = "\t\t\t\t<font style='color: rgb(250,0,0); font-size: 139%; font-weight: bold;'>* </font><font  style='color: rgb(10,10,10); font-size: 99%; font-weight: bold'><em style='font-size: 76%'>~ Required Field for Form Submission</em></font>";
                $form[] = "\t\t\t</td>";
                $form[] = "\t\t</tr>";
                $form[] = "\t\t<tr>";
                $form[] = "\t</table>";
                $form[] = "</form>";
                break;
        }
        return implode("\n", $form);
    }
}

if (!function_exists("mkdirSecure")) {
    /**
     * Make a folder and secure's it with .htaccess mod-rewrite with apache2
     *
     * @param string $path
     * @param integer $perm
     * @param boolean $secure
     *
     * @return boolean
     */
    function mkdirSecure($path = '', $perm = 0777, $secure = true)
    {
        if (!is_dir($path))
        {
            mkdir($path, $perm, true);
            if ($secure == true)
            {
                writeRawFile($path . DIRECTORY_SEPARATOR . '.htaccess', "<Files ~ \"^.*$\">\n\tdeny from all\n</Files>");
            }
            return true;
        }
        return false;
    }
}

if (!function_exists("cleanWhitespaces")) {
    /**
     * Clean's an array of \n, \r, \t when importing for example with file() and includes carriage returns in array
     *
     * @param array $array
     *
     * @return array
     */
    function cleanWhitespaces($array = array())
    {
        foreach($array as $key => $value)
        {
            if (is_array($value))
                $array[$key] = cleanWhitespaces($value);
                else {
                    $array[$key] = trim(str_replace(array("\n", "\r", "\t"), "", $value));
                }
        }
        return $array;
    }
}
