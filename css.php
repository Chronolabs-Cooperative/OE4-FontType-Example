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
    
    $keys = json_decode(file_get_contents(constant("OE4_TMP") . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . 'keys.json'), true);
    foreach($keys as $md5 => $values)
        if ($values['key'] == $inner['key'])
            $fontname = $values['name'];
        
    if (empty($fontname))
        die("Font Key Not Found: " . $inner['key']);
    
    $fonts = getFontsListAsArray(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key']);
    foreach($fonts as $iid => $values ) { 
        if (filesize(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . DIRECTORY_SEPARATOR . $iid)==0) {
            unset($fonts[$iid]);
        }
    }
    $fontskeys = array_keys($fonts);
    header('Context-Type: text/css');
?>
@CHARSET "ISO-8859-1";

/** <?php echo $fontname; ?> */
@font-face {
    font-size:			1.0000001em;
	font-family:		"<?php echo $fontname; ?>";
	src:				url('<?php echo OE4_URL . '/v1/font/' . $inner['key'] . '.eot'; ?>') format('eot');
	src:				local('||'), <?php foreach($fonts as $iid => $values ) { ?>url('<?php echo OE4_URL . '/v1/font/' . $inner['key'] . '.' . $values['type']; ?>') format('<?php echo $values['type']; ?>')<?php echo ($fontskeys[count($fontskeys)-1] == $iid?';':',') . "\t\t\t"; ?>/* Filesize: <?php echo filesize(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . DIRECTORY_SEPARATOR . $iid); ?> bytes, md5: <?php echo md5_file(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . DIRECTORY_SEPARATOR . $iid); ?> */
						<?php } ?>
						
}

.<?php echo str_replace(' ', '', $fontname); ?> {
    font-size:			1.0000001em;
	font-family:		"<?php echo $fontname; ?>", "Trebuchet MS", Arial, Helvetica, sans-serif !important;
}
<?php 
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'footer.php';
?>