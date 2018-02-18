<?php
/*
 * Wordpress Overrides the Laravel __() helper
 * The following allows most use cases of the translation function.
 */

add_filter('gettext', function($default, $request, $domain){

    $translation = app('translator')->getFromJson($request);
    return $translation != $request ? $translation : $default;

}, 20, 3);