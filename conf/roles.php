<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 March 2016 at 16:05:01 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/


$roles = array(
    'DIR'   => array(
        'title'       => _('Director'),
        'size'        => array(
            'Basic',
            'Medium',
            'Big'
        ),
        'instances'   => array(
            'Com',
            'Prod',
            'Serv'
        ),
        'user_groups' => array(
            1,
            2,
            3,
            5,
            6,
            8,
            9,
            10,
            11,
            14,
            15
        )
    ),
    'HR'    => array(
        'title'       => _('Human Resources'),
        'size'        => array(
            'Medium',
            'Big'
        ),
        'instances'   => array(
            'Com',
            'Prod',
            'Serv'
        ),
        'user_groups' => array(6)
    ),
    'ACC'   => array(
        'title'       => _('Accounts'),
        'size'        => array(
            'Medium',
            'Big'
        ),
        'instances'   => array(
            'Com',
            'Prod',
            'Serv'
        ),
        'user_groups' => array(
            14,
            15
        )
    ),
    'MRK'   => array(
        'title'       => _('Marketing'),
        'size'        => array(
            'Medium',
            'Big'
        ),
        'instances'   => array(
            'Com',
            'Serv'
        ),
        'user_groups' => array(9)
    ),
    'WEB'   => array(
        'title'       => _('Web designer'),
        'size'        => array(
            'Medium',
            'Big'
        ),
        'instances'   => array(
            'Com',
            'Serv'
        ),
        'user_groups' => array(10)
    ),
    'WAHM'  => array(
        'title'       => _('Warehouse Supervisor'),
        'size'        => array(
            'Basic',
            'Medium',
            'Big'
        ),
        'instances'   => array('Com'),
        'user_groups' => array(
            3,
            11,
            15
        )
    ),
    'WAHSK' => array(
        'title'       => _('Warehouse Stock Keeper'),
        'size'        => array(
            'Basic',
            'Medium',
            'Big'
        ),
        'instances'   => array('Com'),
        'user_groups' => array(3)
    ),
    'WAHSC' => array(
        'title'       => _('Stock Controller'),
        'size'        => array(
            'Basic',
            'Medium',
            'Big'
        ),
        'instances'   => array('Com'),
        'user_groups' => array(
            3,
            15
        )
    ),
    'BUY'   => array(
        'title'       => _('Buyer'),
        'size'        => array(
            'Basic',
            'Medium',
            'Big'
        ),
        'instances'   => array('Com'),
        'user_groups' => array(
            3,
            15
        )
    ),
    'PICK'  => array(
        'title'       => _('Picker'),
        'size'        => array(
            'Basic',
            'Medium',
            'Big'
        ),
        'instances'   => array('Com'),
        'user_groups' => array(11)
    ),
    'PACK'  => array(
        'title'       => _('Packer'),
        'size'        => array(
            'Basic',
            'Medium',
            'Big'
        ),
        'instances'   => array('Com'),
        'user_groups' => array(11)
    ),
    'OHADM' => array(
        'title'       => _('Dispatch Supervisor'),
        'size'        => array(
            'Basic',
            'Medium',
            'Big'
        ),
        'instances'   => array('Com'),
        'user_groups' => array(11)
    ),
    'PRODM' => array(
        'title'       => _('Production Supervisor'),
        'size'        => array(
            'Basic',
            'Medium',
            'Big'
        ),
        'instances'   => array('Prod'),
        'user_groups' => array(4)
    ),
    'PRODO' => array(
        'title'       => _('Production Operative'),
        'size'        => array(
            'Basic',
            'Medium',
            'Big'
        ),
        'instances'   => array('Prod'),
        'user_groups' => array(4)
    ),
    'CUS'   => array(
        'title'       => _('Customer Service'),
        'size'        => array(
            'Basic',
            'Medium',
            'Big'
        ),
        'instances'   => array(
            'Com',
            'Prod',
            'Serv'
        ),
        'user_groups' => array(2)
    ),
    'CUSM'  => array(
        'title'       => _('Customer Service Supervisor'),
        'size'        => array(
            'Basic',
            'Medium',
            'Big'
        ),
        'instances'   => array(
            'Com',
            'Prod',
            'Serv'
        ),
        'user_groups' => array(2)
    ),

);

?>
