// To change transition speed you must change ALL INSTANCES of the number "300"
// The lower the number the faster the transition - the higher the number the slower the transition
// Default transition is 300 = fast, for a slower transition change the speed to anything over 500

// On window load. This waits until images have loaded which is essential
$(window).load(function () {
    // Fade in images so there isn't a color "pop" document load and then on window load
    $("#slider_content img").animate({
        opacity: 1
    }, 300);
    // clone image
    $('#slider_content img').each(function () {
        var el = $(this);
        el.css({
            "position": "absolute"
        }).wrap("<div class='grayscale_wrapper'>").clone().addClass('img_grayscale').css({
            "position": "absolute",
            "z-index": "998",
            "opacity": "0"
        }).insertBefore(el).queue(function () {
            var el = $(this);
            el.parent().css({
                "width": this.width,
                "height": this.height
            });
            el.dequeue();
        });
        this.src = grayscale(this.src);
    });
    // Fade image 
    $('#slider_content img').mouseover(function () {
        $(this).parent().find('img:first').stop().animate({
            opacity: 1
        }, 300);
    })
    $('.img_grayscale').mouseout(function () {
        $(this).stop().animate({
            opacity: 0
        }, 300);
    });
});
// Grayscale w canvas method
function grayscale(src) {
    var canvas = document.createElement('canvas');
    var ctx = canvas.getContext('2d');
    var imgObj = new Image();
    imgObj.src = src;
    canvas.width = imgObj.width;
    canvas.height = imgObj.height;
    ctx.drawImage(imgObj, 0, 0);
    var imgPixels = ctx.getImageData(0, 0, canvas.width, canvas.height);
    for (var y = 0; y < imgPixels.height; y++) {
        for (var x = 0; x < imgPixels.width; x++) {
            var i = (y * 4) * imgPixels.width + x * 4;
            var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
            imgPixels.data[i] = avg;
            imgPixels.data[i + 1] = avg;
            imgPixels.data[i + 2] = avg;
        }
    }
    ctx.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height);
    return canvas.toDataURL();
}