<?php
return [
    'PUT,PATCH /threads/<id>' => '/thread/update',
    'DELETE /threads/<id>' => '/thread/delete',
    'GET,HEAD /threads/<id>' => '/thread/view',
    'POST /threads' => '/thread/create',
    'GET,HEAD /threads' => '/thread/index',

 	'PUT,PATCH <module>/threads/<id>' => '<module>/thread/update',
    'DELETE <module>/threads/<id>' => '<module>/thread/delete',
    'GET,HEAD <module>/threads/<id>' => '<module>/thread/view',
    'POST <module>/threads' => '<module>/thread/create',
    'GET,HEAD <module>/threads' => '<module>/thread/index',
];