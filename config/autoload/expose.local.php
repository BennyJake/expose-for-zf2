<?php
return array(
    'expose' => array(
        'additional_filters' => array
        (
            array
            (
                'id' => 1,
                'rule' => '(?:"[^"]*[^-]?>)|(?:[^\w\s]\s*\/>)|(?:>")',
                'description' => 'finds html breaking injections including whitespace attacks',
                'tags' => array(
                    'tag' => array(
                        'xss',
                        'csrf'
                    )
                ),
                'impact' => 4
            )
        ),
        'impact_actions' => array(
            0 => array(
                'min' => 4,
                'max' => 8,
                'route' => array(
                    'controller' => 'ExposeForZf2\Controller\Index',
                    'action' => 'warn',
                )
            ),
            1 => array(
                'min' => 9,
                'route' => array(
                    'controller' => 'ExposeForZf2\Controller\Index',
                    'action' => 'ban',
                )
            ),
        ),
    )
);