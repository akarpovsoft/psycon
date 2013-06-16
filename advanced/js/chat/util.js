/**/////////////////////////// utility functions///////// */
function _f(parm)
{
	if(parm < 10)
		return '0'+parm;
	return parm;
}

function str_replace(search, replace, subject)
{
	return subject.split(search).join(replace);
}

function preventbubble(e)
{
    if (e && e.stopPropagation) //if stopPropagation method supported
        e.stopPropagation();
    else
        e.cancelBubble = true;
}

/**///////////////////////////////Debug functions////////////*/
function getFields(obj)
{
//	return JSON.stringify(obj);
	output = '';
	
	if ((typeof( obj) == 'object'))
	{
		for (property in obj)
		{			
			if ((typeof( obj[property]) == 'object'))
				output += property + ': ' + getFields(obj[property])+'; ';
			else
			output += property + ': ' + obj[property]+'; ';
		}
	}
	else output = obj;

	return output;

}

function addDebugInfo(type, data)
{
	output = getFields(data);	
	$('#debug_log').append("<br>type: "+type+"; data: " +output);
}

//////////////// Basic page init function//////////////////////////
function init() {
	$('#message').focus();
	document.onkeydown = function(e)
	{
		e = e || window.event;
		if (e.keyCode == 13)
		{       clearTimeout(Chat.typingInterval);
			Chat.typingInterval = 0;
			Chat.typing = 0;
			Chat.sendMessage($('#message').val());
			preventbubble(e);
			return false;
		}
		return true;
	};
    Chat.setTime(startChatTime, startFreeTime, startTotalTime);
    setTimeout('Chat.refresh()', 2000);

}
