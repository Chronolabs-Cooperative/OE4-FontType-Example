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


    // Converter Defaults
    define('OE4_FONTFORGE', '/usr/bin/fontforge');
    define('OE4_TMP', dirname(__DIR__) . '/data');
    define('OE4_CHARSETS', __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'charactersets.csv');
    define('OE4_CONVERTPE', __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'convert-fonts-ufo.pe');
    define('OE4_EVAL_COMPRESS', 'return gzcompress("%s", 9);');
    
    // Converted Field Seperators
    define('OE4_SEPARATOR', '||');
    define('OE4_FIELDING', '%s::%s');
    define('OE4_SECTION_START', '|'.NULL.'|'.NULL.'|');
    define('OE4_SECTION_END', NULL.'|||'.NULL);
    define('OE4_LENGTH_START', '=|=');
    define('OE4_LENGTH_END', '='.NULL.'=');
    define('OE4_CHAR_START', '|:%s:|');
    define('OE4_CHAR_END', '|'.NULL.NULL.'|');
    
    // Convert Chronologistics Defaults
    define('OE4_DELETE_WHEN', (3600 * 2));
    define('OE4_DELETE_WARNING', (27 * 60));
    
    // EO4 File Headers
    define('OE4_HEADER_MD5',                '%MD55678901234567890123456789012');
    define('OE4_HEADER_GLOBAL',             '--[ Global Byte Allocation ]------------------------------------------------------[ %MD55678901234567890123456789012 ]--');
    define('OE4_HEADER_HEADER',             '--[ Header ]----------------------------------------------------------------------[ %MD55678901234567890123456789012 ]--');
    define('OE4_HEADER_CHARSETS',           '--[ Character Sets ]--------------------------------------------------------------[ %MD55678901234567890123456789012 ]--');
    define('OE4_HEADER_CHARSET_ALLOCATE',   '--[ %charset Allocations ]--------------------------------------------------------[ %MD55678901234567890123456789012 ]--');
    define('OE4_HEADER_FONTINFO_ALLOCATE',  '--[ FontInfo Allocations ]--------------------------------------------------------[ %MD55678901234567890123456789012 ]--');
    define('OE4_HEADER_LICENSES_ALLOCATE',  '--[ Licenses Allocations ]--------------------------------------------------------[ %MD55678901234567890123456789012 ]--');
    define('OE4_HEADER_FONTINFO_DATA',      '--[ FontInfo Data ]---------------------------------------------------------------[ %MD55678901234567890123456789012 ]--');
    define('OE4_HEADER_LICENSES_DATA',      '--[ Licenses Data ]---------------------------------------------------------------[ %MD55678901234567890123456789012 ]--');
    define('OE4_HEADER_CHARSET_DATA',       '--[ %charset Data ]---------------------------------------------------------------[ %MD55678901234567890123456789012 ]--');
?>