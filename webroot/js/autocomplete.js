////file:app/webroot/js/application.js
$(document).ready(function(){
// Caching the movieName textbox:
var code = $('#code');

// Defining a placeholder text:
code.defaultText('Search for people');

// Using jQuery UI's autocomplete widget:
code.autocomplete({
minLength&nbsp;&nbsp; &nbsp;: 1,
source&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;: 'items/search'
});

});

// A custom jQuery method for placeholder text:

$.fn.defaultText = function(value){

var element = this.eq(0);
element.data('defaultText',value);

element.focus(function(){
if(element.val() == value){
element.val('').removeClass('defaultText');
}
}).blur(function(){
if(element.val() == '' || element.val() == value){
element.addClass('defaultText').val(value);
}
});

return element.blur();
}
