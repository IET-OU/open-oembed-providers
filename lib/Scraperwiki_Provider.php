<?php namespace IET_OU\Open_Oembed_Providers;

/**
 * ScraperWiki oEmbed service provider.
 *
 * @copyright Copyright 2013 The Open University.
 * @author N.D.Freear, 2013-01-16.
 *
 * @link https://scraperwiki.com/views/scraperwiki_oembed_v1
 * @link https://gist.github.com/4556849
 */

use \IET_OU\Open_Media_Player\External_Oembed_Provider;

class Scraperwiki_Provider extends External_Oembed_Provider
{
#class Scraperwiki_Provider extends Generic_Iframe_Oembed_Provider {

    public $regex = 'https://views.scraperwiki.com/run/*';
      #array( , 'https://scraperwiki.com/views/*', )
    public $about = <<<EOT
  Write code that gets data. [Initially developed for OLDSMOOC. Public access.]
EOT;
    public $displayname = 'ScraperWiki';
    public $domain = 'views.scraperwiki.com';
    public $subdomains = array();
    public $favicon = 'https://scraperwiki.com/media/images/favicon.ico';
    public $type = 'rich';

    public $_about_url = 'https://scraperwiki.com/about/';
    public $_logo_url = 'https://media.scraperwiki.com/images/nav_logo.gif?576eac3ccaa6';
    public $_examples = array(
    'Cloudworks Mindmap, by Psychemedia'
        => 'https://views.scraperwiki.com/run/cloudworks_mindmap',
    'https://scraperwiki.com/views/cloudworks_mindmap',
    );

    protected $_endpoint_url
      = 'https://views.scraperwiki.com/run/scraperwiki_oembed_v1/';

    protected $_terms_url = 'https://scraperwiki.com/terms_and_conditions#!License-AGPL';
    protected $_test_url = 'http://dl.dropbox.com/u/3203144/ouplayer/wall-map.html';


    protected function _UNUSED_call($url, $matches)
    {
      // Generate the embed URL from the input URL.
        $embed_url = $url;
        $embed_url .= contains($url, '?') ? '&' : '?';
      #$embed_url .= 'format=embed&_for=' . filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);

        $meta = $this->getIframeResponse($url);

        $meta->title = str_replace('_', ' ', ucfirst($matches[1]));
        $meta->embed_url = $embed_url;

        return $meta;
    }
}
