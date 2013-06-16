function popUpWin(url, newWidth, newHeight) 
{
	PrintWindow=window.open(url,"_blank","toolbar=no,location=no,status=no,menubar=no,resizable=yes,scrollbars=yes,width=" + newWidth  + ",height=" + newHeight + 
	",left=200,top=170");
}

function display_image(selection) { 
//However you want to choose the background image, stick it in a variable here.
PreView = window.open("", "Preview", "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=yes,resizable=yes,copyhistory=0,width=400,height=300,left=50,top=50"); 
PreView.document.open(); 
PreView.document.write("<HTML><HEAD>"); 
PreView.document.write("<TITLE>Preview</TITLE>"); 
PreView.document.write("</HEAD><BODY BGCOLOR=FFFFFF TEXT=000000>"); 
//Foreground Image (adjust the top however you want)
PreView.document.write("<IMG HSPACE=0 VSPACE=0 ");
//Set the width and height of the foreground image here if you want
PreView.document.write("SRC='file:/"+selection+"'>");
//Rest of form
PreView.document.write("<p align=right><FORM><INPUT TYPE='button' VALUE='Close' ")
PreView.document.write("onClick='window.close()'></FORM></p>"); 
PreView.document.write("</BODY></HTML>"); 
PreView.document.close(); 
   } 

function getUnique()
{
	date = new Date() ;
	return date.getTime() ;
}

function popUpErrorWin(url) 
{
	PrintWindow=window.open(url,"_blank","toolbar=no,location=no,status=no,menubar=no,resizable=yes,scrollbars=yes,width=450,height=400"+
	",left=30,top=30");

}

function openNew(myUrl,windowName)
 {
   newWin = window.open(myUrl,windowName)
 }

function OnImagePreview(form, cell)
{
	target = document[form][cell];

	if (target.value == "")
	{
		alert("Please first select a file using the 'Browse' key");
		return;
	}

	{	
		display_image(target.value);
		//popUpWin(target.value,400,300);
	}
}

function popUpImage(url_image, title, newWidth, newHeight) 
{
	PrintWindow=window.open("show_image.php?src=" + url_image + "&title=" + title,"_blank","toolbar=no,location=no,status=no,menubar=no,resizable=no,scrollbars=no,width=" + newWidth  + ",height=" + newHeight + 
	",left=150,top=150");
}

var hexVals = new Array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F");
var unsafeString = "\"<>%\\^[]`";

function highlight(element1){element1.focus();element1.select();}

function URLDecode()
{
	var returnstr=unescape(form1.string1.value);

	return returnstr;
        // while coding i found that IE had problem writing '<form>' to innerhtml. </form> was ok. 'unknown runtime error' IE5.5.
}

function URLEncode(val)
{
        var state   = 'urlenc';
        var len     = val.length;
        var backlen = len;
        var i       = 0;

        var newStr  = "";
        var frag    = "";
        var encval  = "";

        for (i=0;i<len;i++) 
        {
                if (isURLok(val.substring(i,i+1)))
                {
                        newStr = newStr + val.substring(i,i+1);
                }
                else
                {
                        tval1=val.substring(i,i+1);
                        newStr = newStr + "%" + decToHex(tval1.charCodeAt(0),16);
                }
        }

		return newStr;
}

function decToHex(num, radix) // part of URL Encode
{
        var hexString = "";
        while (num >= radix)
        {
               temp = num % radix;
               num = Math.floor(num / radix);
               hexString += hexVals[temp];
        }
        hexString += hexVals[num];
        return reversal(hexString);
}

function reversal(s) // part of URL Encode
{
        var len = s.length;
        var trans = "";
        for (i=0; i<len; i++)
        {
                trans = trans + s.substring(len-i-1, len-i);
        }
        s = trans;
        return s;
}

function isURLok(compareChar) // part of URL Encode
{
        if (unsafeString.indexOf(compareChar) == -1 && compareChar.charCodeAt(0) > 32 && compareChar.charCodeAt(0) < 123) 
        {
                return true;
        }
        else
        {
                return false;
        }
}

function StrRemoveAllDirectories(directory)
{
	var Str = new String(directory);

	do
	{
		SlashPos = Str.indexOf("/");
		if (SlashPos<0)	SlashPos = Str.indexOf("\\");
		if (SlashPos<0)	SlashPos = Str.indexOf(":");

		if (SlashPos >= 0)
		{
			Str = Str.substring(SlashPos+1, Str.length);
		}

	} while (SlashPos>=0);

	return Str;
}

function StrRemoveAllSpaces(directory)
{
	var Str = new String(directory);
	var Tmp = new String("");

	do
	{
		SlashPos = Str.indexOf(" ");
		if (SlashPos >= 0)
		{
			if (SlashPos>0)
				Tmp = Str.substring(0, SlashPos);
			else
				Tmp = "";

			Str = Tmp + Str.substring(SlashPos+1, Str.length);
		}

	} while (SlashPos>=0);

	return Str;
}

function isEmailAddr(email)
{
  var result = false;
  if (email.length > 3)
  {
  	var theStr = new String(email);
  	var index = theStr.indexOf("@");
  	if (index > 0)
  	{
    	var pindex = theStr.indexOf(".",index);
    	if ((pindex > index+1) && (theStr.length > pindex+1))
			result = true;
  	}
  }
  return result;
}

function OnWizardSubmit(form_name, location, validate_func)
{
	if (location != '')
	{
		document.forms[form_name].rr_redirect1.value = location;
	}

	if (validate_func != "")
	{
//		alert( validate_func);
		eval(validate_func);
	}
	else
	{
		document.forms[form_name].submit();
	}
}

function isInt(numIn)
{
	var checknum = parseInt(numIn);
	return !isNaN(checknum);
}


function isStrEqNoCase(str1,str2)
{
  return str1.toUpperCase()==str2.toUpperCase();
}

function indexOfNoCase(str1,str2)
{
  return str1.toUpperCase().indexOf(str2.toUpperCase());
}

function strAfter(str,strStart)
{
	var returnStr = "";
	var start = str.indexOf(strStart);
	if (start >= 0)
		returnStr = str.substring(start + strStart.length,str.length);
	return returnStr;
}

function strInBetween(str,strStart,strEnd)
{
	var returnStr = "";
	var start = str.indexOf(strStart);
	if (start >= 0)
	{
		start += strStart.length;
		var end = str.indexOf(strEnd,start);
		
		if (end >= 0)
			returnStr = str.substring(start,end);
	}
	return returnStr;
}

function strReplaceAll(str,strFind,strReplace)
{
	var returnStr = str;
	var start = returnStr.indexOf(strFind);
	while (start>=0)
	{
		returnStr = returnStr.substring(0,start) + strReplace + returnStr.substring(start+strFind.length,returnStr.length);
		start = returnStr.indexOf(strFind,start+strReplace.length);
	}
	return returnStr;
}

function handleClick(funct)
{
	var result = eval( funct + "('" + document.strtest.arg1.value + "','" +
		document.strtest.arg2.value + "','" +
		document.strtest.arg3.value + "')" );
	document.strtest.result.value = result;
}

function SafeDIVGet(name)
{
	if (document.all)
	{
		return eval("document.all."+name);
	}
	else
	{
		return eval("document.ilayer.document."+name);
	}
}

function SafeDIVSetHTML(name, html)
{
	if (document.all)
	{
		SafeDIVGet(name).innerHTML = html;
	}
	else
	{
		SafeDIVGet(name).document.write (html);
		SafeDIVGet(name).document.close ();
	}
}


function OnClearImage(form, image_location, image_empty)
{
	if (document.forms[form].set_picture[0].checked != "")
	{
		document.forms[form].new_picture.disabled = false;

		// Show original image
		document.forms[form].orig_image.src = image_location;
	}
	else
	{
		document.forms[form].new_picture.disabled = true;//style = "display: disabled";//.display = "disabled";//readonly = false;

		// Show empty image
		document.forms[form].orig_image.src = image_empty;
	}
}

function GetDayPrefix(day)
{
	daymod = day % 10;

	switch (daymod)
	{
		case 1:
			return "st";
		break;

		case 2:
			return "nd";
		break;

		case 3:
			return "rd";
		break;

		default:
			return "th";
		break;
	}
}

function makeArray0() {
    for (i = 0; i<makeArray0.arguments.length; i++)
        this[i] = makeArray0.arguments[i];
}

function DateWindow(formObject,title,target,formname)
{
	str = new String(formObject.value);

	if (str.length<12)
	{
		var today=new Date();
		mprefix = '';
		dprefix = '';

		day_num		= today.getDate();
		month_num	= today.getMonth();
		year_num	= today.getYear();
		if (year_num<1900)
			year_num += 1900;

		day		=  day_num;
		month	=  month_num;
		year	=  year_num;
	}
	else
	{
		mdelta	= 0;
		ddelta	= 0;

		if (str.charCodeAt(6)==48)
			ddelta++;
		if (str.charCodeAt(4)==48)
			mdelta++;

	    day		= parseInt(str.substring(6+ddelta,6+2));
		month	= parseInt(str.substring(4+mdelta,4+2));
		year	= parseInt(str.substring(0,0+4));
	
	}

	day--;
	month--;

 mywindow=window.open('date_picker.php?title='+URLEncode(title)+'&formname='+URLEncode(formname)+'&target='+target,'myname','resizable=no,width=350,height=300,top=100,left=150');
 if (mywindow.opener == null)
 {
  mywindow.opener = self;
 }
}

var days      = new makeArray0("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
var months     = new makeArray0('January','February','March','April','May','June','July','August','September','October','November','December');

function DateChanged(day,month,year, obj_timestamp, obj_str)
{
	if (year<1900)
		year+=1900;
	dprefix = '';
	mprefix = '';
	if (month<10)
	{
		mprefix = '0';
	}
	if (day<10)
	{
		dprefix = '0';
	}

	obj_timestamp.value = year + mprefix + month + dprefix + day + '000000';

	firstDay = new Date(year,month-1,day);
	startDay = firstDay.getDay();

	obj_str.value = days[startDay] + ', '+ months[month-1]+' '+day+GetDayPrefix(day)+', '+year;

	
}


function OnStartDateChanged(day,month,year,formname)
{
	// Breakdown enddate
	{
		str = new String(document.forms[formname].enddate_timestamp.value);

		mdelta	= 0;
		ddelta	= 0;

		if (str.charCodeAt(6)==48)
			ddelta++;
		if (str.charCodeAt(4)==48)
			mdelta++;

		day_end		= parseInt(str.substring(6+ddelta,6+2));
		month_end	= parseInt(str.substring(4+mdelta,4+2));
		year_end	= parseInt(str.substring(0,0+4));
	}

	// Verify end-date is not smaller than big-date
	if ((year_end < year) ||
	    ((year_end == year) && (month_end < month)) ||
		((year_end == year) && (month_end == month) && (day_end < day)))
	{
		alert("Launch date cannot begin after Expiration date");
		return;
	}

	// Change
	DateChanged(day,month,year,document.forms[formname].startdate_timestamp,document.forms[formname].startdate);
}

function OnEndDateChanged(day,month,year,formname)
{
	// Breakdown startdate
	{
		str = new String(document.forms[formname].startdate_timestamp.value);

		mdelta	= 0;
		ddelta	= 0;

		if (str.charCodeAt(6)==48)
			ddelta++;
		if (str.charCodeAt(4)==48)
			mdelta++;

		day_start	= parseInt(str.substring(6+ddelta,6+2));
		month_start	= parseInt(str.substring(4+mdelta,4+2));
		year_start	= parseInt(str.substring(0,0+4));
	}

	// Verify end-date is not smaller than big-date
	if ((year < year_start) ||
	    ((year == year_start) && (month < month_start)) ||
		((year == year_start) && (month == month_start) && (day < day_start)))
	{
		alert("Expiration date cannot occur before Launch date");
		return;
	}

	// Change
	DateChanged(day,month,year,document.forms[formname].enddate_timestamp,document.forms[formname].enddate);
}
