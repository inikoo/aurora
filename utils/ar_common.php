<?php

include_once 'common.php';
/** @var User $user */
if ($user->get('User View') != 'Staff') {
    exit;
}
$editor = array(
    'Author Name'  => $user->data['User Alias'],
    'Author Alias' => $user->data['User Alias'],
    'Author Type'  => $user->data['User Type'],
    'Author Key'   => $user->data['User Parent Key'],
    'User Key'     => $user->id,
    'Date'         => gmdate('Y-m-d H:i:s')
);


function is_type($type, $value): bool
{
    switch ($type) {
        case('numeric'):
            if (!is_numeric($value)) {
                return false;
            }
            break;
        case('key'):
            if (!is_numeric($value) or $value <= 0) {
                return false;
            }
            break;
        case('no empty string'):
        case('string with value'):
            if (!is_string($value) and $value != '') {
                return false;
            }
            break;

        case('array'):
            if (!is_array($value)) {
                return false;
            }
            break;
    }

    return true;
}

function prepare_values($data, $value_names)
{
    if (!is_array($data)) {
        exit(
        json_encode(array(
                        'state' => 400,
                        'msg'   => 'Error wrong value 1'
                    ))
        );
    }

    $parsed_data = [];

    foreach ($value_names as $value_name => $extra_data) {
        $optional = (isset($extra_data['optional']) and $extra_data['optional']);
        if (!isset($data[$value_name])) {
            if (!$optional) {
                $response = array(
                    'state' => 400,
                    'msg'   => "Error no value $value_name 2 "
                );
                echo json_encode($response);
                exit;
            } else {
                continue;
            }
        }
        $expected_type = $extra_data['type'];

        switch ($expected_type) {
            case('no empty string'):
            case('string with value'):
            case('string'):
            case('key'):
            case('numeric'):
                if (!is_type($expected_type, $data[$value_name])) {
                    exit(
                    json_encode(array(
                                    'state' => 400,
                                    'msg'   => 'Error wrong value ('.$value_name.') '.$expected_type.' -> '.$data[$value_name]
                                ))
                    );
                }

                $parsed_data[$value_name] = $data[$value_name];
                break;
            case('enum'):
                if (!preg_match(
                    $extra_data['valid values regex'],
                    $data[$value_name]
                )) {
                    exit(
                    json_encode(array(
                                    'state' => 400,
                                    'msg'   => "Error wrong value 4 ".$extra_data['valid values regex']."  "
                                ))
                    );
                }

                $parsed_data[$value_name] = $data[$value_name];
                break;

            case('json array'):

                $tmp = $data[$value_name];

                $raw_data = json_decode($tmp, true);

                if (is_array($raw_data)) {
                    if (!isset($extra_data['required elements'])) {
                        $extra_data['required elements'] = array();
                    }
                    foreach (
                        $extra_data['required elements'] as $element_name => $element_type
                    ) {
                        if (!isset($raw_data[$element_name]) or !is_type(
                                $element_type,
                                $raw_data[$element_name]
                            )) {
                            exit(
                            json_encode(array(
                                            'state' => 400,
                                            'msg'   => "Error wrong 5 value  $element_name  "
                                        ))
                            );
                        }
                    }
                    foreach ($raw_data as $key => $value) {
                        if (is_string($value)) {
                            $raw_data[$key] = html_entity_decode($value);
                        }
                    }

                    $parsed_data[$value_name] = $raw_data;
                } else {
                    exit(
                    json_encode(array(
                                    'state' => 400,
                                    'msg'   => 'Error wrong value json'
                                ))
                    );
                }


                break;
            default:
                $parsed_data[$value_name] = $data[$value_name];
        }
    }


    return $parsed_data;
}

