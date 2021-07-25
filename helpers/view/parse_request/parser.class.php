<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 23 Jul 2021 21:33:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


class parser_request {
    /**
     * @var false
     */
    public bool $authorized = false;
    public string $module = 'utils';
    public string $section = 'not_found';
    public string $parent = 'account';
    public ?int $parent_key = 1;
    public string $object='';
    public string $metadata='';
    public string $tab='';
    public string $key='';
    public string $extra='';
    protected User $user;

    function __construct($user, $view_path) {
        $this->user    = $user;

        $this->authorization();

        if ($this->authorized) {
            $this->parse($view_path);

            if (!$this->authorized) {
                $this->module  = 'utils';
                $this->section = 'forbidden';
            }
        } else {
            $this->section = 'forbidden';

        }
    }

    function authorization() {

    }

    function parse($view_path) {

    }

}