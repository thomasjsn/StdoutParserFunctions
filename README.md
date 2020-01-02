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
{{#htmlvideo: Exhaust-fan-smoke-test|t=3|w=640px}}
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
