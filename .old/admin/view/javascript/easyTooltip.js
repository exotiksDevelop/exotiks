/*
 *  Easy Tooltip 1.0 - jQuery plugin
 *  written by Alen Grakalic
 *  http://cssglobe.com/post/4380/easy-tooltip--jquery-plugin
 *
 *  Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *  Dual licensed under the MIT (MIT-LICENSE.txt)
 *  and GPL (GPL-LICENSE.txt) licenses.
 *
 *  Built for jQuery library
 *  http://jquery.com
 *
 */

(function($) {

    $.fn.easyTooltip = function(options) {

        // default configuration properties
        var defaults = {
            xOffset: 8,
            yOffset: 5,
            tooltipId: "easyTooltip",
            clickRemove: false,
            content: "",
            useElement: "",
            position: "right"
        };

        options = $.extend(defaults, options);
        var content;

        this.each(function() {
            var title = $(this).attr("data-tooltip");
            $(this).hover(function(e) {
                    content = (options.content !== "") ? options.content : title;
                    content = (options.useElement !== "") && $("#" + options.useElement).length ? $("#" + options.useElement).html() : content;
                    $(this).attr("data-tooltip", "");
                    if (typeof content !== "undefined" && content) {
                        $("body").append("<div id='" + options.tooltipId + "' class='tooltip topArrow'>" + content + "</div>");
                        $("#" + options.tooltipId)
                            .css("position", "absolute")
                            .css("top", ($(this).offset().top + options.yOffset) + "px")
                            .css("left", ($(this).offset().left + $(this).outerWidth() + options.xOffset) + "px")
                            .css("display", "none")
                            .fadeIn("fast");
                    }
                },
                function() {
                    $("#" + options.tooltipId).remove();
                    $(this).attr("data-tooltip", title);
                });
            if (options.clickRemove) {
                $(this).mousedown(function(e) {
                    $("#" + options.tooltipId).remove();
                    $(this).attr("data-tooltip", title);
                });
            }
        });
    };
})(jQuery);