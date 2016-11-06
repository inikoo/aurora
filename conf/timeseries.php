<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 16:11:59 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

function get_time_series_config() {

    $time_series = array(
        'Store' => array(
            array(
                'Timeseries Type'      => 'StoreSales',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Store'
            )
        ),

        'Supplier'        => array(
            array(
                'Timeseries Type'      => 'SupplierSales',
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Supplier'
            ),
            array(
                'Timeseries Type'      => 'SupplierSales',
                'Timeseries Frequency' => 'Quarterly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Supplier'
            ),
            array(
                'Timeseries Type'      => 'SupplierSales',
                'Timeseries Frequency' => 'Monthly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Supplier'
            ),
            array(
                'Timeseries Type'      => 'SupplierSales',
                'Timeseries Frequency' => 'Weekly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Supplier'
            ),
            array(
                'Timeseries Type'      => 'SupplierSales',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Supplier'
            ),


        ),
        'ProductCategory' => array(


            array(
                'Timeseries Type'      => 'ProductCategorySales',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),

            array(
                'Timeseries Type'      => 'ProductCategorySales',
                'Timeseries Frequency' => 'Weekly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),
            array(
                'Timeseries Type'      => 'ProductCategorySales',
                'Timeseries Frequency' => 'Monthly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),
            array(
                'Timeseries Type'      => 'ProductCategorySales',
                'Timeseries Frequency' => 'Quarterly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),
            array(
                'Timeseries Type'      => 'ProductCategorySales',
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            )


        ),
        'PartCategory'    => array(


            array(
                'Timeseries Type'      => 'PartCategorySales',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),

            array(
                'Timeseries Type'      => 'PartCategorySales',
                'Timeseries Frequency' => 'Weekly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),
            array(
                'Timeseries Type'      => 'PartCategorySales',
                'Timeseries Frequency' => 'Monthly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),
            array(
                'Timeseries Type'      => 'PartCategorySales',
                'Timeseries Frequency' => 'Quarterly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            ),
            array(
                'Timeseries Type'      => 'PartCategorySales',
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Category'
            )


        )

    );

    return $time_series;

}

?>
