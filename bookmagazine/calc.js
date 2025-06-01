let COI = 0;
function fg(){
        const colors = ["#D0F0C0", "#F5F5DC", "#FCFCEE", "#FFFFE0"];
        document.body.style.background = colors[COI];
        COI = (COI + 1) % 4;
        const BI = setInterval(fg, 100000);
}
$(document).ready(function(){
        fg();
});
$(document).ready(function(){
    $(".slider").slick({
        arrows: true,
        dots: true,
        infinite: true,

        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToScroll: 1,
                    slidesToShow: 1
                }
            }
        ],

        slidesToScroll: 3,
        slidesToShow: 3
    });
});
