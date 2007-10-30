var RuledList = Class.create();
RuledList.prototype = {
  
  initialize: function(element_id) {
    this.setupRows(element_id);
  },
  
  onMouseOverRow: function(event) {
    this.className = this.className.replace(/\s*\bhighlight\b|$/, ' highlight');
  },
  
  onMouseOutRow: function(event) {
    this.className = this.className.replace(/\s*\bhighlight\b\s*/, ' ');
  },
  
  setupRows: function(element_id) {
    var list = $(element_id);
    this.setupRow(list);
    var rows = list.getElementsByTagName('LI');
    for (var i = 0; i < rows.length; i++) {
      this.setupRow(rows[i]);
    }
  },
  
  setupRow: function(row) {
    Event.observe(row, 'mouseover', this.onMouseOverRow.bindAsEventListener(row));
    Event.observe(row, 'mouseout', this.onMouseOutRow.bindAsEventListener(row));
    if (this.onRowSetup) this.onRowSetup(row);
  }
  
};