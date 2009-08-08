
/**
 * DHTML phone number validation script. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)
 */

var phoneNumberDelimiters = "()- ";
var validWorldPhoneChars = phoneNumberDelimiters + "+";
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

function isValidTelecom(strPhone){
  var bracket=5;
  
  strPhone=trim(strPhone);
  if(strPhone.indexOf("+")>1) return false;
  if(strPhone.indexOf("(")!=-1 && strPhone.indexOf("(")>bracket)return false;
  var brchr=strPhone.indexOf("(");
  if(strPhone.indexOf("(")!=-1 && strPhone.charAt(brchr+2)!=")")return false;
  if(strPhone.indexOf("(")==-1 && strPhone.indexOf(")")!=-1)return false;
  s=stripCharsInBag(strPhone,validWorldPhoneChars);
  return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
}

