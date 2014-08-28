/**
 * @project Bridge shoppingcart
 * JS event handlers for admin panel
 */

$(document).ready(function() {
    // Alert before category delete
    $('#category-delete').click(function(e) {
        e.preventDefault();
        ans = confirm("Are you sure to delete?");
        if (ans) {
            document.location.href = $('#category-delete').attr('href');
        }
    });
});

