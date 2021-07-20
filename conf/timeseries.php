<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 16:11:59 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

function get_time_series_config(): array {

    return array(
        'Account' => array(
            array(
                'Timeseries Type'      => 'AccountSales',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Account',
            ),
            array(
                'Timeseries Type'      => 'AccountSales',
                'Timeseries Frequency' => 'Weekly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Account',
            ),
            array(
                'Timeseries Type'      => 'AccountSales',
                'Timeseries Frequency' => 'Monthly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Account',
            ),
            array(
                'Timeseries Type'      => 'AccountSales',
                'Timeseries Frequency' => 'Quarterly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Account',
            ),
            array(
                'Timeseries Type'      => 'AccountSales',
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Account',
            )




        ),
        'Store' => array(

            array(
                'Timeseries Type'      => 'StoreSales',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Store',
            ),
            array(
                'Timeseries Type'      => 'StoreSales',
                'Timeseries Frequency' => 'Weekly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Store',
            ),
            array(
                'Timeseries Type'      => 'StoreSales',
                'Timeseries Frequency' => 'Monthly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Store',
            ),
            array(
                'Timeseries Type'      => 'StoreSales',
                'Timeseries Frequency' => 'Quarterly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Store',
            ),

            array(
                'Timeseries Type'      => 'StoreSales',
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Store',
            )




        ),

        'Supplier'        => array(
            array(
                'Timeseries Type'      => 'SupplierSales',
                'Timeseries Frequency' => 'Daily',
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
                'Timeseries Frequency' => 'Monthly',
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
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Supplier'
            ),






        ),
        'Customer'        => array(
            array(
                'Timeseries Type'      => 'CustomerSales',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Customer'
            ),
            array(
                'Timeseries Type'      => 'CustomerSales',
                'Timeseries Frequency' => 'Weekly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Customer'
            ),
            array(
                'Timeseries Type'      => 'CustomerSales',
                'Timeseries Frequency' => 'Monthly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Customer'
            ),
            array(
                'Timeseries Type'      => 'CustomerSales',
                'Timeseries Frequency' => 'Quarterly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Customer'
            ),
            array(
                'Timeseries Type'      => 'CustomerSales',
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'Sales',
                'Timeseries Parent'    => 'Customer'
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


        ),
        'Warehouse'        => array(
            array(
                'Timeseries Type'      => 'WarehouseStockLeakages',
                'Timeseries Frequency' => 'Daily',
                'Timeseries Scope'     => 'StockLeakages',
                'Timeseries Parent'    => 'Warehouse'
            ),
            array(
                'Timeseries Type'      => 'WarehouseStockLeakages',
                'Timeseries Frequency' => 'Weekly',
                'Timeseries Scope'     => 'StockLeakages',
                'Timeseries Parent'    => 'Warehouse'
            ),
            array(
                'Timeseries Type'      => 'WarehouseStockLeakages',
                'Timeseries Frequency' => 'Monthly',
                'Timeseries Scope'     => 'StockLeakages',
                'Timeseries Parent'    => 'Warehouse'
            ),
            array(
                'Timeseries Type'      => 'WarehouseStockLeakages',
                'Timeseries Frequency' => 'Quarterly',
                'Timeseries Scope'     => 'StockLeakages',
                'Timeseries Parent'    => 'Warehouse'
            ),
            array(
                'Timeseries Type'      => 'WarehouseStockLeakages',
                'Timeseries Frequency' => 'Yearly',
                'Timeseries Scope'     => 'StockLeakages',
                'Timeseries Parent'    => 'Warehouse'
            ),






        ),

    );

}

?>
