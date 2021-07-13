<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 11 Jul 2021 16:46:40 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'utils/prepare_table.php';


class prepare_table_fulfilment_assets extends prepare_table {

    function __construct($db, $accounts, $user) {
        parent::__construct(...func_get_args());
        $this->record_label = [
            [
                '%s asset',
                '%s assets'
            ],
            [
                '%s asset of %s',
                '%s assets of %s'
            ],

        ];

        $this->navigation_sql = [
            'name' => 'CONCAT(LPAD(`Fulfilment Asset Key`,12,0),\' \',`Fulfilment Asset Reference`)',
            'key'  => '`Fulfilment Asset Key`'
        ];
    }


    function prepare_table() {


        $this->where = 'where true ';
        $this->table = '`Fulfilment Asset Dimension` A left join `Location Dimension` on (`Fulfilment Asset Location Key`=`Location Key`) 
          left join `Customer Dimension` on (`Fulfilment Asset Customer Key`=`Customer Key`)  left join `Store Dimension` on (`Store Key`=`Customer Store Key`) 
         ';

        if ($this->parameters['parent'] == 'fulfilment_delivery') {

            $this->where = sprintf(
                'where   `Fulfilment Asset Fulfilment Delivery Key`=%d', $this->parameters['parent_key']
            );


        } else {
            if ($this->parameters['parent'] == 'fulfilment_order') {

                $this->where = sprintf(
                    'where   `Fulfilment Asset Fulfilment Order Key`=%d', $this->parameters['parent_key']
                );


            } elseif ($this->parameters['parent'] == 'customer') {
                $this->where = sprintf(
                    'where   `Fulfilment Asset Customer Key`=%d  ', $this->parameters['parent_key']
                );
            }
        }

        if (isset($parameters['elements_type'])) {


            switch ($parameters['elements_type']) {
                case('state'):
                    $_elements            = '';
                    $num_elements_checked = 0;

                    //'InProcess', 'Stored', 'Returned
                    foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value) {
                        $_value = $_value['selected'];
                        if ($_value) {
                            $num_elements_checked++;
                            $_elements .= ",'".addslashes($_key)."'";
                        }
                    }

                    if ($_elements == '') {
                        $this->where .= ' and false';
                    } elseif ($num_elements_checked < 4) {
                        $_elements   = preg_replace('/^,/', '', $_elements);
                        $this->where .= ' and `Fulfilment Asset State` in ('.$_elements.')';
                    }
                    break;
            }
        }


        if (($this->parameters['f_field'] == 'reference') and $this->f_value != '') {

            $this->wheref = sprintf(
                '  and  `Fulfilment Asset Reference`  like "%s%%" ', addslashes($this->f_value)
            );


        }


        $this->order_direction = $this->sort_direction;


        if ($this->sort_key == 'reference') {
            $this->order = '`Fulfilment Asset Reference`';
        } elseif ($this->sort_key == 'type') {
            $this->order = '`Fulfilment Asset Type`';
        } else {
            $this->order = '`Fulfilment Asset Key`';
        }

        $this->fields = '`Fulfilment Asset Key`,`Fulfilment Asset State`,`Location Key`,`Location Code`,`Fulfilment Asset Customer Key`,`Customer Name`,`Store Type`,`Fulfilment Asset Warehouse Key`,
        `Fulfilment Asset Reference`,`Fulfilment Asset Note`,`Fulfilment Asset Type`';

        $this->sql_totals = "select "."count(`Fulfilment Asset Key`) as num from $this->table $this->where ";


    }

    function get_data() {


        $sql = "select $this->fields from $this->table $this->where $this->wheref order by $this->order $this->order_direction limit $this->start_from,$this->number_results";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($data = $stmt->fetch()) {

            switch ($data['Fulfilment Asset Type']) {
                case 'Pallet':
                    $type = sprintf('<span title="%s"><i class="fa fa-pallet-alt"></i></span>', _('Pallet'));
                    break;
                case 'Box':
                    $type = sprintf('<span title="%s"><i class="fa fa-box-alt"></i></span>', _('Box'));
                    break;
                default:
                    $type = $data['Fulfilment Asset Type'];
                    break;
            }


            switch ($data['Fulfilment Asset State']) {
                case 'InProcess':
                    $state = sprintf('%s', _('In Process'));
                    break;
                case 'Stored':
                    $state = sprintf('%s', _('Stored'));
                    break;
                case 'Returned':
                    $state = sprintf('%s', _('Returned'));
                    break;
                case 'Lost':
                    $state = sprintf('%s', _('Lost'));
                    break;

                default:
                    $state = $data['Fulfilment Asset State'];
                    break;
            }

            if ($data['Store Type'] == 'Dropshipping') {
                $_link_customer = 'fulfilment/'.$data['Fulfilment Asset Warehouse Key'].'/customers/dropshipping/'.$data['Fulfilment Asset Customer Key'];
            } else {
                $_link_customer = 'fulfilment/'.$data['Fulfilment Asset Warehouse Key'].'/customers/asset_keeping/'.$data['Fulfilment Asset Customer Key'];
            }


            $asset_reference = $data['Fulfilment Asset Reference'];
            if ($asset_reference == '') {
                $asset_reference = '<span class="super_discreet italic">'._('No set')."</span>";
            }


            $edit_location = ' 
            
            
            <div class="asset_location_container" data-asset_key="'.$data['Fulfilment Asset Key'].'" >
            <div style="position:relative" class="asset_location '.(($data['Fulfilment Asset State'] != 'Received' and $data['Location Key']==''  ) ? 'hide' : '').'">
			    <i style="position:absolute;right:16px" class="show_asset_location_edit fa fa-pencil very_discreet_on_hover button " onclick="show_asset_location_edit(this)"></i> 
			     <span class="location_code" style="padding-left: 25px">'.$data['Location Code'].'</span>
			    
			    
			    </div>
			    
			    <div style="clear:both"  id="fulfilment_delivery_edit_location_'.$data['Fulfilment Asset Key'].'" class="select_container  '.(($data['Fulfilment Asset State'] != 'Received' and $data['Location Key']==''  ) ? '' : 'hide')
                .' "   >

			  <i class="fa fa-unlink button valid '.($data['Location Key']==''?'invisible':'').'" onclick="edit_asset_location(\'delete\',this)"></i> 

				<input class="fulfilment_asset_location_code margin_left_5 "  placeholder="'._('Location code').'" value="'.$data['Location Code'].'" >
				<i  class="place_item_button  fa  fa-cloud  fa-fw save " aria-hidden="true" title="'._('Save location').'"  data-location_key="" onClick="edit_asset_location(\'edit\',this)"  ></i>
                
                </div>
               </div>
			';


            $location = $edit_location;


            $this->table_data[] = array(
                'id'           => (integer)$data['Fulfilment Asset Key'],
                'customer'     => sprintf('<span class="link" onclick="change_view(\'/%s\')" >%s</span>  ', $_link_customer, $data['Customer Name']),
                'reference'    => $asset_reference,
                'formatted_id' => sprintf('<span class="link" onclick="change_view(\'%s\')" >%05d</span>  ', $_link_customer.'/delivery/'.$data['Fulfilment Asset Key'], $data['Fulfilment Asset Key']),
                'state'        => $state,
                'type'         => $type,
                'notes'        => $data['Fulfilment Asset Note'],
                'location'     => $location,
                'delete'     => '<i class="button fal fa-trash-alt" title="'._('Delete').'" data-asset_key="'.$data['Fulfilment Asset Key'].'" onclick="delete_fulfilment_asset_from_table(this)"></i>'

            );


        }

    }
}