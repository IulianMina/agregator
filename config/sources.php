<?php

$sources = [
    array(
        'url' => 'http://www.gsp.ro/rss.xml',
        'element' => '.articol_inner'
    ),
//    array(
//        'url' => 'http://rss.cnn.com/rss/edition.rss',
//        'element' => '.cnn_strycntntlft'
//    )
];

define('RSS_SOURCES', serialize($sources));




?>