/**
 * @project Bridge shoppingcart
 * JS event handlers for admin panel
 */

$(document).ready(function() {
    $('#category-delete').click(function(e) {        
        e.preventDefault();
        ans = confirm("Are you sure to delete?");
        if(ans) {
            document.location.href = $('#category-delete').attr('href');
        }        
    });
    
    // New category
    /*$('#category-new').click(function(){
     $('#category-list').load("templates/new-category.phtml");       
     });*/


    /*$('#new-category-submit').click(function(){       
     catName = $('#category-name').val();   
     alert(catName);
     if(catName == '') {
     $('#category-name').attr('placeholder','Category name is required');
     }
     });*/
});

