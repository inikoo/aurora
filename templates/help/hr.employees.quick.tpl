<!-- 
About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 19 March 2016 at 17:20:00 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
-->
<div class="item">
	<div class="question">
		<i class="fa fa-caret-right bullet fw"></i> {t}How to add new employees?{/t} 
	</div>
	<div class="answer hide">
		{t}Click in the <i class="fa fa-plus"></i> icon at the table header{/t} 
	</div>
</div>
<div class="item">
	<div class="question">
		<i class="fa fa-caret-right bullet fw"></i> {t}How to add employees in bulk?{/t} 
	</div>
	<div class="answer hide">
		<p>
			{t}Click in the <i class="fa fa-upload"></i> icon at the table header to upload a excel or a CSV file with the following fields{/t} — <i class="fa fa-file-excel-o"></i> <a title="{t}You can use this file as template{/t}" href="upload_arrangement.php?object=employee"> {t}template{/t}</a> — 
		</p>
		<ul>
			<li><b>{t}Payroll id{/t}</b> <i>({t}optional{/t})</i> [{t}string{/t}]</li>
			<li><b>{t}Code{/t}</b> <i>({t}required{/t},{t}unique{/t})</i> [{t}string{/t}]</li>
			<li><b>{t}Name{/t}</b> <i>({t}required{/t})</i> [{t}Forename Surname{/t}]</i></li>
			<li><b>{t}Date of birth{/t}</b> <i>({t}optional{/t})</i> [YYYY-MM-DD] e.g. 1976-02-18</li>
			<li><b>{t}Government ID{/t}</b> <i>({t}optional{/t})</i> [{t}string{/t}] </li>
			<li><b>{t}Email{/t}</b> <i>({t}optional{/t}) [{t}Email{/t}]</i></li>
			<li><b>{t}Contact number{/t}</b> <i>({t}optional{/t})</i> {t}e.g. +44 114 360 9600 please include international code preceded by a +{/t}</li>
			<li><b>{t}Address{/t}</b> <i>({t}optional{/t})</i> [{t}string{/t}]</li>
			<li><b>{t}Next of kind{/t}.</b> <i>({t}optional{/t})</i> [{t}string{/t}]</li>
			<li><b>{t}Type{/t}</b> <i>({t}required{/t})</i> {literal}['<span style="text-decoration:underline">Employee</span>', 'Volunteer', 'TemporalWorker', 'WorkExperience']{/literal}</li>
			<li><b>{t}Job title{/t}</b> <i>({t}optional{/t})</i> [{t}string{/t}]</li>
			<li><b>{t}Supervisor{/t}</b> <i>({t}optional{/t})</i> [Employee code]</li>
			<li><b>{t}Login{/t}</b> <i>({t}optional{/t},{t}unique{/t})</i> [string {t}min length 4{/t}] </li>
			<li><b>{t}Roles{/t}</b> <i>({t}optional{/t})</i> [Comma separated list of roles]</li>
			{t}for more info about system roles click here:{/t} 
			<li><b>{t}Password{/t}</b> <i>({t}optional{/t})</i> [string {t}min length 6{/t}]</li>
			<li><b>{t}PIN{/t}</b> <i>({t}4 characters{/t}).</i> [string {t}min length 4{/t}]</li>
		</ul>
		<p>
			{t}Would not be possible to add an employee if any fields marked as <i>{t}required{/t}</i> are missing or fields marked as <i>{t}unique{/t}</i> are already on record{/t}
		</p>
		<p>
			{t}Invalid <b>email</b> and b>{t}Contact number{/t}</b> would be ignored if not valid{/t}
		</p>
		<p>
			{t}If <b>type</b> value don't match the valid options, <span style="text-decoration:underline">Employee</span> will be used{/t}
		</p>
		<p>
			{t}If <b>login</b> is empty or less of 4 letters the system user will not created{/t}
		</p>
		<p>
			{t}A random string will be used if the <b>password</b> and <b>PIN</b> if shorter than the minimum length{/t}
		</p>
	</div>
</div>


