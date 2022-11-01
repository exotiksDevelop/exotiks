if (typeof(licode) === 'undefined') {
    var licode = {};
}

/**
 * Контроллер ("синглтон"), отвечает за отображение прелоадера
 * @type {licode.preloader}
 */
licode.preloader = new (function() {
    /**
     * Элемент, отрисовывающий прелоадер
     * @type string
     */
    this.element = '<img src="/admin/view/image/dvbusiness/licode.preloader.gif">';
    
    /**
     * Отрисовывает прелоадер поверх блочного элемента
     * @param {object} block Элемент, для которого отрисовывается прелоадер
     * @return {undefined}
     */
    this.on = function(block) {
        var obj = $(block);
        var div = $('<div class="async-preloader"></div>');
        var elementPreload = $(this.element);
        div.append(elementPreload);
        div.css({
            'position' : 'absolute',
            'background-color' : 'rgba(255,255,255,0.8)',
            'z-index' : '10',
            'text-align' : 'center'
        });
        div.width(obj.width());
        div.height(obj.height());
        div.position().left = obj.position().left;
        div.position().top = obj.position().top;
        
        obj.prepend(div);
        elementPreload.css({'margin-top': div.height() / 2 - 25 + 'px'});        
    };
    
    /**
     * Удаляет отрисованный прелоадер в блочном элементе
     * @param {object} block Элемент, для которого покрывается прелоадер
     * @returns {undefined}
     */
    this.off = function(block) {
        $(block).find('.async-preloader').remove();
    };
});