<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 February 2016 at 10:34:34 GMT+8, Kuala Lumpur, Maysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/
include_once 'class.DB_Table.php';
include_once 'trait.ImageSubject.php';
include_once 'trait.AttachmentSubject.php';
include_once 'trait.NotesSubject.php';


class Subject extends DB_Table {
    use ImageSubject, NotesSubject, AttachmentSubject;


    function get_name() {
        return $this->data[$this->table_name.' Name'];
    }


    function get_hello() {


        $unknown_name    = '';
        $greeting_prefix = _('Hello');

        if ($this->data[$this->table_name.' Name'] == '' and $this->data[$this->table_name.' Main Contact Name'] == '') {
            return $unknown_name;
        }
        $greeting = $greeting_prefix.' '.$this->data[$this->table_name.' Main Contact Name'];
        if ($this->data[$this->table_name.' Company Name'] != '') {
            $greeting .= ', '.$this->data['Customer Name'];
        }

        return $greeting;

    }


    function get_greetings($locale = false) {

        if ($locale) {

            if (preg_match('/^es_/', $locale)) {
                $unknown_name    = 'A quien corresponda';
                $greeting_prefix = 'Estimado';
            } else {
                $unknown_name    = _('To whom it corresponds');
                $greeting_prefix = _('Dear');
            }
        } else {
            $unknown_name    = _('To whom it corresponds');
            $greeting_prefix = _('Dear');
        }
        if ($this->data[$this->table_name.' Name'] == '' and $this->data[$this->table_name.' Main Contact Name'] == '') {
            return $unknown_name;
        }
        $greeting = $greeting_prefix.' '.$this->data[$this->table_name.' Main Contact Name'];
        if ($this->data[$this->table_name.' Company Name'] != '') {
            $greeting .= ', '.$this->data[$this->table_name.' Name'];
        }

        return $greeting;

    }


    function get_name_for_grettings() {

        if ($this->data[$this->table_name.' Name'] == '' and $this->data[$this->table_name.' Main Contact Name'] == '') {
            return '';
        }
        $greeting = $this->data[$this->table_name.' Main Contact Name'];
        if ($greeting and $this->data[$this->table_name.' Company Name'] != '') {
            $greeting .= ', '.$this->data[$this->table_name.' Company Name'];
        }


        return $greeting;
    }


    function set_as_main($field, $other_key) {


        switch ($field) {
            case 'Customer Other Email':
            case 'Supplier Other Email':
                $old_main_value = $this->data[$this->table_name.' Main Plain Email'];
                $new_main_value = $this->get("$field $other_key");

                //print $this->table_name.' Main Plain Email  -> '.$new_main_value."\n";
                //print "$field $other_key  -> ".$old_main_value;
                //exit;

                $this->update(
                    array(
                        "$field $other_key" => $old_main_value,

                        $this->table_name.' Main Plain Email' => $new_main_value,
                    ), 'no_history '
                );


                $this->add_changelog_record(
                    $this->table_name.' Main Email', $old_main_value, $new_main_value, '', $this->table_name, $this->id, 'set_as_main'
                );


                $this->other_fields_updated = array(
                    $this->table_name.'_Main_Plain_Email'         => array(
                        'field'           => $this->table_name.'_Main_Plain_Email',
                        'render'          => true,
                        'value'           => $this->get(
                            $this->table_name.' Main Plain Email'
                        ),
                        'formatted_value' => $this->get('Main Plain Email'),
                    ),
                    preg_replace('/ /', '_', "$field $other_key") => array(
                        'field'           => preg_replace(
                            '/ /', '_', "$field $other_key"
                        ),
                        'render'          => true,
                        'value'           => $this->get("$field $other_key"),
                        'formatted_value' => $this->get(
                            preg_replace(
                                '/'.$this->table_name.' /', '', "$field $other_key"
                            )
                        ),
                        'field_type'      => preg_replace('/ /', '_', $field),
                        'formatted_email' => sprintf(
                            '<a href="mailto:%s" >%s</a>', $this->get("$field $other_key"), $this->get("$field $other_key")
                        ),

                    )
                );

                $this->updated = true;

                break;

            case 'Customer Other Telephone':
            case 'Supplier Other Telephone':
                $old_main_value = $this->data[$this->table_name.' Main Plain Telephone'];
                $new_main_value = $this->get("$field $other_key");

                $this->update(
                    array(

                        $this->table_name.' Main Plain Telephone'     => $new_main_value,
                        "$field $other_key"                           => $old_main_value,
                        $this->table_name.' Preferred Contact Number' => 'Telephone',
                    ), 'no_history'
                );


                $this->add_changelog_record(
                    $this->table_name.' Main Telephone', $old_main_value, $new_main_value, '', $this->table_name, $this->id, 'set_as_main'
                );


                $this->other_fields_updated[$this->table_name.'_Main_Plain_Telephone']
                    = array(
                    'field'           => $this->table_name.'_Main_Plain_Telephone',
                    'render'          => true,
                    'value'           => $this->get(
                        $this->table_name.' Main Plain Telephone'
                    ),
                    'formatted_value' => $this->get('Main Plain Telephone'),
                    'label'           => ucfirst(
                            $this->get_field_label(
                                $this->table_name.' Main Plain Telephone'
                            )
                        ).($this->get($this->table_name.' Main Plain Telephone') != '' ? ($this->get(
                            $this->table_name.' Preferred Contact Number'
                        ) == 'Telephone'
                            ? ' <i title="'._(
                                'Main contact number'
                            ).'" class="fa fa-star discret"></i>'
                            : ' <i onClick="set_this_as_main(this)" title="'._(
                                'Set as main contact number'
                            ).'" class="fa fa-star-o discret button"></i>') : ''),

                );

                $this->other_fields_updated[preg_replace(
                    '/ /', '_', "$field $other_key"
                )]
                    = array(
                    'field'           => preg_replace(
                        '/ /', '_', "$field $other_key"
                    ),
                    'render'          => true,
                    'value'           => $this->get("$field $other_key"),
                    'formatted_value' => $this->get(
                        preg_replace(
                            '/'.$this->table_name.' /', '', "$field $other_key"
                        )
                    ),
                    'field_type'      => preg_replace('/ /', '_', $field),

                );


                $this->updated = true;

                break;

            case 'Customer Other Delivery Address':
                //$old_main_value=$this->data[$this->table_name.' Main Plain Telephone'];
                //$new_main_value=$this->get("$field $other_key");
                $old_main_value = $this->get('Delivery Address');


                $type            = 'Delivery';
                $old_main_fields = array(

                    'Address Recipient'            => $this->get(
                        $type.' Address Recipient'
                    ),
                    'Address Organization'         => $this->get(
                        $type.' Address Organization'
                    ),
                    'Address Line 1'               => $this->get(
                        $type.' Address Line 1'
                    ),
                    'Address Line 2'               => $this->get(
                        $type.' Address Line 2'
                    ),
                    'Address Sorting Code'         => $this->get(
                        $type.' Address Sorting Code'
                    ),
                    'Address Postal Code'          => $this->get(
                        $type.' Address Postal Code'
                    ),
                    'Address Dependent Locality'   => $this->get(
                        $type.' Address Dependent Locality'
                    ),
                    'Address Locality'             => $this->get(
                        $type.' Address Locality'
                    ),
                    'Address Administrative Area'  => $this->get(
                        $type.' Address Administrative Area'
                    ),
                    'Address Country 2 Alpha Code' => $this->get(
                        $type.' Address Country 2 Alpha Code'
                    ),


                );


                $new_main_fields = $this->get_other_delivery_address_fields(
                    $other_key
                );

                if (!$new_main_fields) {
                    $this->msg   = 'Error, please refresh and try again';
                    $this->error = true;

                    return;
                }

                //print_r($old_main_fields);
                //print_r($new_main_fields);
                //exit;

                $this->delete_component(
                    'Customer Other Delivery Address', $other_key
                );

                $this->update_address(
                    'Delivery', $new_main_fields, 'no_history'
                );


                $this->add_other_delivery_address(
                    $old_main_fields, 'no_history'
                );


                $this->other_fields_updated['Customer_Delivery_Address']
                    = array(
                    'field'           => 'Customer_Delivery_Address',
                    'render'          => true,
                    'value'           => $this->get(
                        $this->table_name.' Delivery Address'
                    ),
                    'formatted_value' => $this->get('Delivery Address'),
                );

                $this->add_changelog_record(
                    'Customer Delivery Address', $old_main_value, $this->get('Delivery Address'), '', $this->table_name, $this->id, 'set_as_main'
                );


                $this->updated = true;

                break;


            default:
                $this->error = true;
                $this->msg   = "Set as main $field not found";
                break;
        }

    }


    function delete_component($field, $component_key) {

        switch ($field) {
            case 'Customer Other Delivery Address':
                //$old_main_value=$this->data[$this->table_name.' Main Plain Email'];
                //$new_main_value=$this->get("$field $component_key");

                $old_value = $this->get(
                    "Other Delivery Address $component_key"
                );

                $sql = sprintf(
                    'DELETE FROM `Customer Other Delivery Address Dimension` WHERE `Customer Other Delivery Address Key`=%d', $component_key
                );
                $this->db->exec($sql);


                $this->add_changelog_record(
                    _("delivery address"), $old_value, '', '', $this->table_name, $this->id
                );


                $this->updated = true;

                break;

            case 'Customer Other Telephone':
            case 'Supplier Other Telephone':
                $old_main_value = $this->data[$this->table_name.' Main Plain Telephone'];
                $new_main_value = $this->get("$field $component_key");

                $this->update(
                    array(

                        $this->table_name.' Main Plain Telephone'     => $new_main_value,
                        "$field $component_key"                       => $old_main_value,
                        $this->table_name.' Preferred Contact Number' => 'Telephone',
                    ), 'no_history'
                );


                $this->add_changelog_record(
                    $this->table_name.' Main Telephone', $old_main_value, $new_main_value, '', $this->table_name, $this->id, 'set_as_main'
                );


                $this->other_fields_updated[$this->table_name.'_Main_Plain_Telephone']
                    = array(
                    'field'           => $this->table_name.'_Main_Plain_Telephone',
                    'render'          => true,
                    'value'           => $this->get(
                        $this->table_name.' Main Plain Telephone'
                    ),
                    'formatted_value' => $this->get('Main Plain Telephone'),
                    'label'           => ucfirst(
                            $this->get_field_label(
                                $this->table_name.' Main Plain Telephone'
                            )
                        ).($this->get($this->table_name.' Main Plain Telephone') != '' ? ($this->get(
                            $this->table_name.' Preferred Contact Number'
                        ) == 'Telephone'
                            ? ' <i title="'._(
                                'Main contact number'
                            ).'" class="fa fa-star discret"></i>'
                            : ' <i onClick="set_this_as_main(this)" title="'._(
                                'Set as main contact number'
                            ).'" class="fa fa-star-o discret button"></i>') : ''),

                );

                $this->other_fields_updated[preg_replace(
                    '/ /', '_', "$field $component_key"
                )]
                    = array(
                    'field'           => preg_replace(
                        '/ /', '_', "$field $component_key"
                    ),
                    'render'          => true,
                    'value'           => $this->get("$field $component_key"),
                    'formatted_value' => $this->get(
                        preg_replace(
                            '/'.$this->table_name.' /', '', "$field $component_key"
                        )
                    ),
                );


                $this->updated = true;

                break;

            default:
                $this->error = true;
                $this->msg   = "Set as main $field not found";
                break;
        }

    }

    function update_address($type, $fields, $options = '') {


        $old_value    = $this->get("$type Address");
        $old_checksum = $this->get("$type Address Checksum");


        $address_fields           = array();
        $updated_fields_number    = 0;
        $updated_recipient_fields = false;
        $updated_address_fields   = false;

        foreach ($fields as $field => $value) {
            $this->update_field(
                $this->table_name.' '.$type.' '.$field, $value, 'no_history'
            );
            if ($this->updated) {
                $updated_fields_number++;
                if ($field == 'Address Recipient' or $field == 'Address Organization') {
                    $updated_recipient_fields = true;
                } else {
                    $updated_address_fields = true;
                }
            }
        }


        if ($updated_fields_number > 0) {
            $this->updated = true;
        }


        if ($this->updated) {

            $this->update_address_formatted_fields($type, $options);


            if (!preg_match('/no( |\_)history|nohistory/i', $options)) {

                $this->add_changelog_record(
                    $this->table_name." $type Address", $old_value, $this->get("$type Address"), '', $this->table_name, $this->id
                );

            }


            if ($type == 'Contact') {


                $location = $this->get('Contact Address Locality');
                if ($location == '') {
                    $location = $this->get(
                        'Contact Address Administrative Area'
                    );
                }
                if ($location == '') {
                    $location = $this->get(
                        $this->table_name.' Contact Address Postal Code'
                    );
                }


                $this->update(
                    array(
                        $this->table_name.' Location' => trim(
                            sprintf(
                                '<img src="/art/flags/%s.gif" title="%s"> %s', strtolower(
                                    $this->get(
                                        'Contact Address Country 2 Alpha Code'
                                    )
                                ), $this->get(
                                'Contact Address Country 2 Alpha Code'
                            ), $location
                            )
                        )
                    ), 'no_history'
                );

            }

            if ($this->table_name == 'Customer') {

                if ($type == 'Contact' and $old_checksum == $this->get(
                        $this->table_name.' Invoice Address Checksum'
                    )
                ) {
                    $this->update_address('Invoice', $fields, $options);
                }

            }


        }

    }

    function update_address_formatted_fields($type, $options) {

        include_once 'utils/get_addressing.php';

        $new_checksum = md5(
            json_encode(
                array(
                    'Address Recipient'            => $this->get(
                        $type.' Address Recipient'
                    ),
                    'Address Organization'         => $this->get(
                        $type.' Address Organization'
                    ),
                    'Address Line 1'               => $this->get(
                        $type.' Address Line 1'
                    ),
                    'Address Line 2'               => $this->get(
                        $type.' Address Line 2'
                    ),
                    'Address Sorting Code'         => $this->get(
                        $type.' Address Sorting Code'
                    ),
                    'Address Postal Code'          => $this->get(
                        $type.' Address Postal Code'
                    ),
                    'Address Dependent Locality'   => $this->get(
                        $type.' Address Dependent Locality'
                    ),
                    'Address Locality'             => $this->get(
                        $type.' Address Locality'
                    ),
                    'Address Administrative Area'  => $this->get(
                        $type.' Address Administrative Area'
                    ),
                    'Address Country 2 Alpha Code' => $this->get(
                        $type.' Address Country 2 Alpha Code'
                    ),
                )
            )
        );


        $this->update_field(
            $this->table_name.' '.$type.' Address Checksum', $new_checksum, 'no_history'
        );


        if ($type == 'Delivery') {

            $account = new Account();
            $country = $account->get('Account Country 2 Alpha Code');
            $locale  = $account->get('Account Locale');
        } else {

            if ($this->get('Store Key')) {
                $store   = new Store($this->get('Store Key'));
                $country = $store->get('Store Home Country Code 2 Alpha');
                $locale  = $store->get('Store Locale');
            } else {
                $account = new Account();
                $country = $account->get('Account Country 2 Alpha Code');
                $locale  = $account->get('Account Locale');
            }
        }

        list($address, $formatter, $postal_label_formatter)
            = get_address_formatter($country, $locale);


        $address = $address->withRecipient($this->get($type.' Address Recipient'))->withOrganization($this->get($type.' Address Organization'))->withAddressLine1($this->get($type.' Address Line 1'))
            ->withAddressLine2($this->get($type.' Address Line 2'))->withSortingCode($this->get($type.' Address Sorting Code'))->withPostalCode($this->get($type.' Address Postal Code'))
            ->withDependentLocality(
                $this->get($type.' Address Dependent Locality')
            )->withLocality($this->get($type.' Address Locality'))->withAdministrativeArea(
                $this->get($type.' Address Administrative Area')
            )->withCountryCode(
                $this->get($type.' Address Country 2 Alpha Code')
            );


        $xhtml_address = $formatter->format($address);


        if ($this->get($type.' Address Recipient') == $this->get(
                'Main Contact Name'
            )
        ) {
            $xhtml_address = preg_replace(
                '/(class="recipient">.+<\/span>)<br>/', '$1', $xhtml_address
            );
        }

        if ($this->get($type.' Address Organization') == $this->get(
                'Company Name'
            )
        ) {
            $xhtml_address = preg_replace(
                '/(class="organization">.+<\/span>)<br>/', '$1', $xhtml_address
            );
        }

        $xhtml_address = preg_replace(
            '/class="recipient"/', 'class="recipient fn '.($this->get($type.' Address Recipient') == $this->get('Main Contact Name') ? 'hide' : '').'"', $xhtml_address
        );


        $xhtml_address = preg_replace(
            '/class="organization"/', 'class="organization org '.($this->get(
                $type.' Address Organization'
            ) == $this->get('Company Name') ? 'hide' : '').'"', $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address
        );
        $xhtml_address = preg_replace(
            '/class="country"/', 'class="country country-name"', $xhtml_address
        );


        $xhtml_address = preg_replace(
            '/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address
        );


        //print $xhtml_address;
        $this->update_field(
            $this->table_name.' '.$type.' Address Formatted', $xhtml_address, 'no_history'
        );
        $this->update_field(
            $this->table_name.' '.$type.' Address Postal Label', $postal_label_formatter->format($address), 'no_history'
        );

    }

    function update_subject_field_switcher($field, $value, $options = '', $metadata) {



        switch ($field) {


            case($this->table_name.' User Handle'):
            case($this->table_name.' User Password'):
            case($this->table_name.' User Active'):

                $this->get_user_data();


                $system_user         = new User(
                    $this->get($this->table_name.' User Key')
                );
                $system_user->editor = $this->editor;
                $user_field          = preg_replace(
                    '/^'.$this->table_name.' /', '', $field
                );
                //$old_value=$this->get($user_field);

                $system_user->update(array($user_field => $value), $options);
                $this->error   = $system_user->error;
                $this->msg     = $system_user->msg;
                $this->updated = $system_user->updated;
                $this->get_user_data();

                return true;
                break;

            case 'History Note':


                $this->add_note($value, '', '', $metadata['deletable']);
                break;
            case $this->table_name.' Contact Address':
                $this->update_address('Contact', json_decode($value, true));

                return true;
                break;

            case $this->table_name.' Main Plain Email':

                $this->update_email($field, $value, $options);

                return true;
                break;
            case $this->table_name.' Main Plain Telephone':

                $this->update_telephone($field, $value, $options);


                return true;
                break;


            case $this->table_name.' Main Plain Mobile':
            case $this->table_name.' Main Plain FAX':
                $value = preg_replace('/\s/', '', $value);
                if ($value == '+') {
                    $value = '';
                }


                if ($value != '') {

                    include_once 'utils/get_phoneUtil.php';
                    $phoneUtil = get_phoneUtil();

                    try {
                        if ($this->get('Contact Address Country 2 Alpha Code') == '' or $this->get(
                                'Contact Address Country 2 Alpha Code'
                            ) == 'XX'
                        ) {

                            if ($this->get('Store Key')) {
                                $store   = new Store($this->get('Store Key'));
                                $country = $store->get(
                                    'Home Country Code 2 Alpha'
                                );
                            } else {
                                $account = new Account();
                                $country = $account->get(
                                    'Country 2 Alpha Code'
                                );
                            }

                        } else {
                            $country = $this->get(
                                'Contact Address Country 2 Alpha Code'
                            );
                        }
                        $proto_number    = $phoneUtil->parse($value, $country);
                        $formatted_value = $phoneUtil->format(
                            $proto_number, \libphonenumber\PhoneNumberFormat::INTERNATIONAL
                        );

                        $value = $phoneUtil->format(
                            $proto_number, \libphonenumber\PhoneNumberFormat::E164
                        );


                    } catch (\libphonenumber\NumberParseException $e) {
                        $this->error = true;
                        $this->msg   = 'Error 1234';
                    }

                } else {
                    $formatted_value = '';

                }


                $this->update_field($field, $value, 'no_history');
                $this->update_field(
                    preg_replace('/Plain/', 'XHTML', $field), $formatted_value, 'no_history'
                );


                if ($field == $this->table_name.' Main Plain Mobile') {

                    $this->update_field_switcher(
                        $this->table_name.' Preferred Contact Number', ''
                    );

                    $this->other_fields_updated[$this->table_name.'_Main_Plain_Mobile']
                        = array(
                        'field'           => $this->table_name.'_Main_Plain_Mobile',
                        'render'          => true,
                        'label'           => ucfirst(
                                $this->get_field_label(
                                    $this->table_name.' Main Plain Mobile'
                                )
                            ).($this->get(
                                $this->table_name.' Main Plain Mobile'
                            ) != '' ? ($this->get(
                                $this->table_name.' Preferred Contact Number'
                            ) == 'Mobile' ? ' <i title="'._(
                                    'Main contact number'
                                ).'" class="fa fa-star discret"></i>' : ' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fa fa-star-o discret button"></i>')
                                : ''),
                        'value'           => $this->get(
                            $this->table_name.' Main Plain Mobile'
                        ),
                        'formatted_value' => $this->get('Main Plain Mobile')
                    );
                    $this->other_fields_updated[$this->table_name.'_Main_Plain_Telephone']
                        = array(
                        'field'  => $this->table_name.'_Main_Plain_Telephone',
                        'render' => true,
                        'label'  => ucfirst(
                                $this->get_field_label(
                                    $this->table_name.' Main Plain Telephone'
                                )
                            ).($this->get(
                                $this->table_name.' Main Plain Telephone'
                            ) != '' ? ($this->get(
                                $this->table_name.' Preferred Contact Number'
                            ) == 'Telephone' ? ' <i title="'._(
                                    'Main contact number'
                                ).'" class="fa fa-star discret"></i>' : ' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fa fa-star-o discret button"></i>')
                                : ''),

                    );
                } else {
                    $this->other_fields_updated[$this->table_name.'_Main_Plain_FAX']
                        = array(
                        'field'           => $this->table_name.'_Main_Plain_FAX',
                        'render'          => true,
                        'label'           => ucfirst(
                            $this->get_field_label(
                                $this->table_name.' Main Plain FAX'
                            )
                        ),
                        'value'           => $this->get(
                            $this->table_name.' Main Plain FAX'
                        ),
                        'formatted_value' => $this->get('Main Plain FAX')
                    );

                }


                return true;
                break;
            case 'new email':
                $this->add_other_email($value, $options);

                return true;
                break;
            case 'new telephone':
                $this->add_other_telephone($value, $options);

                return true;

                break;

            case $this->table_name.' Preferred Contact Number':


                if ($value == '') {
                    $value = $this->data[$this->table_name.' Preferred Contact Number'];

                    if ($value == '') {
                        $value = 'Mobile';
                    }

                    if ($this->data[$this->table_name.' Main Plain Mobile'] == '' and $this->data[$this->table_name.' Main Plain Telephone'] != '') {
                        $value = 'Telephone';
                    } elseif ($this->data[$this->table_name.' Main Plain Mobile'] != '' and $this->data[$this->table_name.' Main Plain Telephone'] == '') {
                        $value = 'Mobile';
                    } elseif ($this->data[$this->table_name.' Main Plain Mobile'] == '' and $this->data[$this->table_name.' Main Plain Telephone'] == '') {
                        $value = 'Mobile';
                    }

                }


                $this->update_field($field, $value, $options);
                $this->update_field(
                    $this->table_name.' Preferred Contact Number Formatted Number', $this->get('Main XHTML '.$value), $options
                );


                $this->other_fields_updated[$this->table_name.'_Main_Plain_Mobile']
                    = array(
                    'field'  => $this->table_name.'_Main_Plain_Mobile',
                    'render' => true,
                    'label'  => ucfirst(
                            $this->get_field_label(
                                $this->table_name.' Main Plain Mobile'
                            )
                        ).($this->get($this->table_name.' Main Plain Mobile') != '' ? ($this->get(
                            $this->table_name.' Preferred Contact Number'
                        ) == 'Mobile'
                            ? ' <i title="'._('Main contact number').'" class="fa fa-star discret"></i>'
                            : ' <i onClick="set_this_as_main(this)" title="'._(
                                'Set as main contact number'
                            ).'" class="fa fa-star-o discret button"></i>') : ''),
                );

                $this->other_fields_updated[$this->table_name.'_Main_Plain_Telephone']
                    = array(
                    'field'  => $this->table_name.'_Main_Plain_Telephone',
                    'render' => true,
                    'label'  => ucfirst(
                            $this->get_field_label(
                                $this->table_name.' Main Plain Telephone'
                            )
                        ).($this->get($this->table_name.' Main Plain Telephone') != '' ? ($this->get(
                            $this->table_name.' Preferred Contact Number'
                        ) == 'Telephone'
                            ? ' <i title="'._(
                                'Main contact number'
                            ).'" class="fa fa-star discret"></i>'
                            : ' <i onClick="set_this_as_main(this)" title="'._(
                                'Set as main contact number'
                            ).'" class="fa fa-star-o discret button"></i>') : ''),
                );


                return true;

                break;
            case $this->table_name.' Company Name':

                $old_value = $this->get('Company Name');

                if ($value == '' and $this->data[$this->table_name.' Main Contact Name'] == '') {
                    $this->msg   = _(
                        "Company name can't be emply if the contact name is empty as well"
                    );
                    $this->error = true;

                    return true;
                }

                $this->update_field($field, $value, $options);
                if ($value == '') {
                    $this->update_field(
                        $this->table_name.' Name', $this->data[$this->table_name.' Main Contact Name'], 'no_history'
                    );

                } else {
                    $this->update_field(
                        $this->table_name.' Name', $value, 'no_history'
                    );
                }

                if ($old_value == $this->get('Contact Address Organization')) {
                    $this->update_field(
                        $this->table_name.' Contact Address Organization', $value, 'no_history'
                    );
                    $this->update_address_formatted_fields(
                        'Contact', 'no_history'
                    );

                }

                if ($this->table_name == 'Customer') {

                    if ($old_value == $this->get(
                            'Invoice Address Organization'
                        )
                    ) {
                        $this->update_field(
                            $this->table_name.' Invoice Address Organization', $value, 'no_history'
                        );
                        $this->update_address_formatted_fields(
                            'Invoice', 'no_history'
                        );

                    }
                }

                $this->other_fields_updated = array(
                    $this->table_name.'_Name' => array(
                        'field'           => $this->table_name.'_Name',
                        'render'          => true,
                        'value'           => $this->get(
                            $this->table_name.' Name'
                        ),
                        'formatted_value' => $this->get('Name'),


                    )
                );

                return true;
                break;
            case $this->table_name.' Main Contact Name':

                $old_value = $this->get('Main Contact Name');

                if ($value == '' and $this->data[$this->table_name.' Company Name'] == '') {
                    $this->msg   = "Contact name can't be empty if the company name is empty";
                    $this->error = true;

                    return;
                }

                $this->update_field($field, $value, $options);
                if ($this->data[$this->table_name.' Company Name'] == '') {
                    $this->update_field($this->table_name.' Name', $value, 'no_history');

                }


                if ($old_value == $this->get('Contact Address Recipient')) {
                    $this->update_field($this->table_name.' Contact Address Recipient', $value, 'no_history');
                    $this->update_address_formatted_fields('Contact', 'no_history');

                }
                if ($this->table_name == 'Customer') {
                    if ($old_value == $this->get('Invoice Address Recipient')) {
                        $this->update_field($this->table_name.' Invoice Address Recipient', $value, 'no_history');
                        $this->update_address_formatted_fields('Invoice', 'no_history');

                    }
                }

                $this->other_fields_updated = array(
                    $this->table_name.'_Name' => array(
                        'field'           => $this->table_name.'_Name',
                        'render'          => true,
                        'value'           => $this->get($this->table_name.' Name'),
                        'formatted_value' => $this->get('Name'),


                    )
                );

                return true;
                break;


            default:


                if (preg_match('/^History Note (\d+)/i', $field, $matches)) {
                    $history_key = $matches[1];
                    $this->edit_note($history_key, $value);

                    return true;
                }

                if (preg_match(
                    '/^History Note Strikethrough (\d+)/i', $field, $matches
                )) {
                    $history_key = $matches[1];
                    $this->edit_note_strikethrough($history_key, $value);

                    return true;
                }

                if (preg_match(
                    '/^'.$this->table_name.' Other Email (\d+)/i', $field, $matches
                )) {
                    $other_email_key = $matches[1];
                    $old_value       = $this->get($field);
                    if ($value == '') {
                        $old_value = $this->get(
                            preg_replace(
                                '/^'.$this->table_name.' /', '', $field
                            )
                        );
                        $sql       = sprintf(
                            'DELETE FROM `%s Other Email Dimension`  WHERE `%s Other Email %s Key`=%d AND `%s Other Email Key`=%d ', addslashes($this->table_name), addslashes($this->table_name),
                            addslashes($this->table_name), $this->id, addslashes($this->table_name), $other_email_key
                        );
                        $prep      = $this->db->prepare($sql);
                        $prep->execute();
                        if ($prep->rowCount()) {

                            $this->deleted       = true;
                            $this->deleted_value = $old_value;
                            $this->add_changelog_record(
                                $this->table_name.' Other Email', $old_value, '', $options, $this->table_name, $this->id, 'removed'
                            );

                        } else {

                        }
                    } else {
                        $sql = sprintf(
                            'UPDATE `%s Other Email Dimension` SET `%s Other Email Email`=%s WHERE `%s Other Email %s Key`=%d AND `%s Other Email Key`=%d ', addslashes($this->table_name),
                            addslashes($this->table_name), prepare_mysql($value), addslashes($this->table_name), addslashes($this->table_name), $this->id, addslashes($this->table_name),
                            $other_email_key
                        );
                        $tmp = $this->db->prepare($sql);
                        $tmp->execute();
                        if ($tmp->rowCount()) {
                            $this->add_changelog_record(
                                $this->table_name.' Other Email', $old_value, $value, $options, $this->table_name, $this->id
                            );

                            $this->updated = true;
                        } else {

                        }

                    }

                    $this->other_fields_updated = array(
                        $this->table_name.'_Other_Email_'.$other_email_key => array(
                            'field'           => $this->table_name.'_Other_Email_'.$other_email_key,
                            'render'          => true,
                            'value'           => $value,
                            'formatted_value' => $value,
                            'formatted_email' => sprintf(
                                '<a href="mailto:%s" >%s</a>', $value, $value
                            ),
                            'field_type'      => $this->table_name.'_Other_Email',
                        ),

                    );


                    return true;
                }

                if (preg_match(
                    '/^'.$this->table_name.' Other Telephone (\d+)/i', $field, $matches
                )) {


                    $subject_telephone_key = $matches[1];
                    $old_value             = $this->get($field);

                    $value = preg_replace('/\s/', '', $value);
                    if ($value == '+') {
                        $value = '';
                    }

                    if ($value == '') {
                        $old_value = $this->get(
                            preg_replace(
                                '/^'.$this->table_name.' /', '', $field
                            )
                        );
                        $sql       = sprintf(
                            'DELETE FROM `%s Other Telephone Dimension`  WHERE `%s Other Telephone %s Key`=%d AND `%s Other Telephone Key`=%d ', addslashes($this->table_name),
                            addslashes($this->table_name), addslashes($this->table_name), $this->id, addslashes($this->table_name), $subject_telephone_key
                        );
                        $prep      = $this->db->prepare($sql);
                        $prep->execute();
                        if ($prep->rowCount()) {

                            $this->deleted       = true;
                            $this->deleted_value = $old_value;
                            $this->add_changelog_record(
                                'Customer Other Telephone', $old_value, '', $options, $this->table_name, $this->id, 'removed'
                            );

                        } else {

                        }
                        $formatted_value = '';
                    } else {

                        include_once 'utils/get_phoneUtil.php';
                        $phoneUtil = get_phoneUtil();

                        try {
                            if ($this->get(
                                    'Contact Address Country 2 Alpha Code'
                                ) == '' or $this->get(
                                    'Contact Address Country 2 Alpha Code'
                                ) == 'XX'
                            ) {
                                if ($this->get('Store Key')) {
                                    $store   = new Store(
                                        $this->get('Store Key')
                                    );
                                    $country = $store->get(
                                        'Home Country Code 2 Alpha'
                                    );
                                } else {
                                    $account = new Account();
                                    $country = $account->get(
                                        'Country 2 Alpha Code'
                                    );
                                }
                            } else {
                                $country = $this->get(
                                    'Contact Address Country 2 Alpha Code'
                                );
                            }
                            $proto_number    = $phoneUtil->parse(
                                $value, $country
                            );
                            $formatted_value = $phoneUtil->format(
                                $proto_number, \libphonenumber\PhoneNumberFormat::INTERNATIONAL
                            );
                            $value           = $phoneUtil->format(
                                $proto_number, \libphonenumber\PhoneNumberFormat::E164
                            );


                        } catch (\libphonenumber\NumberParseException $e) {

                        }

                        $sql = sprintf(
                            'UPDATE `%s Other Telephone Dimension` SET `%s Other Telephone Number`=%s, `%s Other Telephone Formatted Number`=%s WHERE `%s Other Telephone %s Key`=%d AND `%s Other Telephone Key`=%d ',
                            $this->table_name, $this->table_name, prepare_mysql($value), $this->table_name, prepare_mysql($formatted_value), $this->table_name, $this->table_name,

                            $this->id, $this->table_name, $subject_telephone_key
                        );
                        $tmp = $this->db->prepare($sql);


                        $tmp->execute();
                        if ($tmp->rowCount()) {
                            $this->add_changelog_record(
                                'Customer Other Telephone', $old_value, $value, $options, $this->table_name, $this->id
                            );

                            $this->updated = true;
                        } else {

                        }

                    }


                    $this->other_fields_updated = array(
                        $this->table_name.'_Other_Telephone_'.$subject_telephone_key => array(
                            'field'           => $this->table_name.'_Other_Telephone_'.$subject_telephone_key,
                            'render'          => true,
                            'value'           => $value,
                            'formatted_value' => $formatted_value,
                            'field_type'      => $this->table_name.'_Other_Telephone',
                        ),

                    );

                    return true;
                }

                if (preg_match(
                    '/^'.$this->table_name.' Other Telephone Label (\d+)/i', $field, $matches
                )) {
                    $subject_telephone_key = $matches[1];
                    $old_value             = $this->get($field);


                    $sql = sprintf(
                        'UPDATE `Customer Other Telephone Dimension` SET `Customer Other Telephone Label`=%s  WHERE `Customer Other Telephone Customer Key`=%d AND `Customer Other Telephone Key`=%d ',
                        prepare_mysql($value), $this->id, $subject_telephone_key
                    );
                    $tmp = $this->db->prepare($sql);
                    $tmp->execute();
                    if ($tmp->rowCount()) {
                        $this->add_changelog_record(
                            'Customer Other Telephone Label', $old_value, $value, $options, $this->table_name, $this->id
                        );

                        $this->updated = true;
                    } else {

                    }


                    return true;
                }

                return false;
        }


        return false;
    }

    function update_email($field, $value, $options) {

        if ($value == '' and count(
                $other_emails_data = $this->get_other_emails_data()
            ) > 0
        ) {
            $old_value = $this->get($field);
            foreach ($other_emails_data as $other_key => $other_value) {
                break;
            }
            $this->update_field($field, $other_value['email'], 'no_history');
            $sql  = sprintf(
                'DELETE FROM `%s Other Email Dimension`  WHERE `%s Other Email %s Key`=%d AND `%s Other Email Key`=%d ', addslashes($this->table_name), addslashes($this->table_name),
                addslashes($this->table_name), $this->id, addslashes($this->table_name), $other_key
            );
            $prep = $this->db->prepare($sql);
            $prep->execute();

            $this->deleted_fields_info = array(
                preg_replace(
                    '/ /', '_', $this->table_name.' Other Email '.$other_key
                ) => array(
                    'field' => preg_replace(
                        '/ /', '_', $this->table_name.' Other Email '.$other_key
                    )
                )
            );
            $this->add_changelog_record(
                $this->table_name.' Main Plain Email', $old_value, '', $options, $this->table_name, $this->id
            );
            $this->add_changelog_record(
                $this->table_name.' Main Email', $old_value, $other_value['email'], $options, $this->table_name, $this->id, 'set_as_main'
            );

        } else {

            if ($this->table_name == 'Customer') {

                $sql = sprintf(
                    'SELECT `%s Key`,`%s Name` FROM `%s Dimension`  WHERE `%s Main Plain Email`=%s AND `%s Store Key`=%d AND `%s Key`!=%d ', addslashes($this->table_name),
                    addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), prepare_mysql($value), addslashes($this->table_name), $this->get('Store Key'),
                    addslashes($this->table_name), $this->id
                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        if ($this->table_name == 'Customer') {
                            $msg = _('Another customer has this email');
                        } else {
                            $msg = _('Another object has this email');
                        }

                        $this->error = true;
                        $this->msg   = $msg;

                        return;
                    }

                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

                $sql = sprintf(
                    'SELECT `%s Key`,`%s Name` FROM `%s Other Email Dimension` LEFT JOIN `%s Dimension` ON (`%s Key`=`%s Other Email %s Key`) WHERE `%s Other Email Email`=%s AND `%s Other Email Store Key`=%d ',
                    addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name),
                    addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), prepare_mysql($value), addslashes($this->table_name), $this->get('Store Key')
                );
                //print "$sql\n";
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        if ($this->table_name == 'Customer') {
                            if ($row[$this->table_name.' Key'] == $this->id) {
                                $msg = _('Customer has already this email');
                            } else {
                                $msg = _('Another customer has this email');
                            }
                        } else {
                            $msg = _('Another object has this email');
                        }

                        $this->error = true;
                        $this->msg   = $msg;

                        return;
                    }

                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


            } else {

                $sql = sprintf(
                    'SELECT `%s Key`,`%s Name` FROM `%s Dimension`  WHERE `%s Main Plain Email`=%s AND `%s Key`!=%d ', addslashes($this->table_name), addslashes($this->table_name),
                    addslashes($this->table_name), addslashes($this->table_name), prepare_mysql($value), addslashes($this->table_name), $this->id

                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        if ($this->table_name == 'Supplier') {
                            $msg = _('Another supplier has this email');
                        } else {
                            $msg = _('Another object has this email');
                        }

                        $this->error = true;
                        $this->msg   = $msg;

                        return;
                    }

                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                $sql = sprintf(
                    'SELECT `%s Key`,`%s Name` FROM `%s Other Email Dimension` LEFT JOIN `%s Dimension` ON (`%s Key`=`%s Other Email %s Key`) WHERE `%s Other Email Email`=%s ',
                    addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name),
                    addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), prepare_mysql($value)

                );
                //print "$sql\n";
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        if ($this->table_name == 'Supplier') {
                            if ($row[$this->table_name.' Key'] == $this->id) {
                                $msg = _('Supplier has already this email');
                            } else {
                                $msg = _('Another supplier has this email');
                            }
                        } else {
                            $msg = _('Duplicated email');
                        }

                        $this->error = true;
                        $this->msg   = $msg;

                        return;
                    }

                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

            }


            $this->update_field($field, $value, $options);
        }


        $this->other_fields_updated = array(
            $this->table_name.'_Main_Plain_Email' => array(
                'field'           => $this->table_name.'_Main_Plain_Email',
                'render'          => true,
                'value'           => $this->get(
                    $this->table_name.' Main Plain Email'
                ),
                'formatted_value' => $this->get('Main Plain Email'),
            ),

        );

    }

    function get_other_emails_data() {

        $sql = sprintf(
            "SELECT `%s Other Email Key`,`%s Other Email Email`,`%s Other Email Label` FROM `%s Other Email Dimension` WHERE `%s Other Email %s Key`=%d ORDER BY `%s Other Email Key`",
            addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name),
            $this->id, addslashes($this->table_name)

        );

        $email_keys = array();

        if ($result = $this->db->query($sql)) {

            foreach ($result as $row) {
                $email_keys[$row[$this->table_name.' Other Email Key']] = array(
                    'email' => $row[$this->table_name.' Other Email Email'],
                    'label' => $row[$this->table_name.' Other Email Label'],
                );
            }

        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $email_keys;

    }

    function update_telephone($field, $value, $options) {


        $value = preg_replace('/\s/', '', $value);
        if ($value == '+') {
            $value = '';
        }

        if ($value == '' and count(
                $other_telephones_data = $this->get_other_telephones_data()
            ) > 0
        ) {


            $old_value = $this->get($field);
            foreach ($other_telephones_data as $other_key => $other_value) {
                break;
            }
            $this->update(
                array($field => $other_value['telephone']), 'no_history'
            );
            $sql  = sprintf(
                'DELETE FROM `%s Other Telephone Dimension`  WHERE `%s Other Telephone %s Key`=%d AND `%s Other Telephone Key`=%d ', addslashes($this->table_name), addslashes($this->table_name),
                addslashes($this->table_name), $this->id, addslashes($this->table_name), $other_key
            );
            $prep = $this->db->prepare($sql);
            $prep->execute();

            $this->deleted_fields_info = array(
                preg_replace(
                    '/ /', '_', $this->table_name.' Other Telephone '.$other_key
                ) => array(
                    'field' => preg_replace(
                        '/ /', '_', $this->table_name.' Other Telephone '.$other_key
                    )
                )
            );
            $this->add_changelog_record(
                $this->table_name.' Main Plain Telephone', $old_value, '', $options, $this->table_name, $this->id
            );
            $this->add_changelog_record(
                $this->table_name.' Main Telephone', $old_value, $other_value['telephone'], $options, $this->table_name, $this->id, 'set_as_main'
            );


        } else {

            if ($value != '') {

                list($value, $formatted_value) = $this->get_formatted_number(
                    $value
                );


            } else {
                $formatted_value = '';
            }


            $this->update_field($field, $value, 'no_history');


            $this->update_field(
                preg_replace('/Plain/', 'XHTML', $field), $formatted_value, 'no_history'
            );

            $this->update_field_switcher(
                $this->table_name.' Preferred Contact Number', '', $options
            );


            $this->other_fields_updated = array(
                $this->table_name.'_Main_Plain_Mobile'    => array(
                    'field'  => $this->table_name.'_Main_Plain_Mobile',
                    'render' => true,
                    'label'  => ucfirst(
                            $this->get_field_label(
                                $this->table_name.' Main Plain Mobile'
                            )
                        ).($this->get($this->table_name.' Main Plain Mobile') != '' ? ($this->get(
                            $this->table_name.' Preferred Contact Number'
                        ) == 'Mobile'
                            ? ' <i title="'._('Main contact number').'" class="fa fa-star discret"></i>'
                            : ' <i onClick="set_this_as_main(this)" title="'._(
                                'Set as main contact number'
                            ).'" class="fa fa-star-o discret button"></i>') : ''),
                ),
                $this->table_name.'_Main_Plain_Telephone' => array(
                    'field'  => $this->table_name.'_Main_Plain_Telephone',
                    'render' => true,
                    'label'  => ucfirst(
                            $this->get_field_label(
                                $this->table_name.' Main Plain Telephone'
                            )
                        ).($this->get($this->table_name.' Main Plain Telephone') != '' ? ($this->get(
                            $this->table_name.' Preferred Contact Number'
                        ) == 'Telephone'
                            ? ' <i title="'._(
                                'Main contact number'
                            ).'" class="fa fa-star discret"></i>'
                            : ' <i onClick="set_this_as_main(this)" title="'._(
                                'Set as main contact number'
                            ).'" class="fa fa-star-o discret button"></i>') : ''),
                )
            );
        }


        $this->other_fields_updated[$this->table_name.'_Main_Plain_Mobile']
            = array(
            'field'           => $this->table_name.'_Main_Plain_Mobile',
            'render'          => true,
            'label'           => ucfirst(
                    $this->get_field_label(
                        $this->table_name.' Main Plain Mobile'
                    )
                ).($this->get($this->table_name.' Main Plain Mobile') != '' ? ($this->get($this->table_name.' Preferred Contact Number') == 'Mobile'
                    ? ' <i title="'._('Main contact number').'" class="fa fa-star discret"></i>'
                    : ' <i onClick="set_this_as_main(this)" title="'._(
                        'Set as main contact number'
                    ).'" class="fa fa-star-o discret button"></i>') : ''),
            'value'           => $this->get(
                $this->table_name.' Main Plain Mobile'
            ),
            'formatted_value' => $this->get('Main Plain Mobile')
        );
        $this->other_fields_updated[$this->table_name.'_Main_Plain_Telephone']
            = array(
            'field'           => $this->table_name.'_Main_Plain_Telephone',
            'render'          => true,
            'label'           => ucfirst(
                    $this->get_field_label(
                        $this->table_name.' Main Plain Telephone'
                    )
                ).($this->get($this->table_name.' Main Plain Telephone') != '' ? ($this->get($this->table_name.' Preferred Contact Number') == 'Telephone'
                    ? ' <i title="'._('Main contact number').'" class="fa fa-star discret"></i>'
                    : ' <i onClick="set_this_as_main(this)" title="'._(
                        'Set as main contact number'
                    ).'" class="fa fa-star-o discret button"></i>') : ''),
            'value'           => $this->get(
                $this->table_name.' Main Plain Telephone'
            ),
            'formatted_value' => $this->get('Main Plain Telephone')
        );


    }

    function get_other_telephones_data() {

        $sql = sprintf(
            "SELECT `%s Other Telephone Key`,`%s Other Telephone Number`,`%s Other Telephone Formatted Number`,`%s Other Telephone Label` FROM `%s Other Telephone Dimension` WHERE `%s Other Telephone %s Key`=%d ORDER BY `%s Other Telephone Key`",
            addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name),
            addslashes($this->table_name), $this->id, addslashes($this->table_name)
        );

        $telephone_keys = array();

        if ($result = $this->db->query($sql)) {

            foreach ($result as $row) {
                $telephone_keys[$row[$this->table_name.' Other Telephone Key']]
                    = array(
                    'telephone'           => $row[$this->table_name.' Other Telephone Number'],
                    'formatted_telephone' => $row[$this->table_name.' Other Telephone Formatted Number'],
                    'label'               => $row[$this->table_name.' Other Telephone Label'],
                );
            }

        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $telephone_keys;

    }

    function get_formatted_number($value) {
        include_once 'utils/get_phoneUtil.php';
        $phoneUtil = get_phoneUtil();
        try {
            if ($this->get('Contact Address Country 2 Alpha Code') == '' or $this->get('Contact Address Country 2 Alpha Code') == 'XX') {

                if ($this->get('Store Key')) {
                    $store   = new Store($this->get('Store Key'));
                    $country = $store->get('Home Country Code 2 Alpha');
                } else {
                    $account = new Account();
                    $country = $account->get('Country 2 Alpha Code');
                }

            } else {
                $country = $this->get('Contact Address Country 2 Alpha Code');
            }
            $proto_number    = $phoneUtil->parse($value, $country);
            $formatted_value = $phoneUtil->format(
                $proto_number, \libphonenumber\PhoneNumberFormat::INTERNATIONAL
            );

            $value = $phoneUtil->format(
                $proto_number, \libphonenumber\PhoneNumberFormat::E164
            );


        } catch (\libphonenumber\NumberParseException $e) {

        }

        return array(
            $value,
            $formatted_value
        );

    }

    function add_other_email($value, $options = '') {


        if ($this->table_name == 'Customer') {

            $sql = sprintf(
                'SELECT `%s Key`,`%s Name` FROM `%s Dimension`  WHERE `%s Main Plain Email`=%s AND `%s Store Key`=%d ', addslashes($this->table_name), addslashes($this->table_name),
                addslashes($this->table_name), addslashes($this->table_name), prepare_mysql($value), addslashes($this->table_name), $this->get('Store Key')
            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    if ($this->table_name == 'Customer') {
                        if ($row[$this->table_name.' Key'] == $this->id) {
                            $msg = _('Customer has already this email');
                        } else {
                            $msg = _('Another customer has this email');
                        }
                    } else {
                        $msg = _('Duplicated email');
                    }

                    $this->error = true;
                    $this->msg   = $msg;

                    return;
                }

            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            $sql = sprintf(
                'SELECT `%s Key`,`%s Name` FROM `%s Other Email Dimension` LEFT JOIN `%s Dimension` ON (`%s Key`=`%s Other Email %s Key`) WHERE `%s Other Email Email`=%s AND `%s Other Email Store Key`=%d ',
                addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name),
                addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), prepare_mysql($value), addslashes($this->table_name), $this->get('Store Key')
            );
            //print "$sql\n";
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    if ($this->table_name == 'Customer') {
                        if ($row[$this->table_name.' Key'] == $this->id) {
                            $msg = _('Customer has already this email');
                        } else {
                            $msg = _('Another customer has this email');
                        }
                    } else {
                        $msg = _('Duplicated email');
                    }

                    $this->error = true;
                    $this->msg   = $msg;

                    return;
                }

            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            $sql = sprintf(
                'INSERT INTO `%s Other Email Dimension` (`%s Other Email Store Key`,`%s Other Email %s Key`,`%s Other Email Email`) VALUES (%d,%d,%s)', addslashes($this->table_name),
                addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), $this->get('Store Key'), $this->id, prepare_mysql($value)
            );
        } else {

            $sql = sprintf(
                'SELECT `%s Key`,`%s Name` FROM `%s Dimension`  WHERE `%s Main Plain Email`=%s ', addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name),
                addslashes($this->table_name), prepare_mysql($value)

            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    if ($this->table_name == 'Supplier') {
                        if ($row[$this->table_name.' Key'] == $this->id) {
                            $msg = _('Supplier has already this email');
                        } else {
                            $msg = _('Another supplier has this email');
                        }
                    } else {
                        $msg = _('Duplicated email');
                    }

                    $this->error = true;
                    $this->msg   = $msg;

                    return;
                }

            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            $sql = sprintf(
                'SELECT `%s Key`,`%s Name` FROM `%s Other Email Dimension` LEFT JOIN `%s Dimension` ON (`%s Key`=`%s Other Email %s Key`) WHERE `%s Other Email Email`=%s ',
                addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name),
                addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), prepare_mysql($value)

            );
            //print "$sql\n";
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    if ($this->table_name == 'Supplier') {
                        if ($row[$this->table_name.' Key'] == $this->id) {
                            $msg = _('Supplier has already this email');
                        } else {
                            $msg = _('Another supplier has this email');
                        }
                    } else {
                        $msg = _('Duplicated email');
                    }

                    $this->error = true;
                    $this->msg   = $msg;

                    return;
                }

            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            $sql = sprintf(
                'INSERT INTO `%s Other Email Dimension` (`%s Other Email %s Key`,`%s Other Email Email`) VALUES (%d,%s)', addslashes($this->table_name), addslashes($this->table_name),
                addslashes($this->table_name), addslashes($this->table_name), $this->id, prepare_mysql($value)
            );

        }

        $prep = $this->db->prepare($sql);


        try {
            $prep->execute();

            $inserted_key = $this->db->lastInsertId();
            if ($inserted_key) {

                $this->field_created   = true;
                $field_id              = $this->table_name.'_Other_Email_'.$inserted_key;
                $field                 = preg_replace('/_/', ' ', $field_id);
                $this->new_fields_info = array(
                    array(
                        'clone_from'      => $this->table_name.'_Other_Email',
                        'field'           => $this->table_name.'_Other_Email_'.$inserted_key,
                        'render'          => true,
                        'edit'            => 'email',
                        'value'           => $this->get($field),
                        'formatted_value' => $this->get($field),
                        'label'           => ucfirst(
                                $this->get_field_label(
                                    $this->table_name.' Other Email'
                                )
                            ).' <i onClick="set_this_as_main(this)" title="'._(
                                'Set as main email'
                            ).'" class="fa fa-star-o very_discret button"></i>',


                    )
                );
                $this->add_changelog_record(
                    $this->table_name.' Other Email', '', $value, $options, $this->table_name, $this->id, 'added'
                );

            } else {
                $this->error = true;
                $this->msg   = _('Duplicated email');
            }

        } catch (PDOException $e) {
            $this->error = true;

            if ($e->errorInfo[0] == '23000' && $e->errorInfo[1] == '1062') {
                $this->msg = _('Duplicated email');
            } else {

                $this->msg = $e->getMessage();
            }

        }

    }

    function add_other_telephone($value, $options = '') {

        $this->field_created     = false;
        $this->field_created_key = false;


        $value = preg_replace('/\s/', '', $value);
        if ($value == '+') {
            $value = '';
        }
        if ($value == '') {
            $this->error = true;
            $this->msg   = _('Invalid value');

            return;

        }

        include_once 'utils/get_phoneUtil.php';
        $phoneUtil = get_phoneUtil();

        try {


            if ($this->get('Contact Address Country 2 Alpha Code') == '' or $this->get('Contact Address Country 2 Alpha Code') == 'XX') {
                if ($this->get('Store Key')) {
                    $store   = new Store($this->get('Store Key'));
                    $country = $store->get('Home Country Code 2 Alpha');
                } else {
                    $account = new Account();
                    $country = $account->get('Country 2 Alpha Code');
                }
            } else {
                $country = $this->get('Contact Address Country 2 Alpha Code');
            }
            $proto_number    = $phoneUtil->parse($value, $country);
            $formatted_value = $phoneUtil->format(
                $proto_number, \libphonenumber\PhoneNumberFormat::INTERNATIONAL
            );
            $value           = $phoneUtil->format(
                $proto_number, \libphonenumber\PhoneNumberFormat::E164
            );


        } catch (\libphonenumber\NumberParseException $e) {

        }


        $sql = sprintf(
            'SELECT `%s Key` FROM `%s Dimension`  WHERE  `%s Key`=%d  AND (`%s Main Plain Telephone`=%s OR `%s Main Plain Mobile`=%s OR `%s Main Plain FAX`=%s)  ', addslashes($this->table_name),
            addslashes($this->table_name), addslashes($this->table_name), $this->id, addslashes($this->table_name), prepare_mysql($value), addslashes($this->table_name), prepare_mysql($value),
            addslashes($this->table_name), prepare_mysql($value)

        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($this->table_name == 'Customer') {
                    $msg = _('Customer has already this number');

                }
                if ($this->table_name == 'Supplier') {
                    $msg = _('Supplier has already this number');

                } else {
                    $msg = _('Object has already this number');
                }

                $this->error = true;
                $this->msg   = $msg;

                return;
            }

        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $sql = sprintf(
            'SELECT `%s Other Telephone Key` FROM `%s Other Telephone Dimension`  WHERE  `%s Other Telephone %s Key`=%d  AND `%s Other Telephone Number`=%s  ', addslashes($this->table_name),
            addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name),

            $this->id, addslashes($this->table_name), prepare_mysql($value)


        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($this->table_name == 'Customer') {
                    $msg = _('Customer has already this number');

                }
                if ($this->table_name == 'Supplier') {
                    $msg = _('Supplier has already this number');

                } else {
                    $msg = _('Object has already this number');
                }

                $this->error = true;
                $this->msg   = $msg;

                return;
            }

        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($this->table_name == 'Customer') {
            $sql = sprintf(
                'INSERT INTO `%s Other Telephone Dimension` (`%s Other Telephone Store Key`,`%s Other Telephone %s Key`,`%s Other Telephone Number`,`%s Other Telephone Formatted Number`) VALUES (%d,%d,%s,%s)',
                addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name),
                addslashes($this->table_name), $this->get('Store Key'), $this->id, prepare_mysql($value), prepare_mysql($formatted_value)
            );
        } else {
            $sql = sprintf(
                'INSERT INTO `%s Other Telephone Dimension` (`%s Other Telephone %s Key`,`%s Other Telephone Number`,`%s Other Telephone Formatted Number`) VALUES (%d,%s,%s)',
                addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), $this->id,
                prepare_mysql($value), prepare_mysql($formatted_value)
            );
        }


        $prep = $this->db->prepare($sql);


        try {
            $prep->execute();

            $inserted_key = $this->db->lastInsertId();
            if ($inserted_key) {

                $this->field_created     = true;
                $this->field_created_key = $inserted_key;
                $field_id                = $this->table_name.'_Other_Telephone_'.$inserted_key;
                $field                   = preg_replace('/_/', ' ', $field_id);
                $this->new_fields_info   = array(
                    array(
                        'clone_from'      => $this->table_name.'_Other_Telephone',
                        'field'           => $this->table_name.'_Other_Telephone_'.$inserted_key,
                        'render'          => true,
                        'edit'            => 'telephone',
                        'value'           => $this->get($field),
                        'formatted_value' => $this->get(
                            preg_replace('/'.$this->table_name.' /', '', $field)
                        ),
                        'label'           => ucfirst(
                                $this->get_field_label(
                                    $this->table_name.' Other Telephone'
                                )
                            ).' <i onClick="set_this_as_main(this)" title="'._(
                                'Set as main telephone'
                            ).'" class="fa fa-star-o very_discret button"></i>',


                    )
                );
                $this->add_changelog_record(
                    $this->table_name.' Other Telephone', '', $value, $options, $this->table_name, $this->id, 'added'
                );

            } else {
                $this->error = true;
                $this->msg   = _('Duplicated telephone');
            }

        } catch (PDOException $e) {
            $this->error = true;

            if ($e->errorInfo[0] == '23000' && $e->errorInfo[1] == '1062') {
                $this->msg = _('Duplicated telephone');
            } else {

                $this->msg = $e->getMessage();
            }

        }

    }

    function get_subject_common($key, $arg1 = '') {

        switch ($key) {
            case $this->table_name.' Contact Address':
            case $this->table_name.' Invoice Address':
            case $this->table_name.' Delivery Address':

                if ($key == $this->table_name.' Contact Address') {
                    $type = 'Contact';
                } elseif ($key == $this->table_name.' Delivery Address') {
                    $type = 'Delivery';
                } else {
                    $type = 'Invoice';
                }

                $address_fields = array(

                    'Address Recipient'            => $this->get(
                        $type.' Address Recipient'
                    ),
                    'Address Organization'         => $this->get(
                        $type.' Address Organization'
                    ),
                    'Address Line 1'               => $this->get(
                        $type.' Address Line 1'
                    ),
                    'Address Line 2'               => $this->get(
                        $type.' Address Line 2'
                    ),
                    'Address Sorting Code'         => $this->get(
                        $type.' Address Sorting Code'
                    ),
                    'Address Postal Code'          => $this->get(
                        $type.' Address Postal Code'
                    ),
                    'Address Dependent Locality'   => $this->get(
                        $type.' Address Dependent Locality'
                    ),
                    'Address Locality'             => $this->get(
                        $type.' Address Locality'
                    ),
                    'Address Administrative Area'  => $this->get(
                        $type.' Address Administrative Area'
                    ),
                    'Address Country 2 Alpha Code' => $this->get(
                        $type.' Address Country 2 Alpha Code'
                    ),


                );

                return array(
                    true,
                    json_encode($address_fields)
                );
                break;
            case 'Contact Address':
            case 'Invoice Address':
            case 'Delivery Address':

                return array(
                    true,
                    $this->get(
                        $this->table_name.' '.$key.' Formatted'
                    )
                );
                break;


            case 'Main Plain Telephone':
            case 'Main Plain Mobile':
            case 'Main Plain FAX':

                return array(
                    true,
                    $this->get(
                        $this->table_name.' '.preg_replace('/Plain/', 'XHTML', $key)
                    )
                );
                break;

            case('First Name'):
            case('Last Name'):
            case('Surname'):
            case('Contact Name Object'):
                include_once 'external_libs/HumanNameParser/Name.php';
                include_once 'external_libs/HumanNameParser/Parser.php';
                $name_parser = new HumanNameParser_Parser(
                    $this->data[$this->table_name.' Main Contact Name']
                );

                if ($key == 'First Name') {
                    return array(
                        true,
                        $name_parser->getFirst()
                    );
                } elseif ($key == 'Contact Name Object') {
                    return array(
                        true,
                        $name_parser
                    );
                } else {
                    return array(
                        true,
                        $name_parser->getLast()
                    );
                }


                break;

            default:

                if (preg_match(
                    '/'.$this->table_name.' History Note (\d+)/i', $key, $matches
                )) {


                    return array(
                        true,
                        $this->get_note($matches[1])
                    );

                }
                if (preg_match(
                    '/History Note (Strikethrough )?(\d+)/i', $key, $matches
                )) {


                    return array(
                        true,
                        nl2br($this->get_note($matches[2]))
                    );

                }

                if (preg_match(
                    '/('.$this->table_name.' |)Other Email (\d+)/i', $key, $matches
                )) {


                    $subject_email_key = $matches[2];
                    $sql               = sprintf(
                        "SELECT `%s Other Email Email` FROM `%s Other Email Dimension` WHERE `%s Other Email Key`=%d ", addslashes($this->table_name), addslashes($this->table_name),
                        addslashes($this->table_name), $subject_email_key
                    );
                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            return array(
                                true,
                                $row[$this->table_name.' Other Email Email']
                            );
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }

                }


                if (preg_match(
                        '/'.$this->table_name.' Other Telephone Label (\d+)/i', $key, $matches
                    ) or preg_match(
                        '/Other Telephone Label (\d+)/i', $key, $matches
                    )
                ) {


                    $subject_telephone_key = $matches[1];
                    $sql                   = sprintf(
                        "SELECT `%s Other Telephone Label` FROM `%s Other Telephone Dimension` WHERE `%s Other Telephone Key`=%d ", addslashes($this->table_name), addslashes($this->table_name),
                        addslashes($this->table_name), $subject_telephone_key
                    );
                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            return array(
                                true,
                                $row[$this->table_name.' Other Telephone Label']
                            );
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }

                }


                if (preg_match(
                    '/'.$this->table_name.' Other Telephone (\d+)/i', $key, $matches
                )) {


                    $subject_telephone_key = $matches[1];
                    $sql                   = sprintf(
                        "SELECT `%s Other Telephone Number`,`%s Other Telephone Formatted Number` FROM `%s Other Telephone Dimension` WHERE `%s Other Telephone Key`=%d ",
                        addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), $subject_telephone_key
                    );
                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            return array(
                                true,
                                $row[$this->table_name.' Other Telephone Number']
                            );
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }

                }

                if (preg_match('/Other Telephone (\d+)/i', $key, $matches)) {


                    $subject_telephone_key = $matches[1];
                    $sql                   = sprintf(
                        "SELECT `%s Other Telephone Number`,`%s Other Telephone Formatted Number` FROM `%s Other Telephone Dimension` WHERE `%s Other Telephone Key`=%d ",
                        addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), addslashes($this->table_name), $subject_telephone_key
                    );
                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            return array(
                                true,
                                $row[$this->table_name.' Other Telephone Formatted Number']
                            );
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }

                }

                return array(
                    false,
                    false
                );

        }

    }


}


?>
