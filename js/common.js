function menu(){
    $('nav div, nav ul ul').stop().slideToggle(300)  // 300 = 0.3s
}

$(document)
.on('mouseenter mouseleave', 'nav', menu)