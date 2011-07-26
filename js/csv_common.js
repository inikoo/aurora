function download_csv(e,tipo){
   //alert('export_csv.php?tipo='+tipo)
   window.location.href='export_csv.php?tipo='+tipo;
}

function download_csv_from_dialog(e,args){

fields_to_export_data=Dom.getElementsByClassName('selected', 'td', Dom.get(args.table));
var fields_to_export=new Object;
for(x in fields_to_export_data){
fields_to_export[fields_to_export_data[x].getAttribute('name')]=1;
}
//alert('export_csv.php?tipo='+args.tipo+'&fields='+YAHOO.lang.JSON.stringify(fields_to_export));
   window.location.href='export_csv.php?tipo='+args.tipo+'&fields='+YAHOO.lang.JSON.stringify(fields_to_export);
}
