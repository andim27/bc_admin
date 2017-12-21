window.onload = function() {
    var first = 1;
    var last = 35;
    var path = '/images/backgrounds/';
    var img_src = "url('" + path + getRandomInt(first,last) + ".jpg')";
    var div = document.getElementsByTagName("body");
    div[0].style.backgroundImage = img_src;
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
};