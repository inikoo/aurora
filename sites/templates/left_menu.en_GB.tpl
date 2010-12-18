<div id="left_menu" style="">
  <div class="contacts">
    <h3><a href="info.php?page=contact">{$traslated_labels.contact}</a></h3>
    {if $tel }<span>T {$tel}</span><br/>{/if}
    {if $fax }<span>F {$fax}</span><br/>{/if}
    {if $email }{mailto address="$email" encode="javascript"}{/if}

  </div>
  <div class="catalogue">
    <h3><a href="catalogue.php">{$traslated_labels.catalogue}</a></h3>
    <ul>
      {foreach from=$departments item=department}
      <li><a href="department.php?code={$department.code}">{$department.name}</a></li>
      {/foreach}
  </div>
</div>
