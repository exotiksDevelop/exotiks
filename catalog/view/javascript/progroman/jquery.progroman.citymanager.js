/* ProgRoman - CityManager v8.3 */
if (window.Prmn === undefined) { Prmn = {}; }

Prmn.CityManager = function(options) {
    this.switchers = [];
    this.messages = [];
    this.options = $.extend({}, Prmn.CityManager.DEFAULTS, options);
    this.url_module = this.getHttpHost() + 'index.php?route=extension/module/progroman/citymanager';
    this.cities_popup = null;
    this.confirm_shown = false;

    if ($('#prmn-cmngr-cities').length) {
        this.cities_popup = $('#prmn-cmngr-cities');
        this.initCitiesPopup();
    }
};

Prmn.CityManager.DEFAULTS = {
    base_path: 'auto'
};

Prmn.CityManager.prototype.addSwitcher = function(el) {
    this.switchers.push(new Prmn.CitySwitcher(el));
};

Prmn.CityManager.prototype.addMessage = function(el) {
    this.messages.push(new Prmn.CityMessage(el));
};

Prmn.CityManager.prototype.loadData = function() {
    var self = this, i;
    var need_ajax = this.messages.length > 0;

    if (!need_ajax) {
        for (i in this.switchers) {
            if (!this.switchers[i].loaded) {
                need_ajax = true;
            }
        }
    }
    if (need_ajax) {
        $.ajax({
            url: this.url_module + '/init',
            data: {url: location.pathname + location.search},
            dataType: 'json',
            cache: false,
            success: function(json) {
                var i, j;
                for (i in self.switchers) if (self.switchers.hasOwnProperty(i)) {
                    if (!self.switchers[i].loaded) {
                        self.switchers[i].setContent(json.content);
                    }
                }

                if (json.messages) {
                    for (i in json.messages) {
                        for (j in self.messages) if (self.messages.hasOwnProperty(j)) {
                            if (self.messages[j].key === i) {
                                self.messages[j].setContent(json.messages[i]);
                            }
                        }
                    }
                }

                for (i in self.messages) if (self.messages.hasOwnProperty(i)) {
                    self.messages[i].setDefault();
                }
            }
        });
    }
};

Prmn.CityManager.prototype.showCitiesPopup = function() {
    if (this.cities_popup !== 'loading') {
        var self = this;
        if (!this.cities_popup) {
            this.cities_popup = 'loading';
            $.ajax({
                url: this.url_module + '/cities',
                dataType: 'html',
                cache: false,
                success: function (html) {
                    self.hideAllConfirm();
                    self.cities_popup = $(html);
                    $('body').append(self.cities_popup);
                    self.initCitiesPopup();
                    self.cities_popup.modal('show');
                }
            });
        } else {
            self.hideAllConfirm();
            self.cities_popup.modal('show');
        }
    }
};

Prmn.CityManager.prototype.initCitiesPopup = function() {
    var self = this;
    this.cities_popup.find('.prmn-cmngr-cities__city-name').click(function(ev) {
        ev.preventDefault();
        self.setFias($(this).data('id'));
        self.cities_popup.modal('hide');
    });
    this.autocomplete(this.cities_popup.find('.prmn-cmngr-cities__search'));
    self.cities_popup.on('shown.bs.modal', function() {
        self.cities_popup.find('.prmn-cmngr-cities__search').focus();
    });
};

Prmn.CityManager.prototype.hideAllConfirm = function() {
    var i;
    for (i in this.switchers) {
        this.switchers[i].hideConfirm();
    }
};

Prmn.CityManager.prototype.autocomplete = function(el) {
    var self = this;
    el.prmnAutocomplete({
        'source': self.url_module + '/search',
        'select': function(item) {
            el.val(item.name);
            self.setFias(item.value);
            self.cities_popup.modal('hide');
        }
    });
    el.siblings('ul.dropdown-menu').css({'maxHeight': 300, 'overflowY': 'auto', 'overflowX': 'hidden'});
};

Prmn.CityManager.prototype.setFias = function(id) {
    $.ajax({
        url: this.url_module + '/save&fias_id=' + id,
        dataType: 'json',
        cache: false,
        success: function(json) {
            if (json.success) {
                location.reload();
            }
        }
    });
};

Prmn.CityManager.prototype.getHttpHost = function() {
    if (!Prmn.CityManager.host) {
        Prmn.CityManager.host = location.protocol + '//' + location.host + '/';

        if (this.options.base_path === 'auto') {
            var base = $('base').attr('href'), matches;
            if (base && (matches = base.match(/^http(?:s)?:\/\/[^/]*\/(.*)/))) {
                Prmn.CityManager.host += matches[1];
            }
        } else if (this.options.base_path) {
            Prmn.CityManager.host += this.options.base_path;
        }
    }

    return Prmn.CityManager.host;
};

Prmn.CityManager.prototype.confirmShown = function() {
    if (!this.confirm_shown) {
        this.confirm_shown = true;
        $.get(this.url_module + '/confirmshown');
    }
};

/**
 * CitySwitcher
 * @constructor
 */
Prmn.CitySwitcher = function(el) {
    this.$element = el;
    this.loaded = !el.is(':empty');
    this.confirm = el.find('.prmn-cmngr__confirm');
    var self = this;

    el.on('click', '.prmn-cmngr__city', function() {
        Prmn.citymanager.showCitiesPopup();
    });

    el.on('click', '.prmn-cmngr__confirm-btn', function() {
        $.get(Prmn.citymanager.url_module + '/confirmclick');

        if ($(this).data('value') === 'no') {
            Prmn.citymanager.showCitiesPopup();
        } else if ($(this).data('redirect')) {
            location.href = $(this).data('redirect');
        }

        Prmn.citymanager.hideAllConfirm();
    });

    this.showConfirm();
};

Prmn.CitySwitcher.prototype.setContent = function(html) {
    if (!this.loaded) {
        html = $(html);
        this.$element.html(html);
        this.loaded = true;
        this.confirm = this.$element.find('.prmn-cmngr__confirm');
        this.showConfirm();
    }
};

Prmn.CitySwitcher.prototype.showConfirm = function() {
    if (this.confirm.length) {
        Prmn.citymanager.confirmShown();

        if (!(this.$element.data('confirm') === false)) {
            this.confirm.show();
        } else {
            this.confirm.remove();
        }
    }
};

Prmn.CitySwitcher.prototype.hideConfirm = function() {
    this.confirm.hide();
};

/**
 * CityMessage
 * @constructor
 */
Prmn.CityMessage = function(el) {
    this.$element = el;
    this.key = el.data('key');
    this.default = el.data('default');
    this.$element.removeAttr('data-key').removeAttr('data-default');
    this.found = false;
};

Prmn.CityMessage.prototype.setContent = function(html) {
    this.$element.html(html);
    this.found = true;
};

Prmn.CityMessage.prototype.setDefault = function() {
    if (!this.found) {
        this.$element.html(this.default);
    }
};

$(function() {
    var switchers = $('.prmn-cmngr'), messages = $('.prmn-cmngr-message');
    if (switchers.length || messages.length) {
        Prmn.citymanager = Prmn.city_manager = new Prmn.CityManager();
        switchers.each(function() { Prmn.citymanager.addSwitcher($(this)); });
        messages.each(function() { Prmn.citymanager.addMessage($(this)); });
        Prmn.citymanager.loadData();
    }
});
