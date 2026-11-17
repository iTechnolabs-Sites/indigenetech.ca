jQuery(document).ready(function ($) {
    $('.image-slider').slick({
        arrows: false,
        dots: false,
        autoplay: true,
        autoplaySpeed: 2000,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        speed: 600
    });
});