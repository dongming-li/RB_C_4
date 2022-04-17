class Sidebar
{
    constructor()
    {
        this.logged = 0;
        this.check_login();
        if(!this.logged)
        {
            this.load_login();
        }
        else
        {
            this.logged_in();
        }



        this.assignAllHandlers();
    }

    get_username()
    {
        let self = this;
        $.ajax({
            url: 'ajax/get_username.php',
            type: 'POST',
            async: false,
            data: {
                action: 'get_username'
            },
            success: function (data){
                self.username = data;
                //$("#user-name").text = data;
            }
        });
        this.get_user_level();
    }

    get_user_level()
    {
        let self = this;
        $.ajax({
            url: 'ajax/get_username.php',
            type: 'POST',
            async: false,
            data: {
                action: 'get_user_level'
            },
            success : function (data) {
                //alert(data);
                self.user_level = data;
            }
        })
    }

    assignAllHandlers()
    {

        let self = this;
        self.get_users_by_type();
        $('#logout').click(function () {
            $.ajax({
                url: 'ajax/login_register.php',
                type: 'POST',
                async: false,
                data: {
                    action: 'logout'
                },
                success: function (data) {
                    location.reload();
                }
            })
        })

        // $('#new-file').click(function () {
        //     $('#new-file').hide();
        //     $('#new-file-input').show().focus();
        // });
        //
        // $('#new-file-input').keyup(function (e) {
        //     if (e.which === 13) {
        //         let filename = $('#new-file-input').val();
        //         alert(filename);
        //         $('#new-file-input').hide();
        //         $('#new-file').show();
        //         createNewProjectFile(filename);
        //         $('#new-file-input').outerHTML = (
        //             $('<p></p>')
        //                 .attr({id: filename, class: "files"})
        //         );
        //     }
        // });




    }


    check_login() {
        let self = this;
        $.ajax({
            url:'ajax/login_register.php',
            type: 'POST',
            async: false,
            data: {
                action: 'check'
            },
            success: function (data){
                if (data === "not_logged_in") {
                    //Need to log in
                    self.logged = 0;
                }
                else {
                    self.logged = 1;
                }
            }
        })
    }

    load_login() {
        let self = this;
        $.post("ajax/content_loader.php", {
            content: "LOGIN"
        }, function (data) {
            $("#right-bar").html(data);
            $("#login_button").click( function() {self.login_register()});
        })
    }

    logged_in()
    {
        this.get_username();

        if(this.user_level == 0)        //Client
        {
            $("#right-bar").load('client_page.html');

        }
        else if(this.user_level == 1)   //Developer
        {
            this.editor = new Editor("Alex", "Robert", "Thomas", "Rishab", "right-bar", "ws://proj-309-rb-c-4.cs.iastate.edu:8081");
            this.chat = new ChatWindow('chat', 'sidebar', "ws://proj-309-rb-c-4.cs.iastate.edu:8080", this.username);
            this.get_projects();
            $("#username-display").val(this.username);
        }
        else if(this.user_level == 2)   //Manager
        {
            this.editor = new Editor("Alex", "Robert", "Thomas", "Rishab", "right-bar", "ws://proj-309-rb-c-4.cs.iastate.edu:8081");
            this.chat = new ChatWindow('chat', 'sidebar', "ws://proj-309-rb-c-4.cs.iastate.edu:8080", this.username);
            $("#username-display").val(this.username);
            this.display_manage_page_overview();
        }
    }

    login_register() {

    var self = this;
    var $error = 0;
    var $username;
    var $password;
    var $account_type = $("#account_type").val();
    var $register = "login";
    var user_level;

    if($("#radio_register").is(":checked")){
        $register = "register";
    }

    if ($("#username").val() === ""){
        $("#username-error").html("Invalid username.");
        $error = 1;
    } else {
        $username = $("#username").val();
    }

    if($("#password").val() === ""){
        $("#password-error").html("Invalid password.");
        $error = 1;
    } else {
        $password = $("#password").val();
    }

    if($register === "register"){
        var $email = $("#email").val();
        var $firstname = $("#first_name").val();
        var $lastname = $("#last_name").val();

        if($email === ""){
            $("#email-error").html("Invalid email.");
            $error = 1;
        }
        if($firstname === ""){
            $("#first-error").html("Invalid first name.");
            $error = 1;
        }
        if($lastname === ""){
            $("#last-error").html("Invalid last name.");
            $error = 1;
        }
        if($account_type == "none"){
            $("#type-error").html("Please select.");
            $error = 1;
        }
    }

    if($error == 0){
        if($register === "login"){
            $.post("ajax/login_register.php", {
                action   : "login",
                username : $username,
                password : $password
            }, function(data){
                if(data === 'failed')
                {
                    $("#login_error").html("Failed to log in.");
                }
                else
                {
                    self.logged_in();
                }
            })
        }else{
            $.post("ajax/login_register.php", {
                action  : "register",
                username: $username,
                password: $password,
                type    : $account_type,
                email   : $email,
                first   : $firstname,
                last    : $lastname
            }, function(data){
                if(data === 'Logged in')
                {
                    self.logged_in();
                }
                else
                {
                    $("#login_error").html(data);
                }
            })
        }
    }
}

    get_projects()
    {
        let self = this;

        $.ajax({
            url: 'ajax/update_overview.php',
            type: 'POST',
            async: false,
            data: {
                action: 'get_projects'
            },
            success: function(data) {
                $("#right-bar").html(data);
            }
        });

        $('p.project-name').click(function () {
            self.project = $(this).attr('id');
            self.editor.setContext({project: self.project});
            $("#project_title").text(self.project);
            self.get_branchs();
        })

        if(self.user_level == 1)
        {
            $('#new-project').click(function () {
                $('#new-project').hide();
                $('#new-project-input').show().focus();
            });

            $('#new-project-input').keyup(function (e) {
                if (e.which === 13) {
                    let project_name = $('#new-project-input').val();
                    $('#new-project-input').hide();
                    $('#new-project').show();
                    self.create_new_project(project_name);
                }
            });
        }
    }

    get_branchs()
    {
        let self = this;
        $.ajax({
            url: 'ajax/update_overview.php',
            type: 'POST',
            async: false,
            data: {
                action: 'get_branches',
                project: self.project
            },
            success: function(data) {
                $("#branch-display").html(data);
            }
        });

        self.editor.setContext({branch: self.branch});
        self.get_files();
        $("#new-file").hide();
        $('#new-branch').hide();

        $("#branch-options").on("change", function() {
            self.branch = $("#branch-options option:selected").text();
            self.editor.setContext({branch: self.branch});
            self.get_files();
            $('#new-branch').show();
        })

        if(self.user_level == 1)
        {
            $('#new-branch').click(function () {
                $('#new-branch').hide();
                $('#new-branch-input').show().focus();
                $("#new-file").show();
            });

            $('#new-branch-input').keyup(function (e) {
                if (e.which === 13) {
                    let branchname = $('#new-branch-input').val();
                    $('#new-branch-input').hide();
                    $('#new-branch').show();
                    self.create_new_branch(branchname);
                }
            });
        }
    }

    get_files()
    {
        let self = this;
        $.ajax({
            url: 'ajax/update_overview.php',
            type: 'POST',
            async: false,
            data: {
                action: 'get_files',
                project: self.project,
                branch: self.branch
            },
            success: function(data) {
                $("#file-directory").html(data);
                //alert(data);
            }
        });

        $('p.files').click(function () {
            self.filename = $(this).attr('id');
            self.editor.setContext({file: self.filename});
            self.editor.requestVersions();
        });

        if(self.user_level == 1)
        {
            $('#new-file').click(function () {
                $('#new-file').hide();
                $('#new-file-input').show().focus();
            });

            $('#new-file-input').keyup(function (e) {
                if (e.which === 13) {
                    let filename = $('#new-file-input').val();
                    $('#new-file-input').hide();
                    $('#new-file').show();
                    self.create_new_file(filename);
                }
            });
        }
    }

    create_new_file(filename)
    {
        let self = this;
        $.ajax({
            url: 'ajax/new_file.php',
            type: 'POST',
            async: false,
            data: {
                action: 'new_file',
                project: self.project,
                branch: self.branch,
                filename: filename
            },
            success: function(data){
                //alert(data);
                self.get_files();
            }
        })
    }

    create_new_project(projectname)
    {
        let self = this;
        $.ajax({
            url: 'ajax/new_file.php',
            type: 'POST',
            async: false,
            data: {
                action: "new_project",
                project_name: projectname
            },
            success: function(data){
                //alert(data);
                self.get_projects();
            }
        })
    }

    create_new_branch(branchname)
    {
        let self = this;
        $.ajax({
            url: 'ajax/new_file.php',
            type: 'POST',
            async: false,
            data: {
                action: "new_branch",
                project_name: self.project,
                branch_name: branchname,
                current_branch: self.branch
            },
            success:function(data){
                //alert(data);
                self.get_branchs();
            }
        })
    }

    display_manage_page_overview()
    {
        $("#right-bar").load('manage_page_overview.html');
        this.page_overview_get_projects();
    }

    page_overview_get_projects()
    {
        let self = this;
        $.ajax({
            url: 'ajax/update_overview.php',
            type: 'POST',
            async: true,
            data: {
                action: 'get_projects'
            },
            success: function(data) {
                $("#overview_display_area").html(data);

                $('p.project-name').click(function () {
                    self.project = $(this).attr('id');
                    self.page_overview_get_branch();
                    $("#overview_project_name").text(self.project);
                })
            }
        });
    }

    page_overview_get_branch()
    {
        let self = this;
        $.ajax({
            url: 'ajax/update_overview.php',
            type: 'POST',
            async: false,
            data: {
                action: 'get_branches',
                project: self.project
            },
            success: function(data) {
                $("#overview_branch_name").html(data);
                $("#new-branch").hide();
            }
        });

        self.page_overview_get_files();

        $("#branch-options").on("change", function() {
            self.branch = $("#branch-options option:selected").text();
            self.editor.setContext({branch: self.branch});
            self.page_overview_get_files();
            $("#new-file").hide();
        })
    }



    page_overview_get_files()
    {
        let self = this;
        $.ajax({
            url: 'ajax/update_overview.php',
            type: 'POST',
            async: false,
            data: {
                action: 'overview_get_files',
                project: self.project,
                branch: self.branch
            },
            success: function(data) {
                $("#overview_display_area").html(data);
                $("#new-file").hide();
                let file_versions = {};
                $("#overview_save").prop('disabled', 'disabled');
                $(".overview_run").prop('disabled', 'disabled');

                $(".overview_save_file_version").click(function () {
                    let filename = $(this).attr('id');
                    let version = $("#" + self.escapeSelector(filename)).val();
                    //alert(filename + " " + version);

                    $("select#" + self.escapeSelector(filename)).prop('disabled', 'disabled');
                    $("button#" + self.escapeSelector(filename)).prop('disabled', 'disabled');

                    file_versions[filename] = version;

                    // let debug = "";
                    // for(let key in file_versions)
                    // {
                    //     debug += key + ": " + file_versions[key] + "<br>";
                    // }
                    //
                    // $("#overview_debug_area").html(debug);

                    if(!$("button.overview_save_file_version:enabled").length > 0)
                    {
                        $("#overview_save").prop('disabled', false);

                        $("#overview_save").click(function () {
                            $.ajax({
                                url: 'ajax/chunker.php',
                                type: 'POST',
                                async: false,
                                data: {
                                    request: "snapshot",
                                    project: self.project,
                                    branch: self.branch,
                                    versions: JSON.stringify(file_versions)
                                },
                                success: function(data) {
                                    $("#overview_download").html(
                                        "<a href='../../projects/" + self.project + "/" + self.branch + "/" + data + ".zip' download>Download</a>"
                                    );
                                    $(".overview_run").prop('disabled', false);

                                    $(".overview_run").click(function () {
                                        //alert($(this).attr('id'));

                                        $.ajax({
                                            url: 'ajax/chunker.php',
                                            type: 'POST',
                                            async: false,
                                            data: {
                                                request: 'run',
                                                project: self.project,
                                                branch: self.branch,
                                                timestamp: data,
                                                filename: $(this).attr('id')
                                            },
                                            success: function(data) {
                                                $("#overview_run_output").html("Output:<br>" + data);
                                            }
                                        })
                                    })
                                }
                            })
                        })
                    }
                });
            }
        });
    }

    //https://stackoverflow.com/questions/350292/how-do-i-get-jquery-to-select-elements-with-a-period-in-their-id
    //User: user669677
    escapeSelector(s)
    {
        return s.replace( /(:|\.|\[|\])/g, "\\$1" );
    }

    get_users_by_type()
    {
        let self = this;
        $.ajax({
            url: 'ajax/update_overview.php',
            type: 'POST',
            async: false,
            data: {
                action: 'num_users_by_type',
                type: 'MANAGER'
            },
            success: function(data) {
                $("#num-managers").text(data);
            }
        });
        $.ajax({
            url: 'ajax/update_overview.php',
            type: 'POST',
            async: false,
            data: {
                action: 'num_users_by_type',
                type: 'DEVELOPER'
            },
            success: function(data) {
                $("#num-developers").text(data);
            }
        });
        $.ajax({
            url: 'ajax/update_overview.php',
            type: 'POST',
            async: false,
            data: {
                action: 'num_users_by_type',
                type: 'CLIENT'
            },
            success: function(data) {
                $("#num-clients").text(data);
            }
        })

    }

}