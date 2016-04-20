$(document).ready(function(){
	restore_position();
});

$( window ).unload(function() {
	save_position();
});

var xKey = '';
var yKey = '';

function setKeys() {
	path = location.pathname;
	index = path.search("page:");
	if(index != -1) {
		path= path.substring(0,index-1);
	}
	xKey = path+'/xPos';
	yKey = path+'/yPos';
}

function restore_position() {
	setKeys();
		
	var xPos = sessionStorage.getItem(xKey);
	var yPos = sessionStorage.getItem(yKey); 
	window.scrollTo(xPos, yPos); 
} 

function save_position() {		
	setKeys();
		
	var xPos  = document.body.scrollLeft;	
	var yPos  = document.body.scrollTop;
	
	sessionStorage.setItem(xKey, xPos);
	sessionStorage.setItem(yKey, yPos);	
}
