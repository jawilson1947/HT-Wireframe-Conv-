;(function($, window, document, undefined){

    var AutodeskHelpful = function(element, options) {
        this.options = $.extend(true, {}, this.defaultOptions, options || {});

        var $element = $(element);

        if ($element.hasClass('was-this-helpful')) {
            this.element = $element;
        } else {
            this.element = $(document.createElement('div')).addClass('was-this-helpful');
            $element.append(this.element);
        }

        this._create();
    };

    $.extend(AutodeskHelpful.prototype, {
        defaultOptions: {
            server: '//beehive-dev.autodesk.com',
            event: 'helpful',
            i18n: Drupal.settings.helpful_l10n_strings,
            cssClasses: {
                base: 'helpful-container',
                header: '',
                question: '',
                questionButtons: 'helpful-button small-button',
                commentSubmitButton: ''
            },
            useTrackEvent: true,
            commentsEnabled: true,
            commentsMaxLength: 300
        },

        clearSelection: function() {
            this._setValue(undefined);
        },

        _setValue: function(value) {
            // Set the Machine Translated title for analytics.
          if(this.loggingHelper.options.data.mt==="yes"){
            var title=$('div.caas-content-result.adskLsSegAnyLocale > h1 > span').text(); 
            if(title.trim() !=="") {
              this.loggingHelper.options.data.t=title;
            }
          } else{
            this.loggingHelper.options.data.t=document.title;
          }

            // setValue automatically calls eventlog
            this.loggingHelper.setValue(value);

            // optional call to eventtrack
            if ((value !== undefined) && this.options.useTrackEvent) {
                var data = this.loggingHelper.getData();

                $.extend(data, {
                    ekey: data.ck || data.u,
                    kt: !!data.ck ? 'caaskey' : 'url'
                });

                this.loggingHelper._logger.track(data);
            }
        },

        _disableButtons: function() {
            this.buttonYes.detach();
            this.buttonNo.detach();
        },

        _enableButtons: function() {
            this.theButtons.append(this.buttonYes, this.buttonNo);
        },

        _limitLength: function(textarea) {
            var fn = {
                commonFilter: function (e) {
                    var maxlen = parseInt(textarea.attr('maxlength'), 10) || 300;
                    var val = $.trim(textarea.val());

                    if (val.length > maxlen) {
                        textarea.val(val.substring(0, maxlen));
                        e.preventDefault();
                    }
                },
                downFilter: function (e) {
                    var maxlen = parseInt(textarea.attr('maxlength'), 10) || 300;
                    var val = $.trim(textarea.val());

                    if (val.length === maxlen && e.which > 32) e.preventDefault();
                }
            };

            return $(textarea).on({ keypress: fn.commonFilter, keyup: fn.commonFilter, blur: fn.commonFilter, keydown: fn.downFilter });
        },

        _onStateChange: function(value) {
            if (value !== undefined) this.__userStateChanged = true;

            this.refresh();
        },

        _create: function() {
            var template =  '<div class="{{cssClasses.base}}">' +
                                '<h3 class="{{cssClasses.header}}">{{i18n.HELPFUL_HEADER}}</h3>' +
                                '<div class="helpful-body">' +
                                    '<span class="helpful-text {{cssClasses.question}}">{{i18n.HELPFUL_QUESTION}}</span>' +
                                    '<div class="helpful-action-buttons">' +
                                        '<button class="helpful-action helpful-yes {{cssClasses.questionButtons}}">{{i18n.HELPFUL_YES}}</button>' +
                                        '<button class="helpful-action helpful-no {{cssClasses.questionButtons}}">{{i18n.HELPFUL_NO}}</button>' +
                                        '<span class="helpful-action helpful-reset">{{i18n.HELPFUL_CHANGEANSWER}}</span>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="helpful-comment-wrap">' +
                                    '<p class="helpful-comment-title"></p>' +
                                    '<form class="helpful-comment-form">' +
                                        '<textarea class="helpful-comment-field" maxlength="{{commentsMaxLength}}"></textarea>' +
                                        '<span class="helpful-comment-counter"></span>' +
                                        '<button type="button" class="helpful-action helpful-comment-button {{cssClasses.commentSubmitButton}}">{{i18n.HELPFUL_COMMENT_SUBMIT}}</button>' +
                                    '</form>' +
                                '</div>' +
                            '</div>';

            this.element.empty().append(AKPAnalytics.util.interpolateText(template, this.options));

            this.helpfulBody = this.element.find('.helpful-body');
            this.theButtons = this.element.find('.helpful-action-buttons');
            this.question = this.element.find('.helpful-text');

            this.buttonYes = this.theButtons.find('.helpful-yes').data('adsk-ui-response', {value: true}).detach();
            this.buttonNo = this.theButtons.find('.helpful-no').data('adsk-ui-response', {value: false}).detach();
            this.buttonClear = this.theButtons.find('.helpful-reset').detach();

            this.commentsWrap = this.element.find('.helpful-comment-wrap').detach();
            this.commentsTitle = this.commentsWrap.find('.helpful-comment-title');
            this.commentsCounter = this.commentsWrap.find('.helpful-comment-counter');
            this.commentsField = this.commentsWrap.find('.helpful-comment-field').val('');

            this._limitLength(this.commentsField);
            this._bindEvents();

            // initiate a logging helper
            this.loggingHelper = new AKPAnalytics.LoggingHelper({
                appId: this.options.appId,
                server: this.options.server,
                userId: this.options.userId,
                event: this.options.event,
                stateful: true,
                data: this.options.data,
                onStateChange: $.proxy(this._onStateChange, this)
            });

            return this.refresh();
        },

        _destroy: function () {
            this.element.empty();
        },

        _bindEvents: function(){
            var self = this;

            this.element.on('click', '.helpful-action-buttons .helpful-action', function(){
                var data = $(this).data('adsk-ui-response') || {};

                self._setValue(data.value);
            });

            this.element.on('click', '.helpful-comment-wrap .helpful-comment-button', function(){
                var comment = $.trim(self.commentsField.val());
                var data = self.loggingHelper.getData();

                if (!comment) return;

                $.extend(data, {
                    uc: comment,
                    e: data.e + '_comment'
                });

                self.loggingHelper._logger.log(data);
                self.__userFeedbackGiven = true;

                self.refresh();
            });

            this.element.on('submit', '.helpful-comment-form', function(e){
                e.preventDefault();
            });

            this.element.on('keyup', '.helpful-comment-field', function(){
                self._updateCounter();
            });
        },

        setOptions: function(options) {
            $.extend(true, this.options, options);

            if (options.i18n || options.server || options.event) {
                return this._create();
            } else {
                return this.refresh();
            }
        },

        _updateCounter: function() {
            var comment = $.trim(this.commentsField.val());

            this.commentsCounter.html(comment.length + '/' + this.options.commentsMaxLength);

            if (comment.length === this.options.commentsMaxLength) {
                this.commentsCounter.addClass('limit-reached');
            } else {
                this.commentsCounter.removeClass('limit-reached');
            }
        },

        refresh: function(){
            var currentValue = this.loggingHelper.getValue();

            if (currentValue !== undefined) {
                var feedback = currentValue ? this.options.i18n.HELPFUL_POSITIVEFEEDBACK : this.options.i18n.HELPFUL_NEGATIVEFEEDBACK;

                this.question.html(feedback);
                this.buttonClear.appendTo(this.theButtons);
                this._disableButtons();

                if (this.options.commentsEnabled && this.__userStateChanged && !this.__userFeedbackGiven) {
                    var formTitle = currentValue ? this.options.i18n.HELPFUL_COMMENT_POSITIVE : this.options.i18n.HELPFUL_COMMENT_NEGATIVE;

                    this.commentsTitle.html(formTitle);
                    this.helpfulBody.after(this.commentsWrap);
                } else {
                    this.commentsWrap.detach();

                    if (this.__userFeedbackGiven) {
                        this.helpfulBody.html('<span class="helpful-text">' + this.options.i18n.HELPFUL_COMMENT_SENT + '</span>');
                    }
                }
            } else {
                this.question.html(this.options.i18n.HELPFUL_QUESTION);
                this.buttonClear.detach();
                this._enableButtons();
                this.commentsWrap.detach();
            }

            return this;
        }
    });

    $.fn.helpful = function(options) {
        if (this.data('autodeskHelpful')) {
            if (options === 'option') {
                return this.data('autodeskHelpful').setOptions(arguments[1]);
            } else {
                return this.data('autodeskHelpful');
            }
        } else {
            var widget = new AutodeskHelpful(this, options);

            this.data('autodeskHelpful', widget);
            return widget;
        }
    };

})(jQuery, window, document);
