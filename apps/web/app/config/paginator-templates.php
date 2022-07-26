<?php

/**
 * ページネーションのレイアウトを変更したい場合はここを編集
 */
return [
    'nextActive' => '<a class="pager__page omit" rel="next" href="{{url}}">{{text}}</a>',
    'nextDisabled' => '<a class="pager__page omit" href="" onclick="return false;">{{text}}</a>',
    'prevActive' => '<a  rel="prev" href="{{url}}">{{text}}</a>',
    'prevDisabled' => '<a class="pager__page omit"  href="" onclick="return false;">{{text}}</a>',
    'number' => '<a class="pager__page" href="{{url}}">{{text}}</a>',
    'current' => '<a class="pager__page current" href="{{url}}">{{text}}</a>',
];

