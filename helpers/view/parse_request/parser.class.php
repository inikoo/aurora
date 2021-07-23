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
    public bool $authorized;
    public string $module;
    public string $section;
    public string $parent;
    public ?int $parent_key;
    public string $object;
    public string $metadata;
    public string $tab;
    public string $key;
    public string $extra;
    protected User $user;

    function __construct($user, $view_path) {
        $this->user       = $user;
        $this->authorized = false;
        $this->module     = 'utils';
        $this->section    = 'not_found';
        $this->parent     = 'account';
        $this->parent_key = 1;
        $this->object     = '';
        $this->metadata   = '';
        $this->tab        = '';
        $this->key        = '';
        $this->extra      = '';
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