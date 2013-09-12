$(function() {
    $(".v-product-tile .thumbnail").hover(
        function() {
            $(this).addClass("box-shadow");
            $(this).find(".v-product-description").show();
            $(this).parent().addClass("box-shadow");
        },
        function() {
            $(this).removeClass("box-shadow");
            $(this).find(".v-product-description").hide();
            $(this).parent().removeClass("box-shadow");
        }
    );
});
