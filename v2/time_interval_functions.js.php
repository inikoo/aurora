function parse_time_interval(interval_text){
numeric_part=''
interval_text=interval_text.words();
intervals=new Object();

    for (i in interval_text){
 
        if(interval_text[i].match(/second/)){
            unit='second'
            quantity=parse_number(numeric_part)
            intervals[unit]={'second':quantity};
            numeric_part='';
        }else if  (interval_text[i].match(/minute/)){
            unit='minute'
            quantity=parse_number(numeric_part)
            intervals[unit]={'minute':quantity};
            numeric_part='';
        }else if  (interval_text[i].match(/hour/)){
            unit='hour'
            quantity=parse_number(numeric_part)
            intervals[unit]={'hour':quantity};
            numeric_part='';
        }else if  (interval_text[i].match(/day/)){
            unit='day'
            quantity=parse_number(numeric_part)
            intervals[unit]={'day':quantity};
            numeric_part='';
           }else if  (interval_text[i].match(/week/)){
            unit='week'
            quantity=parse_number(numeric_part)
            intervals[unit]={'week':quantity};
            numeric_part='';
        }else if  (interval_text[i].match(/month/)){
            unit='month'
            quantity=parse_number(numeric_part)
            intervals[unit]={'month':quantity};
            numeric_part='';    
        }else if  (interval_text[i].match(/month/)){
            unit='month'
            quantity=parse_number(numeric_part)
            intervals[unit]={'month':quantity};
            numeric_part='';
        }else if  (interval_text[i].match(/year/)){
            unit='year'
            quantity=parse_number(numeric_part)
            intervals[unit]={'year':quantity};
            numeric_part='';
        }else{
        numeric_part=numeric_part+' '+interval_text[i]
        
        }
        
       // alert(numeric_part)
    }
    
   date=Date.create();
 
   
 
 
 number_detected_intervals=0;
    for (x in intervals){
     number_detected_intervals++;
    date.advance(intervals[x])
     
    }
  
  if(!number_detected_intervals || date.isPast())
    return false;
    
    
    
  return date;
    
}

function convert_word_to_number(string){

var string=string.replace(/one/g,1)
 string=string.replace(/two/g,2)
 string=string.replace(/three/g,3)
 string=string.replace(/four/g,4)
 string=string.replace(/five/g,5)
 string=string.replace(/six/g,6)
 string=string.replace(/seven/g,7)
 string=string.replace(/eight/g,8)
  string=string.replace(/nine/g,9)

 string=string.replace(/ten/g,10)
  string=string.replace(/eleven/g,11)
 string=string.replace(/twelve/g,12)
 string=string.replace(/thirteen/g,13)
 string=string.replace(/fourteen/g,14)
 string=string.replace(/fifteen/g,15)
 string=string.replace(/sixteen/g,16)
  string=string.replace(/seventeen/g,17)
 string=string.replace(/eighteen/g,18)
 string=string.replace(/nineteen/g,19)
 
 string=string.replace(/twenty/g,20)
 string=string.replace(/thirty/g,30)
 string=string.replace(/fourty/g,40)
 string=string.replace(/fifty/g,50)
 string=string.replace(/sixty/g,60)
 string=string.replace(/seventy/g,70)
 string=string.replace(/eighty/g,80)
 string=string.replace(/nmillioninety/g,90)

 
 
 string=string.replace(/hundred/g,100)
  string=string.replace(/thousand/g,1000)
 string=string.replace(/million/g,100000)



return string;
}

function parse_number(numbers_data){

numbers_data=numbers_data.replace(/ and /g,' ')
numbers_data=numbers_data.words();

var total = 0;
var prior = 0;

number_elements=numbers_data.length-1;
for (x in numbers_data){

    value=parseFloat(convert_word_to_number(numbers_data[x]))
    //alert(numbers_data[x]+' '+value+' '+number_elements+' '+x)


   
   if (prior == 0)
      prior = value
   else if (prior > value)
      prior = prior + value
   else
      prior = prior * value
    //alert(numbers_data[x]+' '+value+' '+prior+' '+total)

   if (value >= 1000 || number_elements==x ){
      total = total + prior
      prior = 0
    }
    
}

return total

}

