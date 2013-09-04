{include file='header.tpl'}
<div id="bd" >
 

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>{t}Import Data{/t}</h1>
  </div>
<br>
<h3>{t}To get started, choose your file format{/t}:</h3>
<div class="ImportSection" style="float:left;width:410px;cursor:pointer;margin-right:40px" onClick="location.href='import.php?subject={$scope}&subject_key={$subject_key}'">
<h3>{t}Import from CSV file{/t}</h3>
        <p>{t}Excel, Microsoft Outlook and most other applications allow contacts to be exported as a CSV file{/t}.
        </p>
        </div>
 
<div class="ImportSection" style="clear:none;float:left;width:410px;cursor:pointer" onClick="location.href='import_xml.php?tipo=customers_store&store_key={$store_key}'"><h3>Import from Xml file</h3>
        <p>{t}tXML is a markup language for documents containing structured information{/t}
        </p>
        </div>

 
<div class="ImportSection"  style="float:left;width:410px;cursor:pointer" onClick="location.href='parse_vcard.php?tipo=customers_store&store_key={$store_key}'">
<h3>{t}Import from vCard file{/t}</h3>
        <p>{t}vCard is a file format standard for electronic business cards used by Apple Address Book and many other applications{/t}
        </p>
        </div>
 {*}
<div class="ImportSection"><h3>Import from Spreadsheets file</h3>
        <p>A spreadsheet is a grid that organizes data into columns and rows<br>
        <a href="import_xls.php">Import from Spreadsheets file</a>
        </p>
        </div>
{/*}

</div>

{include file='footer.tpl'}
