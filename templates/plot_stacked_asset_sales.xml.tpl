<settings>
  <number_format>
    <letters>
      <letter number="1000">K</letter>
      <letter number="1000000">M</letter>
      <letter number="1000000000">B</letter>
    </letters>
  </number_format>
  <data_sets>
    <data_set did="0">
      <title>East Stock</title>
      <short>ES</short>
      <file_name>plot_data.csv.php?{$data_args}</file_name>
      <main_drop_down selected="1"/>
      <csv>
        <separator>,</separator>
        <columns>
          <column>date</column>
          {foreach from=$graphs_data item=graph_data}
          <column>val{$graph_data.gid}</column>
                    <column>vol{$graph_data.gid}</column>

          {/foreach}
        </columns>
      </csv>
    </data_set>
  </data_sets>
  <charts>
    <chart cid="0">
      <title>Value</title>
      <height>60</height>
      <grid/>
      <legend>
        <show_date>1</show_date>
      </legend>
      <comparing>
        <recalculate_from_start>0</recalculate_from_start>
      </comparing>
      <events>
        <use_hand_cursor>1</use_hand_cursor>
      </events>
      <graphs>
      {foreach from=$graphs_data item=graph_data}
        <graph gid="{$graph_data.gid}">
          <title>{$graph_data.title}</title>
         
          <fill_alpha>60</fill_alpha>
          
          <data_sources>
            <close>val{$graph_data.gid}</close>
          </data_sources>
          <compare_source>close</compare_source>
          <legend>
            <date>{literal}{close}{/literal}</date>
            <period>{literal}{close}{/literal}</period>
          </legend>
            <period_value>sum</period_value>
            <color>{$graph_data.color}</color>
            {if $graph_data.gid}
            <stack_to>{$graph_data.gid-1}</stack_to>
            {/if}
            
        </graph>
        {/foreach}
       
      </graphs>
    </chart>
  </charts>
  <date_formats>
    <legend>
      <days>month DD, YYYY</days>
    </legend>
  </date_formats>
  <data_set_selector>
    <enabled>0</enabled>
    <compare_list_box_title>Compare to:</compare_list_box_title>
    <drop_down>
      <scroller_color>C7C7C7</scroller_color>
    </drop_down>
  </data_set_selector>
  <period_selector>
    <periods_title>Zoom:</periods_title>
    <custom_period_title>Custom period:</custom_period_title>
    <periods>
      <period pid="0" type="DD" count="10">10D</period>
      <period pid="1" type="MM" count="1">1M</period>
      <period pid="2" type="MM" count="3">3M</period>
      <period pid="3" type="YYYY" count="1" selected="1">1Y</period>
      <period pid="4" type="YYYY" count="3">3Y</period>
      <period pid="5" type="YTD" count="0">YTD</period>
      <period pid="6" type="MAX" count="0">MAX</period>
    </periods>
    <from>{$from}</from>
    <to>{$to}</to>
  </period_selector>
  <header>
    <enabled>0</enabled>
    <text><![CDATA[<b>{literal}{title}{/literal}</b> ({literal}{short}{/literal}) {literal}{description}{/literal}]]></text>
    <text_size>13</text_size>
  </header>
  <balloon>
    <border_color>B81D1B</border_color>
  </balloon>
  <scroller>
    <height>30</height>
    <playback>
      <speed>1</speed>
    </playback>
  </scroller>
  <context_menu>
    <menu function_name="printChart" title="Print chart"/>
  </context_menu>
</settings>