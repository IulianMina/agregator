<?php

class RSSParser {
    private $_feed;

    public function __construct() {
        require_once('autoloader.php');
        require_once('idn/idna_convert.class.php');

        error_reporting(0);

        $this->_feed = new SimplePie();
    }

    function setURL($url) {
        $this->_feed->set_feed_url($url);
    }

    function get() {

        //$feed->set_useragent('Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53');

        $success = $this->_feed->init();

        $this->_feed->handle_content_type();

        $results = null;

        if ($success) {
            foreach ($this->_feed->get_items() as $item) {
                $result = array();
                $result['id'] = $item->get_id();
                $result['title'] = $item->get_title();
                $result['link'] = $item->get_permalink();
                $result['content'] = $item->get_content();
                $result['date'] = $item->get_date('Y-m-d H:i:s');

                $results[] = $result;
            }
            return $results;
        } else {
            throw new Exception('RSS parse error.');
        }
    }

}
?>