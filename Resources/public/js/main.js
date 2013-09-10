$(function() {
    $(".v-product-tile").hover(
        function(){ $(this).addClass("box-shadow") },
        function(){ $(this).removeClass("box-shadow") }
    );
});
