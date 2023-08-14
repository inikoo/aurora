ALTER TABLE `Charge Dimension`
    CHANGE `Charge Scope` `Charge Scope` enum('Hanging','Premium','Insurance','Tracking','Pastpay','CoD') NULL COMMENT '',
    CHANGE `Charge Trigger` `Charge Trigger` enum('Product','Order','Selected by Customer','Payment Type') NULL COMMENT '';