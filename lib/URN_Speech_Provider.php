<?php namespace IET_OU\Open_Oembed_Providers;

/**
 * Part of Open Media Player.
 *
 * @license   http://gnu.org/licenses/gpl.html GPL-3.0+
 * @copyright Copyright 2011-2017 The Open University (IET) and contributors.
 * @link      http://iet-ou.github.io/open-media-player
 */

/**
 * Text-to-speech TTS provider.
 *
 * @copyright Copyright 2017 The Open University.
 * @author N.D.Freear, 11-July-2017.
 */

use IET_OU\Open_Media_Player\Generic_Iframe_Oembed_Provider as IframeProvider;

class URN_Speech_Provider extends IframeProvider
{
    public $regex = 'urn:TTS:*'; // Optional trailing slash.
    public $about = 'Embed a cross-browser speech synthesis/ text-to-speech (TTS) widget.';
    public $displayname = 'simple-speak'; //'Text-to-speech';
    public $domain = 'tts';
    public $favicon = 'http://www.bibsonomy.org/resources/image/favicon.png';
    public $type = 'rich';

    public $_about_url = 'https://www.npmjs.com/package/simple-speak';

    public $_regex_real = 'urn:(tts|TTS):(?P<text>[^&]*)';
    public $_examples = [
        'Hello world!' => 'urn:TTS:Hello%20world...!',
        'Bonjour' => 'urn:TTS:fr:Bonjour',
    ];
    public $_access = 'public';

    const VERSION = '1.3.0-beta';
    const EMBED_URL = 'https://cdn.rawgit.com/nfreear/simple-speak/{version}/embed/?q={text}&lang={lang}&mode={mode}';

    /**
    * @return object
    */
    public function call($url, $matches)
    {
        $text = isset($matches[ 'text' ]) && ! empty($matches[ 'text' ]) ? $matches[ 'text' ] : '';

        $mode = filter_input(INPUT_GET, 'mode', FILTER_SANITIZE_STRING);
        $lang = filter_input(INPUT_GET, 'lang', FILTER_SANITIZE_STRING);

        // Generate the embed URL from the input URL.
        $embed_url = strtr(self::EMBED_URL, [
            '{version}' => self::VERSION,
            '{text}' => $text,
            '{lang}' => $lang ? $lang : '',
            '{mode}' => $mode ? $mode : 'speak',
        ]);

        $meta = $this->getIframeResponse($url);

        $meta->title = 'Speak: ' . $text;
        $meta->embed_url = $embed_url;
        $meta->height = 80;

        return $meta;
    }
}
