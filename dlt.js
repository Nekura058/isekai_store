
document.addEventListener("DOMContentLoaded", function(){
// Attach a confirmation to any element with the class "delete-btn"
    var deleteButtons = document.querySelectorAll(".delete-btn");
    deleteButtons.forEach(function(button){
        button.addEventListener("click", function(e){
            if(!confirm("Are you sure you want to delete this product?")){
                e.preventDefault();
            }
        });
    });
});
