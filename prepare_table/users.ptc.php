<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 05 Aug 2021 20:15:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Original: 2 Oct 2015
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'utils/prepare_table.php';


class prepare_table_users extends prepare_table
{

    function __construct($db, $accounts, $user)
    {
        parent::__construct(...func_get_args());
        $this->record_label = [
            [
                '%s user',
                '%s users'
            ],
            [
                '%s user of %s',
                '%s users of %s'
            ],

        ];

        $this->navigation_sql = [
            'name' => '`User Alias`',
            'key'  => '`User Key`'
        ];
    }


    function prepare_table()
    {

        $this->table  = '`User Dimension` U ';

        switch ($this->parameters['parent']) {
            case 'agent':
                $this->where = sprintf(" where  `User Type`='Agent' and `User Parent Key`=%d ", $this->parameters['parent_key']);
                break;
            case 'supplier':
                $this->where = sprintf(" where  `User Type`='Supplier' and `User Parent Key`=%d ", $this->parameters['parent_key']);
                break;
            default:
                $this->where = " where  `User Type`!='Customer' ";
        }


        if (isset($this->parameters['elements_type'])) {
            switch ($this->parameters['elements_type']) {
                case 'active':
                    $_elements      = '';
                    $count_elements = 0;
                    foreach (
                        $this->parameters['elements'][$this->parameters['elements_type']]['items'] as $_key => $_value
                    ) {
                        if ($_value['selected']) {
                            $count_elements++;
                            $_elements .= ','.prepare_mysql($_key);
                        }
                    }


                    $_elements = preg_replace('/^,/', '', $_elements);
                    if ($_elements == '') {
                        $this->where .= ' and false';
                    } elseif ($count_elements < 3) {
                        $this->where .= ' and `User Active` in ('.$_elements.')';
                    }
                    break;
            }
        }

        if ($this->parameters['f_field'] == 'name' and $this->f_value != '') {
            $this->wheref .= " and  `User Alias` like '".addslashes($this->f_value)."%'    ";
        } elseif ($this->parameters['f_field'] == 'handle' and $this->f_value != '') {
            $this->wheref .= " and  `User Handle` like '".addslashes($this->f_value)."%'    ";
        }


        $this->order_direction = $this->sort_direction;


        if ($this->sort_key == 'user') {
            $this->order = '`User Alias`';
        } elseif ($this->sort_key == 'handle') {
            $this->order = '`User Handle`';
        } elseif ($this->sort_key == 'email') {
            $this->order = '`User Password Recovery Email`';
        } elseif ($this->sort_key == 'active') {
            $this->order = '`User Active`';
        } elseif ($this->sort_key == 'logins') {
            $this->order = '`User Login Count`';
        } elseif ($this->sort_key == 'last_login') {
            $this->order = '`User Last Login`';
        } elseif ($this->sort_key == 'fail_logins') {
            $this->order = '`User Failed Login Count`';
        } elseif ($this->sort_key == 'fail_last_login') {
            $this->order = '`User Last Failed Login`';
        } elseif ($this->sort_key == 'type') {
            $this->order = '`User Type`';
        } else {
            $this->order = '`User Key`';
        }


        $this->sql_totals = "select"." count(Distinct U.`User Key`) as num from $this->table  $this->where  ";


        $this->fields = "`User Failed Login Count`,`User Last Failed Login`,`User Last Login`,`User Login Count`,`User Alias`,`User Handle`,`User Password Recovery Email`,`User Type`,`User Parent Key`,`User Key`,`User Active`";
    }


    function get_data()
    {
        $sql = "select $this->fields from $this->table $this->where $this->wheref order by $this->order $this->order_direction limit $this->start_from,$this->number_results";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(array());
        while ($data = $stmt->fetch()) {
            if ($data['User Active'] == 'Yes') {
                $active      = _('Yes');
                $active_icon = sprintf('<a class="fas fa-check-circle success" title="%s"></a>', _('Active'));
            } else {
                $active      = _('No');
                $active_icon = sprintf('<a class="fas fa-times-circle error" title="%s"></a>', _('Inactive'));
            }


            switch ($data['User Type']) {
                case 'Staff':
                    $type = '<span class="link" onclick="change_view(\'employee/'.$data['User Parent Key'].'\')">'._('Employee').'</span>';
                    break;
                case 'Contractor':
                    $type = '<span class="link" onclick="change_view(\'contractor/'.$data['User Parent Key'].'\')">'._('Contractor').'</span>';

                    break;
                case 'Agent':
                    $type = '<span class="link" onclick="change_view(\'agent/'.$data['User Parent Key'].'\')">'._('Agent').'</span>';
                    break;
                case 'Supplier':
                    $type = '<span class="link" onclick="change_view(\'supplier/'.$data['User Parent Key'].'\')">'._('Supplier').'</span>';

                    break;
                case 'Warehouse':
                    $type = _('Warehouse');
                    break;

                case 'Administrator':
                    $type = _('Administrator');
                    break;

                default:
                    $type = $data['User Type'];
            }


            $this->table_data[] = array(
                'id'              => (integer)$data['User Key'],
                'type'            => $type,
                'handle'          => sprintf('<span class="link" onclick="change_view(\'users/%d\')">%s</span>', $data['User Key'], $data['User Handle']),
                'name'            => $data['User Alias'],
                'email'           => $data['User Password Recovery Email'],
                'active_icon'     => $active_icon,
                'active'          => $active,
                'logins'          => number($data['User Login Count']),
                'last_login'      => ($data ['User Last Login'] == '' ? '' : strftime("%e %b %Y %H:%M %Z", strtotime($data ['User Last Login']." +00:00"))),
                'fail_logins'     => number($data['User Failed Login Count']),
                'fail_last_login' => ($data ['User Last Failed Login'] == '' ? '' : strftime("%e %b %Y %H:%M %Z", strtotime($data ['User Last Failed Login']." +00:00"))),


            );
        }
    }


}