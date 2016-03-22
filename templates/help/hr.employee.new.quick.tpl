<!-- 
About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2016 at 18:59:01 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
-->

<div class="item">
	<div class="question">
		<i class="fa fa-caret-right bullet fw"></i> {t}Fields glossary{/t} 
	</div>
	<div class="answer hide">
		
		<ul>
			<li><b>{t}Payroll id{/t}</b> <i>({t}string{/t},{t}optional{/t})</i> </li>
			<li><b>{t}Code{/t}</b> <i>({t}string{/t},{t}unique{/t})</i> </li>
			<li><b>{t}Name{/t}</b> <i>({t}Forename Surname{/t}).</i></li>
			<li><b>{t}Date of birth{/t}</b> <i>({t}optional{/t})</i></li>
			<li><b>{t}Government ID{/t}.</b> {t}Employment relevant official employee's Id number{/t}</li>

			<li><b>{t}Email{/t}</b> <i>({t}optional but needed for online password recovery{/t})</i></li>
			<li><b>{t}Contact number{/t}</b> <i>({t}optional{/t})</i></li>
			<li><b>{t}Address{/t}</b> <i>({t}optional{/t})</i></li>
			<li><b>{t}Next of kind{/t}.</b> <i>({t}optional but important in case of an emergency{/t})</i></li>
			<li><b>{t}Type{/t}.</b> {t}Employment type.
			<br><i class="fa fa-lightbulb-o"></i> For adding contractors click <span class="link" onclick="change_view('contractor/new')">here</span>{/t}</li>
			<li><b>{t}Job title{/t}</b> <i>({t}optional{/t})</i></li>
			<li><b>{t}Supervisor{/t}</b> <i>({t}optional but needed for some business operations{/t})</i></li>

		</ul>
		
		<p>{t}Optionally you can create a system user{/t}</p>
		<ul>
			<li><b>{t}Login{/t}</b> <i>({t}string{/t},{t}unique{/t}, {t}min length 4 characters{/t})</i> </li>
			<li><b>{t}Roles{/t}</b> {t}Used to determine what permission levels of the user{/t}</li>
			<li><b>{t}Password{/t}</b> <i>({t}min length 6 characters{/t}).</i></li>
			<li><b>{t}PIN{/t}</b> <i>({t}4 characters{/t}).</i> Short secret number used for quick confirmation of some user operations</li>
			
		</ul>
	</div>
</div>
