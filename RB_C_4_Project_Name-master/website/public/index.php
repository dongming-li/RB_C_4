<?php
session_start();
if(isset($_SESSION['username']))
{
    $username = $_SESSION['username'];
    $is_logged_in = 1;
}
else
{
    $is_logged_in = 0;
}
$project = $_SESSION['project'] ?? 'null';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="javascript/autobahn.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="javascript/chatwindow.js"></script>
    <script src="javascript/sidebar.js"></script>
    <script src="javascript/overview.js"></script>
    <script src="javascript/sidebarObject.js"></script>
    <script src="javascript/editor.js"></script>
    <meta charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="css/index.css"/>
    <link type="text/css" rel="stylesheet" href="css/editor.css"/>
    <title>Cylaborate</title>
</head>
<body>

<div id="header">
    <div id="top-bar">
        <div id="project-information">
            <p id="title"><b id="project_title">Project Info</b></p>
            <div id="project-stats">
                <table>
                    <tr><td>Managers</td><td><p id="num-managers" class="user_numbers"></p></td></tr>
                    <tr><td>Developers</td><td><p id="num-developers" class="user_numbers"></p></td></tr>
                    <tr><td>Clients</td><td><p id="num-clients" class="user_numbers"></p></td></tr>
                </table>
            </div>
<!--            <div id="project-options">Add developers</div>-->
        </div>
        <div id="user-name">Name here</div>
        <div id="login_information">
        <p id="username-display"></p><button id="logout">Logout</button>
        </div>
    </div>
</div>
<div id="content">
    <div id="left-bar">
        <div id="left-container">

            <div id="company-name">Iowa State</div>


            <div id="file-directory">

<!--                <b>Cylaborate</b><br>-->
<!--                <p id="new-file" class="files">Add New...</p>-->
<!--                <input type="text" id="new-file-input" hidden>-->
<!--                <p class="files">|---public</p>-->
<!--                <p class="files">|&nbsp&nbsp&nbsp|---css</p>-->
<!--                <p id="css/index.css" class="files">|&nbsp&nbsp&nbsp|&nbsp&nbsp&nbsp|---index.css</p>-->
<!--                <p id="index.html" class="files">|&nbsp&nbsp&nbsp|---index.html</p><br>-->
            </div>
            <div id="branch-display"></div>

            <div id="chat">
            </div>
        </div>
    </div>
    <div id="right-bar">

    </div>
</div>
</div>
</body>
</html>


