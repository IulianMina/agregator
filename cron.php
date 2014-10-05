<?php

$start = microtime(true);

error_reporting(E_ALL);

require 'bootstrap.php';

$RssSources = unserialize(RSS_SOURCES);

$rss = new RSSParser();
$parser = new ContentParser();

foreach ($RssSources as $RssSource) {
    $url = $RssSource['url'];
    $element = $RssSource['element'];

    $rss->setURL($url);

    $sources = $rss->get();

    foreach($sources as $source) {
        $news = array();

        $parser->setURL($source['link'], $element);
        $content = $parser->parse();

        $news['id'] = md5($source['id']);
        $news['title'] = $source['title'];
        $news['content'] = $content;
        $news['url'] = $source['link'];
        $news['source'] = '';
        $news['date'] = $source['date'];

        storeInDb($news);
    }

}

function storeInDb($news) {
    global $db;

    $stmt = 'INSERT INTO aw_blog_temp(unique_id, title, content, url, source, date)
              VALUES(:unique_id, :title, :content, :url, :source, :date)';

    $query = $db->prepare($stmt);
    $query->bindParam(':unique_id', $news['id']);
    $query->bindParam(':title', $news['title']);
    $query->bindParam(':content', $news['content']);
    $query->bindParam(':url', $news['url']);
    $query->bindParam(':source', $news['source']);
    $query->bindParam(':date', $news['date']);

    $query->execute();
}

?>


<?php
$end = microtime(true);
$execTime = ($end - $start);
printf("Page generated in %.5fs.", $execTime);

?>