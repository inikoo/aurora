<div id="left_menu">
  <div class="contacts">
    <h3><a href="page.php?do=contact">Contact</a></h3>
    <span>T +44 (0) 114 272 9165</span><br/>
    <span>F +44 (0) 114 270 6571</span><br/>
    <a href="mailto:mail@ancientwisdom.biz">mail@ancientwisdom.biz</a>
  </div>
  <div class="cataloge">
    <h3><a href="page.php?name=cataloge">Cataloge</a></h3>
    <ul>
      {foreach from=$departments item=department}
      <li><a href="department.php?code={$department.code}">{$department.name}</a></li>
      {/foreach}
  </div>
</div>
