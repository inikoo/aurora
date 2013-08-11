<?php
$truncate_sql="
truncate `Product Department Dimension`;truncate `Product Family Dimension`;truncate `Product Dimension`; truncate `Product Family Department Bridge`;truncate `Product Department Bridge`;truncate `Deal Metadata Dimension`;truncate `Part Dimension`;truncate `Supplier Dimension`;truncate `Product Part List` ;truncate `Supplier Product Part List`;truncate `Supplier Product Dimension`;TRUNCATE `Image Dimension`;
TRUNCATE `Product Image Bridge`;TRUNCATE `Address Bridge`;TRUNCATE `Address Dimension`;TRUNCATE `Company Department Dimension`;TRUNCATE `Company Dimension`;TRUNCATE `Company Web Site Bridge`;TRUNCATE `Contact Dimension`;TRUNCATE `Contract Terms Dimension`;TRUNCATE `Customer Dimension`;TRUNCATE `Email Bridge`;TRUNCATE `Email Dimension`;TRUNCATE `Order Dimension`;TRUNCATE `Order Transaction Fact`;TRUNCATE `Telecom Bridge`;TRUNCATE `Telecom Dimension`;TRUNCATE `Ship To Dimension`;TRUNCATE `History Dimension`;;TRUNCATE `Invoice Dimension`;TRUNCATE `Delivery Note Dimension`;TRUNCATE `Inventory Transaction Fact`;TRUNCATE `Inventory Spanshot Fact`;TRUNCATE `Invoice Delivery Note Bridge`;truncate `Product Category Dimension`;truncate `Shelf Dimension`;
TRUNCATE `Order Delivery Note Bridge`;truncate `Store Default Currency`;
TRUNCATE `Order Invoice Bridge`;
TRUNCATE `Order No Product Transaction Fact`;
TRUNCATE `Purchase Order Dimension`;
TRUNCATE `Location Dimension`;
TRUNCATE `Part Location Dimension`;truncate `Customer Ship To Bridge`;
TRUNCATE `Contact Bridge`;TRUNCATE `Telecom Bridge`;TRUNCATE `Telecom Dimension`;
TRUNCATE `Address Telecom Bridge`;
TRUNCATE `Product History Dimension`;
TRUNCATE `Product Same Code Dimension`;
TRUNCATE `Product Same Code Dimension`;
TRUNCATE `Time Series Dimension`;
TRUNCATE TABLE `Supplier Product History Dimension` ;
TRUNCATE `Time Series Dimension`;
TRUNCATE `Warehouse Dimension`;
TRUNCATE `Location Dimension`;
TRUNCATE `Store Dimension`;
TRUNCATE `Category Dimension`;
TRUNCATE `Category Bridge`;
TRUNCATE `Deal Dimension`;
TRUNCATE `Campaign Deal Schema`;
TRUNCATE `Charge Dimension`;
TRUNCATE `Attachment Dimension`;

TRUNCATE `Image Bridge`;
TRUNCATE `Order Public ID 1`;
truncate `Product Part Dimension`;
truncate `Product Family Bridge`;
truncate `Shipping Dimension`;
truncate `Staff Dimension`;
truncate `Warehouse Area Dimension`;TRUNCATE `Part Picking Fact` ;truncate `MasterKey Dimension`;truncate `Order Transaction Deal Bridge`;
truncate `Company Bridge`;
truncate `Category Bridge`;truncate `History Dimension`;truncate `Invoice Tax Bridge`;
;truncate `Company Area Dimension`;truncate `Account Dimension`;truncate `Company Old ID Bridge`;truncate `Contact Old ID Bridge`;
truncate `Supplier Delivery Note Dimension`;truncate `Supplier Delivery Note Item Part Bridge`;truncate `Purchase Order Transaction Fact`
";

?>