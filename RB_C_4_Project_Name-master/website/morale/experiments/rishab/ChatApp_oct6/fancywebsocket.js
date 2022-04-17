var FancyWebSocket = function(url)
{
	var callbacks = {};
	var ws_url = url;
	var conn;

	this.bind = function(event_name, callback){
		callbacks[event_name] = callbacks[event_name] || [];
		callbacks[event_name].push(callback);
		return this;// chainable
	};

	this.send = function(event_name, event_data){
        this.m_conn.send(event_data);
		return this;
	};

	this.connect = function() {
		if ( typeof(MozWebSocket) == 'function' )
            this.m_conn = new MozWebSocket(url);
		else
            this.m_conn = new WebSocket(url);

		// dispatch to the right handlers
        this.m_conn.onmessage = function (evt) {
			dispatch('message', evt.data);
		};

        this.m_conn.onclose = function () {
            dispatch('close', null)
        }
        this.m_conn.onopen = function () {
            dispatch('open', null)
        }
	};

	this.disconnect = function() {
        this.m_conn.close();
	};

	var dispatch = function(event_name, message){
		var chain = callbacks[event_name];
		if(typeof chain == 'undefined') return; // no callbacks for this event
		for(var i = 0; i < chain.length; i++){
			chain[i]( message )
		}
	}
};