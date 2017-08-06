(function ($) {

    $.fn.yiiListView = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.yiiListView');
            return false;
        }
    };

    var defaults = {
        filterUrl: undefined,
        filterSelector: undefined,

        scrollUpdate: false,
        scrollUpdateUrl: undefined,
        scrollUpdateHeight: 100
    };

    var listData = {};

    var listEvents = {
        /**
         *
         */
        beforeScrollAppend: 'beforeScrollAppend',
        /**
         *
         */
        afterScrollAppend: 'beforeScrollAppend',
    };

    var methods = {
        init: function (options) {
            return this.each(function () {
                var $e = $(this);
                var settings = $.extend({}, defaults, options || {});
                listData[$e.attr('id')] = {settings: settings};

                // list view ajax updating on scroll
                if (settings.scrollUpdate) {
                    var processLoading = false,
                        nextPortion = null;

                    jQuery(window).scroll(function() {
                        if (!nextPortion && !processLoading && !methods.checkStopLoading.apply($e)) {
                            processLoading = true;
                            $.ajax({
                                url: settings.scrollUpdateUrl,
                                data: {
                                    page: settings.itemsOrderDesc ? --settings.currentPage : ++settings.currentPage
                                }
                            }).complete(function() {
                                processLoading = false;
                            }).success(function(data) {
                                nextPortion = $(data).find('#advert-list').html();
                            });
                        }
                        if (methods.checkScrolledBottom(settings) && nextPortion) {
                            $e.append(nextPortion);
                            nextPortion = null;
                        }
                    });
                }
            });
        },

        data: function () {
            var id = $(this).attr('id');
            return listData[id];
        },

        checkScrolledBottom: function(settings) {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - settings.scrollUpdateHeight) {
                return true;
            }
            return false;
        },

        checkStopLoading: function() {
            var data = listData[$(this).attr('id')];
            var settings = data.settings;
            if ((settings.itemsOrderDesc && settings.currentPage < 1)
                || (!settings.itemsOrderDesc && settings.currentPage >= settings.pageCount)) {
                return true;
            }
            return false;
        }

    };
})(window.jQuery);