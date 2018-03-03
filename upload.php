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

    global $inner, $odds;
    define('OE4_NOHTML', true);
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php';
    
	error_reporting(0);
	ini_set('display_errors', false);
	set_time_limit(3600*36*9*14*28);
	
	//echo "Processed Upload Form Fine<br/>";
	$time = time();
	$error = array();
	if (isset($inner['field']) || !empty($inner['field'])) {
		if (empty($_FILES[$inner['field']]))
			$error[] = 'No file uploaded in the correct field name of: "' . $inner['field'] . '"';
		elseif (empty($_FILES['logo']))
			$error[] = 'No file uploaded in the correct field name of: "logo"';
		else {
		    $formats = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'include'  . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'font-converted.diz')); 
			$pass = false;
			foreach($formats as $xtension)
			{
				if (strtolower(substr($_FILES[$inner['field']]['name'], strlen($_FILES[$inner['field']]['name'])- strlen($xtension))) == strtolower($xtension))
					if (in_array($xtension, $formats)) {
					    $filetype = $xtension;
						$pass = true;
						continue;
					}
			}
			if ($pass == false)
				$error[] = 'The file extension type of <strong>'.$_FILES[$inner['field']]['name'].'</strong> is not valid you can only upload the following file types: <em>'.implode("</em>&nbsp;<em>*.", $formats).'</em>!';

			$images = cleanWhitespaces(file(__DIR__ . DIRECTORY_SEPARATOR . 'include'  . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'images-accepted.diz'));
		    $pass = false;
			foreach($images as $xtension)
			{
			    if (strtolower(substr($_FILES['logo']['name'], strlen($_FILES['logo']['name'])- strlen($xtension))) == strtolower($xtension))
			        if (in_array($xtension, $images)) {
			            $imagetype = $xtension;
			            $pass = true;
			            continue;
			         }
			}
			if ($pass == false)
			    $error[] = 'The file extension type of <strong>'.$_FILES['logo']['name'].'</strong> is not valid you can only upload the following file types: <em>'.implode("</em>&nbsp;<em>*.", $images).'</em>!';
        }
    }
	
	if (isset($inner['email']) || !empty($inner['email'])) {
		if (!checkEmail($inner['email']))
			$error[] = 'Converter\'s organisation Email Address is invalid!';
	} else
		$error[] = 'No Converter\'s organisation Email Address for Notification specified!';
	
	if (!isset($inner['name']) || empty($inner['name'])) {
		$error[] = 'No Converter\'s individual or organisation name not specified in survey scope when selected!';
	}
	
	if (!isset($inner['url']) || empty($inner['url'])) {
	    $error[] = 'No Converter\'s organisation URL not specified in survey scope when selected!';
	}
	
	$uploadpath = constant("OE4_TMP") . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . ($key = md5($inner['email'] . DIRECTORY_SEPARATOR . microtime(true)));
	if (!is_dir($uploadpath)) {
		if (!mkdir($uploadpath, 0777, true)) {
			$error[] = 'Unable to make path: '.$uploadpath;
		}
	}
	
	$oe4path = constant("OE4_TMP") . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . ($key . '.oe4');
	if (!is_dir($oe4path)) {
	    if (!mkdir($oe4path, 0777, true)) {
	        $error[] = 'Unable to make path: ' . $oe4path;
	    }
	}
	//echo "Checked for Errors Fine<br/>";
	if (!empty($error))
	{
		redirect(isset($inner['return'])&&!empty($inner['return'])?$inner['return']:'http://'. $_SERVER["HTTP_HOST"], 9, "<center><h1 style='color:rgb(198,0,0);'>Error Has Occured</h1><br/><p>" . implode("<br />", $error) . "</p></center>");
		exit(0);
	}
	
	$keys = json_decode(file_get_contents(constant("OE4_TMP") . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . 'keys.json'), true);
	$keys[$keyskey = md5(microtime(true))]['key'] = $key;
	
	$file = $uploader = $success = array();
	
	if (!move_uploaded_file($_FILES[$inner['field']]['tmp_name'], $file['font'] = $uploadpath . DIRECTORY_SEPARATOR . ($uploader['font'] = $_FILES[$inner['field']]['name']))) {
		redirect(isset($inner['return'])&&!empty($inner['return'])?$inner['return']:'http://'. $_SERVER["HTTP_HOST"], 9, "<center><h1 style='color:rgb(198,0,0);'>Uploading Error Has Occured</h1><br/><p>Fonts API was unable to recieve and store: <strong>".$_FILES[$inner['field']]['name']."</strong>!</p></center>");
		exit(0);
	} else 
	    $success['font'] = array('unixtime' => time(), 'file' => $_FILES[$inner['field']]['name'], 'mime-type' => $_FILES[$inner['field']]['media_type']);
	
    if (!move_uploaded_file($_FILES['logo']['tmp_name'], $file['logo'] = $uploadpath . DIRECTORY_SEPARATOR . ($uploader['logo'] = $_FILES['logo']['name']))) {
	    redirect(isset($inner['return'])&&!empty($inner['return'])?$inner['return']:'http://'. $_SERVER["HTTP_HOST"], 9, "<center><h1 style='color:rgb(198,0,0);'>Uploading Error Has Occured</h1><br/><p>Fonts API was unable to recieve and store: <strong>".$_FILES[$inner['field']]['name']."</strong>!</p></center>");
	    exit(0);
	} else
	    $success['logo'] = array('unixtime' => time(), 'file' => $_FILES['logo']['name'], 'mime-type' => $_FILES['logo']['media_type']);
	    
	if (!empty($error))
	{
		redirect(isset($inner['return'])&&!empty($inner['return'])?$inner['return']:'http://'. $_SERVER["HTTP_HOST"], 9, "<center><h1 style='color:rgb(198,0,0);'>Error Has Occured</h1><br/><p>" . implode("<br />", $error) . "</p></center>");
		exit(0);
	}
	
	@exec("cd " . $uploadpath, $out, $return);
	@exec($exe = sprintf(OE4_FONTFORGE . " -script \"%s\" \"%s\"", __DIR__  . DIRECTORY_SEPARATOR . "include"  . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "convert-fonts-ufo.pe", $file['font']), $out, $return);

	$parts = explode('.', basename($file['font']));
	unset($parts[count($parts)-1]);
	$fill = implode('.', $parts);
	
	$fontdata = array();
	if (file_exists($fontinfo = $uploadpath . DIRECTORY_SEPARATOR . $fill . '.ufo' . DIRECTORY_SEPARATOR . 'fontinfo.plist')) {
	   //$fontdata = xml2array(file_get_contents($fontinfo), true, 'key');
	    $fontvalues = xml2array($xml = file_get_contents($fontinfo));
	    $fontdata['EO4']['basename'] = $fill;
	    $fontdata['EO4']['name'] = $inner['name'];
	    $fontdata['EO4']['email'] = $inner['email'];
	    $fontdata['EO4']['url'] = $inner['url'];
	    $fontdata['EO4']['logo-image']['mime-type'] = $success['mime-type'];
	    $fontdata['EO4']['logo-image']['encoding'] = 'base64';
	    $fontdata['EO4']['logo-image']['image'] = base64_encode(file_get_contents($file['logo']));
	    foreach($fontvalues['plist']['dict']['key'] as $id => $fieldkey)
	    {
	        if ($ipos = strpos($xml, $needle = "    <key>$fieldkey</key>\n    "))
	        {
	            if ($epos = strpos($xml, $eneedle = "\n", $ipos + strlen($needle) + 1))
	            {
	                $scrape = substr($xml, $ipos + strlen($needle), ($epos - ($ipos + strlen($needle) + 1) + 1));
	                if (strpos($scrape, 'string'))
	                    $fontdata[formatElement($fieldkey)] = str_replace(array("<string>", "</string>"), '', $scrape);
                    elseif (strpos($scrape, 'integer'))
	                    $fontdata[formatElement($fieldkey)] = str_replace(array("<integer>", "</integer>"), '', $scrape);
                    elseif (strpos($scrape, 'real'))
	                    $fontdata[formatElement($fieldkey)] = str_replace(array("<real>", "</real>"), '', $scrape);
                    elseif (strpos($scrape, 'true'))
	                    $fontdata[formatElement($fieldkey)] = true;
                    elseif (strpos($scrape, 'false'))
	                    $fontdata[formatElement($fieldkey)] = false;
                    elseif (strpos($scrape, 'array/'))
	                    $fontdata[formatElement($fieldkey)] = array();
                    elseif (strpos($scrape, 'array')) {
                        if ($apos = strpos($xml, $aneedle = "\n    </array>\n", $epos + strlen($scrape) + 1))
                        {
                            $arrayxml = substr($xml, $epos + strlen($scrape), $apos - ($epos + strlen($scrape)));
                            $arrayxml = str_replace(array('\t', '        ', '    ', '<string>', '</string>', '<integer>', '</integer>', '<real>', '</real>'), '', $arrayxml);
                            foreach(explode("\n", $arrayxml) as $valuexml)
                                $fontdata[formatElement($fieldkey)][] = trim($valuexml);
                        }
	                }
	                switch(formatElement($fieldkey))
	                {
	                    case 'Postscript-Font-Name':
	                        $keys[$keyskey]['unixtime'] = time();
	                        $keys[$keyskey]['name'] = trim(formatName($fontdata[formatElement($fieldkey)]));
	                        writeRawFile(constant("OE4_TMP") . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . 'keys.json', json_encode($keys));
	                        
	                    case 'Family-Name':
	                    case 'Style-Name':
	                    case 'Style-Map-Family-Name':
	                    case 'Style-Map-Style-Name':
	                    case 'Postscript-Full-Name':
	                        $fontdata[formatElement($fieldkey)] = trim(formatName($fontdata[formatElement($fieldkey)]));
	                        break;
	                }
	                if (formatElement($fieldkey) == 'Cap-Height') {
	                    $fontdata['Cap-Depth'] = false;
	                    $fontdata['Cap-Scale'] = '0.0';
	                }
	                if (formatElement($fieldkey) == 'X-Height') {
	                    $fontdata['Z-Depth'] = false;
	                }
	                if (formatElement($fieldkey) == 'Descender') {
	                    $fontdata['Depther'] = false;
	                    $fontdata['Scaler'] = '1.0';
	                }
	                if (formatElement($fieldkey) == 'Open-Type-Hhea-Descender') {
	                    $fontdata['Open-Type-Hhea-Depther'] = false;
	                    $fontdata['Open-Type-Hhea-Scaler'] = '0.0';
	                }
	                if (formatElement($fieldkey) == 'Open-Type-OS2-Typo-Descender') {
	                    $fontdata['Open-Type-OS2-Typo-Depther'] = false;
	                    $fontdata['Open-Type-OS2-Typo-Scaler'] = '0.0';
	                }
	                if (formatElement($fieldkey) == 'Open-Type-OS2-Win-Descent') {
	                    $fontdata['Open-Type-OS2-Win-Depth'] = false;
	                    $fontdata['Open-Type-OS2-Win-Scale'] = '0.0';
	                }
	                if (formatElement($fieldkey) == 'Open-Type-OS2-Subscript-Y-Size') {
	                    $fontdata['Open-Type-OS2-Subscript-Z-Size'] = false;
	                    $fontdata['Open-Type-OS2-Subscript-D-Size'] = false;
	                }
	                if (formatElement($fieldkey) == 'Open-Type-OS2-Subscript-Y-Offset') {
	                    $fontdata['Open-Type-OS2-Subscript-Z-Offset'] = false;
	                    $fontdata['Open-Type-OS2-Subscript-D-Offset'] = false;
	                }
	                if (formatElement($fieldkey) == 'Open-Type-OS2-Superscript-Y-Size') {
	                    $fontdata['Open-Type-OS2-Superscript-Z-Size'] = false;
	                    $fontdata['Open-Type-OS2-Superscript-D-Size'] = false;
	                }
	                if (formatElement($fieldkey) == 'Open-Type-OS2-Superscript-Y-Offset') {
	                    $fontdata['Open-Type-OS2-Superscript-Z-Offset'] = false;
	                    $fontdata['Open-Type-OS2-Superscript-D-Offset'] = false;
	                }
	                if (formatElement($fieldkey) == 'Postscript-Stem-Snap-V') {
	                    $fontdata['Postscript-Stem-Snap-D'] = array();
	                }
	                
	            }
	        }
	    }
	    
	    if (!is_dir($glyphpath = $oe4path . DIRECTORY_SEPARATOR . 'glyphs'))
	        if (!mkdir($glyphpath, 0777, true)) {
	            $error[] = 'Unable to make path: ' . $glyphpath;
	        }
	    $characters = array();
	    foreach(getFileListAsArray($glyphsrc = $uploadpath . DIRECTORY_SEPARATOR . $fill . '.ufo' . DIRECTORY_SEPARATOR . 'glyphs') as $glyphfile)
	    {
	        $glyph = array();
	        $glyphvalues = json_decode(json_encode(new SimpleXMLElement($xml = file_get_contents($glyphsrc . DIRECTORY_SEPARATOR . $glyphfile))), true);
	        foreach($glyphvalues as $gkey => $gvalues)
	        {
	            switch ($gkey)
	            {
	                case '@attributes':
	                    foreach($gvalues as $fkey => $fvalue)
	                    {
	                        $glyph[formatElement($fkey)] = $fvalue;
	                    }
	                    break;
	                case 'advance':
	                    foreach($gvalues['@attributes'] as $fkey => $fvalue)
	                    {
	                        $glyph['advance'][formatElement($fkey)] = $fvalue;
	                        if (formatElement($fkey)=='Hex')
	                            $characters[$hex = $fvalue] = $hex;
	                    }
	                    unset($gvalues['@attributes']);
	                    foreach($gvalues as $fkey => $fvalue)
	                    {
	                        $glyph['advance'][formatElement($fkey)] = $fvalue;
	                    }
	                    $glyph['advance'][formatElement('depth')] = false;
	                    break;
	                case 'unicode':
	                    foreach($gvalues['@attributes'] as $fkey => $fvalue)
	                    {
	                        $glyph['unicode'][formatElement($fkey)] = $fvalue;
	                    }
	                    unset($gvalues['@attributes']);
	                    foreach($gvalues as $fkey => $fvalue)
	                    {
	                        $glyph['unicode'][formatElement($fkey)] = $fvalue;
	                    }
	                    break;
	                case 'outline':
	                    foreach($gvalues['contour'] as $ckey => $cvalue)
	                    {
	                        foreach($cvalue['point'] as $pkey => $pvalue)
	                        {
	                            foreach($pvalue['@attributes'] as $fkey => $fvalue)
	                            {
	                                $glyph['contour'][$ckey]['point'][$pkey][formatElement($fkey)] = $fvalue;
	                                if (formatElement($fkey) == 'Y') {
	                                    $glyph['contour'][$ckey]['point'][$pkey]['Z'] = false;
	                                    $glyph['contour'][$ckey]['point'][$pkey]['D'] = false;
	                                }
	                            }
	                            unset($pvalue['@attributes']);
	                            foreach($pvalue as $fkey => $fvalue)
	                            {
	                                $glyph['contour'][$ckey]['point'][$pkey][formatElement($fkey)] = $fvalue;
	                            }
	                        }
	                    }
	                    break;
	                case 'lib':
	                    foreach($gvalues['dict'] as $ckey => $cvalue)
	                    {
	                        switch($ckey)
	                        {
	                            case "key":
	                                $glyph['library'][formatElement($ckey)] = $cvalue;
	                                break;
	                            case "dict":
	                                foreach($cvalue['dict'] as $pkey => $pvalue)
	                                {
	                                    switch($ckey)
	                                    {
	                                        case "key":
	                                            $glyph['library']['dictionary'][formatElement($ckey)][formatElement($pkey)] = $pvalue;
	                                            $glyph['library']['dictionary'][formatElement($ckey)][formatElement($pkey)][] = 'dhints'; 
	                                            break;
	                                        case "array":                                     
        	                                    foreach($pvalue['array'] as $fkey => $fvalue)
        	                                    {
        	                                        foreach($fvalue['dict'] as $dkey => $dvalue)
        	                                        {
        	                                            $dvalue['key'][] = 'depth';
        	                                            $dvalue['key'][] = 'scale';
        	                                            $dvalue['integer'][] = '0';
        	                                            $dvalue['integer'][] = '0.0';
        	                                            $glyph['library']['dictionary'][formatElement($ckey)][formatElement($pkey)][formatElement($fkey)]['dictionary'][formatElement($dkey)] = $dvalue;
        	                                        }
        	                                    }
        	                                    
        	                             }
	                                 }
	                              
	                        }
	                        
	                    }
	                    writeRawFile($glyphpath . DIRECTORY_SEPARATOR . $hex . '.json', json_encode($glyph));
	                    
	                    break;
	            }
	        }
	    }
	    writeRawFile($oe4path . DIRECTORY_SEPARATOR . 'files.json', json_encode($file));
	    writeRawFile($oe4path . DIRECTORY_SEPARATOR . 'success.json', json_encode($success));
	    writeRawFile($oe4path . DIRECTORY_SEPARATOR . 'glyphs.json', json_encode($characters));
	    writeRawFile($oe4path . DIRECTORY_SEPARATOR . 'fontinfo.json', json_encode($fontdata));
	} else 
	    die("File Not Found: $fontinfo");
	
	redirect(isset($inner['return'])&&!empty($inner['return'])?$inner['return']:OE4_URL . '/convert.php?key='.$key, 18, "<center><h1 style='color:rgb(0,198,0);'>Uploading Partially or Completely Successful</h1><br/><div>The following files where uploaded and queued for conversion on the API Successfully:</div><div style='height: auto; clear: both; width: 100%;'><ul style='height: auto; clear: both; width: 100%;'><li style='width: 24%; float: left;'>".implode("</li><li style='width: 24%; float: left;'>", $uploader)."</li></ul></div><br/><div style='clear: both; height: 11px; width: 100%'>&nbsp;</div><p>You will now have to select the conversion options, like licenses, character sets and so on, as well as preview text!</p></center>");
	exit(0);
	
?>