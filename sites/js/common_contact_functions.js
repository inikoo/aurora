
function validate_email(address) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   if(reg.test(address) == false) {
      return false;
   }else
       return true;

}



// Declaring required variables
var digits = "0123456789";
// non-digit characters which are allowed in phone numbers
var phoneNumberDelimiters = "()- ";
// characters which are allowed in international phone numbers
// (a leading + is OK)
var validWorldPhoneChars = phoneNumberDelimiters + "+";
// Minimum no of digits in an international phone no.
var minDigitsInIPhoneNumber = 6;

function isInteger(s)
{   var i;
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}
function trim(s)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not a whitespace, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (c != " ") returnString += c;
    }
    return returnString;
}
function stripCharsInBag(s, bag)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function checkInternationalPhone(strPhone){
var bracket=3
strPhone=trim(strPhone)
if(strPhone.indexOf("+")>1) return false
if(strPhone.indexOf("-")!=-1)bracket=bracket+1
if(strPhone.indexOf("(")!=-1 && strPhone.indexOf("(")>bracket)return false
var brchr=strPhone.indexOf("(")
if(strPhone.indexOf("(")!=-1 && strPhone.charAt(brchr+2)!=")")return false
if(strPhone.indexOf("(")==-1 && strPhone.indexOf(")")!=-1)return false
s=stripCharsInBag(strPhone,validWorldPhoneChars);
return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
}


function check_password(){
    var error_tab=false;
    

    if(Dom.get('password').value!='' && Dom.get('password').value!=Dom.get('password_confirmation').value){
	error_tab=true;
	if(Dom.get('password_confirmation').value==''){
	    Dom.get('password_instructions').innerHTML=Dom.get('password_msg1').innerHTML;
	Dom.addClass(['password_confirmation_label','password_confirmation'],'error');
	
	}else{
	    Dom.get('password_instructions').innerHTML=Dom.get('password_msg2').innerHTML;
	    Dom.addClass(['password_label','password','password_confirmation_label','password_confirmation'],'error');
		
	}

    }else{
	Dom.removeClass(['password_label','password','password_confirmation_label','password_confirmation'],'error');

    }



    //    alert('x');
    if(Dom.get('password').value==''){
	Dom.get('password_instructions').innerHTML=Dom.get('password_msg4').innerHTML;
	Dom.addClass(['password_label','password'],'error');
	error_tab=true;
    }
    if(Dom.get('password').value.length<6){
	Dom.get('password_instructions').innerHTML=Dom.get('password_msg3').innerHTML;
	Dom.addClass(['password_label','password'],'error');
	error_tab=true;
	Dom.get('password_confirmation').value='';
	

    }else{
	Dom.removeClass(['password_label','password'],'error');

    }





    return !error_tab;

}
