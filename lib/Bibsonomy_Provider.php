<?php namespace IET_OU\Open_Oembed_Providers;

/**
 * Part of Open Media Player.
 *
 * @license   http://gnu.org/licenses/gpl.html GPL-3.0+
 * @copyright Copyright 2011-2015 The Open University (IET) and contributors.
 * @link      http://iet-ou.github.io/open-media-player
 */

/*
     This file is part of Open Media Player.

     Open Media Player is free software: you can redistribute it and/or modify
     it under the terms of the GNU General Public License as published by
     the Free Software Foundation, either version 3 of the License, or
     (at your option) any later version.

     Open Media Player is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU General Public License for more details.

     You should have received a copy of the GNU General Public License
     along with Open Media Player.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Bibsonomy oEmbed service provider -- a stop-gap service?!
 *
 * @copyright Copyright 2012 The Open University.
 * @author N.D.Freear, 19 November 2012 - 25 January 2013.
 */

use \IET_OU\Open_Media_Player\Generic_Iframe_Oembed_Provider;

class Bibsonomy_Provider extends Generic_Iframe_Oembed_Provider
{

    public $regex = 'http://www.bibsonomy.org/*'; // Optional trailing slash.
    public $about = <<<EOT
  BibSonomy is a social bookmarking and publication-sharing system. It aims to integrate the features of bookmarking systems as well as team-oriented publication management. [Initially for OLDS-MOOC. Public access. Alpha/ interim.]
EOT;
    public $displayname = 'BibSonomy';
    public $domain = 'bibsonomy.org';
    public $favicon = 'http://www.bibsonomy.org/resources/image/favicon.png';
    public $type = 'rich';

    public $_about_url = 'http://bibsonomy.org/';

    public $_regex_real = 'bibsonomy\.org\/?([^\?]*)(\?.*)?';
    public $_examples = array(
    'Oldsmooc tag' => 'http://www.bibsonomy.org/tag/oldsmooc',
    );
    public $_access = 'public';


    /**
    * @return object
    */
    public function call($url, $matches)
    {
      // Generate the embed URL from the input URL.
        $embed_url = preg_replace('/format=\w*/', '', $url);
        $embed_url .= contains($url, '?') ? '&' : '?';
        $embed_url .= 'format=embed&for=' . filter_input(INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_URL);

        $meta = $this->getIframeResponse($url);

        $meta->title = isset($matches[1]) && !empty($matches[1]) ? $matches[1] : 'BibSonomy home';
        $meta->embed_url = $embed_url;

      //redirect($url . '?format=oembed'); # Doesn't work?!

        return $meta;
    }
}
