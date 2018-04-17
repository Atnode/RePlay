function bbcode(debutbbcode, finbbcode, textareaId) {
    var field  = document.getElementById(textareaId);
    var scroll = field.scrollTop;
    field.focus(); 
        
	    var startSelection   = field.value.substring(0, field.selectionStart);
        var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
        var endSelection     = field.value.substring(field.selectionEnd);
                
        field.value = startSelection + debutbbcode + currentSelection + finbbcode + endSelection;
        field.focus();
        field.setSelectionRange(startSelection.length + debutbbcode.length, startSelection.length + debutbbcode.length + currentSelection.length);

    field.scrollTop = scroll;
}

function smileys(){
  $('.smileys').toggle();
}

