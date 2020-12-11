<?php
class StdoutParserFunctionsHooks {

    // Register any render callbacks with the parser
    public static function onParserFirstCallInit( Parser $parser )
    {
        $parser->setFunctionHook( 'htmlvideo', [ self::class, 'renderHtmlVideo' ] );
        $parser->setFunctionHook( 'partslist', [ self::class, 'renderPartsList' ] );
        $parser->setFunctionHook( 'youtube', [ self::class, 'renderYoutube' ] );
        $parser->setFunctionHook( 'asciinema', [ self::class, 'renderAsciinema' ] );
    }


    public static function onBeforePageDisplay( OutputPage $out, Skin $skin )
    {
        #$out->addLink(['rel' => 'preconnect', 'href' => 'https://static.stdout.no', 'crossorigin' => '']);
        #$out->addLink(['rel' => 'dns-prefetch', 'href' => 'https://static.stdout.no']);
        #$out->addLink(['rel' => 'dns-prefetch', 'href' => 'https://video.stdout.no']);
        $out->addScript('<script async defer data-domain="cavelab.dev" src="https://plausible.io/js/plausible.js"></script>');
    }


    public static function renderHtmlVideo( Parser $parser, $param1 = '' )
    {
        if (empty($param1)) {
            return 'ERROR: No video file specified!';
        }

        parse_str(implode('&', array_slice(func_get_args(), 2)), $params);

        $thumb = sprintf('%03d', $params['thumb'] ?? 1);
        $poster = "https://cavelab-vid.b-cdn.net/${param1}/thumbnail_${thumb}.jpg";

        $width = 640;
        $height = 360;

        $video = [
            "id=\"my-video\"",
            "class=\"video-js vjs-fluid\"",
            "controls",
            "preload=\"auto\"".
            "width=\"$width\"",
            "height=\"$height\"",
            "poster=\"$poster\"",
            "data-setup=\"{}\""
        ];

        $content = "https://cavelab-vid.b-cdn.net/${param1}/playlist.m3u8";

        $output = "<div style=\"max-width:${width}px;max-height:100%\">";
        $output .= "<video " . implode(' ', $video) . ">";
        $output .= "<source type=\"application/x-mpegURL\" src=\"$content\">";
        $output .= "</video>";
        $output .= "</div>";

        $videoObject = json_encode([
            "@context" => "https://schema.org",
            "@type" => "VideoObject",
            "name" => $params['name'],
            "description" => $params['desc'],
            "thumbnailUrl" => [ $poster ],
            "uploadDate" => $params['date'],
            "contentUrl" => $content
        ], JSON_PRETTY_PRINT);

        if (isset($params['name'], $params['desc'], $params['date'])) {
            $parser->getOutput()->addHeadItem("<script type=\"application/ld+json\">$videoObject</script>");
        }

        $parser->getOutput()->addModules('ext.stdoutParser.video');
        $parser->getOutput()->addModuleStyles('ext.stdoutParser.video');

        return [ $output, 'noparse' => true, 'isHTML' => true ];
    }


#    public static function renderHtmlVideo_old( Parser $parser, $param1 = '' )
#    {
#        parse_str(implode('&', array_slice(func_get_args(), 2)), $params);
#
#        if (! array_key_exists('t', $params)) $params['t'] = 3;
#        if (! array_key_exists('w', $params)) $params['w'] = '640';
#        if (! array_key_exists('r', $params)) $params['r'] = '16:9';
#
#        $r = explode(':', $params['r']);
#        $w = str_replace('px', '', $params['w']);
#        $h = $w / ($r[0] / $r[1]);
#
#        $dir = $param1;
#        $file = explode('/', $param1)[0];
#
#        $output = "<div style=\"margin:1em 0\">";
#        $output .= "<video width=\"$w\" height=\"$h\" poster=\"/images/video/$dir/${file}_${params['t']}.jpg\" controls preload=\"metadata\">";
#        $output .= "<source src=\"https://video.stdout.no/$dir/$file.webm\" type=\"video/webm; codecs=vp9,vorbis\">";
#        $output .= "<source src=\"https://video.stdout.no/$dir/$file.mp4\" type=\"video/mp4\">";
#        $output .= "</video>";
#
#        if (array_key_exists('yt', $params)) {
#            $output .= "<small>This video is also available on <a class=\"external\" href=\"https://www.youtube.com/watch?v=${params['yt']}\">YouTube</a>.</small>";
#        }
#
#        $output .= "</div>";
#
#
#        return [ $output, 'noparse' => true, 'isHTML' => true ];
#    }


    public static function renderPartsList( Parser $parser, $param1 = '') {
        $json = file_get_contents('/var/lib/parts-json/' . $param1 . '.json');
        if (empty($json)) {
            return 'ERROR: ' . $param1 . '.json not found or empty!';
        }
        $parts = json_decode($json, true);

        $output = [];
        $output[] = '{| class="wikitable mw-collapsible searchaux"';
        $output[] = '! scope="col" | Qty.';
        $output[] = '! scope="col" | Part';
        $output[] = '|-';

        foreach ($parts as $part)
        {
            $unitConvert = [
                'ea' => '×',
                'cm2' => 'cm<sup>2</sup>'
            ];
            $unit = strtr($part['unit'], $unitConvert);

            $title = html_entity_decode($part['title']);
            if (! is_null($part['url'])) {
                $title = sprintf('[%s %s]', $part['url'], $title);
            }

            $output[] = sprintf('|\'\'\'%s\'\'\' <small>%s</small> || %s',
                $part['q'], $unit, $title
            );

            $output[] = '|-';
        }

        $output[] = '|}';


        return implode("\n", $output);
    }


    public static function renderYoutube( Parser $parser, $param1 = '') {
        $embed = "<div class=\"video-container\"><iframe src=\"https://www.youtube-nocookie.com/embed/${param1}\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe></div>";

        return [ $embed, 'noparse' => true, 'isHTML' => true ];
    }


    public static function renderAsciinema( Parser $parser, $param1 = '', $cols = 80, $rows = 24, $poster = '0:15') {
        if (empty($param1)) {
            return 'ERROR: No asciicast file specified!';
        }

        $parser->getOutput()->addModules('ext.stdoutParser.asciinema');
        $parser->getOutput()->addModuleStyles('ext.stdoutParser.asciinema');

        $output = "<asciinema-player src=\"$param1\" cols=\"$cols\" rows=\"$rows\" poster=\"npt:$poster\" preload></asciinema-player>";

        return [ $output, 'noparse' => true, 'isHTML' => true ];
    }
}
