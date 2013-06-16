/*
 * AJAX queue creating class
 */
AJAXInteraction = newClass(null, {

    req : '',
    options : '',
    timeout : 0,

    constructor: function()
    {
        if (window.XMLHttpRequest) {
            this.req = new XMLHttpRequest();
/*        } else if (window.ActiveXObject) {
            this.req = new ActiveXObject("Microsoft.XMLHTTP");
*/
        }
    },

    processRequest: function()
    {
        clearTimeout(this.timeout);
        if (Chat.ai.req.readyState == 4) {
            if (Chat.ai.req.status == 200) {
                var data = eval('('+Chat.ai.req.responseText+')');
                Chat.ai.options.success(data);
            } 
            else
            {
                Chat.ai.options.error(Chat.ai.req, 'Could not connect to server');
            }
        }
    },

    doPost: function(options)
    {
        // Data for server script
        options.data ={};
        options.data.sessionKey =  sessionKey;
        options.data.t = (new Date).getTime();
        options.data.postId = options.postId;
        this.options = options;

        // Configure XLMHttpObject properties
        this.req.open("POST", options.url, true);
        this.req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        var dat = this.params(options.data);
        this.req.onreadystatechange = this.processRequest;
        
        
        this.req.send(dat);
    },

    params: function(obj)
    {
        var out = '';
        for (var i in obj) {
            out += i + "=" + obj[i] + "&";
        }
        return out;
    }



});
