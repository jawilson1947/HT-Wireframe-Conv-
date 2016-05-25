function autodeskFacetSearchValidateUserInput(form){
  field_id = autodeskFacetSearchGetFieldId(form.elements);
  var userinput = form[field_id].value;

  if (userinput == null || userinput == "") {
      return false;
  }
  var trimuserinput = userinput.replace(/^\s+|\s+$/g,"");
  if (trimuserinput == null || trimuserinput == "") {
      return false;
  }
}

function autodeskFacetSearchGetFieldId(elements){
  for (i = 0; i < elements.length; i++) {
    for (j = 0; j < elements[i].attributes.length; j++) {
      if (elements[i].attributes[j].name == 'name') {
        if (elements[i].attributes[j].value == 'search') {
          for (k = 0; k < elements[i].attributes.length; k++) {
            if (elements[i].attributes[k].name == 'id') {
              return elements[i].attributes[k].value;
            }
          }
        }
      }
    }
  }
  return "";
}