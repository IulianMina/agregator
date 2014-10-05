<?php



class ContentParser {
    private $_url;
    private $_element;
    private $_parsedUrl;

    function __construct($url, $element) {
        require 'QueryPath/src/qp.php';

        $this->_url = $url;
        $this->_element = $element;

        $this->_parsedUrl = $this->parseURL($this->_url);
    }

    /**
     * Main function
     */
    function parse() {
        $file = $this->getSource($this->_url);

        $content = $this->getElementContent($this->_element, $file);
        echo $this->sanitize($content);
    }

    /**
     * Get page source
     * @param $url
     * @return string
     */
    function getSource($url) {
        $parsedUrl = $this->_parsedUrl;

        $options = array(
            "$parsedUrl[protocol]" => array(
                'method'=>"GET",
                'header'=> "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36"
            )
        );

        $context = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }

    /**
     * Get content of html element
     * @param $element
     * @param $file
     * @return mixed
     */
    function getElementContent($element, $file) {
        $dom = new DOMDocument();
        @$dom->loadHTML($file);

        return qp($dom, $element)->html();
    }

    /**
     * Sanitize html
     * @param $text
     * @return mixed|string
     */
    function sanitize($text) {
        // stip tags
        $text = strip_tags($text, '<p><span><strong><br><img>');

        // remove html attributes expect src
        $text = preg_replace("/<([a-z][a-z0-9]*)(?:[^>]*(\ssrc=['\"][^'\"]*['\"]))?[^>]*?(\/?)>/i",'<$1$2$3>', $text);

        // convert relative urls to absolute
        $text = $this->relativeToAbsoluteURL($text, $this->_parsedUrl['protocol'] . '://' . $this->_parsedUrl['domain']);

        // remove encoded extra white spaces
        $text = str_replace('&#13;', ' ', $text);

        // remove extra white spaces
        $text = preg_replace('/\s+/', ' ', $text);

        return $text;
    }

    /**
     * Convert relative URLs to aboslute
     * @param $text
     * @param $base
     * @return mixed
     */
    function relativeToAbsoluteURL($text, $base) {
        if (empty($base))
            return $text;

        // base url needs trailing /
        if (substr($base, -1, 1) != "/")
            $base .= "/";

        // Replace links
        $pattern = "/<a([^>]*) href=\"[^http|ftp|https|mailto]([^\"]*)\"/";
        $replace = "<a\${1} href=\"" . $base . "\${2}\"";
        $text = preg_replace($pattern, $replace, $text);

        // Replace images
        $pattern = "/<img([^>]*) src=\"[^http|ftp|https]([^\"]*)\"/";
        $replace = "<img\${1} src=\"" . $base . "\${2}\"";
        $text = preg_replace($pattern, $replace, $text);

        return $text;
    }

    /**
     * Parse URL and return domain and protocol
     * @param $url
     * @return array
     */
    function parseURL($url) {
        $parsedUrl = parse_url($url);

        return array(
            'protocol' => $parsedUrl['scheme'],
            'domain' => $parsedUrl['host']
        );
    }

}


$url = 'http://www.gsp.ro/fotbal/liga-1/exclusiv-au-decis-sa-ramina-in-ghencea-lucian-sinmartean-si-lukasz-szukala-au-semnat-prelungirile-cu-steaua-435778.html';
$element = '.articol_inner';

$plm = new ContentParser($url, $element);

?>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<?php $plm->parse();  ?>
</body>
</html>