# StdoutParserFunctions

Custom MediaWiki parserhook extension for https://www.stdout.no

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
{{#htmlvideo: Video-file-without-extension(/sub-dir)|t=3|w=640px}}
```

Expected directory structure;
```
/VID_20191118_222156/(240p/)
    VID_20191118_222156_1.jpg
    VID_20191118_222156_2.jpg
    VID_20191118_222156_3.jpg
    VID_20191118_222156_4.jpg
    VID_20191118_222156_5.jpg
    VID_20191118_222156.mp4
    VID_20191118_222156.webm
```

Variables:
* `t`: thumbnail number; default 3
* `w`: video max width; default 640px
* `yt`: YouTube ID, will insert note below video; default none.

Variables are separated by '|', and none are required.

### `parts-list`
Insert project parts list from json file.

```
{{#parts-list: serialno}}
```

## Author
[Thomas Jensen](https://thomas.stdout.no)