class Editor {
    /**
     * Create a new editor instance
     * @param project string: Name of the current project
     * @param branch string: branch name
     * @param file string: file name
     * @param version string: version tag
     * @param containerID string: ID of the element into which the editor should be inserted
     * @param connAddr string: address:port the websocket address for receiving updates
     */
    constructor(project, branch, file, version, containerID, connAddr) {
        this.containerID = containerID;
        this.Context = {project: project, branch: branch, file: file, version: version};
        this.ContextURI = {current: undefined, previous: undefined};
        this.conn = new autobahn.Connection({
            url: connAddr,
            realm: "realm1"
        });
        this.Session = {session: undefined, details: undefined, subscription: undefined};
        this.View = {chunks: undefined, versions: undefined, locked: undefined};
        this.conn.onopen = this.openHandler();
        this.conn.open();


    };

    /**
     * Returns an IIFE to be assigned to the onOpen handler of the autobahn connection. Creates
     * a closure so that the Editor instance is available in the callback, and Editor variables
     * such as Session can be updated to store connection info on successful connection.
     * @returns {Function}
     */
    openHandler() {
        let self = this;
        return function (session, details) {
            console.log("We connected.");
            console.log(session);
            console.log(details);
            console.log(self.Session);
            self.Session.session = session;
            self.Session.details = details;
        }

    }

    /**
     * Returns a function to be assigned to the connection on each subscription to handle data received from
     * WAMP connection
     * @returns {Function} IIFE to handle received data
     */
    receiveHandler(){
        let self = this;
        return function (data) {
            console.log(data);
            self.View.chunks = data;
            self.refreshView();
        }
    }

    /**
     * Uses the Editor.ContextURI to subscribe to a particular update channel corresponding to the file the user
     * is currently working on.
     */
    setSubscribedContext() {
        //TODO improve error handling

        let self = this;
        if(this.Session.subscription !== undefined){
            this.Session.session.unsubscribe(this.Session.subscription).then(
                function (gone) {
                    self.Session.subscription = undefined;
                    console.log("Unsubbed from: " + self.ContextURI.previous)
                }
            );
        }
        this.Session.session.subscribe(this.ContextURI.current, this.receiveHandler()).then(
            function (subscription){
                self.Session.subscription = subscription;
                console.log("Subbed to: " + self.ContextURI.current);
            }
        )
    }

    /**
     * Parses a URI to subscribe to on the websocket using the current context
     */
    genNewContextURI(){
        this.ContextURI.previous = this.ContextURI.current;
        this.ContextURI.current = "com.cylaborate.";
        this.ContextURI.current += this.Context.project.replace(/[\s\.\\]/g, '').toLowerCase() + '.';
        this.ContextURI.current += this.Context.branch.replace(/[\s\.\\]/g, '').toLowerCase() +'.';
        this.ContextURI.current += this.Context.file.replace(/[\s\.\\]/g,'').toLowerCase() +'.';
        this.ContextURI.current += this.Context.version.replace(/[\s\.\\]/g,'').toLowerCase();
    }

    /**
     * Modifies the context of the editor. This is used to control what file the editor is accessing.
     * This makes the necessary calls to regenerate the editor to display the proper context.
     * @param project
     * @param branch
     * @param file
     * @param version
     */
    setContext({project = this.Context.project, branch = this.Context.branch, file = this.Context.file, version = this.Context.version}) {
        this.Context.project = project;
        this.Context.version = version;
        this.Context.file = file;
        this.Context.branch = branch;
        this.genNewContextURI();
        this.setSubscribedContext();
    }

    /**
     * Make the initial request to the server to for version tags for the current context
     */
    requestVersions() {
        let self = this;
        $.ajax({
            url: "ajax/chunker.php",
            data: {
                context: this.ContextURI.current,
                project: this.Context.project,
                branch: this.Context.branch,
                file: this.Context.file,
                request: "get_version_tags"
            },
            method: "post",
            success: function (data) {
                console.log(data);
                self.View.versions = JSON.parse(data);
                self.setContext({version: self.View.versions[0]});
                self.refreshView();
            }
        });

    }

    /**
     * Make initial request to the server for chunks for the current context
     */
    requestChunks() {
        let self = this;
        $.ajax({
            url: "ajax/chunker.php",
            data: {
                channel: this.ContextURI.current,
                project: this.Context.project,
                branch: this.Context.branch,
                file: this.Context.file,
                version: this.Context.version,
                request: "layout",
                action: "get_page"
            },
            method: "post"
        });
    }

    /**
     * Send updated chunk information to the server for broadcasting to other clients
     * @param id current id of the chunk
     * @param contents new contents of the chunk
     */
    updateChunk(id, contents) {
        let self = this;
        let pos = undefined;
        //TODO Dear TJ. Find a method with better time complexity.
        for (let i = 0; i < self.View.chunks.length; i++) {
            if (self.View.chunks[i].id === id) {
                pos = i;
                break;
            }
        }
        //exit instead of sending garbage data
        if (pos === undefined) return;
        $.ajax({
            url: "ajax/chunker.php",
            data: {
                channel: this.ContextURI.current,
                project: this.Context.project,
                branch: this.Context.branch,
                file: this.Context.file,
                version: this.Context.version,
                position: pos,
                id: id,
                content: contents,
                request: "layout",
                action: "update_position"
            },
            method: "post"
        });
    }

    /**
     * Add a new chunk to the file and send the update to the server
     * @param pos
     */
    addChunk(pos) {
        //exit instead of sending garbage data
        if (pos === undefined) return;
        $.ajax({
            url: "ajax/chunker.php",
            data: {
                channel: this.ContextURI.current,
                project: this.Context.project,
                branch: this.Context.branch,
                file: this.Context.file,
                version: this.Context.version,
                position: pos,
                content: '',
                request: "layout",
                action: "add_to_position"
            },
            method: "post"
        });
    }

    /**
     * Place a resource lock on the chunk currently being edited
     * @param id the id of the chunk being edited
     */
    lockChunk(id) {
        if (this.checkLock(id) == false) {
            this.View.locked.push({id: id, owner: true});
        }

    }

    /**
     * Remove the resource lock on the specified chunk
     * @param id the chunk to remove the resource lock from
     */
    unlockChunk(id) {
        if (this.checkLock(id) === true && this.checkLockOwner(id) === true) {
            let lock = this.View.locked
        }
    }

    /**
     * Checks if chunk is locked.
     * @param id
     */
    checkLock(id) {
        let locked = this.View.locked;
        let haslock = false;
        locked.forEach(function (lock) {
            if (lock.id == id) {
                haslock = true;
            }
        });
        return haslock;
    }

    /**
     * Checks if current user owns lock.
     * @param id
     */
    checkLockOwner(id) {
        let locked = this.View.locked;
        let haslock = false;
        locked.forEach(function (lock) {
            if (lock.id == id) {
                haslock = lock.owner;
            }
        });
        return haslock;
    }

    /**
     * Regenerate the HTML for the view, and
     */
    refreshView() {
        $('#' + this.containerID).html(this.generateHTML());
        this.assignAllHandlers();
    }

    /**
     * Generate the HTML for the view
     * @returns {string} HTML string
     */
    generateHTML() {
        let HTML = this.generateHTMLBegin();
        HTML += this.generateHTMLVerSel();
        HTML += this.generateHTMLChunks();
        return HTML + this.generateHTMLEnd();
    }

    /**
     * Generate HTML representing for a blank check
     * @param id temporary chunk id
     * @returns {string} HTML string
     */
    generateHTMLBlankChunk(id) {
        return "<td><textarea class='chunk' id='" + id + "-new-chunk' readonly></textarea></td>"
    }

    /**
     * Generate HTML for an Add button
     * @param loc order of the button in the view
     * @returns {string} HTML string
     */
    generateHTMLAddBtn(loc) {
        return "<td><button type='button' class='chunk-adder' id='add-" + loc + "' name='add-" + loc + "' value='"
            + loc + "'>Add</button></td>";
    }

    /**
     * Generate HTML for the version selection menu
     * @returns {*} HTML string
     */
    generateHTMLVerSel() {
        if (this.View.versions === undefined) return "";
        let HTML = "<select id=\"" + this.containerID + "-verselect\">";
        HTML += "<option value=\"\"></option>";
        this.View.versions.forEach(function (ver) {
            HTML += "<option value=\"" + ver + "\">" + ver + "</option>";
        });
        return HTML + "</select><br>";

    }

    /**
     * Generate HTML for the currently loaded chunks
     * @returns {string} HTML string
     */
    generateHTMLChunks() {
        if (this.View.chunks === undefined) return "";
        let HTML = "";
        let self = this;
        let i = 0;
        HTML += "<table id='" + this.ContextURI.toString() + "'>";
        HTML += "<tr>";
        HTML += this.generateHTMLAddBtn(i);
        HTML += "</tr>";
        this.View.chunks.forEach(function (chunk) {
            i++;
            console.log(chunk.id + " " + chunk.content);
            HTML += "<tr><td></td><td><textarea class='chunk' id='" + chunk.id + "' readonly>";
            HTML += chunk.content + "</textarea></td>";
            HTML += "</tr>";
            HTML += "<tr>" + self.generateHTMLAddBtn(i) + "</tr>";
        });

        return HTML;
    }

    /**
     * Generate closing HTML for the editor
     * @returns {string} HTML string
     */
    generateHTMLEnd() {
        return "</div>";
    }

    /**
     * Generate beginning HTML for the editor
     * @returns {string} HTML string
     */
    generateHTMLBegin() {
        let HTML = "<div id=\"" + this.containerID + "-editor\">";
        return HTML;

    }

    /**
     * Assign appropriate event handlers to Editor elements
     */
    assignAllHandlers() {
        let self = this;
        $('textarea.chunk').focus(function () {
            $(this).prop("readonly", false);
        });
        $('textarea.chunk').focusout(function () {
            $(this).prop("readonly", true);
            console.log("Sending update");
            console.log("old id: " + $(this).prop('id'));
            console.log("new contents: " + $(this).val());
            self.updateChunk($(this).prop('id'), $(this).val());
        });

        $('button.chunk-adder').click(function () {
            //TODO fix this. Love TJ.
            let id = $(this).attr('id');
            alert($('#' + id).attr('value'));
            /* $(self.generateHTMLBlankChunk(id)).insertAfter($('#' + id).parent());
             $('#' + id).remove();*/
            self.addChunk($(this).attr('value'));
        });

        $('#' + this.containerID + '-verselect').change(function () {
            self.setContext({version: this.value});
            self.requestChunks();
        });

        $(document).delegate('#textbox', 'keydown', function (e) {
            var keyCode = e.keyCode || e.which;

            if (keyCode == 9) {
                e.preventDefault();
                var start = this.selectionStart;
                var end = this.selectionEnd;

                // set textarea value to: text before caret + tab + text after caret
                $(this).val($(this).val().substring(0, start)
                    + "\t"
                    + $(this).val().substring(end));

                // put caret at right position again
                this.selectionStart =
                    this.selectionEnd = start + 1;
            }
        });
    }
}
