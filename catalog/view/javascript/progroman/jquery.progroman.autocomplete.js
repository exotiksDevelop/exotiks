if (window.Prmn === undefined) { Prmn = {}; }

Prmn.Autocomplete = function(options, el) {
    this.o = $.extend({'source': []}, options);
    this.el = el;
    this.timer = null;
    this.xhr = null;
    this.items = [];
    this.index;
    this.total;
    var self = this;

    el.attr('autocomplete', 'off').after('<ul class="dropdown-menu prmn-cmngr-cities__search-items"></ul>');
    this.dropdown = el.siblings('ul.dropdown-menu').on({
        mouseenter: function () {
            $(this).addClass('prmn-cmngr-cities__search-item_selected');
            self.setSelected($(this).index());
        },
        mouseleave: function () {
            $(this).removeClass('prmn-cmngr-cities__search-item_selected');
            self.setSelected($(this).index());
        },
        click: function () {
            var value = $(this).data('value');
            if (value && self.items[value]) {
                self.select(self.items[value]);
            }
        }
    }, '.prmn-cmngr-cities__search-item');

    el.on('focus', function() {
        self.request();
    }).on('keydown', function(event) {
        switch (event.keyCode) {
            case 13: //enter
                var value = self.dropdown.find('.prmn-cmngr-cities__search-item').eq(self.index).data('value');
                if (value && self.items[value]) {
                    self.select(self.items[value]);
                }
                break;
            case 27: // escape
                self.hide();
                break;
            case 38: // up
                self.up();
                break;
            case 40: // down
                self.down();
                break;
            default:
                self.request();
                break;
        }
    });
};

Prmn.Autocomplete.prototype.show = function() {
    var pos = this.el.position();

    this.el.siblings('ul.dropdown-menu').css({
        top: pos.top + this.el.outerHeight(),
        left: pos.left
    });

    this.el.siblings('ul.dropdown-menu').show();
    var self = this;

    $(document).on('click.prmnautocomplete', function(e) {
        if (!$(e.target).parents('.prmn-cmngr-cities__search-block').length) {
            self.hide();
        }
    });
};

Prmn.Autocomplete.prototype.hide = function() {
    this.el.siblings('ul.dropdown-menu').hide();
    $(document).off('click.prmnautocomplete');
};

Prmn.Autocomplete.prototype.select = function(item) {
    if (this.o.select) {
        this.o.select.call(this, item);
    }

    this.hide();
};

Prmn.Autocomplete.prototype.up = function() {
    if (this.index > 0) {
        this.setSelected(this.index - 1);
    } else {
        this.setSelected(this.total - 1);
    }

    this.scrollToSelected();
};

Prmn.Autocomplete.prototype.down = function() {
    if (this.index === null || this.index >= (this.total - 1)) {
        this.setSelected(0);
    } else {
        this.setSelected(this.index + 1);
    }

    this.scrollToSelected();
};

Prmn.Autocomplete.prototype.setSelected = function(index) {
    this.index = index;
    this.dropdown.find('.prmn-cmngr-cities__search-item_selected').removeClass('prmn-cmngr-cities__search-item_selected');
    this.dropdown.find('.prmn-cmngr-cities__search-item').eq(this.index).addClass('prmn-cmngr-cities__search-item_selected');
};

Prmn.Autocomplete.prototype.scrollToSelected = function() {
    var selected = this.dropdown.find('.prmn-cmngr-cities__search-item').eq(this.index);
    if (selected.length) {
        var height = selected.height();
        var top = height * this.index;

        if (top < this.dropdown.scrollTop() || top > (this.dropdown.scrollTop() + this.dropdown.height() - height)) {
            this.dropdown.scrollTop(top - this.dropdown.height() / 2 + height);
        }
    }
};

Prmn.Autocomplete.prototype.request = function() {
    clearTimeout(this.timer);
    this.timer = setTimeout(function(self) {
        self.search(self.el.val(), $.proxy(self.response, self));
    }, 200, this);
};

Prmn.Autocomplete.prototype.response = function(json) {
    var html = '', i;
    this.index = null;
    this.total = 0;
    if (json.length) {
        for (i = 0; i < json.length; i++) {
            this.items[json[i]['value']] = json[i];
            html += '<li class="prmn-cmngr-cities__search-item" data-value="' + json[i]['value'] + '"><span>' + json[i]['label'] + '</span></li>';
        }
        this.total = json.length;
    }
    html ? this.show() : this.hide();
    this.el.siblings('ul.dropdown-menu').html(html);
};

Prmn.Autocomplete.prototype.search = function(term, response) {
    if (typeof this.o.source == 'string') {
        if (this.xhr) {
            this.xhr.abort();
        }

        term = $.trim(term);
        if (term && term.length > 2) {
            this.xhr = $.get(this.o.source,
                {"term": term},
                function(json) {
                    response(json);
                }, 'json');
        } else {
            response([]);
        }
    }
};

(function($) {
    $.fn.prmnAutocomplete = function(options) {
        return this.each(function() {
            var self = $(this),
                data = self.data('PrmnAutocomplete');

            if (!data) {
                self.data('PrmnAutocomplete', {target: self, obj: new Prmn.Autocomplete(options, self)});
            }
        });
    };
})(jQuery);