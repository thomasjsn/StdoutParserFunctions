# StdoutParserFunctions

Custom MediaWiki parserhook extension for https://www.stdout.no

## Funtion hooks

### `htmlvideo`
Insert HTML5 video element.

    {{#htmlvideo: Exhaust-fan-smoke-test|variables (not required)}}
    
Variables:
* `t`: thumbnail number; default 3
* `w`: video max width; default 640px
* `yt`: YouTube ID, will insert note belov video; default none.

Variables are separated by '|', and none are required.

### `parts-list`
Insert project parts list from json file.

    {{#parts-list: serialno}}