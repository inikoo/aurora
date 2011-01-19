{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0px 20px">
 {include file='hr_navigation.tpl'}
  <div id="no_details_title"  style="clear:left;xmargin:0 20px;{if $details!=0}display:none{/if}">
    <h1 style="padding-bottom:0px">{$staff->get('Staff Name')} <span style="color:SteelBlue">( {$staff->get('Staff ID')} )</span>
      {if $next.id>0}<a class="prev" href="staff.php?id={$prev.id}" ><img src="art/icons/previous.png" alt="<" title="{$prev.name}"  /></a>{/if}
      {if $next.id>0}<a class="next" href="staff.php?id={$next.id}" ><img src="art/icons/next.png" alt=">" title="{$next.name}"  /></a>{/if}
      
    </h1> 

   
  </div>
  
  
     
  
 
     
     
     

<table border=0 style="padding:0">
{if $staff->get('Staff Alias')}<tr><td valign="top"colspan=2  class="aleft">{$staff->get('Staff Alias')}</td></tr>{/if}
{if $staff->get('Staff ID')}<tr><td valign="top" class="aleft">Staff ID</td><td colspan=2  class="aleft">: {$staff->get('Staff ID')}</td ></tr>{/if}
{if $staff->get('Staff Type')}<tr><td valign="top"  class="aleft">Staff Type</td><td colspan=2  class="aleft">: {$staff->get('Staff Type')}</td ></tr>{/if}
{if $staff->get('Staff Valid from')}<tr><td valign="top" class="aleft">Valid From</td><td colspan=2  class="aleft">: {$staff->get('Staff Valid from')}</td ></tr>{/if}
{if $staff->get('Staff Valid To')}<tr><td valign="top" class="aleft">Valid To</td><td colspan=2  class="aleft">: {$staff->get('Staff Valid To')}</td ></tr>{/if}

</table>

</div>

  <ul class="tabs" id="chooser_ul" style="clear:both;margin-top:25px">
    <li> <span class="item {if $view=='history'}selected{/if}"  id="details">  <span> {t}History Notes{/t}</span></span></li>
   <li> <span class="item {if $view=='working_hours'}selected{/if}"  id="working_hours">  <span> {t}Working Hours{/t}</span></span></li>

  </ul>
  <div  style="clear:both;width:100%;border-bottom:1px solid #ccc">
  </div>
 
  
  
 <div id="block_history" class="data_table" style="margin:20px 0 40px 0;padding:0 20px">
      <span class="clean_table_title">{t}History/Notes{/t}</span>
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
      <div  id="table0"   class="data_table_container dtable btable "> </div>
    </div>
<div id="block_working_hours" class="data_table" style="{*{if $view!='working_hours'}display:none;{/if}clear:both;*}margin:20px 0 40px 0;padding:0 20px">
      <span class="clean_table_title">{t}Working Hours Details{/t}</span>
 {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }
       <div  id="table1"   class="data_table_container dtable btable "> </div>
  </div>


{* --------------------------------------calendar starts from here------------------------------------------------------------------- *}
 <div>

      <div id="calhead" style="padding-left:1px;padding-right:1px;">          
            <div class="cHead"><div class="ftitle">Calendar Of :{$staff->get('Staff Name')}</div>
            <div id="loadingpannel" class="ptogtitle loadicon" style="display: none;">Loading data...</div>
             <div id="errorpannel" class="ptogtitle loaderror" style="display: none;">Sorry, could not load your data, please try again later</div>
            </div>          
            
            <div id="caltoolbar" class="ctoolbar">
              <div id="faddbtn" class="fbutton">
                <div><span title='Click to Create New Event' class="addcal">

                New Event                
                </span></div>
            </div>
            <div class="btnseparator"></div>
             <div id="showtodaybtn" class="fbutton">
                <div><span title='Click to back to today ' class="showtoday">
                Today</span></div>
            </div>
              <div class="btnseparator"></div>

            <div id="showdaybtn" class="fbutton">
                <div><span title='Day' class="showdayview">Day</span></div>
            </div>
            <div  id="showweekbtn" class="fbutton fcurrent">
                <div><span title='Week' class="showweekview">Week</span></div>
            </div>
              <div  id="showmonthbtn" class="fbutton">
                <div><span title='Month' class="showmonthview">Month</span></div>

            </div>
            <div class="btnseparator"></div>
              <div  id="showreflashbtn" class="fbutton">
                <div><span title='Refresh view' class="showdayflash">Refresh</span></div>
                </div>
             <div class="btnseparator"></div>
            <div id="sfprevbtn" title="Prev"  class="fbutton">
              <span class="fprev"></span>

            </div>
            <div id="sfnextbtn" title="Next" class="fbutton">
                <span class="fnext"></span>
            </div>
            <div class="fshowdatep fbutton">
                    <div>
                        <input type="hidden" name="txtshow" id="hdtxtshow" />
                        <span id="txtdatetimeshow">Loading</span>

                    </div>
            </div>
            
            <div class="clear"></div>
            </div>
      </div>
      <div style="padding:1px;">

        <div class="t1 chromeColor">
            &nbsp;</div>
        <div class="t2 chromeColor">
            &nbsp;</div>
        <div id="dvCalMain" class="calmain printborder">
            <div id="gridcontainer" style="overflow-y: visible;">
            </div>
        </div>
        <div class="t2 chromeColor">

            &nbsp;</div>
        <div class="t1 chromeColor">
            &nbsp;
        </div>   
        </div>
     
  </div>
    </div>
{include file='footer.tpl'}

