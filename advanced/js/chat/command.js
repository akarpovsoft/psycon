
Command = newClass(null, {

	constructor: function()
	{
	    //empty
	},
	
	/**
	* Run command ajax request
	*/
	run: function(data)
	{			
		options = {
			data    : data,
			url     : baseChatPath + "chatcore/push",
			success : this.success,
			error   : this.error,
			context : this
		};
		addDebugInfo('push', options.data);
		
		this.ajax(options);		
	},

	/**
	* Run refresh request
	*/
	runRefresh: function(data)
	{			
		options = {
			data    : data,
			url     : baseChatPath + "chatcore/push",
			success : this.refreshSuccess,
			error   : this.refreshError,
			context : this
		};
		addDebugInfo('refresh', options.data);
		
		this.ajax(options);		
	},


	success: function(commands) {
		Chat.connectionError = false;
		Chat.errorsCounter = 0;

                clearInterval(Chat.ajaxTimeout);
                delete(Chat.ajaxTimeout);
		if(commands != null && commands.error) {
			Chat.handleServerError('push', commands);
		}
	},
	
	error: function(XMLHttpRequest, errorType) {
                clearInterval(Chat.ajaxTimeout);
                delete(Chat.ajaxTimeout);
		Chat.handleConnectionError('push', XMLHttpRequest, errorType);
	},
	

	refreshSuccess: function(commands) {
		Chat.connectionError = false;
		Chat.errorsCounter = 0;

                clearInterval(Chat.ajaxTimeout);
                delete(Chat.ajaxTimeout);
		if(commands != null)
		{
			if(commands.error) {
				Chat.handleServerError('refresh', commands);
			} else {
				if(!commands.status) { // if not "status - ok" message
					$.each(commands, function(i, data)
					{
						if(data.command && data.command in Chat)
						{
							addDebugInfo('pop', data);
							with(Chat)
								eval(data.command+'(data.params)');
						}
					});
        			        if(typeof commands.postId !== 'undefined') {
						Chat.postId = commands.postId;
                                        }
				}
			}
			if(commands.total_time) {
				Chat.setTime(commands.chat_time, commands.free_time, commands.total_time);
			}
		}
		setTimeout('Chat.refresh()', 7000);

	},

	refreshError: function(XMLHttpRequest, errorType) {
                clearInterval(Chat.ajaxTimeout);
                delete(Chat.ajaxTimeout);
		Chat.handleConnectionError('refresh', XMLHttpRequest, errorType);
		setTimeout('Chat.refresh()', 7000);
	},

	ajax: function(options) {
		if(options.data == undefined)
			options.data ={};
					
		options.type     = 'POST';
		options.dataType = 'json';
		options.cache    = false,
		options.timeout  = 110000;
		options.data.sessionKey =  sessionKey; 
		options.data.t = (new Date).getTime();
		$.ajax(options);
	}
	
});
