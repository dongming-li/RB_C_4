/**
 * Created in PhpStorm
 * @author wojoinc
 * @description Chat window class for creating simple chat window to connect to websocket.
 */

class ChatWindow {
    /**
     * Constructor for ChatWindow element
     * @param containerElementID element to serve as the container for this ChatWindow. Should already exist in DOM.
     * @param id unique name or id to identify this instance. Appended to the names of members to prevent issues
     * @param connAddr A string representing the server to connect client to. ie. ws://chatserver.com:8080
     * in the case where multiple instances of ChatWindow exist in the same DOM
     */
    constructor(containerElementID, id, connAddr, username) {
        this.m_logElement = {id: id + "-logwindow", readOnly: "readonly", value: ""};
        this.m_entryElement = {id: id + "-entrywindow", type: "text", value: ""};
        this.m_container = $('#' + containerElementID);
        this.id = id;
        this.user = {username: username};
        this.m_conn = new WebSocket(connAddr);

        //insert HTML into DOM
        this.m_container.html(this.createHTMLForWindow());

        this.assignHandlers();

    }

    logChat(message) {
        let $logElem = $('#' + this.m_logElement.id);
        $logElem.val($logElem.val() + message + "\n");
    }

    assignHandlers() {
        //should update these later as we add features
        let self = this;
        this.m_conn.onopen = function (e) {
            //self.logChat(e.data);
            self.m_conn.send(JSON.stringify(self.user));
        };

        this.m_conn.onmessage = function (e) {
            self.logChat(e.data);
        };

        this.m_conn.onerror = function (e) {
            self.logChat(e.data);
            self.logChat("Could not connect to chat server at: " + self.m_conn.url);
        };

        $('#' + this.m_entryElement.id).keypress(function (e) {
            //prevent textarea from inserting new line unless Shift + Enter is pressed
            if (e.keyCode == 13 && this.value && !e.shiftKey) {
                e.preventDefault();
                self.m_conn.send($(this).val());
                $(this).val('');
            }
        });
    }

    createHTMLForWindow() {
        let HTML = "<div class=\"chatwindow\" id=\"" + this.id + "\">";
        HTML += "<textarea id=\"" + this.m_logElement.id + "\" " + this.m_logElement.readOnly + "></textarea><br>";
        HTML += "<textarea id=\"" + this.m_entryElement.id + "\"></textarea>";
        return HTML + '</div>';
    }
}


