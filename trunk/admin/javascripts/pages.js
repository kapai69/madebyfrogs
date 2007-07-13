function part_added() {
  var partNameField = $('part-name-field');
  var partIndexField = $('part-index-field');
  var index = parseInt(partIndexField.value);
  var tab = 'tab-' + index;
  var caption = partNameField.value;
  var page = 'page-' + index;
  tabControl.addTab(tab, caption, page);
  Element.hide('add-part-popup');
  Element.hide('busy');
  partNameField.value = '';
  partIndexField.value = (index + 1).toString();
  $('add-part-button').disabled = false;
  Field.focus(partNameField);
  tabControl.select(tab);
}
function part_loading() {
  $('add-part-button').disabled = true;
  new Effect.Appear('busy');
}
function valid_part_name() {
  var partNameField = $('part-name-field');
  var name = partNameField.value.downcase().strip();
  var result = true;
  if (name == '') {
    alert('Part name cannot be empty.');
    return false;
  }
  tabControl.tabs.each(function(pair){
    if (tabControl.tabs[pair.key].caption == name) {
      result = false;
      alert('Part name must be unique.');
      throw $break;
    }
  })
  return result;
}
function center(element) {
  var header = $('header')
  element = $(element);
  element.style.position = 'fixed'
  var dim = Element.getDimensions(element)
  element.style.top = '200px';
  element.style.left = ((header.offsetWidth - dim.width) / 2) + 'px';
}
function toggle_add_part_popup() {
  var popup = $('add-part-popup');
  var partNameField = $('part-name-field');
  center(popup);
  Element.toggle(popup);
  Field.focus(partNameField);
}
function toggle_chmod_popup(filename) {
  var popup = $('chmod-popup');
  var file_mode = $('chmod_file_mode');
  $('chmod_file_name').value = filename;
  center(popup);
  Element.toggle(popup);
  Field.focus(file_mode);
}
function toggle_popup(id, focus_field) {
  var popup = $(id);
  focus_field = $(focus_field);
  center(popup);
  Element.toggle(popup);
  Field.focus(focus_field);
}