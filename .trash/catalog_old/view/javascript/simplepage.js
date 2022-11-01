(function($) {
    window.Simplepage = function(params) {
        this.params = params;

        this.selectors = {
            agreementCheckBox: "#agreement_checkbox",
            agreementWarning: "#agreement_warning",
            warning: ".warning"
        };

        this.callback = params.javascriptCallback || function() {};

        this.formSubmitted = false;
        this.popup = false;

        this.callFunc = function(func, $target) {
            var self = this;

            if (func && typeof self[func] === "function") {
                self[func]($target);
            } else if (func) {
                //console.log(func + " is not registered");
            }
        };

        this.init = function(popup) {
            var self = this;

            var callbackForComplexField = function($target) {
                var func = $target.attr("data-onchange");
                if (!func) {
                    func = $target.attr("data-onchange-delayed");
                }
                if (func && typeof self[func] === "function") {
                    self[func]($target);
                } else if (func) {
                    //console.log(func + " is not registered");
                }
            };

            if (popup) {
                self.popup = true;
            }

            self.requestTimerId = 0;

            if (self.params.useGoogleApi) {
                self.initGoogleApi(callbackForComplexField);
            }

            if (self.params.useAutocomplete) {
                self.initAutocomplete(callbackForComplexField);
            }

            var $redirect = $(self.params.mainContainer).find('#simple_redirect_url');

            if ($redirect.length) {
                window.location = $redirect.val();
            }

            self.checkIsHuman();
            self.initPopups();
            self.initMasks();
            self.initTooltips(!self.params.useAutocomplete);
            self.initDatepickers(callbackForComplexField);
            self.initTimepickers(callbackForComplexField);
            self.initSelect2();
            self.initFileUploader(function() {
                self.overlay();
            }, function() {
                self.removeOverlay();
            });
            self.initHandlers();
            self.scroll();
            self.displayWarning();
            self.initValidationRules();

            if (typeof self.callback === "function") {
                self.callback();
            }
        };

        this.initHandlers = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);

            $mainContainer.find("*[data-onchange], *[data-onclick]").each(function() {
                var $element = $(this);

                var funcOnChange = $element.attr("data-onchange");
                if (funcOnChange) {
                    $element.on("change", function() {
                        self.callFunc(funcOnChange, $element);
                    });
                }

                var funcOnClick = $element.attr("data-onclick");
                if (funcOnClick) {
                    $element.on("click", function() {
                        self.callFunc(funcOnClick, $element);
                    });
                }
            });

            $mainContainer.submit(function(event) {
                self.requestReloadAll();
                event.preventDefault();
                return false;
            });
        };

        this.addSystemFieldsInForm = function() {
            var self = this;
            if (self.formSubmitted) {
                $(self.params.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "submitted").val(1));
            }
        };

        this.validateAgreements = function() {
            var self = this;

            var result = true;

            var $agreementCheckbox = $(self.params.mainContainer).find(self.selectors.agreementCheckBox).find("input[type=checkbox]");
            var $agreementCheckboxChecked = $(self.params.mainContainer).find(self.selectors.agreementCheckBox).find("input[type=checkbox]:checked");
            var $agreementWarning = $(self.params.mainContainer).find(self.selectors.agreementWarning);

            if ($agreementCheckbox.length && $agreementCheckbox.is(":visible") && $agreementCheckbox.length != $agreementCheckboxChecked.length) {
                if ($agreementWarning.length) {
                    if (self.params.notificationDefault) {
                        $agreementWarning.show();

                        $agreementCheckbox.each(function() {
                            if ($(this).is(":checked")) {
                                $(".agreement_" + $(this).val()).hide();
                            } else {
                                $(".agreement_" + $(this).val()).show();
                            }                        
                        });
                    }

                    if (self.params.notificationToasts) {
                        $agreementCheckbox.each(function() {
                            if (!$(this).is(":checked")) {
                                toastr.error($(".agreement_" + $(this).val()).text());
                            }                        
                        });
                    }

                    result = false;
                }
            } else {
                $agreementWarning.hide();
            }

            return result;
        };

        this.validate = function() {
            var self = this;
            var promises = [];
            var result = true;

            if (!self.validateAgreements()) {
                result = false;
            }

            var promise = self.checkRules().then(function(r) {
                if (!r) {
                    result = false
                }
            });

            var deferred = $.Deferred();

            $.when(promise).then(function() {
                deferred.resolve(result);
            });

            return deferred.promise();
        };

        this.submit = function() {
            var self = this;

            self.validate().then(function(result) {
                if (result) {
                    self.formSubmitted = true;
                    $(self.params.mainContainer).submit();
                } else {
                    self.scroll();

                    if (typeof toastr !== 'undefined' && self.params.notificationCheckForm) {
                        toastr.error(self.params.notificationCheckFormText);
                    }
                }
            });
        };

        this.openLoginBox = function() {
            window.location = this.params.loginLink;
        };

        /**
         * Adds delay for reload execution on 150 ms, it allows to check sequence of events and to execute only the last request to handle of more events in one reloading
         * @param  {Function} callback
         */
        this.requestReloadAll = function(callback) {
            var self = this;
            if (self.requestTimerId) {
                clearTimeout(self.requestTimerId);
                self.requestTimerId = 0;
            }
            self.requestTimerId = window.setTimeout(function() {
                self.reloadAll(callback);
            }, 150);
        };

        this.overlay = function() {
            var self = this;
            var $block = $(self.params.mainContainer);
            if ($block.length) {
                $block.find("input,select,textarea").attr("disabled", "disabled");
                $block.append(
                    $("<div>")
                        .addClass("simplepage_overlay")
                        .attr("id", $block.attr("id") + "_overlay")
                        .css({
                            "background": "url(" + self.params.additionalPath + self.resources.loading + ") no-repeat center center",
                            "opacity": 0.4,
                            "position": "absolute",
                            "width": $block.width(),
                            "height": $block.height(),
                            "z-index": 5000
                        })
                );

                var $overlay = $("#"+$block.attr("id") + "_overlay");

                $overlay.offset({
                    top: $block.offset().top,
                    left: $block.offset().left
                });
            }
        };

        this.removeOverlay = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);

            $mainContainer.find("input,select,textarea").removeAttr("disabled");
            $mainContainer.find(".simplepage_overlay").remove();
        };

        this.displayWarning = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);

            var selectors = [self.selectors.warning, self.selectors.agreementWarning]
                .map(function(item) {
                    return item + ":visible";
                })
                .join(",");

            if ($mainContainer.find(selectors).length) {
                if (typeof toastr !== 'undefined') {
                    if (self.params.notificationToasts) {
                        toastr.error($mainContainer.find(selectors).text());
                    }

                    if (self.params.notificationCheckForm) {
                        toastr.error(self.params.notificationCheckFormText);
                    }                    
                }

                if (!self.params.notificationDefault) {
                    $mainContainer.find(selectors).hide();
                }
            }
        },

        this.scroll = function() {
            var self = this,
                error = false,
                top = 10000,
                bottom = 0;

            var $mainContainer = $(self.params.mainContainer);

            var isOutsideOfVisibleArea = function(y) {
                if (y < $(document).scrollTop() || y > ($(document).scrollTop() + $(document).height())) {
                    return true;
                }
                return false;
            };

            if (self.popup) {
                return;
            }

            if (self.params.scrollToError) {
                $($mainContainer.find(".simplecheckout-rule:visible, .has-error")).each(function() {
                    if ($(this).parents(".simpleregister-block-content").length) {
                        var offset = $(this).parents(".simpleregister-block-content").offset();
                        if (offset.top < top) {
                            top = offset.top;
                        }
                        if (offset.bottom > bottom) {
                            bottom = offset.bottom;
                        }
                    }
                });

                if ($mainContainer.find(".warning").length) {
                    var offset = $mainContainer.find(".warning").offset();
                    if (offset.top < top) {
                        top = offset.top;
                    }
                    if (offset.bottom > bottom) {
                        bottom = offset.bottom;
                    }
                }

                if (top < 10000 && isOutsideOfVisibleArea(top)) {
                    jQuery("html, body").animate({
                        scrollTop: top
                    }, "slow");
                    error = true;
                } else if (bottom && isOutsideOfVisibleArea(bottom)) {
                    jQuery("html, body").animate({
                        scrollTop: bottom
                    }, "slow");
                    error = true;
                }
            }
        };

        this.reloadAll = function(callback) {
            var self = this;
            var postData;
            if (self.isReloading) {
                return;
            }
            self.addSystemFieldsInForm();
            self.isReloading = true;
            postData = $(self.params.mainContainer).find("input,select,textarea").serialize();

            var overlayTimeoutId = 0;

            $.ajax({
                url: self.params.mainUrl,
                data: postData + "&simple_ajax=1",
                type: "POST",
                dataType: "text",
                beforeSend: function() {
                    $('.tooltip ').remove();
                    
                    overlayTimeoutId = window.setTimeout(function() {
                        if (overlayTimeoutId) {
                            self.overlay();
                        }
                    }, 250);
                },
                success: function(data) {
                    clearTimeout(overlayTimeoutId);
                    overlayTimeoutId = 0;
                    var newData = $(self.params.mainContainer, $(data)).get(0);
                    if (!newData && data) {
                        newData = data;
                    }
                    $(self.params.mainContainer).replaceWith(newData);
                    self.init();
                    if (typeof callback === "function") {
                        callback.call(self);
                    }
                    self.removeOverlay();
                    self.isReloading = false;
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    clearTimeout(overlayTimeoutId);
                    overlayTimeoutId = 0;
                    self.removeOverlay();
                    self.isReloading = false;
                }
            });
        };

        this.instances.push(this);
    };

    Simplepage.prototype = inherit(window.Simple.prototype);
})(jQuery);