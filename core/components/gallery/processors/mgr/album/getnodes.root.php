<?php
/**
 * Get all albums as nodes.
 *
 * @package gallery
 * @subpackage processors
 */
$nodes = array();

$c = $modx->newQuery('galAlbum');
$c->select('
    `galAlbum`.*,
    `Parent`.`name` AS `parent_name`
');
$c->leftJoin('galAlbum','Parent');
$c->where(array(
    'parent' => $id,
));
$c->sortby('galAlbum.rank','ASC');
$albums = $modx->getCollection('galAlbum',$c);

foreach ($albums as $album) {
    $albumArray = $album->toArray();

    $albumArray['pk'] = $album->get('id');
    $albumArray['text'] = $album->get('name').' ('.$album->get('id').')';
    $albumArray['leaf'] = false;
    $albumArray['parent'] = 0;
    $albumArray['cls'] = 'icon-tiff';
    $albumArray['classKey'] = 'galAlbum';

    $albumArray['menu'] = array('items' => array());
    $albumArray['menu']['items'][] = array(
        'text' => $modx->lexicon('gallery.album_update'),
        'handler' => 'function(itm,e) { this.updateAlbum(itm,e); }',
    );
    $albumArray['menu']['items'][] = '-';
    $albumArray['menu']['items'][] = array(
        'text' => $modx->lexicon('gallery.album_create'),
        'handler' => 'function(itm,e) { this.createAlbum(itm,e); }',
    );
    $albumArray['menu']['items'][] = '-';
    $albumArray['menu']['items'][] = array(
        'text' => $modx->lexicon('gallery.album_remove'),
        'handler' => 'function(itm,e) { this.removeAlbum(itm,e); }',
    );

    $albumArray['id'] = 'album_'.$album->get('id');
    $nodes[] = $albumArray;
}
unset($albums,$album,$albumArray,$c);

return $nodes;