function change_list()
{
       var a;
       a = document.getElementById('list_name').value;
       window.location='add_people.php?l='+a;
}

function validate()
{
    hide();
    if(document.getElementById("people_first_name").value=="")
    {
        document.getElementById('people_first_name_msg').style.display = 'block';
        document.getElementById('people_first_name_msg').focus();
        return false;
    }

    if(document.getElementById("people_last_name").value=="")
    {
        document.getElementById('people_last_name_msg').style.display = 'block';
        document.getElementById('people_last_name_msg').focus();
        return false;
    }


    var emailID=document.getElementById("people_email").value;

    if (emailID=='')
    {
        //document.getElementById("email_msg").innerHTML = "";
        document.getElementById("email_msg").innerHTML = "Please enter a valid email address.";
        document.getElementById('email_msg').style.display = 'block';
        document.getElementById("people_email").focus();

        return false;
    }
    if (echeck(emailID)==false)
    {
        emailID.value="";
        document.getElementById("people_email").focus();

        //document.getElementById("email_msg").innerHTML = "";
        document.getElementById("email_msg").innerHTML = "Please enter a valid email address.";
        document.getElementById('email_msg').style.display = 'block';

        return false;
    }

        var o = document.getElementById('people_email_type1');
        var t = document.getElementById('people_email_type2');
        var u= document.getElementById('people_email_type3');
        if ( (o.checked == false ) && (t.checked == false )  && (u.checked == false ) )
        {
            document.getElementById('people_email_type_msg').style.display = 'block';
            document.getElementById('people_email_type_msg').focus();

        return false;
        }
        var b = document.getElementById('permission');
        if (b.checked == false) {
          document.getElementById('permission_msg').style.display = 'block';
          document.getElementById('permission_msg').focus();
          return false;
        }


    return true;
}

function echeck(str)
{

        var at="@"
        var dot="."
        var lat=str.indexOf(at)
        var lstr=str.length
        var ldot=str.indexOf(dot)
        if (str.indexOf(at)==-1){
          // alert("Invalid E-mail ID")
           return false
        }

        if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
          // alert("Invalid E-mail ID")
           return false
        }

        if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
           // alert("Invalid E-mail ID")
            return false
        }

         if (str.indexOf(at,(lat+1))!=-1){
           // alert("Invalid E-mail ID")
            return false
         }

         if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
            //alert("Invalid E-mail ID")
            return false
         }

         if (str.indexOf(dot,(lat+2))==-1){
            //alert("Invalid E-mail ID")
            return false
         }

         if (str.indexOf(" ")!=-1){
           // alert("Invalid E-mail ID")
            return false
         }

          return true
}

function hide()
{
    document.getElementById('people_first_name_msg').style.display = 'none';
    document.getElementById('people_last_name_msg').style.display = 'none';
    document.getElementById('email_msg').style.display = 'none';
    document.getElementById('people_email_type_msg').style.display = 'none';
    document.getElementById('permission_msg').style.display = 'none';

}

function show(div_id)
{
    if(div_id=='people_first_name_msg')
    {
        document.getElementById('people_first_name_msg').style.display = 'block';
        document.getElementById('people_last_name_msg').style.display = 'none';
        document.getElementById('email_msg').style.display = 'none';
        document.getElementById('people_email_type_msg').style.display = 'none';
        document.getElementById('permission_msg').style.display = 'none';
    }
    if(div_id=='people_last_name_msg')
    {
        document.getElementById('people_first_name_msg').style.display = 'none';
        document.getElementById('people_last_name_msg').style.display = 'block';
        document.getElementById('email_msg').style.display = 'none';
        document.getElementById('people_email_type_msg').style.display = 'none';
        document.getElementById('permission_msg').style.display = 'none';
    }
    
    if(div_id=='email_msg')
    {
        document.getElementById('people_first_name_msg').style.display = 'none';
        document.getElementById('people_last_name_msg').style.display = 'none';
        document.getElementById('email_msg').style.display = 'block';
        document.getElementById('people_email_type_msg').style.display = 'none';
        document.getElementById('permission_msg').style.display = 'none';
    }

    if(div_id=='people_email_type_msg')
    {
        document.getElementById('people_first_name_msg').style.display = 'none';
        document.getElementById('people_last_name_msg').style.display = 'none';
        document.getElementById('email_msg').style.display = 'none';
        document.getElementById('people_email_type_msg').style.display = 'block';
        document.getElementById('permission_msg').style.display = 'none';

    }

     if(div_id=='permission_msg')
    {
        document.getElementById('people_email_type_msg').style.display = 'none';
        document.getElementById('people_first_name_msg').style.display = 'none';
        document.getElementById('people_last_name_msg').style.display = 'none';
        document.getElementById('email_msg').style.display = 'none';
        document.getElementById('permission_msg').style.display = 'block';

    }




}



