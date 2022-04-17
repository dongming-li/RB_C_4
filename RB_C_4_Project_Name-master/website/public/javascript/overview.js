function get_projects()
{
    $.post("ajax/update_overview.php", {
        action: "get_projects"
    }, function (data) {
        $("#right-bar").html(data);
    })
}