// class Sidebar
// {
//     username;
//     constructor()
//     {
//         let editor = new Editor(null, null, null, null, "right-bar", "ws://proj-309-rb-c-4.cs.iastate.edu:8081");
//         get_username();
//         let chat = new ChatWindow('chat', 'sidebar', "ws://proj-309-rb-c-4.cs.iastate.edu:8080", username)
//     }
//
//     get_username()
//     {
//         $.post("ajax/get_username.php", {
//             action: "get_username"
//         }, function (data) {
//             username = data;
//         })
//     }
//
// }


$(document).ready(function() {
    var sidebar = new Sidebar();
})

// $(document).ready(function () {
//
//     //check_login();
//     localStorage.setItem('active-file', 'test.html');
//     localStorage.setItem('active-project', 'test_project');
//     localStorage.setItem('version', 'test');
//     var $filename = "test.html";
//     var $project = "test_project";
//     var $version;
//     var $mode = "";
//
//     //create chat window
//     var username = get_username();
//     alert(username);
//     let chat = new ChatWindow('chat', 'sidebar', "ws://proj-309-rb-c-4.cs.iastate.edu:8080", username);
//
//
//     $('#new-file').click(function () {
//         $('#new-file').hide();
//         $('#new-file-input').show().focus();
//     });
//
//     $('#new-file-input').keyup(function (e) {
//         if (e.which === 13) {
//             let filename = $('#new-file-input').val();
//             alert(filename);
//             $('#new-file-input').hide();
//             $('#new-file').show();
//             createNewProjectFile(filename);
//             $('#new-file-input').outerHTML = (
//                 $('<p></p>')
//                     .attr({id: filename, class: "files"})
//             );
//         }
//     });
//
//     $('p.files').click(function () {
//         $filename = $(this).attr('id');
//         localStorage.setItem('active-file', $filename);
//         let project = localStorage.getItem('active-project');
//         $.ajax({
//             url: 'ajax/chunker.php',
//             type: 'POST',
//             async: true,
//             data: {
//                 filename: $filename,
//                 project: project,
//                 operation: 2
//             },
//             success: function (data){
//                 //alert(data);
//                 $('#right-bar').html(data);
//                 //TODO find a better way. - Love TJ
//                 assignAllHandlers();
//             }
//
//         });
//     });
//
// });
// /*TODO
//  Get title
//  get company name
//  get file directory
//  get chat
//  send message
//  get project title
//  get url
//  get project stats
//  get project name
//
//  load main window
//  */
//
// function check_login() {
//     $.post("ajax/login_register.php", {
//         action: "check"
//     }, function (data) {
//         if (data == "not_logged_in") {
//             //Need to log in
//             load_login();
//         }
//         else {
//             alert("is");
//         }
//     })
// }
//
//
// function load_overview() {
//     $.post("ajax/content_loader.php", {
//         content: "OVERVIEW"
//     }, function (data) {
//         $("#right-bar").html(data);
//         $mode = "OVERVIEW";
//     })
// }
//
// function load_editor() {
//     $.post("ajax/content_loader.php", {
//         content: "EDITOR"
//     }, function (data) {
//         $("#right-bar").html(data);
//         $mode = "EDITOR";
//     })
// }
//
// function load_login() {
//     $.post("ajax/content_loader.php", {
//         content: "LOGIN"
//     }, function (data) {
//         $("#login_space").html(data);
//         $mode = "LOGIN";
//     })
// }
//
// function table_create() {
//     let table_name = document.getElementById("table_select").value;
//     $.post("ajax/table_create_public.php", {
//         table: table_name
//     }, function (returnStatus) {
//         console.log(returnStatus);
//         $("#error_message").html(returnStatus);
//     })
// }
//
// function need_update() {
//     $.post("ajax/update_changes.php", {
//         action: "check_for_update",
//         version: $version
//     }, function (data) {
//         //TODO
//         //parse return
//         //call functions to update
//         //update version number
//     })
// }
//
// function createNewProjectFile(filename) {
//     $.post('ajax/chunker.php', {
//         file: filename,
//         option: 'create'
//     });
//
// }
//
// function get_username()
// {
//     let user = undefined;
//     $.ajax({
//         url: 'ajax/get_username.php',
//         type: 'POST',
//         async: false,
//         data: {
//             action: "get_username"
//         },
//         success: function (data)
//         {
//             //alert(data);
//             user = data;
//         }});
//     return user;
// }