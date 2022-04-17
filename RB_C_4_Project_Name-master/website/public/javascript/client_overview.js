function submit_review($username, $review_text, $selected_rating) {

    $.post("ajax/client_ajax.php", {
        action: "submit_review",
        text: $review_text,
        rating: $selected_rating,
        username: $username
    }, function (data) {
        console.log(data);
    })
}

$('#submit_review').click(function () {
    $username = "";
    $.ajax({
        url: 'ajax/get_username.php',
        type: 'POST',
        async: false,
        data: {
            action: 'get_username'
        },
        success: function (data) {
            $username = data;
        }
    });
    submit_review($username, $('#review_input').val(), $('#rating_select').val());

    $('#client_support').hide();
    $('#thanks').show();
});

$(document).ready(function () {
    $('#thanks').hide();
});