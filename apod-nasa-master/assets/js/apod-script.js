jQuery(document).ready(function($) {
    $('.nasa-gallery-slider').slick({
        lazyLoad: 'ondemand',
        dots: true,
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        autoplay: true,
        autoplaySpeed: 2000,
        prevArrow:"<button type='button' class='slick-button slick-prev'><</button>",
        nextArrow:"<button type='button' class='slick-button slick-next'>></button>",
    });
});