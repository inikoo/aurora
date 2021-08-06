<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: , 17 January 2020  00:47::06  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';

// Check if Families Deapts is in same family


$sql         = "select `Store Key` from `Store Dimension`";
$stmt_stores = $db->prepare($sql);
$stmt_stores->execute(
    array()
);
while ($row_stores = $stmt_stores->fetch()) {


    $store = get_object('Store', $row_stores['Store Key']);


    print $store->get('Code')."\n";


    $family_root_pass = false;
    $families_root    = get_object('Category', $store->get('Store Family Category Key'));
    if ($families_root->id) {
        $family_root_pass = true;
        if ($families_root->get('Store Key') != $store->id) {
            $family_root_pass = false;
        }
        if ($families_root->get('Category Scope') != 'Product') {
            $family_root_pass = false;
        }
        if ($families_root->get('Category Subject') != 'Product') {
            $family_root_pass = false;
        }
    }

    if (!$family_root_pass) {
        print "Family root \t\t".($family_root_pass ? 'Ok' : 'Fail')."\n";
    }

    $department_root_pass = false;
    $departments_root     = get_object('Category', $store->get('Store Department Category Key'));
    if ($departments_root->id) {
        $department_root_pass = true;
        if ($departments_root->get('Store Key') != $store->id) {
            $department_root_pass = false;
        }
        if ($departments_root->get('Category Scope') != 'Product') {
            $department_root_pass = false;
        }
        if ($departments_root->get('Category Subject') != 'Category') {
            $department_root_pass = false;
        }
    }
    if (!$department_root_pass) {
        print "Department root \t".($department_root_pass ? 'Ok' : 'Fail')."\n";
    }

    $sql =
        "select F.`Category Store Key`,`Category Code`, `Product Category Department Category Key`,`Product Category Key` from `Category Dimension` F left join `Product Category Dimension` PC on (PC.`Product Category Key`=F.`Category Key`)  where `Category Root Key`=? and `Category Branch Type`='Head' ";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $store->get('Store Family Category Key')
        )
    );
    while ($row = $stmt->fetch()) {


        $family_pass = false;
        $family      = get_object('Category', $row['Product Category Key']);


        $departments     = $family->get_categories('objects', 'root_key', $store->get('Store Department Category Key'));
        $num_departments = count($departments);


        $department_key_from_branch = 0;
        if ($num_departments == 0) {
            $missing_department_from_branch = true;
            $department_from_branch_pass    = true;

        } elseif ($num_departments == 1) {
            $missing_department_from_branch = false;
            $department_from_branch_pass    = true;

            $department_key_from_branch = array_pop($departments);


        } elseif ($num_departments > 1) {
            $missing_department_from_branch = false;
            $department_from_branch_pass    = false;
            exit('Ahhhhhhh');

        }


        if ($family->id) {
            $family_pass = true;
            if ($family->get('Store Key') != $store->id) {
                $family_pass = false;
            }
            if ($family->get('Category Scope') != 'Product') {
                $family_pass = false;
            }
            if ($family->get('Category Subject') != 'Product') {
                $family_pass = false;
            }
        }

        if ($row['Product Category Department Category Key'] != '') {

            $department_fail_msg = 'not found :'.$row['Product Category Department Category Key'];

            $department_pass    = false;
            $missing_department = false;
            $department         = get_object('Category', $row['Product Category Department Category Key']);


            if ($department->id) {
                $department_pass = true;
                if ($department->get('Store Key') != $store->id) {
                    $department_fail_msg = 'wrong store';

                    $department_pass = false;
                }
                if ($department->get('Category Scope') != 'Product') {
                    $department_pass     = false;
                    $department_fail_msg = 'wrong scope';

                }
                if ($department->get('Category Subject') != 'Category') {
                    $department_pass     = false;
                    $department_fail_msg = 'wrong subject';

                }


                if (!$missing_department_from_branch and $department_from_branch_pass) {
                    if ($department_key_from_branch->id != $department->id) {
                        $department_pass     = false;
                        $department_fail_msg = "Dept not match ".$department_key_from_branch->id." ".$department->id;

                    }

                }

            }

        } else {
            $department_pass    = true;
            $missing_department = true;

        }


        if (!$family_pass) {
            print "\tFam ".$row['Category Code']." \t".($family_pass ? 'Ok' : 'Fail')."\n";
            //print_r($family->get_categories('objects'));
            //exit;
        }
        if (!$department_pass) {
            print "\tFam ".$row['Category Code']." Dept \t".($department_pass ? 'Ok' : 'Fail '.$department_fail_msg)."\n";

            //  print_r($family->get_categories('objects'));
            // exit;
        }

        if (!$department_from_branch_pass) {
            print "\tFam ".$row['Category Code']." Error Dept branch !!!!!!! BIG ERROR\t";

        }

        if ($missing_department_from_branch and $missing_department) {
            print "\tFam ".$row['Category Code']." Miss dept\n";

        } elseif ($missing_department_from_branch) {
            print "\tFam ".$row['Category Code']." Miss dept branch but have in obj \n";
            // print_r($family->get_categories('objects'));

        } elseif ($missing_department) {
            print "\tFam ".$row['Category Code']." Miss dept obj\n";
            // print_r($family->get_categories('objects'));

        }


    }


    $sql = "select `Product ID`,`Product Family Category Key`,`Product Department Category Key` from `Product Dimension` where `Product Store Key`=?";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $store->id
        )
    );
    while ($row = $stmt->fetch()) {


    }


    print "\n";
}



