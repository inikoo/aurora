{include file='header.tpl'}
<div id="bd" >
 

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Import Data</h1>
  </div>
<br>
<h3>To get started, choose your file format:</h3>
<div class="ImportSection"><h3>Import from CSV file</h3>
        <p>Microsoft Outlook and most other applications allow contacts to be exported as a CSV file<br>
        <a href="import_csv.php?tipo=customers_store">Import from Outlook or CSV file</a>
        </p>
        </div>
 
<div class="ImportSection"><h3>Import from Xml file</h3>
        <p>XML is a markup language for documents containing structured information<br>
        <a href="import_xml.php?tipo=customers_store">Import from Xml file</a>
        </p>
        </div>
<!-- <div class="ImportSection"><h3>Import from Outlook file</h3>
        <p>Microsoft Outlook and most other applications allow contacts to be exported as a Outlook file<br>
        <a href="/crm/import/csv.home.seam">Import from Outlook file</a>
        </p>
        </div> -->
 
<div class="ImportSection"><h3>Import from vCard file</h3>
        <p>vCard is a file format standard for electronic business cards used by Apple Address Book and many other applications<br>
        <a href="">Import from vCard file</a>
        </p>
        </div>
 
<div class="ImportSection"><h3>Import from Spreadsheets file</h3>
        <p>A spreadsheet is a grid that organizes data into columns and rows<br>
        <a href="">Import from Spreadsheets file</a>
        </p>
        </div>


</div>

{include file='footer.tpl'}
