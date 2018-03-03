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

require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php';

?>
	<p>This PHP is a code library to show how *.EO4 Font files are generated as a binary file, you will be able to digress from this example the full functions of the both 2d, 3d, holographic and braille support of this font format!</p>
	<p>Font Formats haven't been addressed for a long time, this is a modern font format that encompasses older formats with the dynamic library set of a modern convience and formats!</p>
    <h2>Upload & Convert a Font File</h2>
    <p>Use this form to commence conversion, this is 2 step, upload then select the conversion options and then process to be output a *.eo4 font file!</p>
    <blockquote>
    	<?php echo getHTMLForm('uploads'); ?>
    </blockquote>
<?php 
require_once __DIR__ . DIRECTORY_SEPARATOR . 'footer.php';
?>