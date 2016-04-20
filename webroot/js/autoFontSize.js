$(document).ready(function(){
	autoFontSize();
});

//Automatic fontsize for <span> Elements in the verticalmenu
function autoFontSize() {
	var originalFontSize = 24;
	var sectionWidth = $('#verticalmenu').width();			
	$('#verticalmenu span').each(function(){
	var spanWidth = $(this).width();
	if (spanWidth > sectionWidth) {
		var newFontSize = (sectionWidth/spanWidth) * originalFontSize;
		newFontSize = newFontSize-1;
			$(this).css({"font-size" : newFontSize, "line-height" : newFontSize/0.8 + "px", "color" : "white"});
		}
	});
}