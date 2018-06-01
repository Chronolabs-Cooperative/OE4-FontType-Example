## Chronolabs Cooperative presents
# OE4 Font File Type Generator Example  
## Version: 1.0.2 (stable)
## by. Dr. Simon Antony Roberts (Sydney) <fontmasters@snails.email>
### The font file type OE4 File Format Generation Example

# Foreword

The font file type OE4 File Format Generation Example is a group of PHP and control data files that allows for a generation of an *.EO4 Font file which supports multiple character sets as well as character types as well as: 2D, 3D, Holography & Braille!

This example of files will guide you in reading as well as utilising this font format so you can have a congruent workflow in text and the displaying of text on your websites!

## Environmental Settings/Configurations

You need to install the following on Ubuntu/Debian

    $ apt-get install php-xml fontforge
    $ service apache2 restart

## Mod Rewrite (Short URLs)

You need to put this in the root of the projects .htaccess file

    php_value memory_limit 32M
    php_value upload_max_filesize 41M
    php_value post_max_size 69M
    php_value error_reporting 0
    php_value display_errors 0
    
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    RewriteRule ^([a-z0-9]{2})/css/(.*?).css$ ./css.php?version=$1&key=$2 [L,NC,QSA]
    RewriteRule ^([a-z0-9]{2})/font/(.*?).(pfa|pfb|pt3|t42|sfd|ttf|bdf|otf|otb|cff|cef|gai|woff|svg|ufo|pf3|ttc|gsf|cid|bin|hqx|dfont|mf|ik|fon|fnt|pcf|pmf|pdb|eot|afm)$ ./font.php?version=$1&key=$2&format=$3 [L,NC,QSA]