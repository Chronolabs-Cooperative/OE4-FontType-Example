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
    
    foreach(getFontsListAsArray(OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key']) as $file => $values)
        if ($values['type'] == $inner['format']) {
            $fontfile = $values['file'];
            continue;
        }
    
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'footer.php';
    
    if (file_exists($file = OE4_TMP . DIRECTORY_SEPARATOR . 'oe4' . DIRECTORY_SEPARATOR . $inner['key'] . DIRECTORY_SEPARATOR . $fontfile)) {
        // Send Headers
        header('Content-Type: ' . mime_content_type($file));
        header('Content-Disposition: attachment; filename="' . $fontfile . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: ' . filesize($file). ' bytes');
        header('Cache-Control: private');
        header('Pragma: private');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        die(file_get_contents($file));
    }
    die("File Font Format Not Found: " . $inner['format']);
     
    
?>