<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Based in 2009 class.Product.php
 Created: 16 February 2016 at 22:35:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'class.Asset.php';
include_once 'trait.ProductAiku.php';

class Product extends Asset
{

    use ProductAiku;

    public ?Page $webpage;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false, $_db = false)
    {
        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }


        $this->table_name    = 'Product';
        $this->ignore_fields = array('Product ID');

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        if (preg_match('/^find/i', $arg1)) {
            $this->find($arg2, $arg3);

            return;
        }

        if (preg_match('/create|new/i', $arg1) and is_array($arg2)) {
            $this->find($arg2, 'create');

            return;
        }
        $this->get_data($arg1, $arg2, $arg3);
    }

    function get_data($key, $id, $aux_id = false)
    {
        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Product Dimension` WHERE `Product ID`=%d",
                $id
            );


            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id          = $this->data['Product ID'];
                $this->historic_id = $this->data['Product Current Key'];


                $this->properties = json_decode($this->data['Product Properties'], true);
            }
        } elseif ($key == 'store_code') {
            $sql = sprintf(
                "SELECT * FROM `Product Dimension` WHERE `Product Store Key`=%d  AND `Product Code`=%s   ORDER BY CASE `Product Status`
    WHEN 'Active' THEN 1
    WHEN 'InProcess' THEN 2
    WHEN 'Suspended' THEN 3
     WHEN 'Discontinued' THEN 4
    ELSE 5
  END; ",
                $id,
                prepare_mysql($aux_id)
            );


            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id          = $this->data['Product ID'];
                $this->historic_id = $this->data['Product Current Key'];
                $this->properties  = json_decode($this->data['Product Properties'], true);
            }
        } elseif ($key == 'historic_key') {
            $sql = sprintf(
                "SELECT * FROM `Product History Dimension` WHERE `Product Key`=%d",
                $id
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->historic_id = $this->data['Product Key'];
                $this->id          = $this->data['Product ID'];


                $sql = sprintf(
                    "SELECT * FROM `Product Dimension` WHERE `Product ID`=%d",
                    $this->data['Product ID']
                );
                if ($row = $this->db->query($sql)->fetch()) {
                    foreach ($row as $key => $value) {
                        $this->data[$key] = $value;
                    }
                    $this->properties = json_decode($this->data['Product Properties'], true);
                }
            }
        } else {
            exit ("wrong id in class.product get_data A :$key  ".$this->get('Code')." \n");
        }

        if ($this->id) {
            $this->get_store_data();
        }
    }

    function get($key, $arg1 = '')
    {
        include_once 'utils/natural_language.php';

        list($got, $result) = $this->get_asset_common($key, $arg1);
        if ($got) {
            return $result;
        }

        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case 'Video':

                if ($this->data['Product Video']) {
                    $url      = $this->data['Product Video'];
                    $video_id = explode('vimeo.com/', $url)[1];

                    return '<iframe src="https://player.vimeo.com/video/'.$video_id.'?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479&background=true"   frameborder="0"  style="background: #000;height: 200px" ></iframe>';
                }

                return '';

            case 'Units Per Case':

                return number($this->data['Product Units Per Case']);

            case 'Units And Name':

                if ($this->data['Product Units Per Case'] > 1) {
                    return get_html_fractions($this->data['Product Units Per Case']).'x '.$this->data['Product Name'];
                } elseif ($this->data['Product Units Per Case'] <= 1) {
                    return get_html_fractions($this->data['Product Units Per Case']).' '.$this->data['Product Name'];
                } else {
                    return $this->data['Product Name'];
                }
                break;

            case 'Service Code':
            case 'Service Name':
            case 'Service Price':
            case 'Service Unit Label':
                return $this->get(preg_replace('/Service/', 'Product', $key));


            case 'Image':


                $image_key = $this->data['Product Main Image Key'];


                if ($image_key) {
                    $img = '/image.php?s=320x280&id='.$image_key;
                } else {
                    $img = '/art/nopic.png';
                }

                return $img;


            case 'Product Webpage Meta Description':
            case 'Webpage Meta Description':
                return $this->webpage->get('Webpage Meta Description');


            case 'Product Webpage Browser Title':
            case 'Webpage Browser Title':
                return $this->webpage->get('Webpage Browser Title');


            case 'Webpage Image':


                $image_key = $this->get('Product Main Image Key');

                if ($image_key) {
                    $img = '/image.php?s=320x280&id='.$image_key;
                } else {
                    $img = '/art/nopic.png';
                }

                return $img;


            case 'Product Webpage Name':
            case 'Webpage Name':


                return $this->webpage->get('Webpage Name');


            case 'Webpage RRP':

                if ($this->data['Product RRP'] == '') {
                    return '';
                }

                $rrp = money($this->data['Product RRP'] / $this->data['Product Units Per Case'], $this->data['Store Currency Code']);
                if ($this->get('Product Units Per Case') != 1) {
                    $rrp .= '/'.$this->get('Product Unit Label');
                }


                return $rrp;

            case 'Formatted Customer Key':

                if ($this->data['Product Customer Key']) {
                    $customer = get_object('Customer', $this->data['Product Customer Key']);
                    if ($customer->id) {
                        return sprintf(
                            '<span onclick="change_view(\'customers/%d/%d\')">%s</span>',
                            $customer->get('Store Key'),
                            $customer->id,
                            $customer->get('Name')
                        );
                    } else {
                        return '<span class="very_discreet">'._('No associated (E)').'</span>';
                    }
                } else {
                    return '<span class="very_discreet">'._('No associated').'</span>';
                }

            case 'Customer Key':

                if ($this->data['Product Customer Key']) {
                    $customer = get_object('Customer', $this->data['Product Customer Key']);
                    if ($customer->id) {
                        return $customer->get('Name');
                    } else {
                        return '';
                    }
                } else {
                    return '';
                }


            case 'Webpage Out of Stock Label':


                return _('Out of stock');


            case 'Webpage Price':

                $price = money(
                    $this->data['Product Price'],
                    $this->data['Store Currency Code'],
                    $this->data['Store Locale'],
                );
                if ($this->data['Product Units Per Case'] == 1) {
                    $price .= '/'.$this->data['Product Unit Label'];
                } elseif ($this->data['Product Units Per Case'] > 1) {
                    $price .= ' ('.money(
                            $this->data['Product Price'] / $this->data['Product Units Per Case'],
                            $this->data['Store Currency Code'],
                            $this->data['Store Locale'],
                        ).'/'.$this->data['Product Unit Label'].')';
                }


                return $price;

            case 'Price':


                $account = get_object('Account', 1);

                $price = money(
                    $this->data['Product Price'],
                    $this->data['Store Currency Code']
                );
                if ($this->data['Product Units Per Case'] == 1) {
                    $price .= '/'.$this->data['Product Unit Label'];
                } elseif ($this->data['Product Units Per Case'] > 1) {
                    $price .= ' ('.money(
                            $this->data['Product Price'] / $this->data['Product Units Per Case'],
                            $this->data['Store Currency Code']
                        ).'/'.$this->data['Product Unit Label'].')';
                }


                if ($this->data['Store Currency Code'] != $account->get('Account Currency')) {
                    include_once 'utils/currency_functions.php';
                    $exchange = currency_conversion(
                        $this->db,
                        $this->data['Store Currency Code'],
                        $account->get('Account Currency'),
                        '- 15 minutes'
                    );
                } else {
                    $exchange = 1;
                }


                $unit_margin      = ($exchange * $this->data['Product Price']) - $this->data['Product Cost'];
                $price_other_info = sprintf(
                    _('margin %s'),
                    percentage($unit_margin, $exchange * $this->data['Product Price'])
                );


                $price_other_info = preg_replace(
                    '/^, /',
                    '',
                    $price_other_info
                );
                if ($price_other_info != '') {
                    $price .= ' <span class="'.($unit_margin < 0 ? 'error' : '').'  discreet padding_left_10">'.$price_other_info.'</span>';
                }


                return $price;
            case 'Cost':
                $account = get_object('Account', 1);
                $cost    = money($this->data['Product Cost'], $account->get('Account Currency'));

                if ($this->data['Product Units Per Case'] != 1 and $this->data['Product Units Per Case'] > 0) {
                    $cost .= ' ('.money(
                            $this->data['Product Cost'] / $this->data['Product Units Per Case'],
                            $account->get('Account Currency')
                        ).'/'.$this->data['Product Unit Label'].')';
                }

                return $cost;
            case 'Unit Price':
                return money(
                    $this->data['Product Price'] / $this->data['Product Units Per Case'],
                    $this->data['Store Currency Code']
                );
            case 'Formatted Per Outer':
                return _('per outer');
            case 'RRP':
                if ($this->data['Product RRP'] == '') {
                    return '';
                }

                return money($this->data['Product RRP'], $this->data['Store Currency Code']);


            case 'Unit RRP':

                if ($this->data['Product RRP'] == '') {
                    return '';
                }

                $rrp = money($this->data['Product RRP'] / $this->data['Product Units Per Case'], $this->data['Store Currency Code']);
                if ($this->get('Product Units Per Case') != 1) {
                    $rrp .= '/'.$this->get('Product Unit Label');
                }


                $unit_margin    = $this->data['Product RRP'] - $this->data['Product Price'];
                $rrp_other_info = sprintf(_('margin %s'), percentage($unit_margin, $this->data['Product RRP']));


                $rrp_other_info = preg_replace('/^, /', '', $rrp_other_info);
                if ($rrp_other_info != '') {
                    $rrp .= ' <span class="'.($unit_margin < 0 ? 'error' : '').'  discreet padding_left_10">'.$rrp_other_info.'</span>';
                }

                return $rrp;

            case 'Product Unit RRP':

                if ($this->data['Product RRP'] == '') {
                    return '';
                }


                if ($this->data['Product Units Per Case'] > 0) {
                    return $this->data['Product RRP'] / $this->data['Product Units Per Case'];
                } else {
                    return '';
                }


            case 'Unit Type':
                if ($this->data['Product Unit Type'] == '') {
                    return '';
                }

                return _($this->data['Product Unit Type']);

            case 'Parts':
                $parts = '';


                $parts_data = $this->get_parts_data(true);


                foreach ($parts_data as $part_data) {
                    $parts .= ', '.number($part_data['Ratio'], 5).'x <span class="button " onClick="change_view(\'part/'.$part_data['Part']->id.'\')">'.$part_data['Part']->get('Reference').'</span>';
                }

                if ($parts == '') {
                    $parts = '<span class="discreet">'._('No parts assigned').'</span>';
                }

                return preg_replace('/^, /', '', $parts);


            case 'Origin Country Code':
                if ($this->data['Product Origin Country Code']) {
                    include_once 'class.Country.php';
                    $country = new Country('code', $this->data['Product Origin Country Code']);

                    return '<img src="/art/flags/'.strtolower($country->get('Country 2 Alpha Code')).'.png" title="'.$country->get('Country Code').'"> '._($country->get('Country Name'));
                } else {
                    return '';
                }


            case 'Origin Country':
                if ($this->data['Product Origin Country Code']) {
                    include_once 'class.Country.php';
                    $country = new Country(
                        'code', $this->data['Product Origin Country Code']
                    );

                    return $country->get('Country Name');
                } else {
                    return '';
                }


            case 'Status':


                switch ($this->data['Product Status']) {
                    case 'Active':
                        $status = _('Active');
                        break;
                    case 'Suspended':
                        $status = _('Suspended');
                        break;
                    case 'Discontinued':
                        $status = _('Discontinued');
                        break;
                    default:
                        $status = $this->data['Product Status'];
                        break;
                }

                return $status;


            case 'Web Configuration':


                switch ($this->data['Product Web Configuration']) {
                    case 'Online Auto':
                        $web_configuration = _('Automatic');
                        break;
                    case 'Online Force For Sale':
                        $web_configuration = _('For sale').' <i class="fa fa-thumbtack padding_left_5" aria-hidden="true"></i>';
                        break;
                    case 'Online Force Out of Stock':
                        $web_configuration = _('Out of Stock').' <i class="fa fa-thumbtack padding_left_5" aria-hidden="true"></i>';
                        break;
                    case 'Offline':
                        $web_configuration = _('Offline');
                        break;
                    default:
                        $web_configuration = $this->data['Product Web Configuration'];
                        break;
                }

                return $web_configuration;


            case 'Web State':

                switch ($this->data['Product Web State']) {
                    case 'For Sale':
                        $web_state = '<span class="'.(($this->get('Product Availability') <= 0 and $this->data['Product Number of Parts'] > 0 and $this->data['Product Availability State'] != 'OnDemand') ? 'error' : '').'">'._(
                                'Online'
                            ).'</span>'.($this->data['Product Web Configuration'] == 'Online Force For Sale' ? ' <i class="fa fa-thumbtack padding_left_5" aria-hidden="true"></i>' : '');
                        break;
                    case 'Out of Stock':
                        $web_state = '<span  class="'.(($this->get(
                                    'Product Availability'
                                ) > 0 and $this->data['Product Number of Parts'] > 0) ? 'error' : '').'">'._(
                                'Out of Stock'
                            ).'</span>'.($this->data['Product Web Configuration'] == 'Online Force Out of Stock' ? ' <i class="fa fa-thumbtack padding_left_5" aria-hidden="true"></i>' : '');
                        break;
                    case 'Discontinued':
                        $web_state = _('Discontinued');
                        break;
                    case 'Offline':

                        if ($this->data['Product Status'] != 'Active') {
                            $web_state = _('Offline');
                        } else {
                            $web_state = '<span class="'.(($this->get(
                                        'Product Availability'
                                    ) > 0 and $this->data['Product Number of Parts'] > 0) ? 'error' : '').'">'._('Offline').'</span>'.($this->data['Product Status'] == 'Active' ? ' <i class="fa fa-thumbtack padding_left_5" aria-hidden="true"></i>' : '');
                        }
                        break;
                    default:
                        $web_state = $this->data['Product Web State'];
                        break;
                }

                return $web_state;

            case 'Availability':

                if ($this->data['Product Availability State'] == 'OnDemand') {
                    return _('On demand');
                } else {
                    return number($this->data['Product Availability']);
                }

            case 'Next Supplier Shipment':
                if ($this->data['Product Next Supplier Shipment'] == '') {
                    return '';
                } else {
                    return strftime("%a, %e %b %y", strtotime($this->data['Product Next Supplier Shipment'].' +0:00'));
                }

            case 'Acc To Day Updated':
            case 'Acc Ongoing Intervals Updated':
            case 'Acc Previous Intervals Updated':

                if ($this->data['Product '.$key] == '') {
                    $value = '';
                } else {
                    $value = strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Product '.$key].' +0:00'));
                }

                return $value;

            case 'Customers Numbers':
                $customer_numbers = number($this->data['Product Number Customers']);

                if ($this->data['Product Number Customers Favored'] > 0) {
                    $customer_numbers .= ', <i class="fa fa-heart" aria-hidden="true"></i>'.number($this->data['Product Number Customers Favored']);
                }

                return $customer_numbers;


            case 'Number Customers Favored':
            case 'Number Customers':
            case 'Number Orders':
            case 'Number Images':
            case 'Number History Records':
                return number($this->data['Product '.$key]);

            case 'Availability State':


                switch ($this->data['Product Availability State']) {
                    case 'Excess':
                        $stock_status = '<i style="color: #13D13D" class="fa fa-circle fa-fw"  title="'._('Excess stock').'"></i> <i class="very_discreet fal fa-spider-web"></i> ';
                        break;
                    case 'OnDemand':
                        $stock_status = '<i style="color: #13D13D" class="fa green fa-circle fa-fw"  title="'._('On demand').'"></i>';
                        break;
                    case 'Normal':
                        $stock_status = '<i style="color: #13D13D" class="fa green fa-circle fa-fw"  title="'._('Normal stock').'"></i>';
                        break;
                    case 'Low':
                        $stock_status = '<i style="color: #FCBE07" class="fa fa-circle fa-fw"  title="'._('Low stock').'"></i>';
                        break;
                    case 'VeryLow':
                        $stock_status = '<i style="color: #F25056" class="fa fa-circle fa-fw"   title="'._('Very low stock').'"></i>';
                        break;
                    case 'OutofStock':
                        $stock_status = '<i style="color: #F25056" class="fa fa-circle fa-fw"   title="'._('Out of stock').'"></i>';
                        break;
                    case 'Error':
                        $stock_status = '<i style="color: #F25056" class="fa fa-circle fa-fw"   title="'._('Error').'"></i>';
                        break;
                    default:
                        $stock_status = $this->data['Product Availability State'];
                        break;
                }

                return $stock_status;
            case 'Pricing Policy Key':
                if (!$this->data['Product Pricing Policy Key']) {
                    return _('No policy');
                }

                $label = '';
                $sql   = "select `Product Pricing Policy Label` from `Product Pricing Policy Dimension` where `Product Pricing Policy Key`=? ";
                $stmt  = $this->db->prepare($sql);
                $stmt->execute(
                    [
                        $this->data['Product Pricing Policy Key']
                    ]
                );
                if ($row = $stmt->fetch()) {
                    $label = $row['Product Pricing Policy Label'];
                }

                return $label;

            default:


                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit)$/',
                    $key
                )) {
                    $amount = 'Product '.$key;


                    return money(
                        $this->data[$amount],
                        $this->get('Product Currency')
                    );
                }
                if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key)) {
                    $field = 'Product '.preg_replace('/ Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    if ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_amount = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                        $suffix          = 'K';
                        $_amount         = $this->data[$field] / 1000;
                    } else {
                        $_amount = $this->data[$field];
                    }

                    $amount = money(
                            $_amount,
                            $this->get('Product Currency'),
                            $locale = false,
                            $fraction_digits
                        ).$suffix;

                    return $amount;
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Margin|GMROI)$/',
                    $key
                )) {
                    $amount = 'Product '.$key;

                    return percentage($this->data[$amount], 1);
                }
                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Customers)$/',
                        $key
                    ) or $key == 'Current Stock') {
                    $field = 'Product '.$key;


                    return number($this->data[$field]);
                }
                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced) Minify$/',
                        $key
                    ) or $key == 'Current Stock') {
                    $field = 'Product '.preg_replace('/ Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 0;
                    if ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_number = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 1;
                        $suffix          = 'K';
                        $_number         = $this->data[$field] / 1000;
                    } else {
                        $_number = $this->data[$field];
                    }

                    return number($_number, $fraction_digits).$suffix;
                }


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Product '.$key, $this->data)) {
                    return $this->data['Product '.$key];
                }
        }

        return '';
    }

    function get_parts_data($with_objects = false)
    {
        $parts_data = array();

        $sql = "SELECT `Part Reference`,`Product Part Key`,`Product Part Linked Fields`,`Product Part Part SKU`,`Product Part Ratio`,`Product Part Note` ,`Part Recommended Product Unit Name`,`Part Units`
                FROM `Product Part Bridge` LEFT JOIN `Part Dimension` ON (`Part SKU`=`Product Part Part SKU`)  WHERE `Product Part Product ID`=? ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->id
        ));
        while ($row = $stmt->fetch()) {
            $part_data = array(
                'Key'            => $row['Product Part Key'],
                'Ratio'          => $row['Product Part Ratio'],
                'Note'           => $row['Product Part Note'],
                'Part SKU'       => $row['Product Part Part SKU'],
                'Part Reference' => $row['Part Reference'],
                'Part Name'      => $row['Part Recommended Product Unit Name'],
                'Part Units'     => $row['Part Units'],
                'Units'          => floatval($row['Part Units']) * floatval($row['Product Part Ratio'])

            );


            if ($row['Product Part Linked Fields'] == '') {
                $part_data['Linked Fields']        = array();
                $part_data['Number Linked Fields'] = 0;
            } else {
                $part_data['Linked Fields']        = json_decode($row['Product Part Linked Fields'], true);
                $part_data['Number Linked Fields'] = count(
                    $part_data['Linked Fields']
                );
            }
            if ($with_objects) {
                $part_data['Part'] = get_object('Part', $row['Product Part Part SKU']);
            }


            $parts_data[] = $part_data;
        }


        return $parts_data;
    }

    function get_store_data()
    {
        $sql = sprintf(
            'SELECT * FROM `Store Dimension` WHERE `Store Key`=%d ',
            $this->data['Product Store Key']
        );
        if ($row = $this->db->query($sql)->fetch()) {
            foreach ($row as $key => $value) {
                $this->data[$key] = $value;
            }
        }
    }

    function find($raw_data, $options)
    {
        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }
            }
        }


        $create = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "SELECT `Product ID` FROM `Product Dimension` WHERE  `Product Store Key`=%s AND `Product Code`=%s  AND `Product Status`!='Discontinued'  ",
            $data['Product Store Key'],
            prepare_mysql($data['Product Code'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Product ID'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Product Code';
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create and !$this->found) {
            $this->create($raw_data);
        }
    }


    function create_variant($variant_data)
    {
        $this->new_product = false;
        $code              = $this->data['Product Code'].'-'.$variant_data['Product Code'];

        if ($code == '') {
            $this->error      = true;
            $this->msg        = _("Code missing");
            $this->error_code = 'product_code_missing';
            $this->metadata   = '';

            return false;
        }

        $sql  = "SELECT count(*) AS num ,`Product ID` FROM `Product Dimension` WHERE `Product Code`=%s AND `Product Store Key`=%d AND `Product Status`!='Discontinued' ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $code,
            $this->id
        ));
        if ($row = $stmt->fetch()) {
            if ($row['num'] > 0) {
                $this->error      = true;
                $this->msg        = sprintf(_('Duplicated code (%s)'), $code);
                $this->error_code = 'duplicate_product_code_reference';
                $this->metadata   = $code;

                return get_object('Product', $row['Product ID']);
            }
        }


        $variant_data['Product Code'] = $code;

        $variant_data['Product Unit Label'] = $this->data['Product Unit Label'];
        $variant_data['Product Unit RRP']   = '';

        $variant_data['is_variant']        = 'Yes';
        $variant_data['variant_parent_id'] = $this->id;

        $variant_data['Product Family Category Key'] = $this->data['Product Family Category Key'];

        $variant_data['Product Variant Position'] = 10000;

        $variant_data['Product Show Variant'] = 'No';

        $store         = get_object('Store', $this->get('Product Store Key'));
        $store->editor = $this->editor;

        //print_r($variant_data);

        $variant = $store->create_product($variant_data);

        if ($variant->id) {
            $variant->fast_update(
                [
                    'Product RRP' => $this->data['Product RRP']
                ]
            );
        }


        $this->new_product = $store->new_product;
        $this->msg         = $store->msg;

        $this->update_variants_stats();
        $this->reindex_variants_positions();

        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping',
            array(
                'type'       => 'update_product_webpages',
                'product_id' => $this->id,
                'scope'      => 'variants',
                'editor'     => $this->editor
            ),
            DNS_ACCOUNT_CODE
        );


        return $variant;
    }


    function reindex_variants_positions()
    {
        $sql  = "select `Product Variant Position`,`Product ID` from `Product Dimension` where `variant_parent_id`=?  order by  `Product Variant Position`,`Product ID` ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            [
                $this->data['variant_parent_id']
            ]
        );
        $positions      = [];
        $position_index = 0;
        while ($row = $stmt->fetch()) {
            $position_index++;

            $positions[$row['Product ID']] = $position_index;
        }


        foreach ($positions as $product_id => $position) {
            $sql = "update `Product Dimension` set `Product Variant Position`=? where`Product ID` =?  ";
            $this->db->prepare($sql)->execute(
                [
                    $position * 10,
                    $product_id
                ]
            );
        }
    }

    function update_variants_stats()
    {
        $number_variants         = 0;
        $number_visible_variants = 0;


        if ($this->data['is_variant'] == 'No') {
            $sql  = "select count(*) as num from `Product Dimension` where `variant_parent_id`=?  and `Product ID`!=?  ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                [
                    $this->id,
                    $this->id
                ]
            );
            while ($row = $stmt->fetch()) {
                $number_variants = $row['num'];
            }

            $sql  = "select count(*) as num from `Product Dimension` where `variant_parent_id`=? and `Product Show Variant`='Yes' and `Product ID`!=?  ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                [
                    $this->id,
                    $this->id
                ]
            );
            while ($row = $stmt->fetch()) {
                $number_visible_variants = $row['num'];
            }
        }

        $this->fast_update(
            [
                'has_variants'            => $number_variants > 0 ? 'Yes' : 'No',
                'number_variants'         => $number_variants,
                'number_visible_variants' => $number_visible_variants
            ]
        );
    }


    function create($data)
    {
        include_once 'utils/natural_language.php';


        $this->data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }
        $this->editor = $data['editor'];


        if ($this->data['Product Valid From'] == '') {
            $this->data['Product Valid From'] = gmdate('Y-m-d H:i:s');
        }


        $this->data['Product Code File As'] = get_file_as($this->data['Product Code']);


        if ($this->data['Product Packing Group'] == '') {
            $this->data['Product Packing Group'] = 'None';
        }


        $keys   = '';
        $values = '';


        //  print_r($this->data);

        foreach ($this->data as $key => $value) {
            $keys   .= ",`".$key."`";
            $values .= ','.prepare_mysql($value, true);
        }
        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);

        $sql = "insert into `Product Dimension` ($keys) values ($values)";


        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
            if (!$this->id) {
                throw new Exception('Error inserting '.$this->table_name);
            }

            $this->get_data('id', $this->id);

            $sql = sprintf(
                "INSERT INTO `Product DC Data` (`Product ID`) VALUES (%d) ",
                $this->id
            );
            $this->db->exec($sql);

            $sql = sprintf(
                "INSERT INTO `Product Data` (`Product ID`) VALUES (%d) ",
                $this->id
            );
            $this->db->exec($sql);


            $history_data = array(
                'History Abstract' => sprintf(_('%s product created'), $this->data['Product Name']),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data,
                true,
                'No',
                'Changes',
                $this->get_object_name(),
                $this->id
            );

            $this->new = true;


            $this->fast_update([
                'Product Properties'        => '{}',
                'Product Tax Category Data' => '{}'

            ]);

            $this->update_product_targeted_marketing_customers();
            $this->update_product_spread_marketing_customers();


            $this->update_historic_object();
            $this->get_data('id', $this->id);

            if ($this->data['is_variant'] == 'No') {
                $this->fast_update([
                    'variant_parent_id' => $this->id,

                ]);
            }


            $this->fork_index_elastic_search();
            $this->model_updated('new', $this->id);
        } else {
            $this->error = true;

            // print "\n\n$sql\n\n";

            $this->msg = 'Error inserting Product record';
        }
    }

    function update_product_targeted_marketing_customers()
    {
        include_once 'utils/asset_marketing_customers.php';

        $store = get_object('Store', $this->get('Store Key'));


        if (is_numeric($store->properties('email_marketing_customers'))) {
            $email_marketing_customers = $store->properties('email_marketing_customers');
        } else {
            $email_marketing_customers = 0;
        }


        $targeted_threshold = min($email_marketing_customers * .05, 500);


        $estimated_recipients = count(get_targeted_product_customers(array(), $this->db, $this->id, $targeted_threshold));


        $this->fast_update_json_field('Product Properties', 'targeted_marketing_customers', $estimated_recipients);
        $this->fast_update_json_field('Product Properties', 'targeted_marketing_customers_last_updated', gmdate('U'));
    }

    function update_product_spread_marketing_customers()
    {
        include_once 'utils/asset_marketing_customers.php';

        $store = get_object('Store', $this->get('Store Key'));

        if (is_numeric($store->properties('email_marketing_customers'))) {
            $email_marketing_customers = $store->properties('email_marketing_customers');
        } else {
            $email_marketing_customers = 0;
        }

        $targeted_threshold   = 5 * min($email_marketing_customers * .05, 500);
        $estimated_recipients = count(get_spread_product_customers(array(), $this->db, $this->id, $targeted_threshold));

        $this->fast_update_json_field('Product Properties', 'spread_marketing_customers', $estimated_recipients);
        $this->fast_update_json_field('Product Properties', 'spread_marketing_customers_last_updated', gmdate('U'));
    }

    function update_historic_object()
    {
        if (!$this->id) {
            return;
        }

        $old_value = $this->get('Product Current Key');
        $changed   = false;


        $desc = $this->get('Product Units Per Case').'x '.$this->get(
                'Product Name'
            ).' ('.$this->get('Price').')';

        $sql = "SELECT `Product Key` FROM `Product History Dimension` WHERE
		`Product History Code`=? AND `Product History Units Per Case`=? AND `Product History Price`=? AND
		`Product History Name`=? AND `Product ID`=?";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->data['Product Code'],
            $this->data['Product Units Per Case'],
            round($this->data['Product Price'], 2),
            $this->data['Product Name'],
            $this->id
        ));
        if ($row = $stmt->fetch()) {
            $this->update(
                array('Product Current Key' => $row['Product Key']),
                'no_history'
            );
            $changed = true;
        } else {
            $sql  =
                "INSERT INTO `Product History Dimension` (`Product ID`,`Product History Code`,`Product History Units Per Case`,`Product History Price`, `Product History Name`,`Product History Valid From`,`Product History Short Description`,`Product History XHTML Short Description`,`Product History Special Characteristic`) VALUES (?,?,?, ?,?,?, ?,?,?) ";
            $stmt = $this->db->prepare($sql);

            if ($stmt->execute(array(
                $this->id,
                $this->data['Product Code'],
                $this->data['Product Units Per Case'],

                round($this->data['Product Price'], 2),
                $this->data['Product Name'],
                gmdate('Y-m-d H:i:s'),

                $desc,
                $desc,
                $this->get('Product Special Characteristic')
            ))) {
                $this->update(
                    array(
                        'Product Current Key' => $this->db->lastInsertId()
                    ),
                    'no_history'
                );
                $changed = true;
            }
        }


        if ($changed and $old_value > 0) {
            require_once 'utils/new_fork.php';


            $store = get_object('Store', $this->get('Product Store Key'));
            if ($store->get('Store Type') != 'External') {
                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'       => 'product_price_updated',
                        'product_id' => $this->id,
                        'editor'     => $this->editor
                    ),
                    DNS_ACCOUNT_CODE
                );
            }
        }
        $this->process_aiku_fetch('Product', $this->id);
    }

    function get_field_label($field)
    {
        switch ($field) {
            case 'Product ID':
                $label = _('id');
                break;
            case 'Product Cost':
                $label = _('Outer cost');
                break;

            case 'Product Description':
                $label = _('Product description');
                break;
            case 'Product Code':
                $label = _('code');
                break;
            case 'Product Outer Description':
                $label = _('description');
                break;
            case 'Product Unit Description':
                $label = _('unit description');
                break;
            case 'Product Price':
                $label = _('Outer price');
                break;
            case 'Product Outer Weight':
                $label = _('weight');
                break;
            case 'Product Outer Dimensions':
                $label = _('dimensions');
                break;
            case 'Product Units Per Outer':
                $label = _('retail units per outer');
                break;

            case 'Product Unit Type':
                $label = _('unit type');
                break;
            case 'Product Label in Family':
                $label = _('label in family');
                break;

            case 'Product Unit Weight':
                $label = _('Weight shown in website');
                break;
            case 'Product Unit Dimensions':
                $label = _('Dimensions shown in website');
                break;
            case 'Product Units Per Case':
                $label = _('units per outer');
                break;
            case 'Product Unit Label':
                $label = _('unit label');
                break;
            case 'Product Parts':
                $label = _('parts');
                break;
            case 'Product Name':
                $label = _('unit name');
                break;

            case 'Product Unit RRP':
                $label = _('unit RRP');
                break;

            case 'Product Tariff Code':
                $label = _('tariff code');
                break;
            case 'Product HTSUS Code':
                $label = 'HTS US';
                break;
            case 'Product Duty Rate':
                $label = _('duty rate');
                break;

            case 'Product UN Number':
                $label = _('UN number');
                break;

            case 'Product UN Class':
                $label = _('UN class');
                break;
            case 'Product Packing Group':
                $label = _('packing group');
                break;
            case 'Product Proper Shipping Name':
                $label = _('proper shipping name');
                break;
            case 'Product Hazard Identification Number':
                $label = _('hazard identification number');
                break;
            case 'Product Materials':
                $label = _('Materials/Ingredients');
                break;
            case 'Product Origin Country Code':
                $label = _('country of origin');
                break;
            case 'Product Units Per Package':
                $label = _('units per SKO');
                break;
            case 'Product Barcode Number':
                $label = _('barcode');
                break;
            case 'Product CPNP Number':
                $label = _('CPNP number');
                break;
            case 'Product UFI':
                $label = _('UFI (Poison Centres)');
                break;
            case 'Product Webpage Browser Title':
                $label = _('webpage browser title');
                break;
            case 'Product Webpage Meta Description':
                $label = _('webpage META description');
                break;
            case 'Product Web Configuration':
                $label = _('web configuration');
                break;
            case 'Product Variant Short Name':
                $label = _('short menu name');
                break;
            case 'Product Pricing Policy Key':
                $label = _('pricing policy');
                break;
            case 'Product GPSR Manufacturer':
                $label = _('manufacturer');
                break;
            case 'Product GPSR EU Responsable':
                $label = _('EU responsible');
                break;
            case 'Product GPSR Warnings':
                $label = _('warnings');
                break;
            case 'Product GPSR Manual':
                $label = _('How to use');
                break;
            case 'Product GPSR Class Category Danger':
                $label = _('Class & category of danger');
                break;
            default:
                $label = $field;
        }

        return $label;
    }

    function get_linked_fields_data()
    {
        $sql = sprintf(
            "SELECT `Product Part Part SKU`,`Product Part Linked Fields` FROM `Product Part Bridge` WHERE `Product Part Product ID`=%d",
            $this->id
        );

        $linked_fields_data = array();
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Product Part Linked Fields'] != '') {
                    $linked_fields = json_decode(
                        $row['Product Part Linked Fields'],
                        true
                    );

                    foreach ($linked_fields as $key => $value) {
                        $value                      = preg_replace(
                            '/\s/',
                            '_',
                            $value
                        );
                        $linked_fields_data[$value] = $row['Product Part Part SKU'];
                    }
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $linked_fields_data;
    }

    function update_status_from_parts()
    {
        $old_status = $this->get('Product Status');

        $status = 'Active';

        $part_objects = $this->get_parts('objects');


        foreach ($part_objects as $part) {
            if ($part->get('Part Status') == 'Discontinuing') {
                $status = 'Discontinuing';
            } elseif ($part->get('Part Status') == 'Not In Use') {
                $status = 'Discontinued';
                break;
            }
        }


        if ($status == 'Active') {
            if ($this->get('Product Status') == 'Discontinuing') {
                $this->update(array('Product Status' => 'Active'));
            }
        } elseif ($status == 'Discontinuing') {
            // if ($this->get('Product Status') == 'Active') {
            $this->update(array('Product Status' => 'Discontinuing'));
            // }
        } elseif ($status == 'Discontinued') {
            $this->update(array('Product Status' => 'Discontinued'));
        }

        $this->update_availability();

        if ($old_status != $this->get('Product Status')) {
            $this->fork_index_elastic_search();
        }
    }

    function get_parts($scope = 'keys')
    {
        $parts = array();

        $sql = "SELECT `Product Part Part SKU` AS `Part SKU` FROM `Product Part Bridge` WHERE `Product Part Product ID`=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->id
        ));
        while ($row = $stmt->fetch()) {
            if ($scope == 'objects') {
                $parts[$row['Part SKU']] = get_object('Part', $row['Part SKU']);
            } else {
                $parts[$row['Part SKU']] = $row['Part SKU'];
            }
        }


        return $parts;
    }

    function update_availability($use_fork = true)
    {
        if ($this->data['Product Type'] == 'Service') {
            return;
        }

        if ($this->data['Product Web Configuration'] == 'Online Force Out of Stock' or $this->data['Product Web Configuration'] == 'Offline') {
            $this->fast_update(array(
                'Product Availability'       => 0,
                'Product Availability State' => 'OutofStock',

            ));

            return;
        }


        $use_pipelines = true;

        $old_availability_state = $this->get('Product Availability State');


        $min_days_available = '';
        $on_demand          = '';


        if ($this->get('Product Number of Parts') > 0) {
            $sql = sprintf(
                " SELECT `Part Days Available Forecast`,`Part Reference`,`Part On Demand`,`Part Stock Status`,`Part Current On Hand Stock`,`Part Current Stock In Process`,`Part Current Stock Ordered Paid` ,`Part Current Stock In Process`,`Part Current On Hand Stock`,`Product Part Ratio`,`Part SKU` FROM     `Product Part Bridge` B LEFT JOIN   `Part Dimension` P   ON (P.`Part SKU`=B.`Product Part Part SKU`)   WHERE B.`Product Part Product ID`=%d   ",
                $this->id
            );


            $stock       = 99999999999;
            $change      = false;
            $stock_error = false;


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    if ($use_pipelines) {
                        $part_stock_this_store   = 0;
                        $part_stock_other_stores = 0;

                        $sql = "SELECT `Quantity On Hand` as stock,`Location Picking Pipeline Location Key`,`Location Key` FROM `Part Location Dimension` PL  left join 
                     
                        `Location Picking Pipeline Bridge` B  on (PL.`Location Key`=B.`Location Picking Pipeline Location Key`) left join 
                         `Picking Pipeline Dimension` on (`Picking Pipeline Key`=`Location Picking Pipeline Picking Pipeline Key`)
                        WHERE `Part SKU`=? and (`Location Picking Pipeline Location Key` is null or `Picking Pipeline Store Key` =? )  ";

                        $stmt2 = $this->db->prepare($sql);

                        $stmt2->execute(array(
                            $row['Part SKU'],
                            $this->data['Product Store Key']
                        ));
                        while ($row2 = $stmt2->fetch()) {
                            // print_r($row2);
                            if ($row2['Location Picking Pipeline Location Key']) {
                                $part_stock_this_store += $row2['stock'];
                            } else {
                                $part_stock_other_stores += $row2['stock'];
                            }
                        }


                        //  print   "$part_stock_this_store $part_stock_other_stores ".$row['Part Current On Hand Stock']."  \n";

                        // Get 'Part Current Stock In Process

                        $stock_reserved_other_stores = 0;
                        $stock_reserved_this_store   = 0;

                        $sql   =
                            "SELECT `Required`+`Given`-`Picked` as reserved ,   `Store Key`    FROM `Inventory Transaction Fact` left join `Order Transaction Fact` on (`Map To Order Transaction Fact Key`=`Order Transaction Fact Key`)  WHERE `Part SKU`=? AND `Inventory Transaction Type`='Order In Process'";
                        $stmt2 = $this->db->prepare($sql);
                        $stmt2->execute(array(
                            $row['Part SKU']
                        ));
                        while ($row2 = $stmt2->fetch()) {
                            //print_r($row2);
                            if ($row2['Store Key'] == $this->data['Product Store Key']) {
                                $stock_reserved_this_store += $row2['reserved'];
                            } else {
                                $stock_reserved_other_stores += $row2['reserved'];
                            }
                        }

                        //  print   "$stock_reserved_this_store $stock_reserved_other_stores ".$row['Part Current Stock In Process']."  \n";

                        // Paid ordered stock

                        $sql =
                            "SELECT (`Order Quantity`+`Order Bonus Quantity`)*`Product Part Ratio` AS reserved , `Store Key`  FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`) LEFT JOIN `Order Dimension` O ON (OTF.`Order Key`=O.`Order Key`)  WHERE `Order State`='InProcess'  and `Order To Pay Amount`<=0 and `Product Part Part SKU`=?   ";

                        $stmt2 = $this->db->prepare($sql);
                        $stmt2->execute(array(
                            $row['Part SKU']
                        ));
                        while ($row2 = $stmt2->fetch()) {
                            // print_r($row2);
                            if ($row2['Store Key'] == $this->data['Product Store Key']) {
                                $stock_reserved_this_store += $row2['reserved'];
                            } else {
                                $stock_reserved_other_stores += $row2['reserved'];
                            }
                        }
                        //   print   "$stock_reserved_this_store $stock_reserved_other_stores ".$row['Part Current Stock Ordered Paid']."  \n";


                        //print "$part_stock_other_stores\n";
                        //print "$stock_reserved_other_stores\n";

                        $part_actual_stock_other_stores = $part_stock_other_stores - $stock_reserved_other_stores;
                        if ($part_actual_stock_other_stores < 0) {
                            $part_actual_stock_other_stores = 0;
                        }


                        //print "Other: Stock $part_stock_other_stores  - $stock_reserved_other_stores = $part_actual_stock_other_stores\n   ";

                        //print "$part_stock_other_stores\n======\n";

                        //print "Store: Stock $part_stock_this_store  - $stock_reserved_this_store \n   ";


                        $part_stock = $part_stock_this_store - $stock_reserved_this_store + $part_actual_stock_other_stores;

                        //print "$part_stock\n";
                        //$part_stock=$part_stock-$row['Part Current Stock In Process']-$row['Part Current Stock Ordered Paid'];

                    } else {
                        $part_stock = $row['Part Current On Hand Stock'] - $row['Part Current Stock In Process'] - $row['Part Current Stock Ordered Paid'];
                    }

                    //print "$part_stock\n";


                    if ($on_demand == '') {
                        $on_demand = $row['Part On Demand'];
                    } else {
                        if ($row['Part On Demand'] == 'No') {
                            $on_demand = 'No';
                        }
                    }


                    if ($min_days_available === '' or $min_days_available > $row['Part Days Available Forecast']) {
                        $min_days_available = $row['Part Days Available Forecast'];
                    }


                    if (is_numeric($part_stock) and is_numeric($row['Product Part Ratio']) and $row['Product Part Ratio'] > 0) {
                        $_part_stock = $part_stock;


                        if ($row['Part Current On Hand Stock'] == 0 and $row['Part Current Stock In Process'] > 0) {
                            $_part_stock = 0;
                        }


                        $_stock = $_part_stock / $row['Product Part Ratio'];


                        if ($stock >= $_stock or $change == false) {
                            if (!($this->get('Product Number of Parts') > 1 and $row['Part On Demand'] == 'Yes')) {
                                $stock  = $_stock;
                                $change = true;
                            }
                        }
                    } else {
                        $stock       = 0;
                        $stock_error = true;
                    }
                }
            }


            if ($stock < 0) {
                $stock = 0;
            } elseif (!$change or $stock_error) {
                $stock = 0;
            } else {
                if (is_numeric($stock) and $stock < 0) {
                    $stock = 0;
                }
            }
        } else {
            $stock = 0;
        }


        if ($min_days_available == '') {
            $min_days_available = 100;
        }

        //exit;

        if ($on_demand == 'Yes') {
            $tipo = 'OnDemand';
        } elseif ($stock == 0) {
            $tipo = 'OutofStock';
        } elseif ($stock == 1 or $min_days_available < 2) {
            $tipo = 'VeryLow';
        } elseif ($stock < 5 or $min_days_available < 7) {
            $tipo = 'Low';
        } elseif ($min_days_available < 101) {
            $tipo = 'Normal';
        } else {
            $tipo = 'Excess';
        }


        $this->fast_update(array(
            'Product Availability'       => $stock,
            'Product Availability State' => $tipo,

        ));

        $this->update_status_availability_state();

        if ($old_availability_state != $this->get('Product Availability State')) {
            $this->update_updated_markers('Stock');
            $this->fork_index_elastic_search();
        }

        $this->update_web_state($use_fork);

        foreach ($this->get_categories('objects') as $category) {
            $category->editor = $this->editor;
            $category->update_product_category_products_data();
        }


        $this->other_fields_updated = array(
            'Product_Availability' => array(
                'field'           => 'Product_Availability',
                'value'           => $this->get('Product Availability'),
                'formatted_value' => $this->get('Availability'),


            ),
            'Product_Web_State'    => array(
                'field'           => 'Product_Web_State',
                'value'           => $this->get('Product Web State'),
                'formatted_value' => $this->get('Web State'),


            )
        );
    }


    function update_status_availability_state()
    {
        switch ($this->data['Product Status']) {
            case 'Discontinuing':
            case 'Discontinued':
                $status_availability_state = $this->data['Product Status'];
                break;
            default:
                if (in_array($this->data['Product Availability State'], [
                    'Excess',
                    'Normal',
                    'OnDemand'
                ])) {
                    $status_availability_state = 'OK';
                } elseif (in_array($this->data['Product Availability State'], [
                    'OutofStock',
                    'Error'
                ])) {
                    $status_availability_state = 'OutofStock';
                } else {
                    $status_availability_state = $this->data['Product Availability State'];
                }
        }

        $this->fast_update(['Product Status Availability State' => $status_availability_state]);
    }

    function update_updated_markers($marker)
    {
        $date = gmdate('Y-m-d H:i:s');

        $this->fast_update(array('Product '.$marker.' Updated' => $date));


        $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';

        $this->db->prepare($sql)->execute([
            $date,
            $date,
            'store_data_feed',
            $this->get('Product Store Key'),
            $date,

        ]);

        if ($this->data['Product Department Category Key'] > 0) {
            $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';

            $this->db->prepare($sql)->execute([
                $date,
                $date,
                'department_data_feed',
                $this->data['Product Department Category Key'],
                $date,

            ]);
        }
    }

    function update_web_state($use_fork = true)
    {
        $store = get_object('Store', $this->get('Product Store Key'));

        if ($store->get('Store Type') == 'External') {
            return;
        }


        $old_web_state = $this->get('Product Web State');


        if ($old_web_state == 'For Sale') {
            $old_web_availability = 'Yes';
        } else {
            $old_web_availability = 'No';
        }

        $web_state = $this->get_web_state();


        $this->fast_update([
            'Product Web State' => $web_state
        ]);


        if ($web_state == 'For Sale') {
            $web_availability = 'Yes';
        } else {
            $web_availability = 'No';
        }


        $web_availability_updated = $old_web_availability != $web_availability;


        if ($web_availability_updated) {
            $sql = "SELECT `Category Key` FROM `Category Bridge` WHERE `Subject Key`=? AND `Subject`='Product' GROUP BY `Category Key`";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(array(
                $this->id
            ));
            while ($row = $stmt->fetch()) {
                /**
                 * @var $category Category
                 */
                $category = get_object('Category', $row['Category Key']);
                $category->update_product_category_products_data();
            }


            if (isset($this->editor['User Key']) and is_numeric($this->editor['User Key'])) {
                $user_key = $this->editor['User Key'];
            } else {
                $user_key = 0;
            }


            $sql = "SELECT UNIX_TIMESTAMP(`Date`) AS date,`Product Availability Key` FROM `Product Availability Timeline` WHERE `Product ID`=?  ORDER BY `Date`  DESC LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(array(
                $this->id
            ));
            if ($row = $stmt->fetch()) {
                $last_record_key  = $row['Product Availability Key'];
                $last_record_date = $row['date'];
            } else {
                $last_record_key  = false;
                $last_record_date = false;
            }


            $new_date_formatted = gmdate('Y-m-d H:i:s');
            $new_date           = gmdate('U');

            $sql = sprintf(
                "INSERT INTO `Product Availability Timeline`  (`Product ID`,`Store Key`,`Department Key`,`Family Key`,`User Key`,`Date`,`Availability`,`Web State`) VALUES (%d,%d,%d,%d,%d,%s,%s,%s) ",
                $this->id,
                $this->data['Product Store Key'],
                $this->data['Product Department Category Key'],
                $this->data['Product Family Category Key'],
                $user_key,
                prepare_mysql($new_date_formatted),
                prepare_mysql($web_availability),
                prepare_mysql($web_state)

            );
            $this->db->exec($sql);

            if ($last_record_key) {
                $sql = sprintf(
                    "UPDATE `Product Availability Timeline` SET `Duration`=%d WHERE `Product Availability Key`=%d",
                    $new_date - $last_record_date,
                    $last_record_key

                );
                $this->db->exec($sql);
            }

            $this->db->exec($sql);

            if ($web_availability == 'Yes') {
                $sql = sprintf(
                    "update `Back in Stock Reminder Fact` set `Back in Stock Reminder State`='Ready',`Back in Stock Reminder Ready Date`=%s where `Back in Stock Reminder State`='Waiting' and `Back in Stock Reminder Product ID`=%d ",
                    prepare_mysql(gmdate('Y-m-d H:i:s')),
                    $this->id
                );
                $this->db->exec($sql);
            } else {
                $sql = sprintf(
                    "update `Back in Stock Reminder Fact` set `Back in Stock Reminder State`='Waiting',`Back in Stock Reminder Ready Date`=NULL  where `Back in Stock Reminder State`='Ready' and  `Back in Stock Reminder Product ID`=%d",
                    $this->id
                );
                $this->db->exec($sql);
            }

            if ($use_fork) {
                include_once 'utils/new_fork.php';


                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'                     => 'update_web_state_slow_forks',
                        'web_availability_updated' => $web_availability_updated,
                        'product_id'               => $this->id,
                        'editor'                   => $this->editor
                    ),
                    DNS_ACCOUNT_CODE
                );
            } else {
                $this->update_web_state_slow_forks($web_availability_updated);
            }
        }
    }

    function get_web_state(): string
    {
        if (!($this->data['Product Status'] == 'Active' or $this->data['Product Status'] == 'Discontinuing') or ($this->data['Product Number of Parts'] == 0 and $this->data['Product Type'] == 'Product')) {
            return 'Offline';
        }
        switch ($this->data['Product Web Configuration']) {
            case 'Online Force Out of Stock':
                return 'Out of Stock';

            case 'Online Force For Sale':
                return 'For Sale';

            case 'Online Auto':


                if ($this->data['Product Type'] == 'Service') {
                    return 'For Sale';
                } else {
                    if ($this->data['Product Number of Parts'] == 0) {
                        return 'Offline';
                    } else {
                        if ($this->data['Product Availability'] > 0 or $this->data['Product Availability State'] == 'OnDemand') {
                            return 'For Sale';
                        } else {
                            return 'Out of Stock';
                        }
                    }
                }

            default:
                return 'Offline';
        }
    }

    function update_web_state_slow_forks($web_availability_updated)
    {
        if ($web_availability_updated) {
            $sql = sprintf(
                "SELECT `Order Key` FROM `Order Transaction Fact` WHERE `Current Dispatching State` IN ('In Process','In Process by Customer') AND `Product ID`=%d ",
                $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $web_availability = ($this->get_web_state() == 'For Sale' ? 'Yes' : 'No');
                    if ($web_availability == 'No') {
                        /**
                         * @var $order \Order
                         */
                        $order = get_object('Order', $row['Order Key']);


                        $order->remove_out_of_stocks_from_basket($this->id);
                    }
                }
            }


            $sql = sprintf(
                "SELECT `Order Key` FROM `Order Transaction Fact` WHERE `Current Dispatching State`='Out of Stock in Basket' AND `Product ID`=%d ",
                $this->id

            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $web_availability = ($this->get_web_state() == 'For Sale' ? 'Yes' : 'No');
                    if ($web_availability == 'Yes') {
                        /** @var Order $order */
                        $order = get_object('Order', $row['Order Key']);
                        $order->restore_back_to_stock_to_basket($this->id);
                    }
                }
            }
        }


        $this->get_data('id', $this->id);
        $this->load_acc_data();

        foreach ($this->get_parts('objects') as $part) {
            $part->update_products_web_status();
        }


        if (!($this->get('Product Status') == 'Active' or $this->get('Product Status') == 'Discontinuing') or $this->get('Product Web Configuration') == 'Offline') {
            $_state = 'Offline';
        } else {
            $_state = 'Online';
        }

        $webpage = get_object('webpage', $this->data['Product Webpage Key']);

        if ($webpage->id) {
            $webpage->update(array('Webpage State' => $_state), 'no_history');
        }


        if ($web_availability_updated) {
            require_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'       => 'update_product_webpages',
                    'product_id' => $this->id,
                    'scope'      => 'web_state',
                    'editor'     => $this->editor
                ),
                DNS_ACCOUNT_CODE
            );
        }
    }

    function load_acc_data()
    {
        $sql = sprintf(
            "SELECT * FROM `Product Data` WHERE `Product ID`=%d",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT * FROM `Product DC Data` WHERE `Product ID`=%d",
            $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }
    }

    function get_categories($output = 'keys', $scope = 'all', $args = '')
    {
        $categories = array();
        $sql        = "SELECT B.`Category Key` FROM `Category Dimension` C LEFT JOIN `Category Bridge` B ON (B.`Category Key`=C.`Category Key`) WHERE `Subject Key`=? AND `Subject`='Product' AND `Category Branch Type`!='Root'";

        $params = array(
            $this->id
        );

        if ($scope == 'root_key') {
            $sql      .= ' and C.`Category Root Key`=?';
            $params[] = $args;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            $params
        );
        while ($row = $stmt->fetch()) {
            if ($output == 'objects') {
                $categories[$row['Category Key']] = get_object('Category', $row['Category Key']);
            } else {
                $categories[$row['Category Key']] = $row['Category Key'];
            }
        }

        return $categories;
    }

    function update_webpages($change_type = '')
    {
        /** @var Page $webpage */
        $webpage = get_object('Page', $this->get('Product Webpage Key'));
        if ($webpage->id) {
            $webpage->reindex();

            switch ($change_type) {
                case 'main_image':


                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'          => 'reindex_webpages_items',
                            'webpages_keys' => $webpage->get_upstream_webpage_keys(),
                        ),
                        DNS_ACCOUNT_CODE
                    );
                    break;

                case 'dimensions':
                case 'weight':
                case 'materials':
                case 'country_origin':
                    return;

                default:
                    $webpages_to_reindex = $webpage->get_upstream_webpage_keys();

                    $date = gmdate('Y-m-d H:i:s');
                    foreach ($webpages_to_reindex as $webpage_to_reindex_key) {
                        if ($webpage_to_reindex_key > 0) {
                            $sql =
                                "insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`,`Stack Metadata`) values (?,?,?, ?,?) ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? , `Stack Metadata`=? , `Stack Counter`=`Stack Counter`+1";


                            $this->db->prepare($sql)->execute(array(
                                $date,
                                $date,
                                'reindex_webpage',
                                $webpage_to_reindex_key,
                                'from_product_'.$change_type,
                                $date,
                                'from_product_'.$change_type

                            ));
                        }
                    }
            }
        }
    }

    function update_sales_from_invoices($interval, $this_year = true, $last_year = true)
    {
        // print $interval;

        include_once 'utils/date_functions.php';


        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb) = calculate_interval_dates($this->db, $interval);


        if ($this_year) {
            $sales_data = $this->get_sales_data($from_date, $to_date);


            $data_to_update = array(
                "Product $db_interval Acc Customers"          => $sales_data['customers'],
                "Product $db_interval Acc Repeat Customers"   => $sales_data['repeat_customers'],
                "Product $db_interval Acc Invoices"           => $sales_data['invoices'],
                "Product $db_interval Acc Profit"             => round($sales_data['profit'], 2),
                "Product $db_interval Acc Invoiced Amount"    => round($sales_data['net'], 2),
                "Product $db_interval Acc Quantity Ordered"   => $sales_data['ordered'],
                "Product $db_interval Acc Quantity Invoiced"  => $sales_data['invoiced'],
                "Product $db_interval Acc Quantity Delivered" => $sales_data['delivered'],

            );

            // print_r($data_to_update);

            $this->fast_update($data_to_update, 'Product Data');

            $data_to_update = array(
                "Product DC $db_interval Acc Profit"          => round($sales_data['dc_profit'], 2),
                "Product DC $db_interval Acc Invoiced Amount" => round($sales_data['dc_net'], 2)
            );
            $this->fast_update($data_to_update, 'Product DC Data');
        }
        if ($from_date_1yb and $last_year) {
            $sales_data = $this->get_sales_data($from_date_1yb, $to_1yb);

            $data_to_update = array(
                "Product $db_interval Acc 1YB Customers"          => $sales_data['customers'],
                "Product $db_interval Acc Repeat Customers"       => $sales_data['repeat_customers'],
                "Product $db_interval Acc 1YB Invoices"           => $sales_data['invoices'],
                "Product $db_interval Acc 1YB Profit"             => round($sales_data['profit'], 2),
                "Product $db_interval Acc 1YB Invoiced Amount"    => round($sales_data['net'], 2),
                "Product $db_interval Acc 1YB Quantity Ordered"   => $sales_data['ordered'],
                "Product $db_interval Acc 1YB Quantity Invoiced"  => $sales_data['invoiced'],
                "Product $db_interval Acc 1YB Quantity Delivered" => $sales_data['delivered'],

            );
            $this->fast_update($data_to_update, 'Product Data');

            $data_to_update = array(
                "Product DC $db_interval Acc 1YB Profit"          => round($sales_data['dc_profit'], 2),
                "Product DC $db_interval Acc 1YB Invoiced Amount" => round($sales_data['dc_net'], 2)
            );
            $this->fast_update($data_to_update, 'Product DC Data');
        }


        if (in_array($db_interval, [
            'Total',
            'Year To Date',
            'Quarter To Date',
            'Week To Date',
            'Month To Date',
            'Today'
        ])) {
            $this->fast_update(['Product Acc To Day Updated' => gmdate('Y-m-d H:i:s')]);
        } elseif (in_array($db_interval, [
            '1 Year',
            '1 Month',
            '1 Week',
            '1 Quarter'
        ])) {
            $this->fast_update(['Product Acc Ongoing Intervals Updated' => gmdate('Y-m-d H:i:s')]);
        } elseif (in_array($db_interval, [
            'Last Month',
            'Last Week',
            'Yesterday',
            'Last Year'
        ])) {
            $this->fast_update(['Product Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')]);
        }
    }

    function get_sales_data($from_date, $to_date)
    {
        $sales_data = array(
            'customers'        => 0,
            'repeat_customers' => 0,
            'invoices'         => 0,
            'net'              => 0,
            'profit'           => 0,
            'ordered'          => 0,
            'invoiced'         => 0,
            'delivered'        => 0,
            'dc_net'           => 0,
            'dc_profit'        => 0,

        );

        if ($from_date == '' and $to_date == '') {
            $sales_data['repeat_customers'] = $this->get_customers_total_data();
        }


        $sql = sprintf(
            "SELECT
		ifnull(count(DISTINCT `Customer Key`),0) AS customers,
		ifnull(count(DISTINCT OTF.`Invoice Key`),0) AS invoices,
		round(ifnull(sum( `Order Transaction Amount` +(  `Cost Supplier`/`Invoice Currency Exchange Rate`)  ),0),2) AS profit,
		round(ifnull(sum(`Order Transaction Amount`),0),2) AS net ,
		round(ifnull(sum(`Delivery Note Quantity`),0),1) AS delivered,
		round(ifnull(sum(`Order Quantity`),0),1) AS ordered,
		round(ifnull(sum(`Delivery Note Quantity`),0),1) AS invoiced,
		round(ifnull(sum((`Order Transaction Amount`)*`Invoice Currency Exchange Rate`),0),2) AS dc_net,
		round(ifnull(sum((`Order Transaction Amount`+`Cost Supplier`)*`Invoice Currency Exchange Rate`),0),2) AS dc_profit
		FROM `Order Transaction Fact` OTF  USE INDEX (`Product ID`,`Invoice Date`) 
		       left join `Invoice Dimension` I on (I.`Invoice Key`=OTF.`Invoice Key`)
		      WHERE OTF.`Invoice Key` >0  and `Invoice Customer Level Type` in ('Normal','VIP')  
		          
		          AND  `Product ID`=%d %s %s ",
            $this->id,
            ($from_date ? sprintf(
                'and OTF.`Invoice Date`>=%s',
                prepare_mysql($from_date)
            ) : ''),
            ($to_date ? sprintf(
                'and OTF.`Invoice Date`<%s',
                prepare_mysql($to_date)
            ) : '')

        );


        //print "$sql\n";


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['customers'] = $row['customers'];
                $sales_data['invoices']  = $row['invoices'];
                $sales_data['net']       = $row['net'];
                $sales_data['profit']    = $row['profit'];
                $sales_data['ordered']   = $row['ordered'];
                $sales_data['invoiced']  = $row['invoiced'];
                $sales_data['delivered'] = $row['delivered'];
                $sales_data['dc_net']    = $row['dc_net'];
                $sales_data['dc_profit'] = $row['dc_profit'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        //print_r($sales_data);
        return $sales_data;
    }

    function get_customers_total_data()
    {
        $repeat_customers = 0;


        $sql = sprintf(
            'SELECT count(`Customer Product Customer Key`) AS num  FROM `Customer Product Bridge` WHERE `Customer Product Invoices`>1 AND `Customer Product Product ID`=%d    ',
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $repeat_customers = $row['num'];
            }
        }


        return $repeat_customers;
    }

    function update_previous_years_data()
    {
        $data_1y_ago = $this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-1 year')), date('Y-01-01 00:00:00'));
        $data_2y_ago = $this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-2 year')), date('Y-01-01 00:00:00', strtotime('-1 year')));
        $data_3y_ago = $this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-3 year')), date('Y-01-01 00:00:00', strtotime('-2 year')));
        $data_4y_ago = $this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-4 year')), date('Y-01-01 00:00:00', strtotime('-3 year')));
        $data_5y_ago = $this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-5 year')), date('Y-01-01 00:00:00', strtotime('-4 year')));

        $data_to_update = array(
            "Product 1 Year Ago Customers"          => $data_1y_ago['customers'],
            "Product 1 Year Ago Repeat Customers"   => $data_1y_ago['repeat_customers'],
            "Product 1 Year Ago Invoices"           => $data_1y_ago['invoices'],
            "Product 1 Year Ago Profit"             => round($data_1y_ago['profit'], 2),
            "Product 1 Year Ago Invoiced Amount"    => round($data_1y_ago['net'], 2),
            "Product 1 Year Ago Quantity Ordered"   => $data_1y_ago['ordered'],
            "Product 1 Year Ago Quantity Invoiced"  => $data_1y_ago['invoiced'],
            "Product 1 Year Ago Quantity Delivered" => $data_1y_ago['delivered'],


            "Product 2 Year Ago Customers"          => $data_2y_ago['customers'],
            "Product 2 Year Ago Repeat Customers"   => $data_2y_ago['repeat_customers'],
            "Product 2 Year Ago Invoices"           => $data_2y_ago['invoices'],
            "Product 2 Year Ago Profit"             => round($data_2y_ago['profit'], 2),
            "Product 2 Year Ago Invoiced Amount"    => round($data_2y_ago['net'], 2),
            "Product 2 Year Ago Quantity Ordered"   => $data_2y_ago['ordered'],
            "Product 2 Year Ago Quantity Invoiced"  => $data_2y_ago['invoiced'],
            "Product 2 Year Ago Quantity Delivered" => $data_2y_ago['delivered'],


            "Product 3 Year Ago Customers"          => $data_3y_ago['customers'],
            "Product 3 Year Ago Repeat Customers"   => $data_3y_ago['repeat_customers'],
            "Product 3 Year Ago Invoices"           => $data_3y_ago['invoices'],
            "Product 3 Year Ago Profit"             => round($data_3y_ago['profit'], 2),
            "Product 3 Year Ago Invoiced Amount"    => round($data_3y_ago['net'], 2),
            "Product 3 Year Ago Quantity Ordered"   => $data_3y_ago['ordered'],
            "Product 3 Year Ago Quantity Invoiced"  => $data_3y_ago['invoiced'],
            "Product 3 Year Ago Quantity Delivered" => $data_3y_ago['delivered'],

            "Product 4 Year Ago Customers"          => $data_4y_ago['customers'],
            "Product 4 Year Ago Repeat Customers"   => $data_4y_ago['repeat_customers'],
            "Product 4 Year Ago Invoices"           => $data_4y_ago['invoices'],
            "Product 4 Year Ago Profit"             => round($data_4y_ago['profit'], 2),
            "Product 4 Year Ago Invoiced Amount"    => round($data_4y_ago['net'], 2),
            "Product 4 Year Ago Quantity Ordered"   => $data_4y_ago['ordered'],
            "Product 4 Year Ago Quantity Invoiced"  => $data_4y_ago['invoiced'],
            "Product 4 Year Ago Quantity Delivered" => $data_4y_ago['delivered'],

            "Product 5 Year Ago Customers"          => $data_5y_ago['customers'],
            "Product 5 Year Ago Repeat Customers"   => $data_5y_ago['repeat_customers'],
            "Product 5 Year Ago Invoices"           => $data_5y_ago['invoices'],
            "Product 5 Year Ago Profit"             => round($data_5y_ago['profit'], 2),
            "Product 5 Year Ago Invoiced Amount"    => round($data_5y_ago['net'], 2),
            "Product 5 Year Ago Quantity Ordered"   => $data_5y_ago['ordered'],
            "Product 5 Year Ago Quantity Invoiced"  => $data_5y_ago['invoiced'],
            "Product 5 Year Ago Quantity Delivered" => $data_5y_ago['delivered'],

        );
        $this->fast_update($data_to_update, 'Product Data');


        $data_to_update = array(
            "Product DC 1 Year Ago Profit"          => round($data_1y_ago['dc_net'], 2),
            "Product DC 1 Year Ago Invoiced Amount" => round($data_1y_ago['dc_profit'], 2),
            "Product DC 2 Year Ago Profit"          => round($data_2y_ago['dc_net'], 2),
            "Product DC 2 Year Ago Invoiced Amount" => round($data_2y_ago['dc_profit'], 2),
            "Product DC 3 Year Ago Profit"          => round($data_3y_ago['dc_net'], 2),
            "Product DC 3 Year Ago Invoiced Amount" => round($data_3y_ago['dc_profit'], 2),
            "Product DC 4 Year Ago Profit"          => round($data_4y_ago['dc_net'], 2),
            "Product DC 4 Year Ago Invoiced Amount" => round($data_4y_ago['dc_profit'], 2),
            "Product DC 5 Year Ago Profit"          => round($data_5y_ago['dc_net'], 2),
            "Product DC 5 Year Ago Invoiced Amount" => round($data_5y_ago['dc_profit'], 2),
        );
        $this->fast_update($data_to_update, 'Product DC Data');
    }

    function update_previous_quarters_data()
    {
        include_once 'utils/date_functions.php';


        foreach (range(1, 4) as $i) {
            $dates     = get_previous_quarters_dates($i);
            $dates_1yb = get_previous_quarters_dates($i + 4);


            $sales_data     = $this->get_sales_data(
                $dates['start'],
                $dates['end']
            );
            $sales_data_1yb = $this->get_sales_data(
                $dates_1yb['start'],
                $dates_1yb['end']
            );

            $data_to_update = array(

                "Product $i Quarter Ago Customers"          => $sales_data['customers'],
                "Product $i Quarter Ago Repeat Customers"   => $sales_data['repeat_customers'],
                "Product $i Quarter Ago Invoices"           => $sales_data['invoices'],
                "Product $i Quarter Ago Profit"             => round($sales_data['profit'], 2),
                "Product $i Quarter Ago Invoiced Amount"    => round($sales_data['net'], 2),
                "Product $i Quarter Ago Quantity Ordered"   => $sales_data['ordered'],
                "Product $i Quarter Ago Quantity Invoiced"  => $sales_data['invoiced'],
                "Product $i Quarter Ago Quantity Delivered" => $sales_data['delivered'],


                "Product $i Quarter Ago 1YB Customers"          => $sales_data_1yb['customers'],
                "Product $i Quarter Ago 1YB Repeat Customers"   => $sales_data['repeat_customers'],
                "Product $i Quarter Ago 1YB Invoices"           => $sales_data_1yb['invoices'],
                "Product $i Quarter Ago 1YB Profit"             => round($sales_data_1yb['profit'], 2),
                "Product $i Quarter Ago 1YB Invoiced Amount"    => round($sales_data_1yb['net'], 2),
                "Product $i Quarter Ago 1YB Quantity Ordered"   => $sales_data_1yb['ordered'],
                "Product $i Quarter Ago 1YB Quantity Invoiced"  => $sales_data_1yb['invoiced'],
                "Product $i Quarter Ago 1YB Quantity Delivered" => $sales_data_1yb['delivered'],


            );
            $this->fast_update($data_to_update, 'Product Data');


            $data_to_update = array(
                "Product DC $i Quarter Ago Profit"              => round($sales_data['dc_net'], 2),
                "Product DC $i Quarter Ago Invoiced Amount"     => round($sales_data['dc_profit'], 2),
                "Product DC $i Quarter Ago 1YB Profit"          => round($sales_data_1yb['dc_net'], 2),
                "Product DC $i Quarter Ago 1YB Invoiced Amount" => round($sales_data_1yb['dc_profit'], 2)
            );
            $this->fast_update($data_to_update, 'Product DC Data');
        }
    }

    function delete()
    {
        $this->deleted = false;


        $sql  = "SELECT `Order Transaction Fact Key` FROM `Order Transaction Fact` WHERE `Product ID`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($this->id));
        if ($row = $stmt->fetch()) {
            $this->update(array('Product Status' => 'Discontinued'));
        } else {
            $sql = sprintf("DELETE FROM `Product Dimension` WHERE `Product ID`=%d", $this->id);
            $this->db->exec($sql);

            $sql = sprintf("DELETE FROM `Product History Dimension` WHERE `Product ID`=%d", $this->id);
            $this->db->exec($sql);

            $sql = sprintf("DELETE FROM `Product Availability Timeline` WHERE `Product ID`=%d", $this->id);
            $this->db->exec($sql);

            $sql = sprintf("DELETE FROM `Product Data` WHERE `Product ID`=%d", $this->id);
            $this->db->exec($sql);
            $sql = sprintf("DELETE FROM `Product DC Data` WHERE `Product ID`=%d", $this->id);
            $this->db->exec($sql);


            $webpage = $this->get_webpage();
            if ($webpage->id) {
                $webpage->delete(false);
            }


            $this->deleted = true;

            $this->fork_index_elastic_search('delete_elastic_index_object');
            $this->model_updated('new', $this->id);
        }
    }

    function get_webpage(): Page
    {
        /** @var Page $page */
        $page = get_object('Page', $this->get('Product Webpage Key'));

        $this->webpage = $page;

        return $page;
    }

    function update_next_shipment()
    {
        $next_delivery_time = 0;

        $sql = sprintf(
            'SELECT `Part Current On Hand Stock`,`Part Next Shipment Date` FROM  `Product Part Bridge` LEFT JOIN `Part Dimension` ON (`Part SKU`=`Product Part Part SKU`)   WHERE `Product Part Product ID`=%d ',
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if (($row['Part Current On Hand Stock'] <= 0 or $row['Part Current On Hand Stock'] == '') and $row['Part Next Shipment Date'] == '') {
                    $next_delivery_time = 0;

                    break;
                }

                $_next_delivery_time = $row['Part Next Shipment Date'];


                if ($_next_delivery_time != '' and strtotime($_next_delivery_time.' +0:00') > gmdate('U')) {
                    if (!$next_delivery_time) {
                        $next_delivery_time = strtotime($_next_delivery_time.' +0:00');
                    } elseif (strtotime($_next_delivery_time) > $next_delivery_time) {
                        $next_delivery_time = strtotime($_next_delivery_time.' +0:00');
                    }
                }
            }

            $old_value = $this->data['Product Next Supplier Shipment'];


            $new_value = (!$next_delivery_time ? '' : gmdate('Y-m-d H:i:s', $next_delivery_time));
            if ($old_value != $new_value) {
                $this->fast_update(array(
                    'Product Next Supplier Shipment' => $new_value
                ));

                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'       => 'update_product_webpages',
                        'product_id' => $this->id,
                        'scope'      => 'next_shipment',
                        'editor'     => $this->editor
                    ),
                    DNS_ACCOUNT_CODE
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '')
    {
        if (is_string($value)) {
            $value = _trim($value);
        }


        switch ($field) {
            case 'Service Code':
            case 'Service Name':
            case 'Service Price':
            case 'Service Unit Label':

                $this->update_field_switcher(preg_replace('/Service/', 'Product', $field), $value, $options, $metadata);

                break;

            case 'Product Customer Key':


                if (is_numeric($value) and $value > 0) {
                    if ($this->data['Product Customer Key'] == $value) {
                        return;
                    }

                    $customer = get_object('Customer', $value);


                    $history_abstract = sprintf(_("Product associated with %s"), $customer->get('Name'));
                } else {
                    if (!$this->data['Product Customer Key']) {
                        return;
                    }
                    $customer = get_object('Customer', $this->data['Product Customer Key']);


                    $history_abstract = sprintf(_("Product disassociated from %s"), $customer->get('Name'));
                }
                $this->fast_update(['Product Customer Key' => $value]);


                $customer->update_associated_products();

                $history_data = array(
                    'History Abstract' => $history_abstract,
                    'History Details'  => '',
                    'Action'           => 'edit'
                );

                $this->add_subject_history(
                    $history_data,
                    true,
                    'No',
                    'Changes',
                    $this->get_object_name(),
                    $this->id
                );

                break;
            case 'Product Webpage Name':


                if (!is_object($this->webpage)) {
                    $this->get_webpage();
                }


                $this->webpage->update(
                    array(

                        'Webpage Name' => $value
                    ),
                    $options
                );

                $this->updated = $this->webpage->updated;


                break;

            case 'Product Webpage Browser Title':
                if (!is_object($this->webpage)) {
                    $this->get_webpage();
                }

                $this->webpage->update(array('Webpage Browser Title' => $value), $options);
                $this->updated = $this->webpage->updated;

                break;

            case 'Product Webpage Meta Description':
                if (!is_object($this->webpage)) {
                    $this->get_webpage();
                }

                $this->webpage->update(
                    array(
                        'Webpage Meta Description' => $value
                    ),
                    $options
                );


                $this->updated = $this->webpage->updated;
                break;


            case('Product Status'):

                $old_state = $this->data['Product Status'];

                if (!in_array($value, array(
                    'Active',
                    'Suspended',
                    'Discontinued',
                    'Discontinuing'
                ))) {
                    $this->error = true;
                    $this->msg   = _('Invalid status').' ('.$value.')';

                    return;
                }


                $this->update_field('Product Status', $value, $options);


                if ($value == 'Suspended' or $value == 'Discontinued') {
                    $this->update_field('Product Valid To', gmdate('Y-m-d H:i:s'), 'no_history');
                } else {
                    $this->update_field('Product Valid To', '', 'no_history');
                }
                if ($value == 'Discontinuing') {
                    $this->update_field('Product Web Configuration', 'Online Auto', 'no_history');
                } elseif ($value == 'Suspended') {
                    $this->update_field('Product Web Configuration', 'Offline', 'no_history');
                } elseif ($value == 'Active') {
                    $this->update_field('Product Web Configuration', 'Online Auto', 'no_history');
                } elseif ($value == 'Discontinued') {
                    $this->update_field('Product Web Configuration', 'Offline', 'no_history');
                }


                if ($old_state == 'Discontinued' and $value != 'Discontinued' and $this->data['Product Webpage Key'] == '') {
                    $store = get_object('store', $this->get('Store Key'));

                    foreach ($store->get_websites('objects') as $website) {
                        /**
                         * @var $website \Website
                         */
                        $website->create_product_webpage($this->id);
                    }
                }


                $this->update_web_state();


                $this->update_metadata = array(
                    'class_html' => array(
                        'Product_Web_State' => $this->get('Web State'),
                    )

                );


                $this->other_fields_updated = array(
                    'Product_Web_Configuration' => array(
                        'field'           => 'Product_Web_Configuration',
                        'render'          => ($value == 'Active' ? true : false),
                        'value'           => $this->get(
                            'Product Web Configuration'
                        ),
                        'formatted_value' => $this->get('Web Configuration'),
                    ),


                );

                if ($old_state != $this->data['Product Status']) {
                    $this->update_updated_markers('Stock');
                    $this->fork_index_elastic_search();
                }
                break;

            case('Product Web Configuration'):

                if (!in_array($value, array(
                    'Online Force Out of Stock',
                    'Online Auto',
                    'Offline',
                    'Online Force For Sale'
                ))) {
                    $this->error = true;
                    $this->msg   = _('Invalid web configuration').' ('.$value.')';

                    return;
                }

                $this->update_field($field, $value, $options);


                if (preg_match('/no_fork/', $options)) {
                    $this->update_web_state($use_fork = false);
                } else {
                    $this->update_web_state();
                }


                $this->update_metadata = array(
                    'class_html' => array(
                        'Product_Web_State' => $this->get('Web State'),
                    )

                );
                $this->fork_index_elastic_search();

                break;
            case 'Product HTSUS Code':
            case 'Product Tariff Code':
            case 'Product Barcode Number':
                $old_value = $this->get($field);

                if (!preg_match('/from_part/', $options) and count($this->get_parts()) == 1) {
                    /**
                     * @var $part \Part
                     */
                    $part = array_values($this->get_parts('objects'))[0];
                    $part->update(
                        array(
                            preg_replace('/^Product/', 'Part', $field) => $value
                        ),
                        $options
                    );

                    $this->get_data('id', $this->id);
                    $this->updated = $part->updated;

                    return;
                }
                $this->update_field($field, $value, $options);
                if ($old_value != $this->get($field)) {
                    $this->update_updated_markers('Data');
                }


                break;
            case 'Product Unit Weight':

                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(_('Invalid weight (%s)'), $value);

                    return;
                }
                $old_value = $this->get($field);

                if (!preg_match('/from_part/', $options) and count($this->get_parts()) == 1) {
                    /**
                     * @var $part \Part
                     */
                    $part = array_values($this->get_parts('objects'))[0];
                    $part->update(
                        array(
                            preg_replace('/^Product/', 'Part', $field) => $value
                        ),
                        $options
                    );

                    $this->get_data('id', $this->id);
                    $this->updated = $part->updated;

                    return;
                }


                $this->update_field($field, $value, $options);


                if ($old_value != $this->get($field)) {
                    $this->update_updated_markers('Data');
                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'       => 'update_product_webpages',
                            'product_id' => $this->id,
                            'scope'      => 'weight',
                            'editor'     => $this->editor
                        ),
                        DNS_ACCOUNT_CODE
                    );
                }
                break;


            case 'Product Unit Dimensions':


                include_once 'utils/parse_natural_language.php';

                $tag = preg_replace('/ Dimensions$/', '', $field);

                if ($value == '') {
                    $dim = '';
                } else {
                    $dim = parse_dimensions($value);
                    if ($dim == '') {
                        $this->error = true;
                        $this->msg   = sprintf(_("Dimensions can't be parsed (%s)"), $value);

                        return;
                    }
                }
                $old_value = $this->get($field);
                if (!preg_match('/from_part/', $options) and count($this->get_parts()) == 1) {
                    /**
                     * @var $part \Part
                     */
                    $part = array_values($this->get_parts('objects'))[0];
                    $part->update(
                        array(
                            preg_replace('/^Product/', 'Part', $field) => $value
                        ),
                        $options
                    );

                    $this->get_data('id', $this->id);
                    $this->updated = $part->updated;


                    return;
                }


                $this->update_field($tag.' Dimensions', $dim, $options);
                $this->fast_update(array('Product Unit XHTML Dimensions' => $this->get('Unit Dimensions')));


                if ($old_value != $this->get($field)) {
                    $this->update_updated_markers('Data');

                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'       => 'update_product_webpages',
                            'product_id' => $this->id,
                            'scope'      => 'dimensions',
                            'editor'     => $this->editor
                        ),
                        DNS_ACCOUNT_CODE
                    );
                }

                break;

            case 'Product Materials':


                if (!preg_match('/from_part/', $options) and count($this->get_parts()) == 1) {
                    /**
                     * @var $part \Part
                     */
                    $part = array_values($this->get_parts('objects'))[0];
                    $part->update(
                        array(
                            preg_replace('/^Product/', 'Part', $field) => $value
                        ),
                        $options
                    );

                    $this->get_data('id', $this->id);
                    $this->updated = $part->updated;

                    return;
                }


                include_once 'utils/parse_materials.php';


                $materials_to_update = array();
                $sql                 = sprintf(
                    'SELECT `Material Key` FROM `Product Material Bridge` WHERE `Product ID`=%d',
                    $this->id
                );
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $materials_to_update[$row['Material Key']] = true;
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                if ($value == '') {
                    $materials = '';


                    $sql = sprintf(
                        "DELETE FROM `Product Material Bridge` WHERE `Product ID`=%d ",
                        $this->id
                    );
                    $this->db->exec($sql);
                } else {
                    $materials_data = parse_materials($value, $this->editor);

                    $sql = sprintf(
                        "DELETE FROM `Product Material Bridge` WHERE `Product ID`=%d ",
                        $this->id
                    );

                    $this->db->exec($sql);

                    foreach ($materials_data as $material_data) {
                        if ($material_data['id'] > 0) {
                            $sql = sprintf(
                                "INSERT INTO `Product Material Bridge` (`Product ID`, `Material Key`, `Ratio`, `May Contain`) VALUES (%d, %d, %s, %s) ",
                                $this->id,
                                $material_data['id'],
                                prepare_mysql($material_data['ratio']),
                                prepare_mysql($material_data['may_contain'])

                            );
                            $this->db->exec($sql);

                            if (isset($materials_to_update[$material_data['id']])) {
                                $materials_to_update[$material_data['id']] = false;
                            } else {
                                $materials_to_update[$material_data['id']] = true;
                            }
                        }
                    }


                    $materials = json_encode($materials_data);
                }


                foreach ($materials_to_update as $material_key => $update) {
                    if ($update) {
                        /**
                         * @var $material \Material
                         */
                        $material = get_object('Material', $material_key);
                        $material->update_stats();
                    }
                }


                $this->update_field('Product Materials', $materials, $options);
                $updated = $this->updated;

                $this->fast_update(array('Product Unit XHTML Materials' => $this->get('Materials')));

                if ($updated) {
                    $this->update_updated_markers('Data');

                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'       => 'update_product_webpages',
                            'product_id' => $this->id,
                            'scope'      => 'materials',
                            'editor'     => $this->editor
                        ),
                        DNS_ACCOUNT_CODE
                    );
                }

                break;


            case 'Product Code':
                $value = _trim($value);

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Code missing');

                    return;
                }

                if (preg_match('/\s/', $value)) {
                    $this->error = true;
                    $this->msg   = _("Code can't have spaces");

                    return;
                }

                if (preg_match('/,/', $value)) {
                    $this->error = true;
                    $this->msg   = _("Code can't have commas");

                    return;
                }

                $sql  = "SELECT count(*) AS num FROM `Product Dimension` WHERE `Product Code`=? AND `Product Store Key`=? AND  `Product Status`!='Discontinued'  AND `Product ID`!=? ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                    $value,
                    $this->get('Product Store Key'),
                    $this->id
                ));
                if ($row = $stmt->fetch()) {
                    if ($row['num'] > 0) {
                        $this->error = true;
                        $this->msg   = sprintf(_("Another product has this code (%s)"), $value);

                        return;
                    }
                }


                $this->update_field($field, $value, $options);
                $updated = $this->updated;
                $this->update_historic_object();
                $this->updated = $updated;

                if ($updated) {
                    $this->update_updated_markers('Data');

                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'       => 'update_product_webpages',
                            'product_id' => $this->id,
                            'scope'      => 'code',
                            'editor'     => $this->editor
                        ),
                        DNS_ACCOUNT_CODE
                    );

                    $this->fork_index_elastic_search();
                }
                break;

            case 'Product Name':
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Product name missing');

                    return;
                }

                $this->update_field($field, $value, $options);
                $updated = $this->updated;
                $this->update_historic_object();


                $this->updated = $updated;


                $this->update_metadata = array(

                    'class_html' => array(
                        'Units_And_Name' => $this->get('Units And Name'),


                    )
                );

                if ($updated) {
                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'       => 'update_product_webpages',
                            'product_id' => $this->id,
                            'scope'      => 'name',
                            'editor'     => $this->editor
                        ),
                        DNS_ACCOUNT_CODE
                    );

                    $this->update_updated_markers('Data');
                    $this->fork_index_elastic_search();
                }


                break;
            case 'Product Unit Label':
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Unit label missing');

                    return;
                }

                $this->update_field($field, $value, $options);

                $this->other_fields_updated = array(
                    'Product_Price'    => array(
                        'field'           => 'Product_Price',
                        'render'          => true,
                        'value'           => $this->get('Product Price'),
                        'formatted_value' => $this->get('Price'),
                    ),
                    'Product_Unit_RRP' => array(
                        'field'           => 'Product_Unit_RRP',
                        'render'          => true,
                        'value'           => $this->get('Product Unit RRP'),
                        'formatted_value' => $this->get('Unit RRP'),
                    ),

                );


                if ($this->updated) {
                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'       => 'update_product_webpages',
                            'product_id' => $this->id,
                            'scope'      => 'label',
                            'editor'     => $this->editor
                        ),
                        DNS_ACCOUNT_CODE
                    );

                    $this->update_updated_markers('Data');
                    $this->fork_index_elastic_search();
                }

                break;
            case 'Product Label in Family':

                $this->update_field(
                    'Product Special Characteristic',
                    $value,
                    $options
                );

                $this->update_field($field, $value, $options);


                if ($this->updated) {
                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'       => 'update_product_webpages',
                            'product_id' => $this->id,
                            'scope'      => 'label_in_family',
                            'editor'     => $this->editor
                        ),
                        DNS_ACCOUNT_CODE
                    );

                    $this->update_updated_markers('Data');
                    $this->fork_index_elastic_search();
                }


                break;

            case 'Product Variant Position':

                $sql  = "select `Product Variant Position`,`Product ID` from `Product Dimension` where `variant_parent_id`=?  and `Product ID`!=?  order by  `Product Variant Position`,`Product ID` ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    [
                        $this->data['variant_parent_id'],
                        $this->id
                    ]
                );
                $positions      = [];
                $position_index = 0;
                while ($row = $stmt->fetch()) {
                    $position_index++;
                    $positions[$row['Product ID']] = $position_index;
                }


                foreach ($positions as $product_id => $position) {
                    $sql = "update `Product Dimension` set `Product Variant Position`=? where`Product ID` =?  ";
                    $this->db->prepare($sql)->execute(
                        [
                            $position * 10,
                            $product_id
                        ]
                    );
                }


                $new_pos = ($value * 10) - 5;

                $this->fast_update([$field => $new_pos]);
                $this->reindex_variants_positions();

                $id = $this->id;
                if ($this->data['is_variant'] == 'Yes') {
                    $id = $this->data['variant_parent_id'];
                }
                include_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'       => 'update_product_webpages',
                        'product_id' => $id,
                        'scope'      => 'variants',
                        'editor'     => $this->editor
                    ),
                    DNS_ACCOUNT_CODE
                );

                break;
            case 'Product Show Variant':
                $this->update_field($field, $value, $options);

                $this->update_variants_stats();

                $id = $this->id;
                if ($this->data['is_variant'] == 'Yes') {
                    $id     = $this->data['variant_parent_id'];
                    $parent = get_object('Product', $id);
                    $parent->update_variants_stats();
                }
                include_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'       => 'update_product_webpages',
                        'product_id' => $id,
                        'scope'      => 'variants',
                        'editor'     => $this->editor
                    ),
                    DNS_ACCOUNT_CODE
                );

                break;
            case 'Product Variant Short Name':
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Value missing');

                    return;
                }
                $this->update_field($field, $value, $options);

                $id = $this->id;
                if ($this->data['is_variant'] == 'Yes') {
                    $id = $this->data['variant_parent_id'];
                }

                include_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'       => 'update_product_webpages',
                        'product_id' => $id,
                        'scope'      => 'variants',
                        'editor'     => $this->editor
                    ),
                    DNS_ACCOUNT_CODE
                );

                break;
            case 'Product Price':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Price missing');

                    return;
                }

                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(_('Invalid price (%s)'), $value);

                    return;
                }


                $this->update_field($field, $value, $options);
                $updated                    = $this->updated;
                $this->other_fields_updated = array(

                    'Product_Unit_RRP' => array(
                        'field'           => 'Product_Unit_RRP',
                        'render'          => true,
                        'value'           => $this->get('Product Unit RRP'),
                        'formatted_value' => $this->get('Unit RRP'),
                    ),

                );


                $this->update_historic_object();
                $this->updated = $updated;
                if ($updated) {
                    foreach ($this->get_parts('objects') as $part) {
                        if ($part->id) {
                            $part->update_commercial_value();
                        }
                    }

                    $this->update_updated_markers('Price');


                    $id = $this->id;
                    if ($this->data['is_variant'] == 'Yes') {
                        $id = $this->data['variant_parent_id'];
                    }
                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'       => 'update_product_webpages',
                            'product_id' => $id,
                            'scope'      => 'price',
                            'editor'     => $this->editor
                        ),
                        DNS_ACCOUNT_CODE
                    );

                    $this->fork_index_elastic_search();
                }
                break;


            case 'Product Unit RRP':


                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(_('Invalid unit RRP (%s)'), $value);

                    return;
                }

                if ($value == '') {
                    $this->update_field('Product RRP', '', $options);
                } else {
                    $this->update_field(
                        'Product RRP',
                        $value * $this->data['Product Units Per Case'],
                        $options
                    );
                }
                if ($this->updated) {
                    $this->update_updated_markers('Data');


                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'       => 'update_product_webpages',
                            'product_id' => $this->id,
                            'scope'      => 'rrp',
                            'editor'     => $this->editor
                        ),
                        DNS_ACCOUNT_CODE
                    );
                }

                break;

            case 'Product Units Per Case':
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Units per outer missing');

                    return;
                }

                if (!is_numeric($value) or $value < 0) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid units per outer (%s)'),
                        $value
                    );

                    return;
                }

                $old_value = $this->get('Product Units Per Case');

                $this->update_field('Product Units Per Case', $value, $options);
                $updated = $this->updated;
                if (is_numeric($old_value) and $old_value > 0) {
                    $rrp_per_unit = $this->get('Product RRP') / $old_value;
                    $this->update_field(
                        'Product RRP',
                        $rrp_per_unit * $this->get('Product Units Per Case'),
                        $options
                    );
                }


                $this->other_fields_updated = array(
                    'Product_Price'    => array(
                        'field'           => 'Product_Price',
                        'render'          => true,
                        'value'           => $this->get('Product Price'),
                        'formatted_value' => $this->get('Price'),
                    ),
                    'Product_Unit_RRP' => array(
                        'field'           => 'Product_Unit_RRP',
                        'render'          => true,
                        'value'           => $this->get('Product Unit RRP'),
                        'formatted_value' => $this->get('Unit RRP'),
                    ),

                );

                $this->update_historic_object();
                $this->updated = $updated;

                if ($updated) {
                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'       => 'update_product_webpages',
                            'product_id' => $this->id,
                            'scope'      => 'units_per_case',
                            'editor'     => $this->editor
                        ),
                        DNS_ACCOUNT_CODE
                    );

                    $this->fork_index_elastic_search();
                    $this->update_updated_markers('Data');
                    $this->update_updated_markers('Price');
                }


                break;


            case 'Parts':


                if ($value != '') {
                    include_once 'class.Part.php';
                    $product_parts = array();
                    foreach (preg_split('/,/', $value) as $part_data) {
                        $part_data = _trim($part_data);

                        if (preg_match('/([0-9]*\.?[0-9]+)x\s+/', $part_data, $matches)) {
                            $ratio = floatval($matches[1]);


                            if ($ratio <= 0) {
                                $this->error      = true;
                                $this->msg        = sprintf(_('Invalid parts format, use: %s'), '<i>'._('number').'</i>>x '._('reference').', <i>'._('number').'</i>>x '._('reference').', ...');
                                $this->error_code = 'invalid_parts';
                                $this->metadata   = $value;

                                return false;
                            }
                            $part_data = preg_replace('/([0-9]*\.?[0-9]+)x\s+/', '', $part_data);
                        } else {
                            $this->error      = true;
                            $this->msg        = sprintf(_('Invalid parts format, use: %s'), '<i>'._('number').'</i>>x '._('reference').', <i>'._('number').'</i>>x '._('reference').', ...');
                            $this->error_code = 'invalid_parts';
                            $this->metadata   = $value;

                            return false;
                        }

                        $part = new Part('reference', _trim($part_data));

                        $product_parts[] = array(
                            'Ratio'    => $ratio,
                            'Part SKU' => $part->id,
                            'Note'     => ''
                        );
                    }


                    foreach ($product_parts as $product_part) {
                        if (!is_array($product_part)

                        ) {
                            $this->error      = true;
                            $this->msg        = "Can't parse product parts";
                            $this->error_code = 'can_not_parse_product_parts_no_array';
                            $this->metadata   = '';


                            return false;
                        }

                        if (!isset($product_part['Part SKU'])


                        ) {
                            $this->error      = true;
                            $this->msg        = "Can't parse product parts, missing part sku";
                            $this->error_code = 'can_not_parse_product_parts_missing_part_sku';
                            $this->metadata   = '';


                            return false;
                        }

                        if (!array_key_exists('Ratio', $product_part)

                        ) {
                            $this->error      = true;
                            $this->msg        = "Can't parse product parts, missing ratio";
                            $this->error_code = 'can_not_parse_product_parts_missing_ratio';
                            $this->metadata   = '';


                            return false;
                        }

                        if (!array_key_exists('Note', $product_part)

                        ) {
                            $this->error      = true;
                            $this->msg        = "Can't parse product parts, missing note";
                            $this->error_code = 'can_not_parse_product_parts_missing_note';
                            $this->metadata   = '';


                            return false;
                        }

                        if (is_null($product_part['Note'])) {
                            $product_part['Note'] = '';
                        }

                        if (

                            !is_numeric($product_part['Part SKU'])) {
                            $this->error      = true;
                            $this->msg        = "Can't parse product parts";
                            $this->error_code = 'can_not_parse_product_parts_wrong_part_sku';
                            $this->metadata   = '';


                            return false;
                        }

                        if (

                            !is_string($product_part['Note'])) {
                            $this->error      = true;
                            $this->msg        = "Can't parse product parts";
                            $this->error_code = 'can_not_parse_product_parts_note_is_not_string';
                            $this->metadata   = '';


                            return false;
                        }


                        $part = get_object('Part', $product_part['Part SKU']);

                        if (!$part->id) {
                            $this->error      = true;
                            $this->msg        = 'Part not found';
                            $this->error_code = 'part_not_found';
                            $this->metadata   = $product_part['Part SKU'];


                            return false;
                        }


                        if (!is_numeric($product_part['Ratio']) or $product_part['Ratio'] < 0) {
                            $this->error      = true;
                            $this->msg        = sprintf(
                                _('Invalid parts per product (%s)'),
                                $product_part['Ratio']
                            );
                            $this->error_code = 'invalid_parts_per_product';
                            $this->metadata   = array($product_part['Ratio']);

                            return false;
                        }
                    }
                } else {
                    $product_parts = [];
                }


                $sql = sprintf(
                    'DELETE FROM `Product Part Bridge` WHERE  `Product Part Product ID`=%d ',
                    $this->id
                );

                $this->db->exec($sql);


                $this->update_part_list(json_encode($product_parts), $options);

                break;
            case 'Product Parts':


                $this->update_part_list($value, $options);

                break;
            case 'Product Public':
                if ($value == 'Yes' and in_array($this->get('Product Status'), array(
                        'Suspended',
                        'Discontinued'
                    ))) {
                    return;
                }
                $this->update_field($field, $value, $options);
                break;


            case 'Product Family Code':

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _("Family's code missing");

                    return;
                }


                $root_category = get_object('Category', $this->get('Store Family Category Key'));
                if ($root_category->id) {
                    $root_category->editor = $this->editor;
                    $family                = $root_category->create_category(array('Category Code' => $value));
                    if ($family->id) {
                        $this->update_field_switcher('Product Family Category Key', $family->id, $options);

                        $this->fast_update(['Product Department Category Key' => $family->get('Product Category Department Category Key')]);
                    } else {
                        $this->error = true;
                        $this->msg   = _("Can't create family");

                        return;
                    }
                } else {
                    $this->error = true;
                    $this->msg   = _("Product families not configured");

                    return;
                }


                break;


            case 'Product Family Category Key':

                $old_value = $this->get($field);

                if ($value) {
                    if ($this->data['Product Family Category Key'] != $value) {
                        $old_family = get_object('Category', $this->data['Product Family Category Key']);

                        /**
                         * @var $family \Category
                         */
                        $family = get_object('Category', $value);

                        $family->associate_subject($this->id, false, '', 'skip_direct_update');


                        if ($old_family->id) {
                            /** @var $old_website \Page */
                            $old_website = get_object('Webpage', $old_family->get('Product Category Webpage Key'));
                            if ($old_website->id) {
                                $old_website->reindex_items();
                                if ($old_website->updated) {
                                    $old_website->publish();
                                }
                            }
                        }

                        if ($family->id) {
                            /** @var $website \Page */
                            $website = get_object('Webpage', $family->get('Product Category Webpage Key'));
                            if ($website->id) {
                                $website->reindex_items();
                                if ($website->updated) {
                                    $website->publish();
                                }
                            }
                        }


                        $this->fast_update(['Product Department Category Key' => $family->get('Product Category Department Category Key')]);
                    }
                } else {
                    if ($this->data['Product Family Category Key'] != '') {
                        $category = new Category($this->data['Product Family Category Key']);

                        if ($category->id) {
                            $category->disassociate_subject($this->id);
                        }


                        $this->fast_update(['Product Department Category Key' => '']);
                    }
                }

                $this->update_field($field, $value, 'no_history');

                $categories = '';
                foreach ($this->get_category_data() as $item) {
                    $categories .= sprintf(
                        '<li><span class="button" onclick="change_view(\'category/%d\')" title="%s">%s</span></li>',
                        $item['category_key'],
                        $item['label'],
                        $item['code']

                    );
                }
                $this->update_metadata = array(
                    'class_html' => array(
                        'Categories' => $categories,

                    )
                );

                if ($old_value != $this->get($field)) {
                    $this->update_updated_markers('Data');
                }


                break;
            case 'Product UN Number':
            case 'Product UN Class':
            case 'Product Packing Group':
            case 'Product Proper Shipping Name':
            case 'Product Hazard Identification Number':
            case('Product Duty Rate'):
            case('Product CPNP Number'):
            case('Product UFI'):

                $old_value = $this->get($field);
                if (!preg_match('/from_part/', $options) and count($this->get_parts()) == 1) {
                    /**
                     * @var $part \Part
                     */
                    $part = array_values($this->get_parts('objects'))[0];
                    $part->update(
                        array(
                            preg_replace('/^Product/', 'Part', $field) => $value
                        ),
                        $options
                    );

                    $this->get_data('id', $this->id);
                    $this->updated = $part->updated;

                    return;
                }


                $this->update_field($field, $value, $options);

                if ($old_value != $this->get($field)) {
                    $this->update_updated_markers('Data');


                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'       => 'update_product_webpages',
                            'product_id' => $this->id,
                            'scope'      => 'mix_properties',
                            'editor'     => $this->editor
                        ),
                        DNS_ACCOUNT_CODE
                    );
                }

                break;
            case 'Product Origin Country Code':

                $old_value = $this->get($field);
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _("Country of origin missing");

                    return;
                }

                include_once 'class.Country.php';
                $country = new Country('find', $value);
                if ($country->get('Country Code') == 'UNK') {
                    $this->error = true;
                    $this->msg   = sprintf(_("Country not found (%s)"), $value);

                    return;
                }

                $value = $country->get('Country Code');

                if (!preg_match('/from_part/', $options) and count($this->get_parts()) == 1) {
                    $part = array_values($this->get_parts('objects'))[0];
                    $part->update(
                        array(
                            preg_replace('/^Product/', 'Part', $field) => $value
                        ),
                        $options
                    );

                    $this->get_data('id', $this->id);
                    $this->updated = $part->updated;

                    return;
                }


                $this->update_field($field, $value, $options);

                if ($old_value != $this->get($field)) {
                    $this->update_updated_markers('Data');


                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping',
                        array(
                            'type'       => 'update_product_webpages',
                            'product_id' => $this->id,
                            'scope'      => 'country_origin',
                            'editor'     => $this->editor
                        ),
                        DNS_ACCOUNT_CODE
                    );
                }


                break;

            case 'History Note':


                $this->add_note($value, '', '', $metadata['deletable']);
                break;


            default:

                if (array_key_exists($field, $this->base_data())) {
                    $this->update_field($field, $value, $options);
                } elseif (array_key_exists(
                    $field,
                    $this->base_data('Product Data')
                )) {
                    $this->update_table_field(
                        $field,
                        $value,
                        $options,
                        'Product Data',
                        'Product Data',
                        $this->id
                    );
                } elseif (array_key_exists(
                    $field,
                    $this->base_data('Product DC Data')
                )) {
                    $this->update_table_field(
                        $field,
                        $value,
                        $options,
                        'Product DC Data',
                        'Product DC Data',
                        $this->id
                    );
                }
        }
        //$this->reread();

    }

    function update_part_list($value, $options = '')
    {
        $num_parts_edited = 0;

        $value     = json_decode($value, true);
        $part_list = $this->get_parts_data();

        $old_part_list_keys = array();
        foreach ($part_list as $product_part) {
            $old_part_list_keys[$product_part['Key']] = $product_part['Key'];
        }


        $new_part_list_keys = array();

        foreach ($value as $product_part) {
            if (isset($product_part['Key'])) {
                $new_part_list_keys[$product_part['Key']] = $product_part['Key'];
            }
        }


        foreach (array_diff($old_part_list_keys, $new_part_list_keys) as $product_part_key) {
            $num_parts_edited++;
            $sql = "delete from `Product Part Bridge` where `Product Part Key`=? ";
            $this->db->prepare($sql)->execute(array(
                $product_part_key
            ));
        }


        foreach ($value as $product_part) {
            if (isset($product_part['Key']) and $product_part['Key'] > 0) {
                $sql = sprintf(
                    'UPDATE `Product Part Bridge` SET `Product Part Note`=%s WHERE `Product Part Key`=%d AND `Product Part Product ID`=%d ',
                    prepare_mysql($product_part['Note']),
                    $product_part['Key'],
                    $this->id
                );

                $updt = $this->db->prepare($sql);
                $updt->execute();
                if ($updt->rowCount()) {
                    $num_parts_edited++;
                    $this->updated = true;
                }


                if ($product_part['Ratio'] == 0) {
                    $sql = sprintf(
                        'DELETE FROM `Product Part Bridge` WHERE `Product Part Key`=%d AND `Product Part Product ID`=%d ',
                        $product_part['Key'],
                        $this->id
                    );

                    $updt = $this->db->prepare($sql);
                    $updt->execute();
                    if ($updt->rowCount()) {
                        $num_parts_edited++;
                        $this->updated = true;
                    }
                } else {
                    $sql = sprintf(
                        'UPDATE `Product Part Bridge` SET `Product Part Ratio`=%f WHERE `Product Part Key`=%d AND `Product Part Product ID`=%d ',
                        $product_part['Ratio'],
                        $product_part['Key'],
                        $this->id
                    );

                    $updt = $this->db->prepare($sql);
                    $updt->execute();
                    if ($updt->rowCount()) {
                        $num_parts_edited++;
                        $this->updated = true;
                    }
                }
            } else {
                if ($product_part['Part SKU'] > 0) {
                    $sql = sprintf(
                        "INSERT INTO `Product Part Bridge` (`Product Part Product ID`,`Product Part Part SKU`,`Product Part Ratio`,`Product Part Note`,`Product Part Linked Fields`) VALUES (%d,%d,%f,%s,'')",
                        $this->id,
                        $product_part['Part SKU'],
                        $product_part['Ratio'],
                        prepare_mysql($product_part['Note'], false)
                    );
                    //    print $sql;
                    $this->db->exec($sql);
                    $num_parts_edited++;
                    $this->updated = true;
                }
            }
        }


        $this->get_data('id', $this->id);


        $this->update_weight();
        $this->update_part_numbers();
        $this->update_cost();

        $this->update_metadata = array(
            'class_html' => array(
                'Package_Weight' => $this->get('Package Weight'),
                'Product_Cost'   => $this->get('Cost'),
                'Product_Price'  => $this->get('Price'),

            )

        );


        $parts        = $this->get_parts();
        $number_parts = count($parts);


        if ($number_parts == 1) {
            $part = get_object('Part', array_pop($parts));
            $this->fast_update(array(
                'Product Tariff Code'                  => $part->get('Part Tariff Code'),
                'Product HTSUS Code'                   => $part->get('Part HTSUS Code'),
                'Product Duty Rate'                    => $part->get('Part Duty Rate'),
                'Product Origin Country Code'          => $part->get('Part Origin Country Code'),
                'Product UN Number'                    => $part->get('Part UN Number'),
                'Product UN Class'                     => $part->get('Part UN Class'),
                'Product Packing Group'                => $part->get('Part Packing Group'),
                'Product Proper Shipping Name'         => $part->get('Part Proper Shipping Name'),
                'Product Hazard Identification Number' => $part->get('Part Hazard Identification Number'),
                'Product Unit Weight'                  => $part->get('Part Unit Weight'),
                'Product Unit Dimensions'              => $part->get('Part Unit Dimensions'),
                'Product Materials'                    => $part->data['Part Materials'],
                'Product Barcode Number'               => $part->data['Part Barcode Number'],
                'Product Barcode Key'                  => $part->data['Part Barcode Key'],

            ));

            if ($this->get('Product Materials') != '') {
                $this->fast_update(array('Product Unit XHTML Materials' => $this->get('Materials')));
            } else {
                $this->fast_update(array('Product Unit XHTML Materials' => ''));
            }

            $this->update_updated_markers('Data');

            $sql = "SELECT `Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Object`='Part' AND `Image Subject Object Key`=? and `Image Subject Object Image Scope`='Marketing' ORDER BY `Image Subject Order` ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(array($part->id));
            while ($row = $stmt->fetch()) {
                $this->link_image($row['Image Subject Image Key'], 'Marketing');
            }


            require_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'       => 'update_product_webpages',
                    'product_id' => $this->id,
                    'scope'      => 'weight',
                    'editor'     => $this->editor
                ),
                DNS_ACCOUNT_CODE
            );
        }


        if ($num_parts_edited > 0) {
            if ($number_parts == 0) {
                $history_abstract = _("Product's parts deleted");
            } else {
                $history_abstract = sprintf(_("Product's parts changed to %s"), $this->get('Parts'));
            }

            $history_data = array(
                'History Abstract' => $history_abstract,
                'History Details'  => '',
                'Action'           => 'edit'
            );

            $this->add_subject_history(
                $history_data,
                true,
                'No',
                'Changes',
                $this->get_object_name(),
                $this->id
            );
        }


        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping',
            array(
                'type'       => 'product_part_list_updated',
                'product_id' => $this->id,
                'editor'     => $this->editor
            ),
            DNS_ACCOUNT_CODE
        );

        $this->process_aiku_fetch('Product', $this->id, 'parts');
    }


    function updating_packing_data()
    {
        $this->load_acc_data();
        $sko            = '';
        $carton         = '';
        $batch          = '';
        $sko_per_carton = '';

        if ($this->get('Product Number of Parts') == 1) {
            foreach ($this->get_parts_data('Objects') as $part_data) {
                $sko = $part_data['Ratio'];


                if ($part_data['Part']->get('Part SKOs per Carton') > 0) {
                    $sko_per_carton = $part_data['Part']->get('Part SKOs per Carton') / $part_data['Ratio'];

                    $carton = $part_data['Ratio'] / $part_data['Part']->get('Part SKOs per Carton');
                }
            }
        }

        $this->fast_update_json_field('Product Properties', 'packing_sko', $sko);
        $this->fast_update_json_field('Product Properties', 'packing_carton', $carton);
        $this->fast_update_json_field('Product Properties', 'packing_batch', $batch);


        $this->fast_update(['Product Outers Per Carton' => $sko_per_carton]);
    }

    function update_weight()
    {
        $old_weight = $this->data['Product Package Weight'];

        $weight = 0;

        $sql = "SELECT `Part Package Weight`,`Product Part Ratio` FROM `Product Part Bridge`  left join `Part Dimension` on (`Product Part Part SKU`=`Part SKU`)  WHERE `Product Part Product ID`=?";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $this->id
        ));
        while ($row = $stmt->fetch()) {
            if (is_numeric($row['Part Package Weight']) and $row['Part Package Weight'] > 0) {
                $weight += $row['Part Package Weight'] * $row['Product Part Ratio'];
            }
        }

        $this->fast_update(array(
            'Product Package Weight' => $weight,


        ));

        if ($old_weight != $weight) {
            $this->update_updated_markers('Data');

            require_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'       => 'update_product_webpages',
                    'product_id' => $this->id,
                    'scope'      => 'weight',
                    'editor'     => $this->editor
                ),
                DNS_ACCOUNT_CODE
            );
        }
    }

    function update_part_numbers()
    {
        $number_parts = 0;

        $sql = sprintf(
            'SELECT count(`Product Part Part SKU`) AS num FROM `Product Part Bridge`  WHERE `Product Part Product ID`=%d',
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_parts = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $this->fast_update(array(
            'Product Number of Parts' => $number_parts,


        ));
    }

    function get_category_data()
    {
        $type = 'Product';

        $sql = sprintf(
            "SELECT B.`Category Key`,`Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=%d AND B.`Subject`=%s",
            $this->id,
            prepare_mysql($type)
        );

        $category_data = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sql = "SELECT `Category Key`,`Category Label`,`Category Code` FROM `Category Dimension` WHERE `Category Key`=?";


                $stmt2 = $this->db->prepare($sql);
                $stmt2->execute(array(
                    $row['Category Root Key']
                ));
                if ($row2 = $stmt2->fetch()) {
                    $root_label = $row2['Category Label'];
                    $root_code  = $row2['Category Code'];
                    $root_key   = $row2['Category Key'];
                } else {
                    $root_label = '';
                    $root_code  = '';
                    $root_key   = '';
                }


                if ($row['Is Category Field Other'] == 'Yes' and $row['Other Note'] != '') {
                    $value = $row['Other Note'];
                } else {
                    $value = $row['Category Label'];
                }
                $category_data[] = array(
                    'root_label'        => $root_label,
                    'root_code'         => $root_code,
                    'root_category_key' => $root_key,
                    'label'             => $row['Category Label'],
                    'code'              => $row['Category Code'],
                    'value'             => $value,
                    'category_key'      => $row['Category Key']
                );
            }
        }


        return $category_data;
    }

    function update_cost()
    {
        $cost = 0;

        foreach ($this->get_parts_data($with_objects = true) as $part_data) {
            if ($part_data['Part']->get('Part Cost in Warehouse') != '') {
                $part_cost = $part_data['Part']->get('Part Cost in Warehouse');
            } else {
                $part_cost = $part_data['Part']->get('Part Cost');
            }

            $cost += $part_cost * $part_data['Ratio'];
        }

        $this->fast_update([
            'Product Cost' => $cost
        ]);
    }

    function get_deal_components($scope = 'keys', $options = 'Active')
    {
        switch ($options) {
            case 'Active':
                $where = 'AND `Deal Component Status`=\'Active\'';
                break;
            default:
                $where = '';
                break;
        }


        $deal_components = array();


        $parent_categories = $this->get_parent_categories();

        if (count($parent_categories) > 0) {
            $sql = sprintf(
                "SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Allowance Target`='Category' AND `Deal Component Allowance Target Key` in (%s) $where",
                join(',', $parent_categories)

            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    if ($scope == 'objects') {
                        $deal_components[$row['Deal Component Key']] = get_object('DealComponent', $row['Deal Component Key']);
                    } else {
                        $deal_components[$row['Deal Component Key']] = $row['Deal Component Key'];
                    }
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }
        }


        return $deal_components;
    }

    function get_parent_categories($scope = 'keys')
    {
        $type              = 'Product';
        $parent_categories = array();

        $sql = sprintf(
            "SELECT B.`Category Key`,`Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=%d AND B.`Subject`=%s",
            $this->id,
            prepare_mysql($type)
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($scope == 'keys') {
                    $parent_categories[$row['Category Key']] = $row['Category Key'];
                } elseif ($scope == 'objects') {
                    $parent_categories[$row['Category Key']] = get_object('Category', $row['Category Key']);
                } elseif ($scope == 'data') {
                    $sql = sprintf(
                        "SELECT `Category Label`,`Category Code` FROM `Category Dimension` WHERE `Category Key`=%d",
                        $row['Category Root Key']
                    );


                    if ($result2 = $this->db->query($sql)) {
                        if ($row2 = $result2->fetch()) {
                            $root_label = $row2['Category Label'];
                            $root_code  = $row2['Category Code'];
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                    if ($row['Is Category Field Other'] == 'Yes' and $row['Other Note'] != '') {
                        $value = $row['Other Note'];
                    } else {
                        $value = $row['Category Label'];
                    }
                    $parent_categories[] = array(
                        'root_label'   => $root_label,
                        'root_code'    => $root_code,
                        'label'        => $row['Category Label'],
                        'code'         => $row['Category Code'],
                        'value'        => $value,
                        'category_key' => $row['Category Key']
                    );
                }
            }
        }


        return $parent_categories;
    }

    function get_attachments()
    {
        $attachments = array();


        $sql = sprintf(
            'SELECT `Attachment Subject Type`, `Attachment Bridge Key`,`Attachment Caption`  FROM `Product Part Bridge`  LEFT JOIN `Attachment Bridge` AB  ON (AB.`Subject Key`=`Product Part Part SKU`)    WHERE AB.`Subject`="Part" AND  `Product Part Product ID`=%d  AND `Attachment Public`="Yes"  ',
            $this->id
        );

        //    print $sql;


        if ($result2 = $this->db->query($sql)) {
            foreach ($result2 as $row2) {
                if ($row2['Attachment Subject Type'] == 'MSDS') {
                    $label = '<span title="'._('Material safety data sheet').'">MSDS</span>';
                } else {
                    $label = _('Attachment');
                }


                $attachments[] = array(
                    'id'    => $row2['Attachment Bridge Key'],
                    'label' => $label,
                    'name'  => $row2['Attachment Caption']
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $attachments;
    }

    function get_next_deliveries_data()
    {
        $next_delivery_time = 0;

        $next_deliveries_data = array();

        $parts_data = $this->get_parts_data();

        if (count($parts_data) == 1) {
            $part_data = array_pop($parts_data);
            //                            'qty'  => number($row['Purchase Ordering Units'] / $part->get('Part Units Per Package') / $part_data['Ratio']),
            /** @var Part $part */
            $part           = get_object('Part', $part_data['Part SKU']);
            $supplier_parts = $part->get_supplier_parts();
            if (count($supplier_parts) > 0) {
                $sql = sprintf(
                    'SELECT  `Supplier Part Packages Per Carton`,`Purchase Order Key`,`Supplier Delivery Transaction State`,`Supplier Delivery Parent`,`Supplier Delivery Parent Key`,`Part Units Per Package`,
                `Supplier Delivery Units`, `Supplier Delivery Checked Units`,
                ifnull(`Supplier Delivery Placed Units`,0) AS placed,POTF.`Supplier Delivery Key`,`Supplier Delivery Public ID` FROM 
                `Purchase Order Transaction Fact` POTF LEFT JOIN 
                `Supplier Delivery Dimension` PO  ON (PO.`Supplier Delivery Key`=POTF.`Supplier Delivery Key`)  left join  
                `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`) left join 
                `Part Dimension` Pa on (SP.`Supplier Part Part SKU`=Pa.`Part SKU`)
                WHERE POTF.`Supplier Part Key` IN (%s)  AND  POTF.`Supplier Delivery Key` IS NOT NULL AND `Supplier Delivery Transaction State` in ("InProcess","Dispatched","Received","Checked")
                

                
                 ',
                    join($supplier_parts, ',')
                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        // print_r($row);


                        if ($row['Supplier Delivery Checked Units'] > 0 or $row['Supplier Delivery Checked Units'] == '') {
                            if ($row['Supplier Delivery Checked Units'] == '') {
                                $raw_units_qty = $row['Supplier Delivery Units'];
                            } else {
                                $raw_units_qty = $row['Supplier Delivery Checked Units'] - $row['placed'];
                            }


                            if ($raw_units_qty > 0) {
                                $_next_delivery_time = strtotime('tomorrow');

                                $raw_outer_qty = $raw_units_qty / $row['Part Units Per Package'] / $part_data['Ratio'];


                                switch ($row['Supplier Delivery Transaction State']) {
                                    case 'InProcess':
                                        $state = sprintf('%s', _('In Process'));
                                        break;
                                    case 'Consolidated':
                                        $state = sprintf('%s', _('Consolidated'));
                                        break;
                                    case 'Dispatched':
                                        $state = sprintf('%s', _('Dispatched'));
                                        break;
                                    case 'Received':
                                        $state = sprintf('%s', _('Received'));
                                        break;
                                    case 'Checked':
                                        $state = sprintf('%s', _('Checked'));
                                        break;


                                    default:
                                        $state = $row['Supplier Delivery State'];
                                        break;
                                }

                                $next_deliveries_data[] = array(
                                    'type'            => 'delivery',
                                    'qty'             => '+'.number($raw_outer_qty),
                                    'raw_outer_qty'   => $raw_outer_qty,
                                    'raw_units_qty'   => $raw_units_qty,
                                    'date'            => '',
                                    'formatted_link'  => sprintf(
                                        '<i class="fal fa-truck fa-fw" ></i> <i style="visibility: hidden" class="fal fa-truck fa-fw" ></i> <span class="link" onclick="change_view(\'%s/%d/delivery/%d\')"> %s</span>',
                                        strtolower($row['Supplier Delivery Parent']),
                                        $row['Supplier Delivery Parent Key'],
                                        $row['Supplier Delivery Key'],
                                        $row['Supplier Delivery Public ID']
                                    ),
                                    'link'            => sprintf('%s/%d/delivery/%d', strtolower($row['Supplier Delivery Parent']), $row['Supplier Delivery Parent Key'], $row['Supplier Delivery Key']),
                                    'order_id'        => $row['Supplier Delivery Public ID'],
                                    'formatted_state' => '<span class=" italic">'.$row['Supplier Delivery Transaction State'].'</span>',
                                    'state'           => $state,

                                    'po_key' => $row['Purchase Order Key']
                                );


                                if ($_next_delivery_time > $next_delivery_time) {
                                    $next_delivery_time = $_next_delivery_time;
                                }
                            }
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                $sql = sprintf(
                    'SELECT `Supplier Part Packages Per Carton`,POTF.`Purchase Order Transaction State`,`Purchase Order Submitted Units`,`Supplier Delivery Key` ,`Purchase Order Estimated Receiving Date`,`Purchase Order Public ID`,POTF.`Purchase Order Key` ,
                `Part Units Per Package`,`Purchase Order Ordering Units`,`Purchase Order Submitted Units`
        FROM `Purchase Order Transaction Fact` POTF LEFT JOIN `Purchase Order Dimension` PO  ON (PO.`Purchase Order Key`=POTF.`Purchase Order Key`)  
          left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`) left join 
                `Part Dimension` Pa on (SP.`Supplier Part Part SKU`=Pa.`Part SKU`)
        
        WHERE POTF.`Supplier Part Key`IN (%s) AND  POTF.`Supplier Delivery Key` IS NULL AND POTF.`Purchase Order Transaction State` NOT IN ("Placed","Cancelled") ',
                    join($supplier_parts, ',')
                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        if ($row['Purchase Order Transaction State'] == 'InProcess') {
                            $raw_units_qty = $row['Purchase Order Ordering Units'];
                            $raw_outer_qty = $raw_units_qty / $row['Part Units Per Package'] / $part_data['Ratio'];

                            $_next_delivery_time = 0;
                            $date                = '';
                            $formatted_state     = '<span class="very_discreet italic">'._('Draft').'</span>';
                            $link                = sprintf(
                                '<i class="fal fa-fw  very_discreet fa-clipboard" ></i> <i class="fal fa-fw  very_discreet fa-seedling" title="%s" ></i> <span class="link very_discreet" onclick="change_view(\'suppliers/order/%d\')"> %s</span>',
                                _('In process'),
                                $row['Purchase Order Key'],
                                $row['Purchase Order Public ID']
                            );
                            $qty                 = '<span class="very_discreet italic">+'.number($raw_outer_qty).'</span>';
                        } elseif ($row['Purchase Order Transaction State'] == 'Submitted') {
                            $raw_units_qty = $row['Purchase Order Submitted Units'];
                            $raw_outer_qty = $raw_units_qty / $row['Part Units Per Package'] / $part_data['Ratio'];

                            $_next_delivery_time = 0;
                            $date                = '';
                            $formatted_state     = '<span class="very_discreet italic">'._('Submitted').'</span>';
                            $link                = sprintf(
                                '<i class="fal fa-fw  very_discreet fa-clipboard" ></i> <i class="fal fa-fw very_discreet fa-paper-plane" title="%s" ></i> <span class="link very_discreet" onclick="change_view(\'suppliers/order/%d\')"> %s</span>',
                                _('Submitted'),
                                $row['Purchase Order Key'],
                                $row['Purchase Order Public ID']
                            );
                            $qty                 = '<span class="very_discreet italic">+'.number($raw_outer_qty).'</span>';
                        } else {
                            $raw_units_qty = $row['Purchase Order Submitted Units'];
                            $raw_outer_qty = $raw_units_qty / $row['Part Units Per Package'] / $part_data['Ratio'];

                            $_next_delivery_time = strtotime($row['Purchase Order Estimated Receiving Date'].' +0:00');
                            $date                = strftime("%e %b %y", strtotime($row['Purchase Order Estimated Receiving Date'].' +0:00'));

                            $formatted_state = strftime("%e %b %y", strtotime($row['Purchase Order Estimated Receiving Date'].' +0:00'));
                            $link            = sprintf(
                                '<i class="fal fa-fw  fa-clipboard" ></i> <i class="fal fa-fw  fa-calendar-check" title="%s" ></i> <span class="link" onclick="change_view(\'suppliers/order/%d\')">  %s</span>',
                                _('Confirmed'),
                                $row['Purchase Order Key'],
                                $row['Purchase Order Public ID']
                            );
                            $qty             = '+'.number($raw_outer_qty);
                        }


                        $next_deliveries_data[] = array(
                            'type'           => 'po',
                            'qty'            => $qty,
                            '$raw_outer_qty' => $raw_outer_qty,
                            'raw_units_qty'  => $raw_units_qty,

                            'date'            => $date,
                            'formatted_state' => $formatted_state,

                            'formatted_link' => $link,
                            'link'           => sprintf('suppliers/order/%d', $row['Purchase Order Key']),
                            'order_id'       => $row['Purchase Order Public ID'],
                            'state'          => $row['Purchase Order Transaction State'],
                            'po_key'         => $row['Purchase Order Key']
                        );


                        if ($_next_delivery_time > $next_delivery_time) {
                            $next_delivery_time = $_next_delivery_time;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }
            }
        }


        return $next_deliveries_data;
    }

    function properties($key)
    {
        return ($this->properties[$key] ?? '');
    }

    function update_product_donut_marketing_customers()
    {
        include_once 'utils/asset_marketing_customers.php';

        $store              = get_object('Store', $this->get('Store Key'));
        $targeted_threshold = min($store->properties('email_marketing_customers') * .05, 500);


        $targeted_customers = get_targeted_product_customers(array(), $this->db, $this->id, $targeted_threshold);


        $spread_customers = get_spread_product_customers(array(), $this->db, $this->id, 5 * $targeted_threshold);


        $customers            = array_diff($spread_customers, $targeted_customers);
        $estimated_recipients = count($customers);

        $this->fast_update_json_field('Product Properties', 'donut_marketing_customers', $estimated_recipients);
        $this->fast_update_json_field('Product Properties', 'donut_marketing_customers_last_updated', gmdate('U'));
    }

    function get_aiku_params($field, $value = '')
    {
        $url = AIKU_URL.'products/'.$this->id;


        switch ($field) {
            case 'Object':
                $url = AIKU_URL.'products/';


                $params = $this->get_aiku_params('historic_props')[1];
                $params += $this->get_aiku_params('Product Status')[1];

                $params['legacy_id'] = $this->id;

                $legacy_data                         = [];
                $legacy_data['store_key']            = $this->get('Product Store Key');
                $legacy_data['product_historic_key'] = $this->get('Product Current Key');


                $legacy_data += json_decode($this->get_aiku_params('historic_data')[1]['legacy'], true);
                $legacy_data += json_decode($this->get_aiku_params('parts')[1]['legacy'], true);


                $params['legacy'] = json_encode($legacy_data);

                print_r($params);

                break;

            case 'historic_props':
                $params         = [];
                $params['name'] = $this->data['Product Name'];
                $params['code'] = $this->data['Product Code'];
                $units          = $this->data['Product Units Per Case'];
                if ($units == 0) {
                    $units = 1;
                }
                $params['units']      = $units;
                $params['unit_price'] = $this->data['Product Price'] / $units;

                $legacy_data                         = [];
                $legacy_data['product_historic_key'] = $this->get('Product Current Key');
                $legacy_data                         += json_decode($this->get_aiku_params('historic_data')[1]['legacy'], true);
                $params['legacy']                    = json_encode($legacy_data);

                break;
            case 'historic_data':

                $product_historic_data = [];

                $sql  = "select `Product History Code`,`Product History Name` ,`Product History Units Per Case`,`Product History Valid From`,`Product History Price`,`Product Key`
                                    from `Product History Dimension` where `Product ID`=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                    $this->id
                ));
                while ($row = $stmt->fetch()) {
                    $product_historic_data[] = $row;
                }
                $legacy_data['historic_products'] = $product_historic_data;


                $params['legacy'] = json_encode($legacy_data);
                break;
            case 'parts':
                $parts = [];

                $sql  = "select * from `Product Part Bridge` where `Product Part Product ID`=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array(
                    $this->id
                ));
                while ($row = $stmt->fetch()) {
                    $parts[] = $row;
                }
                $legacy_data          = [];
                $legacy_data['parts'] = $parts;

                $params['legacy'] = json_encode($legacy_data);
                break;
            case 'Product Status':


                $status = true;
                if ($this->data['Product Status'] == 'Discontinued') {
                    $status = false;
                }

                switch ($this->data['Product Status']) {
                    case 'InProcess':
                        $state = 'creating';
                        break;
                    case 'Discontinuing':
                        $state = 'discontinuing';
                        break;
                    case 'Discontinued':
                        $state = 'discontinued';
                        break;

                    default:
                        $state = 'active';
                }


                $params['state']  = $state;
                $params['status'] = $status;


                break;


            default:
                return [
                    false,
                    false
                ];
        }


        return [
            $url,
            $params
        ];
    }

}



