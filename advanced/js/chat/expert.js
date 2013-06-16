// Inherited class.
ChatExpert = newClass(Chat,
{
	connectionLostProcess : false,	
    setTime: function(chat_time, free_time, total_time)
    {
        var chat_time_m = Math.floor(chat_time / 60);
        var chat_time_s = Math.floor(chat_time % 60);
        var free_time_m = Math.floor(free_time / 60);
        var free_time_s = Math.floor(free_time % 60);

        if(chat_time<=0 && showTimeAlert==0)
    	{
        	showTimeAlert = 1;
        	alert(The_client_has_no_paid_time);
//        	var parent = document.getElementById("header");
//            if(parent)
//                parent.innerHTML = parent.innerHTML + '<div class="redLine">'+The_client_has_no_paid_time+'&nbsp;<input type="button" onclick="Chat.endSession()" value="'+End_session+'" name="endsession"></div>';
//        	$("[id='L_chatTime']").addClass("redLine");
//        	$("[id='endsession']").addClass("redLine");
//        	$("[id='chatTime']").addClass("redLine");
    	}
        chat_time_m = (chat_time_m <= 0) ? 0 : chat_time_m;
        chat_time_s = (chat_time_s <= 0) ? 0 : chat_time_s;
        free_time_m = (free_time_m <= 0) ? 0 : free_time_m;
        free_time_s = (free_time_s <= 0) ? 0 : free_time_s;
        
        $("#chatTime").val(_f(chat_time_m)+":"+_f(chat_time_s));
    	$("#freeTime").val(_f(free_time_m)+":"+_f(free_time_s));
    },

	endSession: function()
	{
		if(window.confirm(Are_you_sure_you_want))
		{
			$('#waiting_end_session').show();
			Chat.endSessionWaiting = setTimeout(function() {Chat.closeSession();}, 10000);

	        this.command.run({'command' : 'endSession'});
		}
	},


	closeSession: function(params)
	{
		clearTimeout(Chat.endSessionWaiting);
		if(params != null)
			addLost = params.addLost;
		else addLost = 0;
		
		clearInterval(Chat.chatTick);
		alert(Chat_is_over);
                newwin = window.open(baseChatPath+'expert/endSession?session_key='+sessionKey+"&addLost="+addLost,"readerendsession","scrollbars=yes,menubar=no,resizable=1,location=no,width=1050,height=500,left=200,top=200");
                newwin.focus();
		location.href = baseChatPath+'monitor?force_break=1';
	},

	connectionLost : function(params)
	{	
		if(!this.connectionLostProcess) {
			this.connectionLostProcess=true;	
		        this.command.run({'command' : 'endSession'});
			alert('Connection to opponent lost');
			$('#waiting_end_session').show();
			Chat.endSessionWaiting = setTimeout(function() {Chat.closeSession();}, 10000);
		}
	},


    ///Reader can pause or wake up chat    
    pause: function (obj)
    {
		document.getElementById('pause').disabled=true;
        options = {
			data    : {"command"   : "pause"},
			url     : baseChatPath + "chatcore/push",
			success : function (data) {
				document.getElementById('pause').disabled=false;
			},
			error   : function (XMLHttpRequest, errorType) {
				document.getElementById('pause').disabled=false;
			},
			context : this
		};
		addDebugInfo('push', options.data);
		this.command.ajax(options);		
        

        var params = new Object();       
        params.style = 'redLine';
        params.nickName = '';
        params.nameColor = 'myName';
        
        if(chatPause)
        {
            chatPause = 0;
            obj.value = Stand_By;           
            params.message = Stand_by_is_OFF;
        }
        else
        {
            chatPause = 1;
            obj.value = Wake_Up;            
            params.message = Stand_by_is_ON;
        }

        /// add text to user screen
        this.text(params);

    },

    addLostTime: function()
    {
		if(window.confirm(Are_you_sure_you_want_to_end)) {

			$('#waiting_end_session').show();
			Chat.endSessionWaiting = setTimeout(function() {Chat.closeSession();}, 10000);
			
			var data = {'command' : 'addLostTime'};
	        this.command.run(data);
		}
    },

    userBan: function()
    {
    	if(window.confirm(Ban_this_client) == true)
        {
            var banreason = prompt(Please_enter_the_reason_of_banning, ' ');
            if ((banreason==' ') || (banreason == null))
            {
            	Chat.userBan();
            }
            else
            {
				$('#waiting_end_session').show();
				Chat.endSessionWaiting = setTimeout(function() {Chat.closeSession();}, 10000);

            	data = {
                        "command" : "userBan",
                        "params" : {'banReason' : escape(banreason)}
    		    };
                this.command.run(data);
                alert(Your_session_is_now_over);
                //top.window.location.href="end_session.php?clientid=9615&session_id=114976";
                //getform.disabled = true;
            }
        }
    },

    personalBan: function()
    {
    	if(window.confirm(End_session_with_NO_payment) == true)
    	{
			var banreason = prompt(Please_enter_the_reason_of_banning, ' ');

            if ((banreason==' ') || (banreason == null))
            {
            	Chat.personalBan();
            }
            else
            {
				$('#waiting_end_session').show();
				Chat.endSessionWaiting = setTimeout(function() {Chat.closeSession();}, 10000);

				var data = {
						"command" : "text",
						"params" : {'message' : Due_to_various_reasons , 'style' : 'redLine'}
				};

				this.command.run(data);
				
				var data = {
						"command" : "personalBan",
						"params" : {'banReason' : escape(banreason)}
				};
				this.command.run(data);
				alert(Your_session_is_now_over);
			}
    		//top.window.location.href="end_session.php?clientid=9615&session_id=114976&notes=nopayment&banreader=1";
    		//getform.disabled = true;
    	}
    },
    
    areYouHere: function()
    {
        sendMessage(Are_you_here);
    }
});

var showTimeAlert = 0;
var chatPause = 0;    /// 0 - work,1 - pause
var Chat = '';

function pageInit()
{
	window.resizeTo(1050, 600);
	Chat = new ChatExpert();
	init();
}

function pageUnload()
{
    if(!Chat.endSessionWaiting)
    {
        if(window.confirm('Do you want to close session?'))
            {
                Chat.endSession();
            }
    }       
}

//function pageUnload()
//{
//    if(!Chat.endSessionWaiting)
//    {
//        if(window.confirm('Do you want to close session?'))
//            {
//                Chat.endSession();
//            }
//    }
//    return false;
//}