/**
 * Base class
 */
Chat = newClass(null, {

	endSessionWaiting : 0,
	chatTick : '',
	typing : 0,
	typingInterval : 0,
	command : '', 
	ai : '',
	errorsCounter : 0,
	postId : 0,
	maxErrors : 3,
	connectionError : false, // if connection error occured
        soundMode : '',
        
	constructor: function()
	{
		this.command = new Command();
	},
	
	/**
	* Function request only once at the chat start and checks for commands from server
	*/
/*
	check: function()
	{
		options = {
			url     : baseChatPath + "chatcore/pop",
			success : this.success, 
			error   : this.error,
			postId   : this.postId,
			context : this
		};
		this.ai = new AJAXInteraction();
                this.ai.doPost(options);
                if(typeof Chat.ajaxTimeout === 'undefined')
                    Chat.ajaxTimeout = setInterval('Chat.ajaxTimeoutCheck()', 60000);
	},
	
	success: function(commands) {
		Chat.connectionError = false;
		Chat.errorsCounter = 0;
                clearInterval(Chat.ajaxTimeout);
                delete(Chat.ajaxTimeout);
		if(commands != null)
		{
			if(!commands.status) { // if not "status - ok" message
				if(commands.error) {
					Chat.handleServerError('pop', commands);
				} else {
					$.each(commands, function(i, data)
					{
						if(data.command && data.command in Chat)
						{
							addDebugInfo('pop', data);
							with(Chat)
								eval(data.command+'(data.params)');
						}
					});
					Chat.postId = commands.postId;
				}
			}
	
		}
	
		/// check again
		Chat.check();
	},
	error: function(XMLHttpRequest, errorType) {
                clearInterval(Chat.ajaxTimeout);
                delete(Chat.ajaxTimeout);
		Chat.handleConnectionError('pop', XMLHttpRequest, errorType);
		Chat.check();
	},

	tick: function()
	{
        this.command.run({'command' : 'Tick'});
	},
*/	

	handleConnectionError: function(type, XMLHttpRequest, errorType) {
	        Chat.errorsCounter++;
		try {
			addDebugInfo(type, {'error': errorType, responseText :XMLHttpRequest.responseText, sessionKey : sessionKey});
		} catch (err) {
			addDebugInfo(type, {'error': 'Timeout error'});
		}
		if((Chat.errorsCounter>Chat.maxErrors || type=='push') && !Chat.connectionError) {
			msg='Seems internet connection is lost or server is unavailable.Please check connection';
			if(type=='push')
				msg="Couldn't send chat command."+msg;
			alert(msg);
			// @TODO: finish Chat.connectionLost and insert here
			Chat.errorsCounter = 0;
			Chat.connectionError = true;
		}
	},

	handleServerError: function(type, response) {
		alert("Server error : \r\n"+response.error);
		addDebugInfo(type, {error : response.error, responseText :response});
	},

	refresh: function()
	{
              if(typeof Chat.ajaxTimeout === 'undefined')
                    Chat.ajaxTimeout = setInterval('Chat.ajaxTimeoutCheck()', 60000);
              this.command.runRefresh({'command' : 'refresh', "params" : {'postId' : this.postId}});
	},
        
        ajaxTimeoutCheck: function()
        {
        /// check again
//	Chat.check();        
//         this.refresh();        
        },
	

	text: function(params)
	{
	    var text = params.message;
	    var textStyle = params.style;
	    var nickName = ''; 
	    if(params.nickName)
	        nickName = params.nickName+': ';
	
	    if(params.nameColor)
	        var nameColor = params.nameColor;
	    else
	        var nameColor = 'oppName';

		if(params.message)
	    if(params.message.length > 0)
	    {
	        if(textStyle == undefined)
	            textStyle = 'blackLine';
	        text = str_replace('\n', '<br>', text);
	        var parent = document.getElementById("chatlog");
	        if(parent)
	            parent.innerHTML = parent.innerHTML + '<span class="'+nameColor+'">'+ nickName +'</span><span class="'+textStyle+'">'+ text +'</span><br>';
	        parent.scrollTop = parent.scrollHeight; //scroll chatlog to last message
                Chat.getSoundMode();
                if(params.nickName != myNick && userType == 2 && Chat.soundMode == 'on') /// if READER recieves message
                {   
                    $('#sound').html(sound);
                }
	    }
	    return true;
	},
	
	sendMessage: function(text, textStyle)
	{
//	    if($('#message').val().length > 0)
//	    {
            var params = new Object();
            params.message = text;
            params.style = textStyle;
            params.nickName = myNick;
            params.nameColor = 'myName';
            this.text(params);
            if(textStyle == undefined)
                textStyle = 'blackLine';
            $("#message").val(''); //clear message field
            // JSON for send to server
            data =
			{
				"command" : "text",
                "params" : {'message' : text, 'style' : textStyle}
            };
            this.command.run(data);
//	    }
	},

	sendTypingMsg: function()
	{
            if(!Chat.typing)
            {
                    Chat.typing = 1;
                    setTimeout('Chat.clearTypingFlag()', 20000);
                    data = { "command" : "typing", "params" : ''};
                    this.command.run(data);			
            }		
	},

	clearTypingFlag : function()
	{
		Chat.typing = 0;
	},

	 showTyping: function(params)
        {
		Chat.clearTyping();
		
                name = params.nickName;		
		$('span.typing').html(name +' typing...');
		setTimeout('Chat.clearTyping()', 5000);
        },

        clearTyping: function()
        {
           $('span.typing').html('');
        },
        
        getSoundMode: function()
        {
            var url = baseChatPath + "expert/getSoundMode";
            $.ajax({    
                url: url,  
                cache: false,  
                success: function(html){ 
                    Chat.soundMode = html;
                }  
            });
        }
	
//end of class
});

