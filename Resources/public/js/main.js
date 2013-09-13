$(function() {
    $(".v-product-tile .thumbnail").hover(
        function() {
            $(this).addClass("box-shadow");
            $(this).find(".v-product-box").show();
            $(this).parent().addClass("box-shadow");
        },
        function() {
            $(this).removeClass("box-shadow");
            $(this).find(".v-product-box").hide();
            $(this).parent().removeClass("box-shadow");
        }
    );
});
