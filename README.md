# StdoutParserFunctions

> Custom MediaWiki parserhook extension for https://cavelab.dev

## Installation
* Clone this repository in s directory called `StdoutParserFunctions` in your `extensions/` folder.
* Add the following code at the bottom of your `LocalSettings.php`:
```
wfLoadExtension( 'StdoutParserFunctions' );
```
* Done! – Navigate to Special:Version on your wiki to verify that the extension is successfully installed.

## Function hooks

### `htmlvideo`
Insert HTML5 video element.

```
{{#htmlvideo: video-file|parameter=value}}
```

Expected directory structure;
```
/video-file/
    playlist.m3u8
    thumbnail_001.jpg
```

Parameters:
* `thumb`: thumbnail number; default `1`
* `name`: video name
* `desc`: video description
* `date`: video upload date

Parameters are separated by `|`, and none are required.

If parameters `name`, `desc` and `date` are present, a video object will be defined.

### `partslist`
Insert project parts list from json file.

```
{{#partslist: serialno}}
```

### `youtube`
Insert YouTube embedded video.

```
{{#youtube: video-id}}
```

### `asciinema`
Insert asciicast video player.

```
{{#asciinema: cast-url|cols|rows|poster}}
```

Parameters:
* `cols`: columns; default `80`
* `rows`: rows; default `24`
* `poster`: poster timestamp; default `0:15`

## Author
**Thomas Jensen**
* Twitter: [@thomasjsn](https://twitter.com/thomasjsn)
* Github: [@thomasjsn](https://github.com/thomasjsn)
* Website: [cavelab.dev](https://cavelab.dev/wiki/User:Thomas)

## License
The MIT License (MIT). Please see [license file](LICENSE.txt) for more information.
