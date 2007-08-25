var SiteMap = Class.create();
SiteMap.prototype = Object.extend({}, RuledList.prototype); // Inherit from RuledList

Object.extend(SiteMap.prototype, {
  
  ruledListInitialize: RuledList.prototype.initialize,
  
  initialize: function(id, expanded) {
    this.ruledListInitialize(id);
    this.expandedRows = expanded;
  },
  
  onRowSetup: function(row) {
    var toggler = row.getElementsByTagName('img')[0];
    Event.observe(toggler, 'click', this.onMouseClickRow.bindAsEventListener(this), false);
  },
  
  onMouseClickRow: function(event) {
    var element = Event.element(event);
    if (this.isExpander(element)) {
      var row = Event.findElement(event, 'li');
      if (this.hasChildren(row)) {
        this.toggleBranch(row, element);
      }
    }
  },
  
  hasChildren: function(row) {
    return ! /\bno-children\b/.test(row.className);
  },
  
  isExpander: function(element) {
    return (element.tagName.strip().downcase() == 'img') && /\bexpander\b/i.test(element.className);
  },
  
  isExpanded: function(row) {
    return /\bchildren-visible\b/i.test(row.className);
  },
  
  isRow: function(element) {
    return element.tagName && (element.tagName.strip().downcase() == 'li');
  },
  
  extractLevel: function(row) {
    if (/level-(\d+)/i.test(row.className))
      return RegExp.$1.toInteger();
  },
  
  extractPageId: function(row) {
    if (/page_(\d+)/i.test(row.id))
      return RegExp.$1.toInteger();
  },
  
  getExpanderImageForRow: function(row) {
    var images = $A(row.getElementsByTagName('img', row));
    var expanders = [];
    images.each(function(image){
      expanders.push(image);
    }.bind(this));
    return expanders.first();
  },
  
  saveExpandedCookie: function() {
    document.cookie = "expanded_rows=" + this.expandedRows.uniq().join(",");
  }, 
  
  hideBranch: function(row, img) {
    var level = this.extractLevel(row);
    
    for (var i = row.childNodes.length-1; i>=0; i--) {
        if (row.childNodes[i].nodeName == 'UL') {
            Element.hide(row.childNodes[i]);
            break;
        }
    }

    var pageId = this.extractPageId(row);
    var newExpanded = [];
    
    for (i = 0; i < this.expandedRows.length; i++) {
      if (this.expandedRows[i] != pageId) {
        newExpanded.push(this.expandedRows[i]);
      }
    }
    
    this.expandedRows = newExpanded;
    this.saveExpandedCookie();
    
    if (img == null) {
      img = this.getExpanderImageForRow(row);
    }
    
    img.src = img.src.replace(/collapse/, 'expand');
    row.className = row.className.replace(/children-visible/, 'children-hidden');
  },
  
  showBranchInternal: function(row, img) {
    var level = this.extractLevel(row);
    var children = false;

    for (var i=row.childNodes.length-1; i>=0; i--) {
        if (row.childNodes[i].nodeName == 'UL') {
            Element.show(row.childNodes[i]);
            children = true;
            break;
        }
    }

    if ( ! children)
      this.getBranch(row);

    if (img == null)
      img = this.getExpanderImageForRow(row);

    img.src = img.src.replace(/expand/, 'collapse');
    row.className = row.className.replace(/children-hidden/, 'children-visible');
  },
  
  showBranch: function(row, img) {
    this.showBranchInternal(row, img);
    this.expandedRows.push(this.extractPageId(row));
    this.saveExpandedCookie();
  },
  
  getBranch: function(row) {
    var level = this.extractLevel(row).toString();
    var id = this.extractPageId(row).toString();
    new Ajax.Updater(
      row,
      '?/pages/children/' + id + '/' + level,
      {
        evalScripts: true,
        asynchronous: true,
        insertion: Insertion.EndOfRow,
        onLoading: function(request) {
          Element.show('busy-' + id);
          this.updating = true;
        }.bind(this),
        onComplete: function(request) {
          this.setupRow(row);
          this.updating = false;
          Effect.Fade('busy-' + id);
        }.bind(this)
      }
    );
  },
  
  toggleBranch: function(row, img) {
    if (! this.updating) {
      if (this.isExpanded(row)) {
        this.hideBranch(row, img);
      } else {
        this.showBranch(row, img);
      }
    }
  }

});

Insertion.EndOfRow = Class.create();
Insertion.EndOfRow.prototype = {
  initialize: function(element, content) {
    this.element = $(element);
    this.content = content.stripScripts();

    this.element.innerHTML += this.content;

    setTimeout(function() {content.evalScripts()}, 10);
  }
};