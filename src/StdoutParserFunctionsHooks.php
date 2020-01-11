<?php
class StdoutParserFunctionsHooks {

    // Register any render callbacks with the parser
    public static function onParserFirstCallInit( Parser $parser )
    {
        $parser->setFunctionHook( 'htmlvideo', [ self::class, 'renderHtmlVideo' ] );
        $parser->setFunctionHook( 'partslist', [ self::class, 'renderPartsList' ] );
    }


    public static function renderHtmlVideo( Parser $parser, $param1 = '' )
    {
        parse_str(implode('&', array_slice(func_get_args(), 2)), $params);

        if (! array_key_exists('t', $params)) $params['t'] = 3;
        if (! array_key_exists('w', $params)) $params['w'] = '640px';

        $dir = $param1;
        $file = explode('/', $param1)[0];

        $output = "<div style=\"max-width:${params['w']};margin-bottom:1em\">";
        $output .= "<video width=\"100%\" height=\"auto\" poster=\"https://video.stdout.no/$dir/${file}_${params['t']}.jpg\" controls preload=\"metadata\">";
        $output .= "<source src=\"https://video.stdout.no/$dir/$file.webm\" type=\"video/webm; codecs=vp9,vorbis\">";
        $output .= "<source src=\"https://video.stdout.no/$dir/$file.mp4\" type=\"video/mp4\">";
        $output .= "</video>";

        if (array_key_exists('yt', $params)) {
            $output .= "<small>This video is also available on <a class=\"external\" href=\"https://www.youtube.com/watch?v=${params['yt']}\">YouTube</a>.</small>";
        }

        $output .= "</div>";


        return [ $output, 'noparse' => true, 'isHTML' => true ];
    }


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
}
