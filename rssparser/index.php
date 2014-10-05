<?php

error_reporting(E_ALL);

include_once('autoloader.php');
include_once('idn/idna_convert.class.php');

$feed = new SimplePie();

$feed->set_feed_url('http://rss.cnn.com/rss/edition.rss');
//$feed->set_useragent('Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53');

$success = $feed->init();

$feed->handle_content_type();

if ($success) {
    foreach ($feed->get_items() as $item) {
        echo 'id: ' . $item->get_id() . '<br />';
        echo '<b>' . $item->get_title() . '</b><br />';
        echo '<a href="' . $item->get_permalink() . '">' . $item->get_permalink() . '</a><br />';
        echo $item->get_content() . '<br />';
        echo '<hr />';
    }
} else {
    echo 'a aparut o eroare';
}

?>