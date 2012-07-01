<?xml version="1.0" encoding="UTF-8"?>
<!-- Only the settings with values not equal to defaults are in this file. If you want to see the
full list of available settings, check the amstock_settings.xml file in the amstock folder. -->

<settings>
  <add_time_stamp>true</add_time_stamp>                                                

  
  
  <margins>0</margins>                                                   
  <max_series>100</max_series>

  <number_format>  
    <letters>
       <letter number="1000">K</letter>
       <letter number="1000000">M</letter>
       <letter number="1000000000">B</letter>
    </letters>      
  </number_format>
  
  <data_sets> 
  {foreach from=$graphs_data item=graph_data}
  
    <data_set did="{$graph_data.gid}">
       <title>{$graph_data.title}</title>
       <short>{if isset($graph_data.short_title)}{$graph_data.short_title}{/if}</short>
       <color>#00b8bf</color>
       <file_name>plot_data.csv.php?{$graph_data.csv_args}</file_name>
       <compare_list_box selected="false">false</compare_list_box>
         <main_drop_down selected="false">true</main_drop_down>
       <csv>
         <reverse>true</reverse>
         <separator>,</separator>
         <date_format>YYYY-MM-DD</date_format>
         <decimal_separator>.</decimal_separator>
         <columns>
           <column>date</column>
           <column>open</column>
           <column>high</column>
           <column>low</column>
           <column>close</column>
           <column>volume</column>           
         </columns>
       </csv>
    </data_set>
    {/foreach}
 
  </data_sets>



  <charts>
  	<chart cid="0">
      <border_color>#CCCCCC</border_color>
      <border_alpha>0</border_alpha>
     
      <grid>
        <x>
          <alpha>10</alpha>
          <dashed>true</dashed>    
        </x>
        
        <y_left>
          <alpha>10</alpha>
          <dashed>true</dashed>
        </y_left>
      </grid>	
      
      <values>
        <y_left>
          <bg_alpha>70</bg_alpha>
          <bg_color>000000</bg_color>
          <text_color>FFFFFF</text_color>   
        </y_left>
      </values>	      

      <legend>
        <fade_others_to>10</fade_others_to>
        <show_date>true</show_date>
        <positive_color>#B3FF66</positive_color>
        <negative_color>#db4c3c</negative_color>
        <graph_on_off>false</graph_on_off>   
        <show_balloon>false</show_balloon>     
      </legend>

      <column_width>70</column_width>
  		<graphs>
  			<graph gid="0">
  				<type>candlestick</type>
  				<data_sources>
  				  <open>open</open>
  				  <high>high</high>
  				  <low>low</low>
  				  <close>close</close>
          </data_sources>
          
          <compare_source></compare_source>
  				<cursor_color>002b6d</cursor_color>
  				<positive_color>#006600</positive_color>
          <negative_color>db4c3c</negative_color>
  				<fill_alpha>70</fill_alpha>
  		    <legend>
            <date key="true" title="true"><![CDATA[{$graph_data.label}  {literal}{close}{/literal} ({literal}Open: {open} High:{high} Low: {low} Close:{close}{/literal}) ]]></date>
            <period key="true" title="true"><![CDATA[open:<b>{literal}{open}{/literal}</b> low:<b>{literal}{low}{/literal}</b> high:<b>{literal}{high}{/literal}</b> close:<b>{literal}{close}{/literal}</b>]]></period>
           
          </legend>         
  			</graph>
  			
  		</graphs>
  	</chart>  
  </charts>
  
  
  <data_set_selector>
    <position>top</position>
    <main_drop_down_title>{t}Select{/t}:</main_drop_down_title>
    <compare_list_box_title>{t}Show also{/t}:</compare_list_box_title>
    <drop_down>
      <scroller_color>C7C7C7</scroller_color>
    </drop_down>
  </data_set_selector>
  


 
  
  <period_selector>
    
    <button>
      <bg_color_hover>FEC514</bg_color_hover>
      <bg_color_selected>DB4C3C</bg_color_selected>
      <text_color_selected>FFFFFF</text_color_selected>
    </button>
  
		<periods>		
      <period type="DD" count="10">10D</period>
    	<period type="MM" count="1">1M</period>
    	<period selected="true" type="MM" count="3">3M</period>
    	<period type="YYYY" count="1">1Y</period>
    	<period type="YYYY" count="3">3Y</period>
    	<period type="YTD" count="0">YTD</period>
    	<period type="MAX">MAX</period>
		</periods>
		
		<periods_title>Zoom:</periods_title>
		<custom_period_title>Custom period:</custom_period_title> 
  </period_selector>

  <header>
    <enabled>false</enabled>
  </header>

  <plot_area>
    <border_color>b6bece</border_color>
  </plot_area>

  <scroller>
    <graph_data_source>close</graph_data_source>
    <graph_selected_fill_alpha></graph_selected_fill_alpha>
    <resize_button_color></resize_button_color>
    
    <playback>
      <enabled>true</enabled>
      <color>002b6d</color>
      <color_hover>db4c3c</color_hover>
      <speed>3</speed>
      <max_speed>10</max_speed>
      <speed_indicator>
        <color>002b6d</color>
      </speed_indicator>
    </playback>
  </scroller>
</settings>
