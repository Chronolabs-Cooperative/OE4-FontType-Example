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

    define('OE4_NOHTML', true);
    include_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Refresh" content="<?php echo $GLOBALS['time']; ?>; url=<?php echo $GLOBALS['url']; ?>" />
    <meta property="og:title" content="<?php echo OE4_VERSION; ?>"/>
    <meta property="og:type" content="api<?php echo OE4_TYPE; ?>"/>
    <meta property="og:image" content="<?php echo OE4_URL; ?>/assets/images/logo_500x500.png"/>
    <meta property="og:url" content="<?php echo (isset($_SERVER["HTTPS"])?"https://":"http://").$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" />
    <meta property="og:site_name" content="<?php echo OE4_VERSION; ?> - <?php echo OE4_COMPANY; ?>"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="rating" content="general" />
    <meta http-equiv="author" content="wishcraft@users.sourceforge.net" />
    <meta http-equiv="copyright" content="<?php echo OE4_COMPANY; ?> &copy; <?php echo date("Y"); ?>" />
    <meta http-equiv="generator" content="Chronolabs Cooperative (<?php echo $place['iso3']; ?>)" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo OE4_VERSION; ?> || <?php echo OE4_COMPANY; ?></title>
    
    <link rel="stylesheet" href="<?php echo OE4_URL; ?>/assets/css/style.css" type="text/css" />
    <!-- Custom Fonts -->
    <link href="<?php echo OE4_URL; ?>/assets/media/Labtop/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo OE4_URL; ?>/assets/media/Labtop Bold/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo OE4_URL; ?>/assets/media/Labtop Bold Italic/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo OE4_URL; ?>/assets/media/Labtop Italic/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo OE4_URL; ?>/assets/media/Labtop Superwide Boldish/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo OE4_URL; ?>/assets/media/Labtop Thin/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo OE4_URL; ?>/assets/media/Labtop Unicase/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo OE4_URL; ?>/assets/media/LHF Matthews Thin/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo OE4_URL; ?>/assets/media/Life BT Bold/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo OE4_URL; ?>/assets/media/Life BT Bold Italic/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo OE4_URL; ?>/assets/media/Prestige Elite/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo OE4_URL; ?>/assets/media/Prestige Elite Bold/style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo OE4_URL; ?>/assets/media/Prestige Elite Normal/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo OE4_URL; ?>/assets/css/gradients.php" type="text/css" />
    <link rel="stylesheet" href="<?php echo OE4_URL; ?>/assets/css/shadowing.php" type="text/css" />

</head>

<body>
<div class="main">
    <?php echo $GLOBALS['message']; ?>
</div>
</html>
<?php 
