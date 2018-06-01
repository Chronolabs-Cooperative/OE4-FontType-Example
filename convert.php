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

    if (isset($_REQUEST['op']) && $_REQUEST['op'] == 'convert')
        define('OE4_NOHTML', true);
    
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php';

    $fontinfo = json_decode(file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'fontinfo.json'), true);
    $files = json_decode(file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'files.json'), true);
    
    $keys = json_decode(file_get_contents(constant("OE4_TMP") . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . 'keys.json'), true);
    foreach($keys as $skeykey => $values)
        if ($values['key'] == $inner['key'])
            $keys[$skeykey]['unixtime'] = time();
    file_put_contents(constant("OE4_TMP") . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . 'keys.json', json_encode($keys));
    
    switch ($inner['op'])
    {
        default:
            if (!isset($fontinfo["oe4"]['Previews']))
                $fontinfo["oe4"]['Previews'][] = 'Quick Brown Fox Ran Over the Lazy Dog!';
                
?>
	<p>This is the font currently queued for converting to: <strong><?php echo $fontinfo['OE4']['Basename']; ?>.oe4</strong>; you can change you're licensing as well as the character set imported form the upload font and then compile the modern font file!</p>
	<h2>Font: <?php echo $fontinfo['Postscript-Font-Name']; ?></h2>
	<p>The following details are for this font conversion:~
		<ul>
			<li>Font Family: <em><?php echo $fontinfo['Family-Name']; ?></em></li>
			<li>Font Style: <em><?php echo $fontinfo['Style-Name']; ?></em></li>
			<li>Converter Name: <em><?php echo $fontinfo['OE4']['Name']; ?></em></li>
			<li>Converter eMail: <em><?php echo $fontinfo['OE4']['Email']; ?></em></li>
			<li>Converter URL: <em><?php echo $fontinfo['OE4']['Url']; ?></em></li>
			<li>Version: <em><?php echo $fontinfo['Version-Major'] . '.' . $fontinfo['Version-Minor']; ?></em></li>
		</ul>
	</p>
    <h2>Conversion Options</h2>
    <p>Use this form to finalise conversion, this is the final step, select your license(s) at least one is required and select your character set (also one is required minimal)!</p>
    <blockquote>
    	<?php echo getHTMLForm('convert', $inner['key']); ?>
    </blockquote>
    <h2>Conversion ASCII Art</h2>
    <p>The file is stamped with the ASCII Art of your logo you attached it looks like this:~</p>
    <pre style="overflow: scroll; height: 640px; font-size: 0.4232648em;">
<?php echo image2ascii($files['logo'], 61, 4); ?>
	</pre>
<?php 
            break;
        case "convert":

            $error = array();
            
            if (!isset($inner['licenses']) || count($inner['licenses'])==0)
                $error[] = 'License is not selected you have to select at least one licenses!';
            
            if (!isset($inner['charsets']) || count($inner['charsets'])==0)
                $error[] = 'Character Sets are not selected you have to select at least one character set!';
            
            if (!empty($error))
            {
                redirect(OE4_URL . '/convert.php?key='.$inner['key'], 9, "<center><h1 style='color:rgb(198,0,0);'>Error Has Occured</h1><br/><p>" . implode("<br />", $error) . "</p></center>");
                exit(0);
            }
            
            $charsetsdata = array_map('str_getcsv', file(OE4_CHARSETS_CSV));
            $licensesdata = json_decode(file_get_contents(OE4_LICENSES_JSON), true);
            
            $glyphs = json_decode(file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4' . DIRECTORY_SEPARATOR . 'glyphs.json'), true);
            $success = json_decode(file_get_contents(DIRECTORY_SEPARATOR . 'success.json'), true);
            
            $charsets = $licences = $fontdata = '';
            $charsetsarray = $chrsets = array();
            foreach($inner['charsets'] as $charset)
            {
                foreach($charsetsdata as $values)
                    if ($values[0] == $charset)
                    {
                        $chrsets[] = sprintf(OE4_FIELDING, $values[0], $values[1]);
                        $charsetsarray[$values[0]] = $values[1];
                    }
            }
            
            $charsets = OE4_SECTION_START . implode(OE4_SEPARATOR, $chrsets) . OE4_SECTION_END;
            writeRawFile(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'charsets.data', $charsets);
            foreach($inner['charsets'] as $charset)
            {
                $chatset = OE4_SECTION_START. implode(OE4_SEPARATOR, $glyphs) . OE4_SECTION_END;
                writeRawFile(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . $charset . '.charset.data', $chatset);
            }
            foreach($inner['charsets'] as $charset)
            {
                $charsetsallocations = array();
                $chatset = OE4_SECTION_START;
                $glyphfiles = getFileListAsArray($srcpath = OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4' . DIRECTORY_SEPARATOR . 'glyphs');
                $index = 0;
                foreach($glyphfiles as $glyphfile)
                {
                    $index++;
                    $chatset .= sprintf(OE4_CHAR_START, (integer)$glyphfile); 
                    $charsetsallocations[(integer)$glyphfile]['start'] = strlen($chatset);
                    $chatset .= eval(sprintf(OE4_EVAL_COMPRESS, $srcpath . DIRECTORY_SEPARATOR . $glyphfile));
                    $charsetsallocations[(integer)$glyphfile]['ended'] = strlen($chatset);
                    $charsetsallocations[(integer)$glyphfile]['length'] = $charsetsallocations[(integer)$glyphfile]['ended'] - $charsetsallocations[(integer)$glyphfile]['start'] + strlen(sprintf(OE4_CHAR_START, (integer)$glyphfile)) + strlen(OE4_CHAR_END);
                    $chatset .= OE4_CHAR_END . ($index < count($glyphfiles)?OE4_SEPARATOR:"") ;
                }
                $chatset .= OE4_SECTION_END;
                writeRawFile(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . $charset . '.glyphs.data', $chatset);
                writeRawFile(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . $charset . '.allocations.data', OE4_SECTION_START . json_encode($charsetsallocations) . OE4_SECTION_END);
            }
            $licensesbycode = $licensesallocations = array();
            $licences = OE4_SECTION_START;
            $index = 0;
            foreach($inner['licenses'] as $license)
            {
                $licensesbycode[$licensesdata[$license]['code']] = $licensesdata[$license]['code'];
                $licensesallocations[$licensesdata[$license]['code']]['start'] = strlen($licences);
                $index++;
                writeRawFile($licefile = OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . $license . '.license.data', $licensesdata[$license]);
                $licences .= sprintf(OE4_FIELDING, $licensesdata[$license]['code'], eval(sprintf(OE4_EVAL_COMPRESS, $licefile))) . ($index < count($inner['licenses'])?OE4_SEPARATOR:"");
                $licensesallocations[$licensesdata[$license]['code']]['ended'] = strlen($licences) - strlen(($index < count($inner['licenses'])?OE4_SEPARATOR:""));
                $licensesallocations[$licensesdata[$license]['code']]['length'] = strlen($licences);
            }
            sort($licensesbycode);
            $licences .= OE4_SECTION_END;
            writeRawFile($licefile = OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'licenses.data', $licences);
            writeRawFile(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'licenses.allocations.data', OE4_SECTION_START . json_encode($licensesallocations) . OE4_SECTION_END);
            $fontinfoallocations = array();
            $fntinfo = OE4_SECTION_START;
            $index = 0;
            foreach($fontinfo as $key => $values)
            {
                $fontinfoallocations[$key]['start'] = strlen($fntinfo);
                $index++;
                writeRawFile($infofile = OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . $key . '.fontinfo.data', json_encode($values));
                $fntinfo .= sprintf(OE4_FIELDING, $key, eval(sprintf(OE4_EVAL_COMPRESS, $infofile))) . ($index < count($fontinfo)?OE4_SEPARATOR:"");
                $fontinfoallocations[$key]['ended'] = strlen($fntinfo) - strlen(($index < count($fontinfo)?OE4_SEPARATOR:""));
                $fontinfoallocations[$key]['length'] = strlen($fntinfo);
            }
            $fntinfo .= OE4_SECTION_END;
            writeRawFile($licefile = OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'fontinfo.data', $fntinfo);
            writeRawFile(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'fontinfo.allocations.data', OE4_SECTION_START . json_encode($fontinfoallocations) . OE4_SECTION_END);
            writeRawFile(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'ascii.art.data', image2ascii($files['logo'], 61, 4));
            
            // Begin *.EO4 font/text output
            $template = file(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'oe4-file-template.dat');
            $output = array();
            foreach($template as $key => $value)
            {
                $value = str_replace("\r", "", str_replace("\n", "", $value));
                switch ($value)
                {
                    default:
                        $output[] = $value;
                        break;
                    case OE4_HEADER_HEADER:
                        $output[] = str_replace(OE4_HEADER_MD5, md5_file(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'ascii.art.data'), OE4_HEADER_HEADER);
                        $output[] = file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'ascii.art.data');
                        break;
                    case '%asciiart':
                        break;
                    case OE4_HEADER_CHARSETS:
                        $output[] = str_replace(OE4_HEADER_MD5, md5_file(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'charsets.data'), OE4_HEADER_CHARSETS);
                        $output[] = file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'charsets.data');
                        break;
                    case OE4_HEADER_CHARSET_ALLOCATE:
                        foreach($inner['charsets'] as $charset)
                        {
                            $output[] = str_replace('--------------------------------------------------------', str_repeat('-', strlen('--------------------------------------------------------') - (strlen($charsetsarray[$charset])) - strlen('%charset')), str_replace('%charset', $charsetsarray[$charset], str_replace(OE4_HEADER_MD5, md5_file(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . $charset . '.allocations.data'), OE4_HEADER_CHARSET_ALLOCATE)));
                            $output[] = file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . $charset . '.allocations.data');
                        }
                        break;
                    case OE4_HEADER_FONTINFO_ALLOCATE:
                        $output[] = str_replace(OE4_HEADER_MD5, md5_file(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'fontinfo.allocations.data'), OE4_HEADER_FONTINFO_ALLOCATE);
                        $output[] = file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'fontinfo.allocations.data');
                        break;
                    case OE4_HEADER_LICENSES_ALLOCATE:
                        $output[] = str_replace(OE4_HEADER_MD5, md5_file(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'licenses.allocations.data'), OE4_HEADER_LICENSES_ALLOCATE);
                        $output[] = file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'licenses.allocations.data');
                        break;
                    case OE4_HEADER_FONTINFO_DATA:
                        $output[] = str_replace(OE4_HEADER_MD5, md5_file(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'fontinfo.data'), OE4_HEADER_FONTINFO_DATA);
                        $output[] = file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'fontinfo.data');
                        break;
                    case OE4_HEADER_LICENSES_DATA:
                        $output[] = str_replace(OE4_HEADER_MD5, md5_file(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'licenses.data'), OE4_HEADER_LICENSES_DATA);
                        $output[] = file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'licenses.data');
                        break;
                    case OE4_HEADER_CHARSET_DATA:
                        foreach($inner['charsets'] as $charset)
                        {
                            $output[] = str_replace('---------------------------------------------------------------', str_repeat('-', strlen('---------------------------------------------------------------') - (strlen($charsetsarray[$charset])) - strlen('%charset')), str_replace('%charset', $charsetsarray[$charset], str_replace(OE4_HEADER_MD5, md5_file(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . $charset . '.glyphs.data'), OE4_HEADER_CHARSET_DATA)));
                            $output[] = file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . $charset . '.glyphs.data');
                        }
                        break;
                }
                
            }
            $globalallocations = array();
            $type = '';
            $end = $start = $datastart = $dataend = $pos = $length = 0;
            foreach($output as $key => $value)
            {
                if ($type != '')
                {
                    $datastart = $length + 1;
                    $dataend = $length + strlen($value) + 1;
                    $globalallocations[$type]['start'] = $start;
                    $globalallocations[$type]['end'] = $start;
                    $globalallocations[$type]['data']['start'] = $datastart;
                    $globalallocations[$type]['data']['end'] = $dataend;
                    $globalallocations[$type]['data']['length'] = $dataend - $datastart;
                    $type = '';
                }
                if (strpos(' ' . $value, '--[') && $value != '+------------------------------------------------------------------------[ Chronolabs Cooperative + Undo Corporation ]-+')
                {
                    $type = trim(substr($value, 3, strpos($value, "]", 3) - 3));
                    $start = $length;
                    $end = $start + strlen($value) + 1;
                }
                $length = $length + strlen($value) + 1;
            }
            foreach($output as $key => $value)
            {
                if ($value == OE4_HEADER_GLOBAL)
                {
                    $output[$key] = str_replace(OE4_HEADER_MD5, md5(OE4_SECTION_START . json_encode($globalallocations) . OE4_SECTION_END), OE4_HEADER_GLOBAL);
                } elseif (substr($value,0,strlen('%globalallocation')) == '%globalallocation')
                {
                    $output[$key] = OE4_SECTION_START . json_encode($globalallocations) . OE4_SECTION_END;
                } elseif (strpos($value, '%font')) {
                    $output[$key] = str_replace('%font' . str_repeat(' ', strlen($fontinfo['Family-Name']) - 5), $fontinfo['Family-Name'], $value);
                } elseif (strpos($value, '%vr')) {
                    $output[$key] = str_replace('%vr' . str_repeat(' ', strlen($fontinfo['Version-Major'] . '.' . $fontinfo['Version-Minor']) - 3), $fontinfo['Version-Major'] . '.' . $fontinfo['Version-Minor'], $value);
                } elseif (strpos($value, '%lics')) {
                    $output[$key] = str_replace('%lics' . str_repeat(' ', strlen(implode(", ", $licensesbycode)) - 5), implode(", ", $licensesbycode), $value);
                } elseif (strpos($value, '%name')) {
                    $output[$key] = str_replace('%name' . str_repeat(' ', strlen($fontinfo['OE4']['Name']) - 5), $fontinfo['OE4']['Name'], $value);
                } elseif (strpos($value, '%url')) {
                    $output[$key] = str_replace('%url' . str_repeat(' ', strlen($fontinfo['OE4']['Url']) - 4), $fontinfo['OE4']['Url'], $value);
                } elseif (strpos($value, '%email')) {
                    $output[$key] = str_replace('%email' . str_repeat(' ', strlen($fontinfo['OE4']['Email']) - 6), $fontinfo['OE4']['Email'], $value);
                } elseif (strpos($value, 'yyyy/mn/dd hh:mm:ii')) {
                    $output[$key] = str_replace('[ yyyy/mn/dd hh:mm:ii ]', '[ '. date('Y/m/d H:i:s') . ' ]' . str_repeat('-', strlen('[ yyyy/mn/dd hh:mm:ii ]') - strlen('[ '. date('Y/m/d H:i:s') . ' ]')), $value);
                }
            }

            header('Content-Type: font/text+oe4');
            header('Content-Disposition: attachment; filename="' . $fontinfo['Family-Name'] . '.oe4"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: '.strlen(implode("\n", $output)). ' bytes');
            header('Cache-Control: private');
            header('Pragma: private');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            
            die(implode("\n", $output)); 
    }
?>
<?php 
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'footer.php';
?>