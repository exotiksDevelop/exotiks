(function($) {
    window.Simplecheckout = function(params) {
        this.params = params;

        this.callback = params.javascriptCallback || function() {};

        this.selectors = {
            paymentForm: "#simplecheckout_payment_form",
            paymentButtons: "#simplecheckout_payment_form div.buttons:last",
            step: ".simplecheckout-step",
            buttons: "#buttons",
            buttonPrev: "#simplecheckout_button_prev",
            buttonNext: "#simplecheckout_button_next",
            buttonCreate: "#simplecheckout_button_confirm",
            buttonBack: "#simplecheckout_button_back",
            stepsMenu: "#simplecheckout_step_menu",
            stepsMenuItem: ".simple-step",
            stepsMenuDelimiter: ".simple-step-delimiter",
            stepsMenuTop: ".simplecheckout-top-menu",
            stepsMenuBottom: ".simplecheckout-bottom-menu",
            stepsMenuVerticalItem: ".simple-step-vertical",
            proceedText: "#simplecheckout_proceed_payment",
            agreementCheckBox: "#agreement_checkbox",
            agreementWarning: "#agreement_warning",
            block: ".simplecheckout-block",
            overlay: ".simplecheckout_overlay"
        };

        this.classes = {
            stepsMenuCompleted: "simple-step-completed",
            stepsMenuCurrent: "simple-step-current",
            stepsMenuVerticalCompleted: "simple-step-vertical-completed"
        };

        this.blocks = [];
        this.$steps = [];
        this.requestTimerId = 0;
        this.currentStep = 1;
        this.saveStepNumber = this.currentStep;
        this.stepReseted = false;
        this.formSubmitted = false;
        this.backCount = -1;
        this.$paymentForm = false;
        this.storageUsed = false;

        var checkIsInContainer = function($element, selector) {
            if ($element.parents(selector).length) {
                return true;
            }
            return false;
        };

        this.callFunc = function(func, $target) {
            var self = this;

            if (func && typeof self[func] === "function") {
                self[func]($target);
            } else if (func) {
                //console.log(func + " is not registered");
            }
        };

        this.registerBlock = function(object) {
            var self = this;
            object.setParent(self);
            self.blocks.push(object);
        };

        this.initBlocks = function() {
            var self = this;
            for (var i in self.blocks) {
                if (!self.blocks.hasOwnProperty(i)) continue;

                self.blocks[i].init();
            }
        };

        this.init = function(disableScroll, changeStep) {
            var self = this;

            if (typeof disableScroll === "undefined") {
                disableScroll = false;
            }

            var callbackForComplexField = function($target) {
                self.focusedFieldId = ''
                var func = $target.attr("data-onchange");
                if (!func) {
                    func = $target.attr("data-onchange-delayed");
                }
                if (func && typeof self[func] === "function") {
                    self[func]($target);
                } else if (func) {
                    //console.log(func + " is not registered");
                }
                self.setDirty();
            };

            self.requestTimerId = 0;

            if (!self.isPaymentFormEmpty()) {
                if (self.$paymentForm) {
                    self.restorePaymentForm(self.$paymentForm);
                }
            } else {
                self.$paymentForm = false;
            }

            if (self.params.useGoogleApi) {
                self.initGoogleApi(callbackForComplexField);
            }

            if (self.params.useAutocomplete) {
                self.initAutocomplete(callbackForComplexField);
            }

            self.checkIsHuman();
            self.addObserver();
            self.initPopups();
            self.initMasks();
            self.initTooltips(!self.params.useAutocomplete);
            self.initDatepickers(callbackForComplexField);
            self.initTimepickers(callbackForComplexField);
            self.initSelect2();
            self.initFileUploader(function() {
                self.overlayAll();
            }, function() {
                self.removeOverlays();
                self.setDirty();
            });
            self.initHandlers();
            self.initBlocks();
            self.initSteps(changeStep);

            self.initAbandonedCart();

            if (!disableScroll) {
                self.scroll();
            }

            self.initValidationRules();

            if (!self.isPaymentFormEmpty() && self.useReloadingOfPaymentForm()) {
                self.initReloadingOfPaymentForm();
            }

            if (typeof self.callback === "function") {
                self.callback();
            }

            $(window).on("unload", function() {
                var doSomething = 1;
                doSomething++;
            });

            if (self.params.useStorage) {
                self.initStorage();
            }
        };

        this.useReloadingOfPaymentForm = function() {
            if (typeof this.params.enableAutoReloaingOfPaymentFrom !== 'undefined' && this.params.enableAutoReloaingOfPaymentFrom) {
                if (typeof window.simpleTypingSpeed !== 'undefined' && window.simpleTypingSpeed > 1000) {
                    return false;
                }

                return true;
            }

            return false;
        },

        this.initHandlers = function() {
            var self = this;
            $(self.params.mainContainer).find("*[data-onchange], *[data-onclick]").each(function() {
                var bind = true,
                    $element = $(this);

                for (var i in self.blocks) {
                    if (!self.blocks.hasOwnProperty(i)) continue;

                    if (checkIsInContainer($element, self.blocks[i].currentContainer)) {
                        bind = false;
                        break;
                    }
                }

                if (bind) {
                    var funcOnChange = $element.attr("data-onchange");
                    if (funcOnChange) {
                        $element.on("change", function() {
                            self.setDirty($(this));
                            self.callFunc(funcOnChange, $element);
                        });
                    }
                    var funcOnClick = $element.attr("data-onclick");
                    if (funcOnClick) {
                        $element.on("click", function() {
                            if ($element.attr("data-onclick-stopped")) {
                                return;
                            }
                            self.setDirty();
                            self.callFunc(funcOnClick, $element);
                        });
                    }
                }
            });
        };

        this.skipKey = function(keyCode) {
            if ($.inArray(keyCode,[9,13,16,17,18,19,20,27,35,36,37,38,39,40,91,93,224]) > -1) {
                return true;
            }

            return false;
        };

        this.addObserver = function() {
            var self = this;

            $(self.params.mainContainer).find("input[type=radio], input[type=checkbox], select").on("change", function() {
                if (!checkIsInContainer($(this), self.selectors.paymentForm)) {
                    self.setDirty($(this));
                }
            });

            $(self.params.mainContainer).find("input, textarea").on("keydown", function(e) {
                if (self.skipKey(e.keyCode)) {
                    return;
                }

                if (!checkIsInContainer($(this), self.selectors.paymentForm)) {
                    self.setDirty($(this));
                }
            });
        };

        this.initReloadingOfPaymentForm = function() {
            var self = this;

            var reload = function(disableScroll) {
                var $field = $(this);

                if (typeof disableScroll === "undefined") {
                    disableScroll = false;
                }

                if (!checkIsInContainer($field, self.selectors.paymentForm)) {
                    self.validate(true).then(function(result) {
                        if (result) {
                            self.reloadAll(undefined, disableScroll);
                        }
                    });
                }
            };

            $(self.params.mainContainer).find("input[data-mask][data-reload-payment-form], input[type=radio][data-reload-payment-form], input[type=checkbox][data-reload-payment-form], select[data-reload-payment-form], input[type=date][data-reload-payment-form], input[type=time][data-reload-payment-form]").on("change", reload);

            var timeoutId = 0;

            $(self.params.mainContainer).find("input[type=text][data-reload-payment-form]:not([data-mask]), input[type=email][data-reload-payment-form]:not([data-mask]), input[type=tel][data-reload-payment-form]:not([data-mask]), textarea[data-reload-payment-form]").on("keydown", function(e) {
                if (self.skipKey(e.keyCode)) {
                    return;
                }

                if (timeoutId) {
                    clearTimeout(timeoutId);
                }

                timeoutId = window.setTimeout(function() {
                    clearTimeout(timeoutId);

                    if ($(self.params.mainContainer).data("timeoutId") != timeoutId) {
                        return;
                    }

                    reload(true);
                }, 500);

                $(self.params.mainContainer).data("timeoutId", timeoutId);
            });
        };

        this.savePaymentForm = function() {
            var self = this;

            if (!self.isPaymentFormEmpty()) {
                self.$paymentForm = $(self.params.mainContainer).find(self.selectors.paymentForm).find("input[type=text],select,textarea,input[type=radio]:checked,input[type=checkbox]:checked");
            } else {
                self.$paymentForm = false;
            }
        };

        this.initStorage = function() {
            var self = this;

            if (!self.storageUsed) {
                var needReloading = false;

                $(self.params.mainContainer).find("input[type=text], input[type=email], input[type=tel], select, textarea").each(function() {
                    var $el = $(this);
                    var id = $el.attr("id");
                    var value = localStorage.getItem(id);
                    
                    if (id && value) {
                        if ($el.is("select")) {
                            if ($el.find("option[value='" + value + "']").length) {
                                if ($el.val() != value) {
                                    $el.val(value);

                                    if ($el.attr("data-onchange")) {
                                        needReloading = true;
                                    }
                                }
                            }
                        } else {
                            if ($el.val() != value) {
                                $el.val(value);

                                if ($el.attr("data-onchange")) {
                                    needReloading = true;
                                }
                            }
                        }
                    }
                });

                if (needReloading) {
                    self.reloadAll();
                }

                self.storageUsed = true;
            }

            if (!$(self.params.mainContainer).attr("data-logged")) {
                $(self.params.mainContainer).find("input[type=text], input[type=email], input[type=tel], select, textarea").on("change", function() {
                    var $el = $(this);

                    if (checkIsInContainer($el, "#simplecheckout_customer") || checkIsInContainer($el, "#simplecheckout_shipping_address") || checkIsInContainer($el, "#simplecheckout_payment_address")) {
                        localStorage.setItem($el.attr("id"), $el.val());
                    }
                });
            }
        }

        this.restorePaymentForm = function($oldForm) {
            var self = this;
            var $paymentForm = $(self.params.mainContainer).find(self.selectors.paymentForm);

            $oldForm.each(function() {
                var $field = $(this);
                var name = $field.attr("name");
                var id = $field.attr("id");
                var value = $field.val();

                if ($field.is("input[type=text]") || $field.is("select") || $field.is("textarea")) {
                    if (name) {
                        $paymentForm.find("[name='" + name + "']").val(value);
                    } else if (id) {
                        $paymentForm.find("#" + id).val(value);
                    }
                }

                if ($field.is("input[type=radio]") || $field.is("input[type=checkbox]")) {
                    if (name) {
                        $paymentForm.find("[name='" + name + "'][value='" + value + "']").attr("checked", "checked");
                    } else if (id) {
                        $paymentForm.find("#" + id + "[value='" + value + "']").attr("checked", "checked");
                    }
                }
            });

            delete $oldForm;
        };

        this.initAbandonedCart = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);
            
            $mainContainer.find("input:not([data-onchange=reloadAll],[data-onclick=reloadAll]), select:not([data-onchange=reloadAll],[data-onclick=reloadAll]), textarea:not([data-onchange=reloadAll],[data-onclick=reloadAll])").on('change', function() {
                $.ajax({
                    url: "index.php?" + self.params.additionalParams + "route=checkout/simplecheckout/abandoned",
                    data: self.createPostData(),
                    type: "POST",
                    dataType: "text"                    
                });
            });
        };

        this.setDirty = function($element) {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);

            if (self.useReloadingOfPaymentForm()) {
                self.savePaymentForm();

                if ($element && ($element.attr("data-reload-payment-form") || ($element.attr("data-onchange") && $element.attr("data-onchange") == "reloadAll"))) {
                    $mainContainer.find(self.selectors.paymentForm).attr("data-invalid", "true").find("input,select,textarea").attr("disabled", "disabled");
                }
            } else {
                $mainContainer.find(self.selectors.paymentForm).attr("data-invalid", "true").empty();
            }

            $mainContainer.find("*[data-payment-button=true]").remove();
            $mainContainer.find(self.selectors.proceedText).hide();
            self.formSubmitted = false;
            if (self.currentStep == self.stepsCount) {
                $mainContainer.find(self.selectors.buttons).show();
                $mainContainer.find(self.selectors.buttonCreate).show();
            }
        };

        this.preventOrderDeleting = function(callback) {
            var self = this;
            $.get("index.php?" + self.params.additionalParams + "route=" + self.params.mainRoute + "/prevent_delete", function() {
                if (typeof callback === "function") {
                    callback();
                }
            });
        };

        this.clickOnConfirmButton = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);
            var $paymentForm = $mainContainer.find(self.selectors.paymentForm);

            if (self.isPaymentFormEmpty()) {
                return;
            }

            var gatewayLink = $paymentForm.find("div.buttons a:last").attr("href");
            var $submitButton = $paymentForm.find("div.buttons input[type=button]:last,div.buttons input[type=submit]:last,div.buttons input[type=image]:last,div.buttons button:last,div.buttons a.button:last[href='#'],div.buttons a.btn:last[href='#'],div.buttons a.button:last:not([href]),div.buttons a.btn:last:not([href])");
            var $lastButton = $paymentForm.find("input[type=button]:last,input[type=submit]:last,input[type=image]:last,button:last");
            var lastLink = $paymentForm.find("a:last").attr("href");

            var overlayButton = function() {
                $mainContainer.find(self.selectors.buttonCreate).attr("disabled", "disabled");
                if (!$mainContainer.find(".wait").length) {
                    //$mainContainer.find(self.selectors.buttonCreate).after("<span class='wait'>&nbsp;<img src='" + self.params.additionalPath + self.resources.loadingSmall + "' alt='' /></span>");
                }
            };

            var removeOverlay = function() {
                $mainContainer.find(self.selectors.buttonCreate).removeAttr("disabled");
                $mainContainer.find(".wait").remove();
            };

            if (typeof gatewayLink !== "undefined" && gatewayLink !== "" && gatewayLink !== "#") {
                overlayButton();
                self.preventOrderDeleting(function() {
                    removeOverlay();
                    window.location = gatewayLink;
                    self.blockFieldsDuringPayment();
                    self.proceed();
                });
            } else if ($submitButton.length) {
                if ($submitButton.attr("href") == "#") {
                    $submitButton.removeAttr("href");
                }

                overlayButton();
                self.preventOrderDeleting(function() {
                    removeOverlay();
                    if (!$submitButton.attr("disabled")) {
                        $submitButton.mousedown().click();
                        self.blockFieldsDuringPayment($submitButton);
                        self.proceed();
                    }
                });
            } else if ($lastButton.length) {
                overlayButton();
                self.preventOrderDeleting(function() {
                    removeOverlay();
                    if (!$lastButton.attr("disabled")) {
                        $lastButton.mousedown().click();
                        self.blockFieldsDuringPayment($lastButton);
                        self.proceed();
                    }
                });
            } else if (typeof lastLink !== "undefined" && lastLink !== "" && lastLink !== "#") {
                overlayButton();
                self.preventOrderDeleting(function() {
                    removeOverlay();
                    window.location = lastLink;
                    self.blockFieldsDuringPayment();
                    self.proceed();
                });
            }
        };

        this.isPaymentFormValid = function() {
            var self = this;
            return !self.isPaymentFormEmpty() && !$(self.params.mainContainer).find(self.selectors.paymentForm).attr("data-invalid") ? true : false;
        };

        this.isPaymentFormVisible = function() {
            var self = this;
            return !self.isPaymentFormEmpty() && $(self.params.mainContainer).find(self.selectors.paymentForm).find(":visible:not(form)").length > 0 ? true : false;
        };

        this.isPaymentFormEmpty = function() {
            var self = this;
            var $paymentForm = $(self.params.mainContainer).find(self.selectors.paymentForm);

            return $paymentForm.length && $paymentForm.find("*").length > 0 ? false : true;
        };

        this.replaceCreateButtonWithConfirm = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);
            var $paymentForm = $(self.params.mainContainer).find(self.selectors.paymentForm);

            if (self.isPaymentFormEmpty()) {
                return;
            }

            var $gatewayLink = $paymentForm.find("div.buttons a:last");
            var $submitButton = $paymentForm.find("div.buttons input[type=button]:last,div.buttons input[type=submit]:last,div.buttons input[type=image]:last,div.buttons button:last,div.buttons a.button:last:not([href]),div.buttons a.btn:last:not([href])");
            var $lastButton = $paymentForm.find("input[type=button]:last,input[type=submit]:last,input[type=image]:last,button:last");
            var $lastLink = $paymentForm.find("a:last");

            var $obj = false;

            if ($gatewayLink.length) {
                $obj = $gatewayLink;
            } else if ($submitButton.length) {
                $obj = $submitButton;
            } else if ($lastButton.length) {
                $obj = $lastButton;
            } else if ($lastLink.length) {
                $obj = $lastLink;
            }

            if ($obj) {
                if ($obj.attr("href") == "#") {
                    $obj.removeAttr("href");
                }

                var $clone = $obj.clone(false).removeAttr("onclick").addClass("btn button");

                $mainContainer.find(self.selectors.buttonCreate).hide().before($clone);

                $clone.attr("data-payment-button", "true").bind("mousedown", function() {
                    if ($obj.attr("disabled")) {
                        return;
                    }

                    $obj.mousedown();
                }).bind("click", function() {
                    if ($obj.attr("disabled")) {
                        return;
                    }

                    self.preventOrderDeleting(function() {
                        self.proceed();
                        $obj.click();
                        self.blockFieldsDuringPayment($obj);
                    });
                });
            } else {
                $mainContainer.find(self.selectors.buttons).hide();
                self.preventOrderDeleting();
            }
        };

        this.blockFieldsDuringPayment = function($button) {
            var self = this;

            self.disableAllFieldsBeforePayment();

            if (typeof $button !== "undefined") {
                var timerId = setInterval(function() {
                    if (!$button.attr("disabled")) {
                        self.enableAllFieldsAfterPayment();
                        clearInterval(timerId);
                    }
                }, 250);
            }
        };

        this.disableAllFieldsBeforePayment = function() {
            var self = this;

            $(self.params.mainContainer).find(self.selectors.block).each(function() {
                if ($(this).attr("id") == "simplecheckout_payment_form") {
                    return;
                }
                $(this).find("input,select,textarea").attr("disabled", "disabled");
                $(this).find("[data-onclick]").attr("data-onclick-stopped", "true");
            });
        };

        this.enableAllFieldsAfterPayment = function() {
            var self = this;

            $(self.params.mainContainer).find(self.selectors.block).each(function() {
                if ($(this).attr("id") == "simplecheckout_payment_form") {
                    return;
                }
                $(this).find("input:not([data-dummy]),select,textarea").removeAttr("disabled");
                $(this).find("[data-onclick]").removeAttr("data-onclick-stopped");
            });
        };

        this.proceed = function() {
            var self = this;
            if (self.params.displayProceedText && !self.isPaymentFormVisible()) {
                $(self.params.mainContainer).find(self.selectors.proceedText).show();
            }
        };

        this.gotoStep = function($target) {
            var self = this;
            var step = $target.attr("data-step");
            if (step < self.currentStep) {
                $.when(self.hideCurrentStep()).then(function() {
                    self.currentStep = step;
                    self.setDirty();
                    self.displayCurrentStep(true);
                });
            } else if (step > self.currentStep) {
                self.nextStep($target);
            }
        };

        this.previousStep = function($target) {
            var self = this;
            if (self.currentStep > 1) {
                $.when(self.hideCurrentStep()).then(function() {
                    self.currentStep--;
                    self.setDirty();
                    self.displayCurrentStep(true);
                });
            }
        };

        this.nextStep = function($target) {
            var self = this;

            if ($target.data("clicked")) {
                return;
            }

            $target.data("clicked", true);

            self.validate(false).then(function(result) {
                if (result) {
                    if (self.currentStep < self.$steps.length) {
                        self.currentStep++;
                    }

                    self.hideCurrentStep();
                    
                    self.submitForm(true);
                } else {
                    if (typeof toastr !== 'undefined' && self.params.notificationCheckForm) {
                        toastr.error(self.params.notificationCheckFormText);
                    }

                    $target.data("clicked", false);

                    self.scroll();
                }
            });
        };

        this.saveStep = function() {
            var self = this;
            if (self.currentStep) {
                $(self.params.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "next_step").val(self.currentStep));
            }
        };

        this.ignorePost = function() {
            var self = this;
            $(self.params.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "ignore_post").val(1));
        };

        this.addSystemFieldsInForm = function() {
            var self = this;
            if (self.formSubmitted) {
                $(self.params.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "create_order").val(1));
            }
            if (self.currentStep) {
                $(self.params.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "next_step").val(self.currentStep));
            }
        };

        this.getAgreementCheckboxStep = function() {
            var step = typeof this.params.agreementCheckboxStep !== "undefined" && this.params.agreementCheckboxStep !== "" ? (this.params.agreementCheckboxStep + 1) : this.stepsCount - 1;

            if (step > this.stepsCount - 1) {
                step = this.stepsCount - 1;
            }

            return step;
        }

        this.setLocationHash = function(hash) {
            window.location.hash = hash;
            this.backCount--;
        };

        this.initSteps = function(changeStep) {
            var self = this;
            var i = 1;
            var $mainContainer = $(self.params.mainContainer);
            var $steps = $mainContainer.find(self.selectors.step);

            self.stepReseted = false;
            self.$steps = [];
            self.stepsCount = $steps.length || 1;

            $steps.each(function() {
                var $step = $(this);
                self.$steps.push($step);
                // check steps before current for errors and set step with error as current
                var $errorBlocks = $step.find(self.selectors.block + "[data-error=true]");
                if (i < self.currentStep && $errorBlocks.length) {
                    self.currentStep = i;
                    self.stepReseted = true;
                }
                i++;
            });

            if (self.stepsCount > 1 && !self.stepReseted && (self.currentStep == self.stepsCount || self.currentStep > self.getAgreementCheckboxStep()) && $mainContainer.attr("data-error") == "true") {
                self.currentStep--;
                self.stepReseted = true;
            }

            //a fix for case when some steps are suddenly hidden after ajax request
            if (self.stepsCount > 1 && !self.stepReseted && self.currentStep > self.stepsCount) {
                self.currentStep = self.stepsCount;
            }

            $mainContainer.find(self.selectors.paymentButtons).hide();

            if (!self.isPaymentFormVisible()) {
                $mainContainer.find(self.selectors.paymentForm).css({
                    "margin": "0",
                    "padding": "0"
                });
            }

            self.displayCurrentStep(changeStep);
        };

        this.hideCurrentStep = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);
            
            $mainContainer.find(self.selectors.agreementWarning).hide();
                
            if (typeof self.params.menuType !== "undefined" && self.params.menuType == 2) {
                $mainContainer.find(self.selectors.buttons).hide();
                return $mainContainer.find(self.selectors.step).slideUp("slow");
            } else {
                //return $mainContainer.find(self.selectors.step).hide();
            }          
        };

        this.displayCurrentStep = function(changeStep) {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);

            var initButtons = function() {
                if (self.stepsCount > 1) {
                    if (self.currentStep == 1) {
                        $mainContainer.find(self.selectors.buttonPrev).hide();
                    } else {
                        $mainContainer.find(self.selectors.buttonBack).hide();
                    }

                    if (self.currentStep < self.stepsCount) {
                        $mainContainer.find(self.selectors.buttonNext).show();
                        $mainContainer.find(self.selectors.buttonCreate).hide();                    
                    }

                    $mainContainer.find(self.selectors.agreementCheckBox).hide();

                    if (self.currentStep == self.getAgreementCheckboxStep()) {
                        $mainContainer.find(self.selectors.agreementCheckBox).show();
                    }
                }

                if (typeof self.params.stepButtons !== 'undefined' && typeof self.params.stepButtons[self.currentStep] !== 'undefined' && self.params.stepButtons[self.currentStep] != '') {
                    var $button = $mainContainer.find(self.stepsCount > 1 ? self.selectors.buttonNext : self.selectors.buttonCreate);

                    if ($button.find('span').length) {
                        $button = $button.find('span');
                    }

                    $button.html(self.params.stepButtons[self.currentStep]);
                }  

                if (self.currentStep == self.stepsCount) {
                    $mainContainer.find(self.selectors.buttonNext).hide();
                    self.replaceCreateButtonWithConfirm();
                }
            };

            var initStepsMenu = function() {
                if (typeof self.params.menuType !== "undefined" && self.params.menuType == 2) {
                    for (var i = 1; i < self.stepsCount + 1; i++) {
                        var $topItem = $mainContainer.find(self.selectors.stepsMenuTop + " " + self.selectors.stepsMenuVerticalItem + "[data-step=" + i + "]");
                        var $bottomItem = $mainContainer.find(self.selectors.stepsMenuBottom + " " + self.selectors.stepsMenuVerticalItem + "[data-step=" + i + "]");
                        
                        if (i <= self.currentStep) {
                            $topItem.show();
                            $topItem.addClass(self.classes.stepsMenuVerticalCompleted);
                            $bottomItem.hide();
                        } else {
                            $topItem.removeClass(self.classes.stepsMenuVerticalCompleted);
                            $topItem.hide();
                            $bottomItem.show();
                        }
                    }
                } else {
                    $mainContainer.find(self.selectors.stepsMenu + " " + self.selectors.stepsMenuItem).removeClass(self.classes.stepsMenuCompleted).removeClass(self.classes.stepsMenuCurrent);
                    $mainContainer.find(self.selectors.stepsMenu + " " + self.selectors.stepsMenuDelimiter + " img").attr("src", self.params.additionalPath + self.resources.next);

                    for (var i = 1; i < self.currentStep; i++) {
                        $mainContainer.find(self.selectors.stepsMenu + " " + self.selectors.stepsMenuItem + "[data-step=" + i + "]").addClass(self.classes.stepsMenuCompleted);
                        $mainContainer.find(self.selectors.stepsMenu + " " + self.selectors.stepsMenuDelimiter + "[data-step=" + (i + 1) + "] img").attr("src", self.params.additionalPath + self.resources.nextCompleted);
                    }

                    $mainContainer.find(self.selectors.stepsMenu + " " + self.selectors.stepsMenuItem + "[data-step=" + self.currentStep + "]").addClass(self.classes.stepsMenuCurrent);
                }
            };

            var hideAllSteps = function() {
                $mainContainer.find(self.selectors.step).hide();
            };

            var isLastStepHasOnlyPaymentForm = function() {
                var $lastStep = $mainContainer.find(self.selectors.step + ":last");
                return $lastStep.find(self.selectors.block).length == 1 && $lastStep.find(self.selectors.paymentForm).length == 1 ? true : false;
            };

            if (self.currentStep == self.stepsCount && !self.isPaymentFormVisible() && self.isPaymentFormValid() && (isLastStepHasOnlyPaymentForm() || self.formSubmitted)) {
                self.clickOnConfirmButton();
                if (isLastStepHasOnlyPaymentForm()) {
                    self.currentStep--;
                }
            }

            hideAllSteps();

            if (typeof self.$steps[self.currentStep - 1] !== "undefined") {
                if (changeStep) {
                    if (typeof self.params.menuType !== "undefined" && self.params.menuType == 2) {
                        self.$steps[self.currentStep - 1].slideDown("slow");
                        $mainContainer.find(self.selectors.buttons).show();
                    } else {
                        self.$steps[self.currentStep - 1].show();

                    }
                } else {
                    self.$steps[self.currentStep - 1].show();
                }                
            }

            if (self.stepsCount > 1) {
                self.setLocationHash("step_" + self.currentStep);
            }

            initStepsMenu();
            initButtons();
        };

        this.scroll = function() {
            var self = this,
                error = false,
                top = 10000,
                bottom = 0;

            var $mainContainer = $(self.params.mainContainer);

            var isOutsideOfVisibleArea = function(y) {
                if (y < $(window).scrollTop() || y > ($(window).scrollTop() + $(window).height())) {
                    return true;
                }
                return false;
            };

            if (self.params.popup) {
                return;
            }

            if (self.params.scrollToError) {
                $($mainContainer.find("[data-error=true]:visible")).each(function() {
                    var offset = $(this).offset();
                    if (offset.top < top) {
                        top = offset.top;
                    }
                    if (offset.bottom > bottom) {
                        bottom = offset.bottom;
                    }
                });

                $($mainContainer.find(".simplecheckout-warning-block:visible")).each(function() {
                    var offset = $(this).offset();
                    if (offset.top < top) {
                        top = offset.top;
                    }
                    if (offset.bottom > bottom) {
                        bottom = offset.bottom;
                    }
                });

                $($mainContainer.find(".simplecheckout-rule:visible")).each(function() {
                    if ($(this).parents(".simplecheckout-block").length) {
                        var offset = $(this).parents(".simplecheckout-block").offset();
                        if (offset.top < top) {
                            top = offset.top;
                        }
                        if (offset.bottom > bottom) {
                            bottom = offset.bottom;
                        }
                    }
                });

                if (top < 10000 && isOutsideOfVisibleArea(top)) {
                    $("html, body").animate({
                        scrollTop: top
                    }, "slow");
                    error = true;
                } else if (bottom && isOutsideOfVisibleArea(bottom)) {
                    $("html, body").animate({
                        scrollTop: bottom
                    }, "slow");
                    error = true;
                }
            }

            if (self.params.scrollToPaymentForm && !error) {
                if (self.isPaymentFormVisible()) {
                    top = $mainContainer.find(self.selectors.paymentForm).offset().top + $mainContainer.find(self.selectors.paymentForm).outerHeight();
                    if (top && isOutsideOfVisibleArea(top)) {
                        $("html, body").animate({
                            scrollTop: top
                        }, "slow");
                    }
                }
            }

            if ($mainContainer.find(self.selectors.stepsMenu).length && self.currentStep != self.saveStepNumber) {
                top = $mainContainer.find(self.selectors.stepsMenu + " " + "[data-step=" + self.currentStep + "]:visible").offset().top;

                if (top && isOutsideOfVisibleArea(top)) {
                    $("html, body").animate({
                        scrollTop: top
                    }, "slow");
                }
            }

            self.saveStepNumber = self.currentStep;
        };

        this.validateAgreements = function(silent) {
            var self = this;

            var result = true;

            var $agreementCheckbox = $(self.params.mainContainer).find(self.selectors.agreementCheckBox).find("input[type=checkbox]");
            var $agreementCheckboxChecked = $(self.params.mainContainer).find(self.selectors.agreementCheckBox).find("input[type=checkbox]:checked");
            var $agreementWarning = $(self.params.mainContainer).find(self.selectors.agreementWarning);

            if ($agreementCheckbox.length && $agreementCheckbox.is(":visible") && $agreementCheckbox.length != $agreementCheckboxChecked.length) {
                if ($agreementWarning.length) {
                    if (!silent) {
                        $agreementWarning.attr("data-error", "true");

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
                    }

                    result = false;
                }
            } else {
                $agreementWarning.hide().removeAttr("data-error");
            }

            return result;
        };

        this.validate = function(silent) {
            var self = this;
            var result = true;
            var promises = [];

            if (typeof silent === "undefined") {
                silent = false;
            }

            if (!self.validateAgreements(silent)) {
                result = false;
            }

            for (var i in self.blocks) {
                if (!self.blocks.hasOwnProperty(i)) continue;

                var promise = self.blocks[i].validate(silent).then(function(validatorResult) {
                    if (!validatorResult) {
                        result = false;
                    }
                });

                promises.push(promise);
            }

            if (typeof simpleValidate === 'function') {
                if (!simpleValidate()) {
                    result = false; 
                }
            }

            var deferred = $.Deferred();

            $.when.apply($, promises).then(function() {
                deferred.resolve(result);
            });

            return deferred.promise();
        };

        this.backHistory = function() {
            var self = this;
            history.go(self.backCount);
        };

        this.createOrder = function() {
            var self = this;

            self.validate(false).then(function(result) {
                if (result) {
                    self.formSubmitted = true;
                    self.submitForm();
                } else {
                    if (typeof toastr !== 'undefined' && self.params.notificationCheckForm) {
                        toastr.error(self.params.notificationCheckFormText);
                    }

                    self.scroll();
                }
            });
        };

        this.submitForm = function(changeStep) {
            var self = this;
            self.requestReloadAll(null, changeStep);
        };

        /**
         * Adds delay for reload execution on 150 ms, it allows to check sequence of events and to execute only the last request to handle of more events in one reloading
         * @param  {Function} callback
         */
        this.requestReloadAll = function(callback, changeStep) {
            var self = this;
            if (self.requestTimerId) {
                clearTimeout(self.requestTimerId);
                self.requestTimerId = 0;
            }
            self.requestTimerId = window.setTimeout(function() {
                self.reloadAll(callback, false, changeStep);
            }, 150);
        };

        this.overlayAll = function() {
            var self = this;

            for (var i in self.blocks) {
                if (!self.blocks.hasOwnProperty(i)) continue;

                self.blocks[i].overlay();
            }

            $(self.params.mainContainer).find(self.selectors.block).each(function() {
                if (!$(this).data("initialized")) {
                    SimplecheckoutBlock.prototype.overlay.apply(self, [$(this)]);
                }
            });
        };

        this.removeOverlays = function() {
            var self = this;

            $(self.params.mainContainer).find(self.selectors.overlay).remove();
            $(self.params.mainContainer).find("input:not([data-dummy]),select,textarea").removeAttr("disabled");
        };

        this.createPostData = function() {
            var self = this;
            var usedBlocks = [];
            var usedFields = [];
            var fields = [];

            var copyFields = function(serializedFields, skipUsed) {
                for (var i in serializedFields) {
                    if (!serializedFields.hasOwnProperty(i)) continue;

                    var info = serializedFields[i];

                    if (typeof skipUsed === "undefined" || info.name.indexOf("[]") > -1 || (skipUsed && $.inArray(info.name, usedFields) == -1)) {
                        usedFields.push(info.name)
                        fields.push(encodeURIComponent(info.name)+"="+encodeURIComponent(info.value));
                    }
                }
            };

            $(self.params.mainContainer + " .simplecheckout-step:visible .simplecheckout-block:not(#simplecheckout_payment_form)").each(function() {
                var $block = $(this);

                if ($block.attr("id")) {
                    usedBlocks.push($block.attr("id"));
                }

                copyFields($block.find("input,select,textarea").serializeArray());
            });

            $(self.params.mainContainer + " .simplecheckout-step:not(:visible) .simplecheckout-block:not(#simplecheckout_payment_form)").each(function() {
                var $block = $(this);

                if ($block.attr("id") && $.inArray($block.attr("id"), usedBlocks) > -1) {
                    return;
                }

                copyFields($block.find("input,select,textarea").serializeArray());
            });

            var otherFields = $(self.params.mainContainer + " *:not(#simplecheckout_payment_form)").find("input,select,textarea").serializeArray();

            copyFields(otherFields, true);

            var allFields = $(self.params.mainContainer + " > input,select,textarea").serializeArray();

            copyFields(allFields, true);

            return fields.join("&");
        }

        /**
         * Reload all blocks via main controller which includes all registered blocks as childs
         * @param  {Function} callback
         */
        this.reloadAll = function(callback, disableScroll, changeStep) {
            var self = this;

            if (self.isReloading) {
                return;
            }

            if (typeof disableScroll === "undefined") {
                disableScroll = false;
            }

            if (typeof changeStep === "undefined") {
                changeStep = false;
            }

            self.addSystemFieldsInForm();
            self.isReloading = true;

            var postData = self.createPostData();
            var overlayTimeoutId = 0;

            $.ajax({
                url: self.params.mainUrl,
                data: postData + "&simple_ajax=1",
                type: "POST",
                dataType: "text",
                beforeSend: function() {
                    $('.tooltip ').remove();

                    overlayTimeoutId = window.setTimeout(function() {
                        if (overlayTimeoutId && !(typeof self.params.menuType !== "undefined" && self.params.menuType == 2 && changeStep)) {
                            self.overlayAll();
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
                    self.init(disableScroll, changeStep);

                    if (typeof callback === "function") {
                        callback.call(self);
                    }

                    self.removeOverlays();
                    self.isReloading = false;
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    clearTimeout(overlayTimeoutId);
                    overlayTimeoutId = 0;
                    self.removeOverlays();
                    self.isReloading = false;
                }
            });
        };

        this.reloadBlock = function(container, callback) {
            var self = this;
            if (self.isReloading) {
                return;
            }
            self.isReloading = true;
            var postData = $(self.params.mainContainer).find("input,select,textarea").serialize();
            $.ajax({
                url: self.params.mainUrl,
                data: postData + "&simple_ajax=1",
                type: "POST",
                dataType: "text",
                beforeSend: function() {},
                success: function(data) {
                    var newData = $(container, $(data)).get(0);
                    if (!newData && data) {
                        newData = data;
                    }
                    $(container).replaceWith(newData);
                    self.init();
                    if (typeof callback === "function") {
                        callback.call(self);
                    }
                    self.isReloading = false;
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    self.isReloading = false;
                }
            });
        };

        this.registerBlock(new SimplecheckoutCart("#simplecheckout_cart", "checkout/simplecheckout_cart"));
        this.registerBlock(new SimplecheckoutShipping("#simplecheckout_shipping", "checkout/simplecheckout_shipping"));
        this.registerBlock(new SimplecheckoutPayment("#simplecheckout_payment", "checkout/simplecheckout_payment"));
        this.registerBlock(new SimplecheckoutForm("#simplecheckout_customer", "checkout/simplecheckout_customer"));
        this.registerBlock(new SimplecheckoutForm("#simplecheckout_payment_address", "checkout/simplecheckout_payment_address"));
        this.registerBlock(new SimplecheckoutForm("#simplecheckout_shipping_address", "checkout/simplecheckout_shipping_address"));
        this.registerBlock(new SimplecheckoutComment("#simplecheckout_comment", "checkout/simplecheckout_comment"));

        var login = new SimplecheckoutLogin("#simplecheckout_login", "checkout/simplecheckout_login");
        login.setParent(this);
        login.init();
        login.shareMethod("open", "openLoginBox");

        this.instances.push(this);
    };

    Simplecheckout.prototype = inherit(window.Simple.prototype);

    /**
     * It is parent of all blocks
     */

    function SimplecheckoutBlock(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;
    }

    SimplecheckoutBlock.prototype.setParent = function(object) {
        this.simplecheckout = object;
        this.params = object.params;
        this.resources = object.resources;
    };

    SimplecheckoutBlock.prototype.reloadAll = function(callback) {
        if (this.simplecheckout) {
            this.simplecheckout.requestReloadAll(callback);
        } else {
            this.reload();
        }
    };

    SimplecheckoutBlock.prototype.reload = function(callback) {
        var self = this;
        if (self.isReloading) {
            return;
        }
        self.isReloading = true;
        var postData = $(self.params.mainContainer).find(self.currentContainer + ":visible").find("input,select,textarea").serialize();
        $.ajax({
            url: "index.php?" + self.params.additionalParams + "route=" + self.currentRoute,
            data: postData + "&simple_ajax=1",
            type: "POST",
            dataType: "text",
            beforeSend: function() {
                self.overlay();
            },
            success: function(data) {
                var newData = $(self.currentContainer, $(data)).get(0);
                if (!newData && data) {
                    newData = data;
                }
                $(self.params.mainContainer).find(self.currentContainer + ":visible").replaceWith(newData);
                if (typeof callback === "function") {
                    callback.call(self);
                }
                self.removeOverlay();
                self.isReloading = false;
                self.init();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                self.removeOverlay();
                self.isReloading = false;
            }
        });
    };

    SimplecheckoutBlock.prototype.load = function(callback, container) {
        var self = this;
        if (self.isLoading) {
            return;
        }
        if (typeof callback !== "function") {
            container = callback;
            callback = null;
        }
        self.isLoading = true;
        $.ajax({
            url: "index.php?" + self.params.additionalParams + "route=" + self.currentRoute,
            type: "GET",
            dataType: "text",
            beforeSend: function() {
                self.overlay();
            },
            success: function(data) {
                var newData = $(self.currentContainer, $(data)).get(0);
                if (!newData && data) {
                    newData = data;
                }
                if (newData) {
                    if (container) {
                        $(container).html(newData);
                    } else {
                        $(self.currentContainer).replaceWith(newData);
                    }
                }
                if (typeof callback === "function") {
                    callback();
                }
                self.removeOverlay();
                self.isLoading = false;
                self.init();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                self.removeOverlay();
                self.isLoading = false;
            }
        });
    };

    SimplecheckoutBlock.prototype.overlay = function(useBlock) {
        var self = this;
        var $block = (useBlock && $(useBlock)) || $(self.params.mainContainer).find(self.currentContainer);

        if ($block.length) {
            if (~~$block.height() < 50) {
                return;
            }
            $block.find("input,select,textarea").attr("disabled", "disabled");
            $block.append("<div class='simplecheckout_overlay' id='" + $block.attr("id") + "_overlay'></div>");
            $block.find(".simplecheckout_overlay")
                .css({
                    "background": "url(" + self.params.additionalParams + self.resources.loading + ") no-repeat center center",
                    "opacity": 0.4,
                    "position": "absolute",
                    "width": $block.width(),
                    "height": $block.height(),
                    "z-index": 5000
                })
                .offset({
                    top: $block.offset().top,
                    left: $block.offset().left
                });
        }
    };

    SimplecheckoutBlock.prototype.removeOverlay = function() {
        var self = this;
        var $mainContainer = $(self.params.mainContainer);

        if (typeof self.currentContainer !== "undefined") {
            $mainContainer.find(self.currentContainer).find("input:not([data-dummy]),select,textarea").removeAttr("disabled");
            $mainContainer.find(self.currentContainer + "_overlay").remove();
        }
    };

    SimplecheckoutBlock.prototype.hasError = function() {
        return $(this.params.mainContainer).find(this.currentContainer).attr("data-error") ? true : false;
    };

    SimplecheckoutBlock.prototype.init = function(useContainer) {
        var self = this;
        var $mainContainer = $(self.params.mainContainer);
        var $currentContainer = $mainContainer.find(self.currentContainer + ":visible");

        if (!$currentContainer.length) {
            return;
        }

        var callFunc = function(func, $target, e) {
            if (func && typeof self[func] === "function") {
                self[func]($target, e);
            } else if (func) {
                //console.log(func + " is not registered");
            }
        };

        $currentContainer.find("*[data-onchange]").on("change", function(e) {
            if (typeof self.simplecheckout !== "undefined") {
                self.simplecheckout.setDirty($(this));
            }
            callFunc($(this).attr("data-onchange"), $(this), e);
        });

        $currentContainer.find("*[data-onclick]").on("click", function(e) {
            if ($(this).attr("data-onclick-stopped")) {
                return;
            }

            if (typeof self.simplecheckout !== "undefined") {
                self.simplecheckout.setDirty();
            }

            callFunc($(this).attr("data-onclick"), $(this), e);
        });

        $currentContainer.find("*[data-onkeydown]").on("keydown", function(e) {
            if (typeof self.simplecheckout !== "undefined") {
                self.simplecheckout.setDirty();
            }

            callFunc($(this).attr("data-onkeydown"), $(this), e);
        });

        if (self.isEmpty()) {
            ////console.log(self.currentContainer + " is empty");
        }

        if (!self.hasError() && $currentContainer.attr("data-hide")) {
            $currentContainer.hide();
        }

        self.addFocusHandler();
        self.restoreFocus();

        $currentContainer.data("initialized", true);
    };

    SimplecheckoutBlock.prototype.validate = function(silent) {
        var self = this;

        if (typeof silent === "undefined") {
            silent = false;
        }

        return self.simplecheckout.checkRules(self.currentContainer, silent);
    };

    SimplecheckoutBlock.prototype.isEmpty = function() {
        if ($(this.params.mainContainer).find(this.currentContainer).find("*").length) {
            return false;
        }
        return true;
    };

    SimplecheckoutBlock.prototype.shareMethod = function(name, asName) {
        SimplecheckoutBlock.prototype[asName] = bind(this[name], this);
    };

    SimplecheckoutBlock.prototype.displayWarning = function() {
        if (this.params.notificationDefault) {
            $(this.params.mainContainer).find(this.currentContainer).find(".simplecheckout-warning-block").show();
        }

        if (this.params.notificationToasts) {
            $(this.params.mainContainer).find(this.currentContainer).find(".simplecheckout-warning-block").each(function() {
                toastr.error($(this).text());
            });
        }
    };

    SimplecheckoutBlock.prototype.hideWarning = function() {
        $(this.params.mainContainer).find(this.currentContainer).find(".simplecheckout-warning-block").hide();
    };

    SimplecheckoutBlock.prototype.focusedFieldId = "";

    SimplecheckoutBlock.prototype.addFocusHandler = function() {
        var self = this;
        var $currentContainer = $(self.params.mainContainer).find(self.currentContainer + ":visible");

        $currentContainer.find("input,textarea,select").focus(function() {
            self.simplecheckout.focusedFieldId = $(this).attr("id");
        });

        $(self.params.mainContainer).find("*:not(input,textarea,select)").focus(function() {
            self.simplecheckout.focusedFieldId = "";
        });
    };

    SimplecheckoutBlock.prototype.restoreFocus = function() {
        var self = this;
        var $currentContainer = $(self.params.mainContainer).find(self.currentContainer + ":visible");
        var focusedFieldId = self.simplecheckout.focusedFieldId;

        if (focusedFieldId) {
            var focusedField = $currentContainer.find("#" + focusedFieldId);

            if (focusedField.length && focusedField.is(":visible") && ((focusedField.attr("type") && focusedField.attr("type") == "text" && !focusedField.attr("data-type") && !focusedField.attr("data-mask")) || focusedField.is("textarea"))) {
                var $field = $currentContainer.find("#" + focusedFieldId);
                var value = $field.val();
                $field.val("").focus().val(value);
            }
        }
    };

    function SimplecheckoutCart(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
            self.initMiniCart();
        };

        this.validate = function(silent) {
            var self = this;
            var result = true;
            var deferred = $.Deferred();

            if (typeof silent === "undefined") {
                silent = false;
            }

            if (!silent && this.hasError()) {
                this.displayWarning();

                result = false;
            }

            deferred.resolve(result);

            return deferred.promise();
        };  

        this.initMiniCart = function() {
            var self = this;
            var $mainContainer = $(self.params.mainContainer);
            var total = $mainContainer.find("#simplecheckout_cart_total").html();
            var weight = $mainContainer.find("#simplecheckout_cart_weight").text();

            if (total) {
                $.each(["#cart_total", "#cart-total", "#cart_menu .s_grand_total", "#cart .tb_items", "#menu_wrap #cart-total"], function(index, selector) {
                    $(selector).html(total);
                });

                $("#weight").text(weight);

                if (self.params.currentTheme == "shoppica2") {
                    $("#cart_menu div.s_cart_holder").html("");
                    $.getJSON("index.php?" + self.params.additionalParams + "route=tb/cartCallback", function(json) {
                        if (json["html"]) {
                            $("#cart_menu span.s_grand_total").html(json["total_sum"]);
                            $("#cart_menu div.s_cart_holder").html(json["html"]);
                        }
                    });
                }

                if (self.params.currentTheme == "shoppica") {
                    $("#cart_menu div.s_cart_holder").html("");
                    $.getJSON("index.php?" + self.params.additionalParams + "route=module/shoppica/cartCallback", function(json) {
                        if (json["output"]) {
                            $("#cart_menu span.s_grand_total").html(json["total_sum"]);
                            $("#cart_menu div.s_cart_holder").html(json["output"]);
                        }
                    });
                }
            }
        };

        this.increaseProductQuantity = function($target) {
            var self = this;

            var $quantity = $target.parents(".quantity").find("input");
            var quantity = parseFloat($quantity.val());
            var step = +($quantity.attr("data-minimum") || 1);

            if (!isNaN(quantity)) {
                $quantity.val(quantity + step);

                if (self.timerId) {
                    clearTimeout(self.timerId);
                    self.timerId = 0;
                }
                
                self.timerId = window.setTimeout(function() {
                    self.reloadAll();
                }, 300);                
            }
        };

        this.decreaseProductQuantity = function($target) {
            var self = this;

            var $quantity = $target.parents(".quantity").find("input");
            var quantity = parseFloat($quantity.val());
            var step = +($quantity.attr("data-minimum") || 1);
            
            if (!isNaN(quantity) && quantity > step) {
                $quantity.val(quantity - step);
                
                if (self.timerId) {
                    clearTimeout(self.timerId);
                    self.timerId = 0;
                }

                self.timerId = window.setTimeout(function() {
                    self.reloadAll();
                }, 300);  
            }
        };

        this.changeProductQuantity = function($target) {
            var self = this;

            if (typeof $target[0] !== "undefined" && typeof $target[0].tagName !== "undefined" && $target[0].tagName !== "INPUT") {
                $target = $target.parents("td").find("input");
            }

            var quantity = parseFloat($target.val());

            if (!isNaN(quantity)) {
                self.reloadAll();
            }
        };

        this.removeProduct = function($target) {
            var self = this;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer + ":visible");

            var productKey = $target.attr("data-product-key");
            $currentContainer.find("#simplecheckout_remove").val(productKey);

            self.reloadAll();
        };

        this.removeGift = function($target) {
            var self = this;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer + ":visible");

            var giftKey = $target.attr("data-gift-key");
            $currentContainer.find("#simplecheckout_remove").val(giftKey);

            self.reloadAll();
        };

        this.removeCoupon = function($target) {
            var self = this;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer + ":visible");

            $currentContainer.find("input[name='coupon']").val("");
            self.reloadAll();
        };

        this.removeReward = function($target) {
            var self = this;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer + ":visible");

            $currentContainer.find("input[name='reward']").val("");
            self.reloadAll();
        };

        this.removeVoucher = function($target) {
            var self = this;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer + ":visible");

            $currentContainer.find("input[name='voucher']").val("");
            self.reloadAll();
        };
    }

    SimplecheckoutCart.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutLogin(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.removeTransformCss = function() {
            $(this.params.mainContainer)
                .parents()
                .filter(function() {
                    var transformStyle = $(this).css("transform");
                    if (transformStyle && transformStyle != "none") {
                        return true;
                    }
                    return false;
                }).each(function() {
                    var style = $(this).css("transform");
                    $(this).css("transform", "none").data('transform-style', style);
                });
        }

        this.restoreTransformCss = function() {
            $(this.params.mainContainer)
                .parents()
                .filter(function() {
                    return $(this).data("transform-style");
                }).each(function() {
                    $(this).css("transform", $(this).data("transform-style")).data("transform-style", "");
                });
        }

        this.initPopupLayer = function() {
            var self = this;
            var position = $("#simple_login_layer").parent().css("position");
            if (!$("#simple_login_layer").length || position == "fixed" || position == "relative" || position == "absolute") {
                $("#simple_login_layer").remove();
                $("#simple_login").remove();
                $(self.params.mainContainer).append("<div id='simple_login_layer'></div><div id='simple_login'><div id='temp_popup_container'></div></div>");
                $("#simple_login_layer").on("click", function() {
                    self.close();
                });
            }

            self.removeTransformCss();

            $("#simple_login_layer")
                .css("position", "fixed")
                .css("top", "0")
                .css("left", "0")
                .css("right", "0")
                .css("bottom", "0");

            $("#simple_login_layer").fadeTo(500, 0.8);
        };

        this.openPopup = function() {
            var self = this;
            self.initPopupLayer();
            if (!$(self.currentContainer).html()) {
                self.load(function() {
                    if ($(self.currentContainer).html()) {
                        self.resizePopup();
                    } else {
                        self.closePopup();
                    }
                }, "#temp_popup_container");
            } else {
                self.hideWarning();
                self.resizePopup();
            }
        };

        this.resizePopup = function() {
            $("#simple_login").show();
            $("#simple_login").css("height", $(this.currentContainer).outerHeight() + 20);
            $("#simple_login").css("top", window.innerHeight / 2 - ($("#simple_login").outerHeight() ? $("#simple_login").outerHeight() : $("#simple_login").height()) / 2);
            $("#simple_login").css("left", $(window).width() / 2 - ($("#simple_login").outerWidth() ? $("#simple_login").outerWidth() : $("#simple_login").width()) / 2);
        };

        this.closePopup = function() {
            var self = this;
            $("#simple_login_layer").fadeOut(500, function() {
                $(this).hide().css("opacity", "1");
                self.restoreTransformCss();
            });
            $("#simple_login").fadeOut(500, function() {
                $(this).hide();
            });
        };

        this.openFlat = function() {
            var self = this;
            if (!$(self.currentContainer).length) {
            $("<div id='temp_flat_container'><img src='" + self.params.additionalPath + self.resources.loading + "'></div>").insertBefore(self.params.loginBoxBefore);
                self.load("#temp_flat_container");
            }
            self.hideWarning();
            $(self.currentContainer).show();
        };

        this.closeFlat = function() {
            $(this.currentContainer).hide();
        };

        this.isOpened = function() {
            return $("#temp_flat_container *:visible").length ? true : false;
        };

        this.open = function() {
            var self = this;
            /*if (self.getParam("logged")) {
                return;
            }*/
            if (self.params.loginBoxBefore) {
                self.openFlat();
            } else {
                self.openPopup();
            }
        };

        this.close = function() {
            var self = this;
            if (self.params.loginBoxBefore) {
                self.closeFlat();
            } else {
                self.closePopup();
            }
        };

        this.login = function() {
            var self = this;
            this.reload(function() {
                if (!self.hasError()) {
                    self.closePopup();
                    self.closeFlat();
                    if (self.simplecheckout) {
                        self.simplecheckout.saveStep();
                        self.simplecheckout.ignorePost();
                        self.simplecheckout.reloadAll();
                    } else {
                        window.location.reload();
                    }
                } else {
                    self.resizePopup();
                }
            });
        };

        this.detectEnterAndLogin = function($target, e) {
            if (e.keyCode == 13) {
                this.login();
            }
        };
    }

    SimplecheckoutLogin.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutComment(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };
    }

    SimplecheckoutComment.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutShipping(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.validate = function(silent) {
            var self = this;
            var result = true;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer + ":visible");
            var deferred = $.Deferred();

            if (typeof silent === "undefined") {
                silent = false;
            }

            if ($currentContainer.length && !$currentContainer.find("input:checked").length && !$currentContainer.find("option:selected").length) {
                if (!silent) {
                    self.displayWarning();
                }
                result = false;
            }

            SimplecheckoutBlock.prototype.validate.apply(self, arguments).then(function(validatorResult) {
                deferred.resolve(result && validatorResult);
            });

            return deferred.promise();
        };
    }

    SimplecheckoutShipping.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutPayment(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.validate = function(silent) {
            var self = this;
            var result = true;
            var $currentContainer = $(self.params.mainContainer).find(self.currentContainer + ":visible");
            var deferred = $.Deferred();

            if (typeof silent === "undefined") {
                silent = false;
            }

            if ($currentContainer.length && !$currentContainer.find("input:checked").length && !$currentContainer.find("option:selected").length) {
                if (!silent) {
                    self.displayWarning();
                }
                result = false;
            }

            SimplecheckoutBlock.prototype.validate.apply(self, arguments).then(function(validatorResult) {
                deferred.resolve(result && validatorResult);
            });

            return deferred.promise();
        };
    }

    SimplecheckoutPayment.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutForm(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.validate = function(silent) {
            var self = this;
            var result = true;
            var deferred = $.Deferred();

            if (typeof silent === "undefined") {
                silent = false;
            }

            SimplecheckoutBlock.prototype.validate.apply(self, arguments).then(function(validatorResult) {
                deferred.resolve(result && validatorResult);
            });

            return deferred.promise();
        };

        this.reloadAll = function($element) {
            var self = this;
            window.setTimeout(function() {
                if (!$element.attr("data-valid") || $element.attr("data-valid") == "true") {
                    SimplecheckoutBlock.prototype.reloadAll.apply(self, arguments);
                }
            }, 0);

        };
    }

    SimplecheckoutForm.prototype = inherit(SimplecheckoutBlock.prototype);

    (function() {
        var inputs = {};

        $(document).on("keyup", "input[type=text],input[type=email],input[type=tel],textarea", function(e) {
            if ($.inArray(e.keyCode,[9,13,16,17,18,19,20,27,35,36,37,38,39,40,91,93,224]) > -1) {
                return true;
            }

            var inputId = $(this).attr("id");

            if (inputId) {
                var currentTime = new Date().getTime();

                var delta = 500;

                if (typeof inputs[inputId] !== 'undefined') {
                    if ((currentTime - inputs[inputId].time) < 10000) {
                        delta = (currentTime - inputs[inputId].time + inputs[inputId].delta) / 2;
                    }
                }

                inputs[inputId] = {
                    time: currentTime,
                    delta: delta
                };
            }

            var deltaSum = 0;
            var deltaCounter = 0;

            for (var i in inputs) {
                if (!inputs.hasOwnProperty(i)) continue;

                deltaSum += inputs[i].delta;
                deltaCounter++;
            }

            if (deltaCounter) {
                var speed = deltaSum / deltaCounter;

                if (speed > 500) {
                    window.simpleTypingSpeed = speed;
                } else {
                    window.simpleTypingSpeed = 500;
                }
            }
        });
    })();
})(jQuery || $);