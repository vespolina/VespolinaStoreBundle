$(function() {
    $(".v-product-tile .thumbnail").hover(
        function(){ $(this).addClass("box-shadow") },
        function(){ $(this).removeClass("box-shadow") }
    );
});
