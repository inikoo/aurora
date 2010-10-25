function download_csv(e,tipo){
   window.location.href='export_csv.php?tipo='+tipo;
}

function download_csv_from_dialog(e,args){

alert(args)
fields_to_export=YAHOO.lang.JSON.stringify(Dom.getElementsByClassName('selected', 'td', args.table));


   window.location.href='export_csv.php?tipo='+args.tipo+'&fields='+fields_to_export;
}