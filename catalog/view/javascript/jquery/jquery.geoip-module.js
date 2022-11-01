(function($) {

    var GeoIPModuleDialog = function(o, el) {
        this.o = $.extend({autoOpen: false}, o);
        this.el = el;
        this.prefix = 'geoip-';

        this.modal = $('<div class="' + this.prefix + 'modal ' + this.prefix + 'fade"></div>');
        this.backdrop = $('<div class="' + this.prefix + 'backdrop ' + this.prefix + 'fade"></div>').height($(window).height());
        this.dialog = $('<div class="' + this.prefix + 'modal-dialog"></div>');
        this.content = $('<div class="' + this.prefix + 'modal-content"></div>');
        var close = $('<div class="' + this.prefix + 'close">&times;</div>');
        this.content.append(close);
        $('body').append(this.modal.append(this.backdrop, this.dialog.append(this.content.append(el))));

        var self = this;

        $(window).resize(function() {
            self.backdrop.height($(window).height());
        });

        if (this.o.autoOpen) {
            this.open();
        } else {
            this.close();
        }

        close.click(function() {
            self.close();
        });

        this.backdrop.click(function() {
            self.close();
        });

        $(document).keydown(function(e) {
            if (e.which == 27) {
                self.close();
            }
        });
    };

    GeoIPModuleDialog.prototype.open = function() {
        $('body').addClass(this.prefix + 'modal-open');
        this.modal.addClass(this.prefix + 'in').show();
        this.backdrop.addClass(this.prefix + 'in');

        if (this.o.open) {
            this.o.open.apply(this);
        }
    };

    GeoIPModuleDialog.prototype.close = function() {
        $('body').removeClass(this.prefix + 'modal-open');
        this.modal.removeClass(this.prefix + 'in').hide();
        this.backdrop.removeClass(this.prefix + 'in');
    };

    var methods = {
        init: function(o) {
            return this.each(function() {
                var self = $(this),
                    data = self.data('GeoIPModuleDialog');

                if (!data) {
                    self.data('GeoIPModuleDialog', {target: self, obj: new GeoIPModuleDialog(o, self)});
                }
            });
        },
        open: function() {
            $(this).data('GeoIPModuleDialog').obj.open();
        },
        close: function() {
            $(this).data('GeoIPModuleDialog').obj.close();
        }
    };

    $.fn.geoipModuleDialog = function(method) {

        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist!');
        }

        return false;
    }
})(jQuery);

(function($) {
    var DialogCustom = {
        createConfirm: function(self) {
            self.confirmBlock.addClass('geoip-custom-popup');

            self.confirmBlock.append('<div class="geoip-confirm-buttons"><input type="button" class="geoip-confirm-yes" value="' + self.lang['btnYes'] + '">'
            + ' <input type="button" class="geoip-confirm-no" value="' + self.lang['btnNo'] + '"> </div>');

            self.confirmBlock.find('.geoip-confirm-yes').click(function() {
                self.confirmBlock.hide();
            });

            self.confirmBlock.find('.geoip-confirm-no').click(function() {
                self.confirmBlock.hide();
                DialogCustom.openChoose(self);
            });
        },
        createChoose:  function(self) {
            self.chooseBlock.addClass('geoip-custom-popup');
            self.chooseBlock.geoipModuleDialog();
        },
        openChoose:    function(self) {
            if (self.citiesLoaded) {
                self.chooseBlock.geoipModuleDialog('open');
            } else {
                self.loadCities(function() {
                    self.chooseBlock.geoipModuleDialog('open');
                });
            }
        },
        closeChoose:   function(self) {
           self.chooseBlock.geoipModuleDialog('close');
        }
    };

    var GeoIPModule = function(o, el) {
        this.o = $.extend({useAjax: false, confirmRegion: false, dialogView: 'custom', httpServer: location.host, lang: {}}, o);
        this.lang = this.o.lang;
        this.http_host = location.protocol + '//' + this.o.httpServer + '/';
        this.el = el;
        this.citiesLoaded = false;
        var self = this;
        var dialogs = DialogCustom;

        el.addClass('geoip-module').append('<div class="geoip-text"></div>');

        if (this.o.useAjax) {
            $.get(this.http_host + 'index.php?route=module/geoip/getCity',
                function(json) {
                    el.find('.geoip-text').html(self.lang['yourZone'] + ' <span class="zone">' + json.zone + '</span>');
                },
                'json'
            );
        } else {
            el.find('.geoip-text').html(self.lang['yourZone'] + ' <span class="zone">' + self.lang['zoneName'] + '</span>');
        }

        this.chooseBlock = $('<div class="geoip-choose-region"></div>');
        el.after(this.chooseBlock);
        dialogs.createChoose(this);

        this.chooseBlock.on('click', '.choose-city', function() {
            self.setRegion($(this).attr('data-id'));
            dialogs.closeChoose(self);
            return false;
        });

        el.on('click', '.geoip-text', function() {
            dialogs.openChoose(self);
        });

        if (this.o.confirmRegion) {
            this.confirmBlock = $('<div class="geoip-confirm-region">' + this.lang['confirmRegion'] + '</div>');
            el.append(this.confirmBlock);
            dialogs.createConfirm(self);
        }

        this.setRules();
    };

    GeoIPModule.prototype.loadCities = function(callback) {
        var self = this;
        if (!this.citiesLoaded) {
            $.ajax({
                url:      self.http_host + 'index.php?route=module/geoip/getList',
                dataType: 'html',
                success:  function(html) {
                    self.chooseBlock.html(html);
                    var input = self.chooseBlock.find('.geoip-popup-input');
                    self.autocomplete(input, self.chooseBlock.find('.geoip-block'));
                    input.siblings('ul.dropdown-menu').css({'maxHeight': 300, 'overflowY': 'auto', 'overflowX': 'hidden'});
                    input.focus();
                    self.citiesLoaded = true;
                    callback.apply();
                }
            });
        }
    };

    GeoIPModule.prototype.autocomplete = function(el, appendTo) {
        var xhr = false;
        var self = this;

        el.geoipAutocomplete({
            'source': function(request, response) {
                if (xhr) {
                    xhr.abort();
                }

                request = $.trim(request);
                if (request && request.length > 2) {
                    xhr = $.get(self.http_host + 'index.php?route=module/geoip/search&term=' + encodeURIComponent(request),
                        function(json) {
                            response(json);
                        }, 'json');
                }
                else {
                    response([]);
                }
            },
            'select': function(item) {
                el.val(item.value);
                self.setRegion(item.fias_id);
            }
        });
    };

    GeoIPModule.prototype.setRegion = function(id) {
        $.get(this.http_host + 'index.php?route=module/geoip/save&fias_id=' + id,
            function(json) {
                if (json.success) {
                    location.reload();
                }
            },
            'json'
        );
    };

    GeoIPModule.prototype.setRules = function() {
        if (this.o.useAjax) {
            $.get(this.http_host + 'index.php?route=module/geoip/getRules', function(json) {
                if (json.rules) {
                    $('.geoip-rule').each(function() {
                        if (json.rules[$(this).attr('data-key')]) {
                            $(this).after(json.rules[$(this).attr('data-key')]);
                        } else if ($(this).attr('data-default')) {
                            $(this).after($(this).attr('data-default'));
                        }
                        $(this).remove();
                    });
                } else {
                    $('.geoip-rule').each(function() {
                        if ($(this).attr('data-default')) {
                            $(this).after($(this).attr('data-default'));
                        }
                        $(this).remove();
                    });
                }
            }, 'json');
        }
    };

    var methods = {
        init: function(o) {
            return this.each(function() {
                var self = $(this),
                    data = self.data('GeoIPModule');

                if (!data) {
                    self.data('GeoIPModule', {target: self, obj: new GeoIPModule(o, self)});
                }
            });
        }
    };

    $.fn.geoipModule = function(method) {

        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist!');
        }

        return false;
    }
})(jQuery);

(function($) {
    $.fn.geoipAutocomplete = function(option) {
        return this.each(function() {
            this.timer = null;
            this.items = [];

            $.extend(this, option);

            $(this).attr('autocomplete', 'off');

            // Focus
            $(this).on('focus', function() {
                this.request();
            });

            // Blur
            $(this).on('blur', function() {
                setTimeout(function(object) {
                    object.hide();
                }, 200, this);
            });

            // Keydown
            $(this).on('keydown', function(event) {
                switch (event.keyCode) {
                    case 27: // escape
                        this.hide();
                        break;
                    default:
                        this.request();
                        break;
                }
            });

            // Click
            this.click = function(event) {
                event.preventDefault();

                value = $(event.target).parent().attr('data-value');

                if (value && this.items[value]) {
                    this.select(this.items[value]);
                }
            };

            // Show
            this.show = function() {
                var pos = $(this).position();

                $(this).siblings('ul.dropdown-menu').css({
                    top: pos.top + $(this).outerHeight(),
                    left: pos.left
                });

                $(this).siblings('ul.dropdown-menu').show();
            };

            // Hide
            this.hide = function() {
                $(this).siblings('ul.dropdown-menu').hide();
            };

            // Request
            this.request = function() {
                clearTimeout(this.timer);

                this.timer = setTimeout(function(object) {
                    object.source($(object).val(), $.proxy(object.response, object));
                }, 200, this);
            };

            // Response
            this.response = function(json) {
                html = '';

                if (json.length) {
                    for (i = 0; i < json.length; i++) {
                        this.items[json[i]['value']] = json[i];
                    }

                    for (i = 0; i < json.length; i++) {
                        if (!json[i]['category']) {
                            html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
                        }
                    }

                    // Get all the ones with a categories
                    var category = [];

                    for (i = 0; i < json.length; i++) {
                        if (json[i]['category']) {
                            if (!category[json[i]['category']]) {
                                category[json[i]['category']] = [];
                                category[json[i]['category']]['name'] = json[i]['category'];
                                category[json[i]['category']]['item'] = [];
                            }

                            category[json[i]['category']]['item'].push(json[i]);
                        }
                    }

                    for (i in category) {
                        html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

                        for (j = 0; j < category[i]['item'].length; j++) {
                            html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
                        }
                    }
                }

                if (html) {
                    this.show();
                } else {
                    this.hide();
                }

                $(this).siblings('ul.dropdown-menu').html(html);
            };

            $(this).after('<ul class="dropdown-menu"></ul>');
            $(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));
        });
    }
})(jQuery);