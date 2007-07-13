<?php

// Some nice to have regexps
define('EMAIL_FORMAT', "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i");
define('URL_FORMAT', "/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}((:[0-9]{1,5})?\/.*)?$/i");

// Some date format
define('DATE_MYSQL', 'Y-m-d H:i:s');
define('EMPTY_DATETIME', '0000-00-00 00:00:00');

// PHP predefined constants
// DIRECTORY_SEPARATOR  = /

// DATE_ATOM            = Y-m-d\TH:i:sP
// DATE_COOKIE          = D, d M Y H:i:s T
// DATE_ISO8601         = Y-m-d\TH:i:sO
// DATE_RFC822          = D, d M Y H:i:s T
// DATE_RFC850          = l, d-M-y H:i:s T
// DATE_RFC1036         = l, d-M-y H:i:s T
// DATE_RFC1123         = D, d M Y H:i:s T
// DATE_RFC2822         = D, d M Y H:i:s O
// DATE_RFC3339         = Y-m-d\TH:i:sP
// DATE_RSS             = D, d M Y H:i:s T
// DATE_W3C             = Y-m-d\TH:i:sP