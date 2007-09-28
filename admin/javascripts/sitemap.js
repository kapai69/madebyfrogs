var SiteMap = Class.create();
SiteMap.prototype = Object.extend({}, RuledList.prototype);

Object.extend(SiteMap.prototype, {
  
  ruledListInitialize: RuledList.prototype.initialize,
  
  initialize: function(id, expanded) {
    this.id = id;
    this.ruledListInitialize(id);
    this.expandedRows = expanded;
    this.sortablize();
  },
  
  sortablize: function() {
    Sortable.destroy(this.id);
    Sortable.create(this.id, { constraint:'vertical', scroll:window, handle:'handle', tree:true,
      onChange: SiteMap.prototype.adjustLevelOf,
      onUpdate: SiteMap.prototype.update
    });
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
      '?/page/children/' + id + '/' + level,
      {
        evalScripts: true,
        asynchronous: true,
        insertion: Insertion.EndOfRow,
        onLoading: function(request) {
          Element.show('busy-' + id);
          this.updating = true;
        }.bind(this),
        onComplete: function(request) {
          this.setupRows(row);
          this.sortablize();
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
  },
  
  adjustLevelOf: function(element) {
    // this will make the page displayed at the level + 1 of the parent
    var currentLevel = 1;
    var parentLevel = 0;
    currentElementSelected = element;
    
    if (/level-(\d+)/i.test(element.className))
      currentLevel = RegExp.$1.toInteger();
      
    if (/level-(\d+)/i.test(element.parentNode.parentNode.className))
      parentLevel = RegExp.$1.toInteger();

    if (currentLevel != parentLevel+1) {
       Element.removeClassName(element, 'level-'+currentLevel);
       Element.addClassName(element, 'level-'+(parentLevel+1));
    }
    // this will update all childs level
    var container = Element.findChildren(element, false, false, 'ul');
    if (container.length == 1) {
        var childs = Element.findChildren(container[0], false, false, 'li');
        for (var i=0; i < childs.length; i++) {
            childs[i].className = childs[i].className.replace(/level-(\d+)/, 'level-'+(parentLevel+2));
        }
    }
  },
  
  update: function() {
    var parent = currentElementSelected.parentNode;
    var parent_id = 1;
    var pages = [];
    var data = '';
    
    if (/page_(\d+)/i.test(currentElementSelected.parentNode.parentNode.id)) {
      parent_id = RegExp.$1.toInteger();
    }
    
    pages = Element.findChildren(parent, false, false, 'li');
    
    for(var i=0; i<pages.length; i++) {
      data += 'pages[]='+SiteMap.prototype.extractPageId(pages[i])+'&';
    }
    
    new Ajax.Request('?/page/reorder/'+parent_id, {method: 'post', parameters: { 'data': data }});
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