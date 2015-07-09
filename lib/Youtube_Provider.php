<?php namespace IET_OU\Open_Oembed_Providers;

/**
 * YouTube/HTML5 oEmbed service provider.
 *
 * @copyright Copyright 2012 The Open University.
 */

use \IET_OU\Open_Media_Player\Oembed_Provider;
use \IET_OU\Open_Media_Player\Oembed_Local_Embed_Interface;


// TODO: move to separate file!
class Youtube_Player extends \IET_OU\Open_Media_Player\Base_Player
{
}


class Youtube_Provider extends Oembed_Provider implements Oembed_Local_Embed_Interface
{

    public $regex = array('http://*youtube.com/watch*', 'http://youtu.be/*');
    public $about = <<<EOT
  YouTube is the world's most popular online video community, allowing millions of people to discover, watch and share originally-created videos.
  Embed videos from YouTube with a HTML5-video option. [Initially for Cloudworks/OULDI. Public access.]
EOT;
    public $displayname = 'YouTube';
    #public $name = 'youtube';
    public $domain = 'youtube.com';
    public $subdomains = array('m.youtube.com');
    public $favicon = 'http://youtube.com/favicon.ico';
    public $type = 'video';

    public $_about_url = 'http://youtube.com/';
    public $_regex_real = '(youtu\.be\/|youtube\.com\/watch\?.*v=)(?P<id>[\w\-_]+)&*.*';
    public $_examples = array(
    'Vice-Chancellor Peter Horrocks' => 'http://youtu.be/EQEy5_QE2tQ',
    'Interview with Martin Bean (captions)' => 'http://youtube.com/watch?v=NaBBk-kpmL4',
    'http://youtu.be/NaBBk-kpmL4',
    'Brian McAllister, Roadtrip Nation (OLnet)' => 'http://youtube.com/watch?v=VesKht_8HCo',
    '_OEM'=>'/oembed?url=http%3A//youtu.be/NaBBk-kpmL4',
    );
    public $_access = 'public';

    const API_URL =
    'https://www.googleapis.com/youtube/v3/videos?part=liveStreamingDetails,id,player,status,contentDetails,snippet&id=%s&key=%s';

    /**
    * Implementation of call().
    * @return object
    */
    public function call($url, $matches)
    {
        $video_id = $matches[ 'id' ]; #1
        $theme = $this->param_get('theme');
        if ('oup-light' == $theme) {
            $embed_url = site_url(sprintf('/embed/-/%s/%s', $his->domain, $video_id));
        }
        elseif (preg_match('/^(dark|light)$', $theme)) {
            $embed_url = sprintf('//www.youtube.com/embed/%s', $video_id);
        } else {
            $this->_error("Unrecognized value for YouTube parameter {theme}", 400);
        }

        $meta = array(
            'embed_url' => $embed_url,
            'theme' => $theme,
        'url'=>$url,
        'provider_name'=> 'YouTube',
        'provider_mid' => $video_id,
        'title' => null,
        'author'=> null,
        'timestamp'=>null,
      #signature=44A4BF0C1FBD2ED9EDF492CB0DB54032633BEBC2.EE68DF33D462D9F026839B632204559C75322F8B
      #&hl=en-GB&asr_langs=en%2Cko%2Cja%2Ces&lang=en
        '_caption_url' =>
        'http://www.youtube.com/api/timedtext?sparams=asr_langs%2Ccaps%2Cv%2Cexpire&asr_langs=en%2Cko%2Cja%2Ces&v='.$video_id.'&caps=asr&expire=1342827491&key=yttt1&x-signature&type=track&lang=en&name&kind&fmt=1',
        );

        return (object) $meta;
    }


    protected function api_request($video_id)
    {
        // Create a project & get a server API key ...
        // https://console.developers.google.com/project
        $api_key = $this->config_item('youtube_api_key');

        $api_url = sprintf(self::API_URL, $video_id, $api_key);

        $http = new \IET_OU\Open_Media_Player\Http();
        $resp = $http->request($api_url);
        /*if ($resp->is_not_found) {
            $this->_error("YouTube video not found, $video_id", 404);
        }
        else*/if (!$resp->success) {
            $this->_error("YouTube embed error", $resp->http_code);
        }
        $resp->obj = json_decode($resp->data);
        return $resp;
    }

    public function local_embed($video_id)
    {
        $resp = $this->api_request($video_id);

        $this->_debug($resp->obj);

        if (1 !== $resp->obj->pageInfo->totalResults) {
            $this->_error("YouTube video not found, $video_id", 404);
        }

        $video = $resp->obj->items[ 0 ];
        $thumbnail = $video->snippet->thumbnails->high;

        $_debug = true;
        $_theme_name = 'oup-light';

        $player = new \IET_OU\Open_Oembed_Providers\Youtube_Player([
            'id' => $video_id,
            'title' => $video->snippet->title,
            'mime_type' => 'video/youtube',
            'media_type' => 'video',
            'media_url' => 'https://youtube.com/watch?v=' . $video_id,
            'poster_url' => $thumbnail->url,
            'width'  => $thumbnail->width,
            'height' => $thumbnail->height,
            'iso_duration' => $video->contentDetails->duration,
            '_theme' => $_theme_name,
        ]);

        $view_data = array(
            'meta' => $player,
            'theme'=> $_theme_name,
            'debug'=> $_debug,
            'standalone' => false,
            // Auto-generate 'embed' or 'popup'.
            'mode' => strtolower(get_class($this)),
            'req'  => null,
            'google_analytics' => null,  //TODO.
            'popup_url' => null,
            '_caption_url' => null,
        );

        $this->theme = $this->CI->load->theme($_theme_name);

        $this->theme->prepare($player);

        $view_data['params'] = $view_data['meta'];
        $view_data['params']->debug = $this->CI->_is_debug(OUP_DEBUG_MAX);  #$this->_debug;
        $view_data['params']->debug_score = $this->CI->_is_debug(1, $score = true);

        $this->CI->load->theme_view(null, $view_data);
    }
}
