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
    
    $glyphs = json_decode(file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4' . DIRECTORY_SEPARATOR . 'glyphs.json'), true);
    $files = json_decode(file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'files.json'), true);
    $success = json_decode(file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'success.json'), true);
    $fontinfo = json_decode(file_get_contents(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . '.oe4'  . DIRECTORY_SEPARATOR . 'fontinfo.json'), true);
    
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
    	<?php echo getHTMLForm('convert', $inner['key'], $fontinfo); ?>
    </blockquote>
    <h2>Conversion ASCII Art</h2>
    <p>The file is stamped with the ASCII Art of your logo you attached it looks like this:~</p>
    <pre style="overflow: scroll; height: 640px; font-size: 0.4232648em;">
<?php echo image2ascii($files['logo'], 61, 4); ?>
	</pre>
<?php 
            break;
        case "convert":
            break;
            
    }
?>
<?php 
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'footer.php';
?>