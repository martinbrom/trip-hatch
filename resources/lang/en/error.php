<?php

return [
    '401' => [
        'message' => 'You don\'t have sufficient rights to perform this action',
        'heading' => 'Looks like you ended up in the wrong place',
        'link' => [
            'text' => 'You can try going back and refreshing the page or visit our',
            'content' => 'homepage'
        ],
        'title' => 'Trip Hatch - unauthorized'
    ],
    '404' => [
        'message' => ' ',
        'heading' => 'The page you are looking for doesn\'t exist',
        'link' => [
            'text' => 'You can try going back or visit our',
            'content' => 'homepage'
        ],
        'title' => 'Trip Hatch - page not found'
    ],
    '500' => [
        'message' => 'Our server is currently having some issues, but we are working on fixing the issue',
        'heading' => 'Oops, we may have broken something',
        'link' => [
            'text' => 'Please wait a moment or try contacting our',
            'content' => 'administrator'
        ],
        'title' => 'Trip Hatch - internal server error'
    ],
    'base' => [
        'message' => ' ',
        'heading' => 'Something went wrong',
        'link' => [
            'text' => 'You can try going back or visit our',
            'content' => 'homepage'
        ],
        'title' => 'Trip Hatch - something went wrong'
    ]
];
