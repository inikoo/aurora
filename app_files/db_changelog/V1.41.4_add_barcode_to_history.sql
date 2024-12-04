ALTER TABLE  `History Dimension` CHANGE `Direct Object` `Direct Object` enum('Picking Pipeline','Fulfilment Asset','Fulfilment Delivery','Customer Client','Website Header','Website Footer','Shipping Zone','Shipping Zone Schema','List','Agent','Agent Supplier Purchase Order','Email Template','Charge','Customer Poll Query','Customer Poll Query Option','Email Campaign Type','Deal','Deal Component','Production Part','Website User','Staff','Supplier Part','Order Basket Purge','Email Campaign','Deal Campaign','Account','After Sale','Delivery Note','Category','Warehouse','Warehouse Area','Shelf','Location','Company Department','Company Area','Position','Store','User','Product','Address','Customer','Note','Order','Telecom','Email','Company','Contact','FAX','Telephone','Mobile','Work Telephone','Office Fax','Supplier','Family','Department','Attachment','Supplier Product','Part','Site','Page','Invoice','Category Customer','Category Part','Category Invoice','Category Supplier','Category Product','Category Family','Purchase Order','Supplier Invoice','Webpage','Website','Prospect','Supplier Delivery','Barcode') NULL;

update `History Dimension` H left join `Email Template History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Email Template'  where `Email Template Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Website Footer History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Website Footer'  where `Website Footer Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Website Header History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Website Header'  where `Website Header Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;


update `History Dimension` H left join `List History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='List'  where `List Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Agent History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Agent'  where `Agent Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Email Campaign Type History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Email Campaign Type'  where `Email Campaign Type Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Customer Poll Query Option History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Customer Poll Query Option'  where `Customer Poll Query Option Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Customer Poll Query History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Customer Poll Query'  where `Customer Poll Query Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Agent Supplier Purchase Order History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Agent Supplier Purchase Order'  where `Agent Supplier Purchase Order Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Shipping Zone History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Shipping Zone'  where `Shipping Zone Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Shipping Zone Schema History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Shipping Zone Schema'  where `Shipping Zone Schema Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Charge History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Charge'  where `Charge Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Agent History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Agent'  where `Agent Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Email Template History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Email Template'  where `Email Template Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H    set `Direct Object` ='Agent'  where `History Abstract` like 'Agent %'  and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H    set `Direct Object` ='Website User'  where `History Abstract` = 'Website user  created'  and   `Direct Object`=''  and `Direct Object Key`>0;

update `History Dimension` H left join `Customer Client History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Customer Client'  where `Customer Client Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Website User History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Website User'  where `Website User Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;

update `History Dimension` H left join `Fulfilment Asset History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Fulfilment Asset'  where `Fulfilment Asset Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Fulfilment Delivery History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Fulfilment Delivery'  where `Fulfilment Delivery Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Picking Pipeline History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Picking Pipeline'  where `Picking Pipeline Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;

update `History Dimension` H    set `Direct Object` ='Picking Pipeline'  where `History Abstract` like 'Picking band %'  and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Supplier Part History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Supplier Part'  where `Supplier Part Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Deal Campaign History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Deal Campaign'  where `Deal Campaign Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H left join `Supplier Delivery History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Supplier Delivery'  where `Supplier Delivery Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;

update `History Dimension` H    set `Direct Object` ='Supplier Delivery'  where `History Abstract` like 'Supplier delivery created'  and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H    set `Direct Object` ='Prospect'  where `History Abstract` like '%nvitation email sent%'  and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H    set `Direct Object` ='Prospect'  where `History Abstract` like 'prospect record created%'  and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H    set `Direct Object` ='Supplier Delivery'  where `History Abstract` like 'Supplier Delivery%'  and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H    set `Direct Object` ='Fulfilment Asset'  where `History Abstract` like 'Fulfilment Asset%'  and   `Direct Object`=''  and `Direct Object Key`>0;
update `History Dimension` H    set `Direct Object` ='Fulfilment Delivery'  where `History Abstract` like 'Fulfilment Delivery%'  and   `Direct Object`=''  and `Direct Object Key`>0;

update `History Dimension` H left join `Email Campaign History Bridge` B on (H.`History Key`=B.`History Key`)    set `Direct Object` ='Email Campaign'  where `Email Campaign Key`>0 and   `Direct Object`=''  and `Direct Object Key`>0;

update `History Dimension` H    set `Direct Object` ='Prospect'  where `History Abstract` like 'prospect%'  and   `Direct Object`=''  and `Direct Object Key`>0;


