!function($, wysi) {
    "use strict";

    var templates = function(key, locale) {

        var tpl = {
            "font-styles":
                "<li class='dropdown'>" +
                  "<a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>" +
                  "<i class='icon-font'></i>&nbsp;<span class='current-font'>" + locale.font_styles.normal + "</span>&nbsp;<b class='caret'></b>" +
                  "</a>" +
                  "<ul class='dropdown-menu'>" +
                    "<li><a data-wysihtml5-command='formatBlock' data-wysihtml5-command-value='div'>" + locale.font_styles.normal + "</a></li>" +
                    "<li><a data-wysihtml5-command='formatBlock' data-wysihtml5-command-value='h1'>" + locale.font_styles.h1 + "</a></li>" +
                    "<li><a data-wysihtml5-command='formatBlock' data-wysihtml5-command-value='h2'>" + locale.font_styles.h2 + "</a></li>" +
                    "<li><a data-wysihtml5-command='formatBlock' data-wysihtml5-command-value='h3'>" + locale.font_styles.h3 + "</a></li>" +
                  "</ul>" +
                "</li>",

            "emphasis":
                "<li>" +
                  "<div class='btn-group'>" +
                    "<a class='btn' data-wysihtml5-command='bold' title='CTRL+B'>" + locale.emphasis.bold + "</a>" +
                    "<a class='btn' data-wysihtml5-command='italic' title='CTRL+I'>" + locale.emphasis.italic + "</a>" +
                    "<a class='btn' data-wysihtml5-command='underline' title='CTRL+U'>" + locale.emphasis.underline + "</a>" +
                  "</div>" +
                "</li>",

            "lists":
                "<li>" +
                  "<div class='btn-group'>" +
                    "<a class='btn' data-wysihtml5-command='insertUnorderedList' title='" + locale.lists.unordered + "'><i class='icon-list'></i></a>" +
                    "<a class='btn' data-wysihtml5-command='insertOrderedList' title='" + locale.lists.ordered + "'><i class='icon-th-list'></i></a>" +
                    "<a class='btn' data-wysihtml5-command='Outdent' title='" + locale.lists.outdent + "'><i class='icon-indent-right'></i></a>" +
                    "<a class='btn' data-wysihtml5-command='Indent' title='" + locale.lists.indent + "'><i class='icon-indent-left'></i></a>" +
                  "</div>" +
                "</li>",

            "link":
                "<li>" +
                  "<div class='bootstrap-wysihtml5-insert-link-modal modal hide fade'>" +
                    "<div class='modal-header'>" +
                      "<a class='close' data-dismiss='modal'>&times;</a>" +
                      "<h3>" + locale.link.insert + "</h3>" +
                    "</div>" +
                    "<div class='modal-body'>" +
                      "<input value='http://' class='bootstrap-wysihtml5-insert-link-url input-xlarge'>" +
                    "</div>" +
                    "<div class='modal-footer'>" +
                      "<a href='#' class='btn' data-dismiss='modal'>" + locale.link.cancel + "</a>" +
                      "<a href='#' class='btn btn-primary' data-dismiss='modal'>" + locale.link.insert + "</a>" +
                    "</div>" +
                  "</div>" +
                  "<a class='btn' data-wysihtml5-command='createLink' title='" + locale.link.insert + "'><i class='icon-share'></i></a>" +
                "</li>",

            "image":
                "<li>" +
                  "<div class='bootstrap-wysihtml5-insert-image-modal modal hide fade'>" +
                    "<div class='modal-header'>" +
                      "<a class='close' data-dismiss='modal'>&times;</a>" +
                      "<h3>" + locale.image.insert + "</h3>" +
                    "</div>" +
                    "<div class='modal-body'>" +
                      "<input value='http://' class='bootstrap-wysihtml5-insert-image-url input-xlarge'>" +
                    "</div>" +
                    "<div class='modal-footer'>" +
                      "<a href='#' class='btn' data-dismiss='modal'>" + locale.image.cancel + "</a>" +
                      "<a href='#' class='btn btn-primary' data-dismiss='modal'>" + locale.image.insert + "</a>" +
                    "</div>" +
                  "</div>" +
                  "<a class='btn' data-wysihtml5-command='insertImage' title='" + locale.image.insert + "'><i class='icon-picture'></i></a>" +
                "</li>",

            "html":
                "<li>" +
                  "<div class='btn-group'>" +
                    "<a class='btn' data-wysihtml5-action='change_view' title='" + locale.html.edit + "'><i class='icon-pencil'></i></a>" +
                  "</div>" +
                "</li>",

            "color":
                "<li class='dropdown'>" +
                  "<a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>" +
                    "<span class='current-color'>" + locale.colours.black + "</span>&nbsp;<b class='caret'></b>" +
                  "</a>" +
                  "<ul class='dropdown-menu'>" +
                    "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='black'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='black'>" + locale.colours.black + "</a></li>" +
                    "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='silver'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='silver'>" + locale.colours.silver + "</a></li>" +
                    "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='gray'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='gray'>" + locale.colours.gray + "</a></li>" +
                    "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='maroon'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='maroon'>" + locale.colours.maroon + "</a></li>" +
                    "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='red'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='red'>" + locale.colours.red + "</a></li>" +
                    "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='purple'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='purple'>" + locale.colours.purple + "</a></li>" +
                    "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='green'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='green'>" + locale.colours.green + "</a></li>" +
                    "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='olive'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='olive'>" + locale.colours.olive + "</a></li>" +
                    "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='navy'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='navy'>" + locale.colours.navy + "</a></li>" +
                    "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='blue'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='blue'>" + locale.colours.blue + "</a></li>" +
                    "<li><div class='wysihtml5-colors' data-wysihtml5-command-value='orange'></div><a class='wysihtml5-colors-title' data-wysihtml5-command='foreColor' data-wysihtml5-command-value='orange'>" + locale.colours.orange + "</a></li>" +
                  "</ul>" +
                "</li>"
        };
        return tpl[key];
    };


    var Wysihtml5 = function(el, options) {
        this.el = el;
        this.toolbar = this.createToolbar(el, options || defaultOptions);
        this.editor =  this.createEditor(options);

        window.editor = this.editor;

        $('iframe.wysihtml5-sandbox').each(function(i, el){
            $(el.contentWindow).off('focus.wysihtml5').on({
                'focus.wysihtml5' : function(){
                    $('li.dropdown').removeClass('open');
                }
            });
        });
    };

    Wysihtml5.prototype = {

        constructor: Wysihtml5,

        createEditor: function(options) {
            options = options || {};
            options.toolbar = this.toolbar[0];

            var editor = new wysi.Editor(this.el[0], options);

            if(options && options.events) {
                for(var eventName in options.events) {
                    editor.on(eventName, options.events[eventName]);
                }
            }
            return editor;
        },

        createToolbar: function(el, options) {
            var self = this;
            var toolbar = $("<ul/>", {
                'class' : "wysihtml5-toolbar",
                'style': "display:none"
            });
            var culture = options.locale || defaultOptions.locale || "en";
            for(var key in defaultOptions) {
                var value = false;

                if(options[key] !== undefined) {
                    if(options[key] === true) {
                        value = true;
                    }
                } else {
                    value = defaultOptions[key];
                }

                if(value === true) {
                    toolbar.append(templates(key, locale[culture]));

                    if(key === "html") {
                        this.initHtml(toolbar);
                    }

                    if(key === "link") {
                        this.initInsertLink(toolbar);
                    }

                    if(key === "image") {
                        this.initInsertImage(toolbar);
                    }
                }
            }

            if(options.toolbar) {
                for(key in options.toolbar) {
                    toolbar.append(options.toolbar[key]);
                }
            }

            toolbar.find("a[data-wysihtml5-command='formatBlock']").click(function(e) {
                var target = e.target || e.srcElement;
                var el = $(target);
                self.toolbar.find('.current-font').text(el.html());
            });

            toolbar.find("a[data-wysihtml5-command='foreColor']").click(function(e) {
                var target = e.target || e.srcElement;
                var el = $(target);
                self.toolbar.find('.current-color').text(el.html());
            });

            this.el.before(toolbar);

            return toolbar;
        },

        initHtml: function(toolbar) {
            var changeViewSelector = "a[data-wysihtml5-action='change_view']";
            toolbar.find(changeViewSelector).click(function(e) {
                toolbar.find('a.btn').not(changeViewSelector).toggleClass('disabled');
            });
        },

        initInsertImage: function(toolbar) {
            var self = this;
            var insertImageModal = toolbar.find('.bootstrap-wysihtml5-insert-image-modal');
            var urlInput = insertImageModal.find('.bootstrap-wysihtml5-insert-image-url');
            var insertButton = insertImageModal.find('a.btn-primary');
            var initialValue = urlInput.val();

            var insertImage = function() {
                var url = urlInput.val();
                urlInput.val(initialValue);
                self.editor.currentView.element.focus();
                self.editor.composer.commands.exec("insertImage", url);
            };

            urlInput.keypress(function(e) {
                if(e.which == 13) {
                    insertImage();
                    insertImageModal.modal('hide');
                }
            });

            insertButton.click(insertImage);

            insertImageModal.on('shown', function() {
                urlInput.focus();
            });

            insertImageModal.on('hide', function() {
                self.editor.currentView.element.focus();
            });

            toolbar.find('a[data-wysihtml5-command=insertImage]').click(function() {
                var activeButton = $(this).hasClass("wysihtml5-command-active");

                if (!activeButton) {
                    insertImageModal.modal('show');
                    insertImageModal.on('click.dismiss.modal', '[data-dismiss="modal"]', function(e) {
                        e.stopPropagation();
                    });
                    return false;
                }
                else {
                    return true;
                }
            });
        },

        initInsertLink: function(toolbar) {
            var self = this;
            var insertLinkModal = toolbar.find('.bootstrap-wysihtml5-insert-link-modal');
            var urlInput = insertLinkModal.find('.bootstrap-wysihtml5-insert-link-url');
            var insertButton = insertLinkModal.find('a.btn-primary');
            var initialValue = urlInput.val();

            var insertLink = function() {
                var url = urlInput.val();
                urlInput.val(initialValue);
                self.editor.currentView.element.focus();
                self.editor.composer.commands.exec("createLink", {
                    href: url,
                    target: "_blank",
                    rel: "nofollow"
                });
            };
            var pressedEnter = false;

            urlInput.keypress(function(e) {
                if(e.which == 13) {
                    insertLink();
                    insertLinkModal.modal('hide');
                }
            });

            insertButton.click(insertLink);

            insertLinkModal.on('shown', function() {
                urlInput.focus();
            });

            insertLinkModal.on('hide', function() {
                self.editor.currentView.element.focus();
            });

            toolbar.find('a[data-wysihtml5-command=createLink]').click(function() {
                var activeButton = $(this).hasClass("wysihtml5-command-active");

                if (!activeButton) {
                    insertLinkModal.appendTo('body').modal('show');
                    insertLinkModal.on('click.dismiss.modal', '[data-dismiss="modal"]', function(e) {
                        e.stopPropagation();
                    });
                    return false;
                }
                else {
                    return true;
                }
            });
        }
    };

    // these define our public api
    var methods = {
        resetDefaults: function() {
            $.fn.wysihtml5.defaultOptions = $.extend(true, {}, $.fn.wysihtml5.defaultOptionsCache);
        },
        bypassDefaults: function(options) {
            return this.each(function () {
                var $this = $(this);
                $this.data('wysihtml5', new Wysihtml5($this, options));
            });
        },
        shallowExtend: function (options) {
            var settings = $.extend({}, $.fn.wysihtml5.defaultOptions, options || {});
            var that = this;
            return methods.bypassDefaults.apply(that, [settings]);
        },
        deepExtend: function(options) {
            var settings = $.extend(true, {}, $.fn.wysihtml5.defaultOptions, options || {});
            var that = this;
            return methods.bypassDefaults.apply(that, [settings]);
        },
        init: function(options) {
            var that = this;
            return methods.shallowExtend.apply(that, [options]);
        }
    };

    $.fn.wysihtml5 = function ( method ) {
        if ( methods[method] ) {
            return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.wysihtml5' );
        }
    };

    $.fn.wysihtml5.Constructor = Wysihtml5;

    var defaultOptions = $.fn.wysihtml5.defaultOptions = {
        "font-styles": true,
        "color": false,
        "emphasis": true,
        "lists": true,
        "html": false,
        "link": true,
        "image": true,
        events: {},
        parserRules: {
            /**
             * CSS Class white-list
             * Following CSS classes won't be removed when parsed by the wysihtml5 HTML parser
             */
            "classes": {
                "wysiwyg-clear-both": 1,
                "wysiwyg-clear-left": 1,
                "wysiwyg-clear-right": 1,
                "wysiwyg-color-aqua": 1,
                "wysiwyg-color-black": 1,
                "wysiwyg-color-blue": 1,
                "wysiwyg-color-fuchsia": 1,
                "wysiwyg-color-gray": 1,
                "wysiwyg-color-green": 1,
                "wysiwyg-color-lime": 1,
                "wysiwyg-color-maroon": 1,
                "wysiwyg-color-navy": 1,
                "wysiwyg-color-olive": 1,
                "wysiwyg-color-purple": 1,
                "wysiwyg-color-red": 1,
                "wysiwyg-color-silver": 1,
                "wysiwyg-color-teal": 1,
                "wysiwyg-color-white": 1,
                "wysiwyg-color-yellow": 1,
                "wysiwyg-float-left": 1,
                "wysiwyg-float-right": 1,
                "wysiwyg-font-size-large": 1,
                "wysiwyg-font-size-larger": 1,
                "wysiwyg-font-size-medium": 1,
                "wysiwyg-font-size-small": 1,
                "wysiwyg-font-size-smaller": 1,
                "wysiwyg-font-size-x-large": 1,
                "wysiwyg-font-size-x-small": 1,
                "wysiwyg-font-size-xx-large": 1,
                "wysiwyg-font-size-xx-small": 1,
                "wysiwyg-text-align-center": 1,
                "wysiwyg-text-align-justify": 1,
                "wysiwyg-text-align-left": 1,
                "wysiwyg-text-align-right": 1
            },
            /**
             * Tag list
             *
             * The following options are available:
             *
             *    - add_class:        converts and deletes the given HTML4 attribute (align, clear, ...) via the given method to a css class
             *                        The following methods are implemented in wysihtml5.dom.parse:
             *                          - align_text:  converts align attribute values (right/left/center/justify) to their corresponding css class "wysiwyg-text-align-*")
             *                            <p align="center">foo</p> ... becomes ... <p> class="wysiwyg-text-align-center">foo</p>
             *                          - clear_br:    converts clear attribute values left/right/all/both to their corresponding css class "wysiwyg-clear-*"
             *                            <br clear="all"> ... becomes ... <br class="wysiwyg-clear-both">
             *                          - align_img:    converts align attribute values (right/left) on <img> to their corresponding css class "wysiwyg-float-*"
             *
             *    - remove:             removes the element and its content
             *
             *    - rename_tag:         renames the element to the given tag
             *
             *    - set_class:          adds the given class to the element (note: make sure that the class is in the "classes" white list above)
             *
             *    - set_attributes:     sets/overrides the given attributes
             *
             *    - check_attributes:   checks the given HTML attribute via the given method
             *                            - url:            allows only valid urls (starting with http:// or https://)
             *                            - src:            allows something like "/foobar.jpg", "http://google.com", ...
             *                            - href:           allows something like "mailto:bert@foo.com", "http://google.com", "/foobar.jpg"
             *                            - alt:            strips unwanted characters. if the attribute is not set, then it gets set (to ensure valid and compatible HTML)
             *                            - numbers:  ensures that the attribute only contains numeric characters
             */
            "tags": {
                "tr": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "strike": {
                    "remove": 1
                },
                "form": {
                    "rename_tag": "div"
                },
                "rt": {
                    "rename_tag": "span"
                },
                "code": {},
                "acronym": {
                    "rename_tag": "span"
                },
                "br": {
                    "add_class": {
                        "clear": "clear_br"
                    }
                },
                "details": {
                    "rename_tag": "div"
                },
                "h4": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "em": {},
                "title": {
                    "remove": 1
                },
                "multicol": {
                    "rename_tag": "div"
                },
                "figure": {
                    "rename_tag": "div"
                },
                "xmp": {
                    "rename_tag": "span"
                },
                "small": {
                    "rename_tag": "span",
                    "set_class": "wysiwyg-font-size-smaller"
                },
                "area": {
                    "remove": 1
                },
                "time": {
                    "rename_tag": "span"
                },
                "dir": {
                    "rename_tag": "ul"
                },
                "bdi": {
                    "rename_tag": "span"
                },
                "command": {
                    "remove": 1
                },
                "ul": {},
                "progress": {
                    "rename_tag": "span"
                },
                "dfn": {
                    "rename_tag": "span"
                },
                "iframe": {
                    "remove": 1
                },
                "figcaption": {
                    "rename_tag": "div"
                },
                "a": {
                    "check_attributes": {
                        "href": "url" // if you compiled master manually then change this from 'url' to 'href'
                    },
                    "set_attributes": {
                        "rel": "nofollow",
                        "target": "_blank"
                    }
                },
                "img": {
                    "check_attributes": {
                        "width": "numbers",
                        "alt": "alt",
                        "src": "url", // if you compiled master manually then change this from 'url' to 'src'
                        "height": "numbers"
                    },
                    "add_class": {
                        "align": "align_img"
                    }
                },
                "rb": {
                    "rename_tag": "span"
                },
                "footer": {
                    "rename_tag": "div"
                },
                "noframes": {
                    "remove": 1
                },
                "abbr": {
                    "rename_tag": "span"
                },
                "u": {},
                "bgsound": {
                    "remove": 1
                },
                "sup": {
                    "rename_tag": "span"
                },
                "address": {
                    "rename_tag": "div"
                },
                "basefont": {
                    "remove": 1
                },
                "nav": {
                    "rename_tag": "div"
                },
                "h1": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "head": {
                    "remove": 1
                },
                "tbody": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "dd": {
                    "rename_tag": "div"
                },
                "s": {
                    "rename_tag": "span"
                },
                "li": {},
                "td": {
                    "check_attributes": {
                        "rowspan": "numbers",
                        "colspan": "numbers"
                    },
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "object": {
                    "remove": 1
                },
                "div": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "option": {
                    "rename_tag": "span"
                },
                "select": {
                    "rename_tag": "span"
                },
                "i": {},
                "track": {
                    "remove": 1
                },
                "wbr": {
                    "remove": 1
                },
                "fieldset": {
                    "rename_tag": "div"
                },
                "big": {
                    "rename_tag": "span",
                    "set_class": "wysiwyg-font-size-larger"
                },
                "button": {
                    "rename_tag": "span"
                },
                "noscript": {
                    "remove": 1
                },
                "svg": {
                    "remove": 1
                },
                "input": {
                    "remove": 1
                },
                "table": {},
                "keygen": {
                    "remove": 1
                },
                "h5": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "meta": {
                    "remove": 1
                },
                "map": {
                    "rename_tag": "div"
                },
                "isindex": {
                    "remove": 1
                },
                "mark": {
                    "rename_tag": "span"
                },
                "caption": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "tfoot": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "base": {
                    "remove": 1
                },
                "video": {
                    "remove": 1
                },
                "strong": {},
                "canvas": {
                    "remove": 1
                },
                "output": {
                    "rename_tag": "span"
                },
                "marquee": {
                    "rename_tag": "span"
                },
                "b": {},
                "q": {
                    "check_attributes": {
                        "cite": "url"
                    }
                },
                "applet": {
                    "remove": 1
                },
                "span": {},
                "rp": {
                    "rename_tag": "span"
                },
                "spacer": {
                    "remove": 1
                },
                "source": {
                    "remove": 1
                },
                "aside": {
                    "rename_tag": "div"
                },
                "frame": {
                    "remove": 1
                },
                "section": {
                    "rename_tag": "div"
                },
                "body": {
                    "rename_tag": "div"
                },
                "ol": {},
                "nobr": {
                    "rename_tag": "span"
                },
                "html": {
                    "rename_tag": "div"
                },
                "summary": {
                    "rename_tag": "span"
                },
                "var": {
                    "rename_tag": "span"
                },
                "del": {
                    "remove": 1
                },
                "blockquote": {
                    "check_attributes": {
                        "cite": "url"
                    }
                },
                "style": {
                    "remove": 1
                },
                "device": {
                    "remove": 1
                },
                "meter": {
                    "rename_tag": "span"
                },
                "h3": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "textarea": {
                    "rename_tag": "span"
                },
                "embed": {
                    "remove": 1
                },
                "hgroup": {
                    "rename_tag": "div"
                },
                "font": {
                    "rename_tag": "span",
                    "add_class": {
                        "size": "size_font"
                    }
                },
                "tt": {
                    "rename_tag": "span"
                },
                "noembed": {
                    "remove": 1
                },
                "thead": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "blink": {
                    "rename_tag": "span"
                },
                "plaintext": {
                    "rename_tag": "span"
                },
                "xml": {
                    "remove": 1
                },
                "h6": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "param": {
                    "remove": 1
                },
                "th": {
                    "check_attributes": {
                        "rowspan": "numbers",
                        "colspan": "numbers"
                    },
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "legend": {
                    "rename_tag": "span"
                },
                "hr": {},
                "label": {
                    "rename_tag": "span"
                },
                "dl": {
                    "rename_tag": "div"
                },
                "kbd": {
                    "rename_tag": "span"
                },
                "listing": {
                    "rename_tag": "div"
                },
                "dt": {
                    "rename_tag": "span"
                },
                "nextid": {
                    "remove": 1
                },
                "pre": {},
                "center": {
                    "rename_tag": "div",
                    "set_class": "wysiwyg-text-align-center"
                },
                "audio": {
                    "remove": 1
                },
                "datalist": {
                    "rename_tag": "span"
                },
                "samp": {
                    "rename_tag": "span"
                },
                "col": {
                    "remove": 1
                },
                "article": {
                    "rename_tag": "div"
                },
                "cite": {},
                "link": {
                    "remove": 1
                },
                "script": {
                    "remove": 1
                },
                "bdo": {
                    "rename_tag": "span"
                },
                "menu": {
                    "rename_tag": "ul"
                },
                "colgroup": {
                    "remove": 1
                },
                "ruby": {
                    "rename_tag": "span"
                },
                "h2": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "ins": {
                    "rename_tag": "span"
                },
                "p": {
                    "add_class": {
                        "align": "align_text"
                    }
                },
                "sub": {
                    "rename_tag": "span"
                },
                "comment": {
                    "remove": 1
                },
                "frameset": {
                    "remove": 1
                },
                "optgroup": {
                    "rename_tag": "span"
                },
                "header": {
                    "rename_tag": "div"
                }
            }
        },
        locale: "en"
    };

    if (typeof $.fn.wysihtml5.defaultOptionsCache === 'undefined') {
        $.fn.wysihtml5.defaultOptionsCache = $.extend(true, {}, $.fn.wysihtml5.defaultOptions);
    }

    var locale = $.fn.wysihtml5.locale = {
        en: {
            font_styles: {
                normal: "Normal text",
                h1: "Heading 1",
                h2: "Heading 2",
                h3: "Heading 3"
            },
            emphasis: {
                bold: "Bold",
                italic: "Italic",
                underline: "Underline"
            },
            lists: {
                unordered: "Unordered list",
                ordered: "Ordered list",
                outdent: "Outdent",
                indent: "Indent"
            },
            link: {
                insert: "Insert link",
                cancel: "Cancel"
            },
            image: {
                insert: "Insert image",
                cancel: "Cancel"
            },
            html: {
                edit: "Edit HTML"
            },
            colours: {
                black: "Black",
                silver: "Silver",
                gray: "Grey",
                maroon: "Maroon",
                red: "Red",
                purple: "Purple",
                green: "Green",
                olive: "Olive",
                navy: "Navy",
                blue: "Blue",
                orange: "Orange"
            }
        }
    };

}(window.jQuery, window.wysihtml5);