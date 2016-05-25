;
(function($, window, document, undefined) {

  var AutodeskSearchHelpful = function(element, options) {
    this.options = $.extend(true, {}, this.defaultOptions, options || {});

    var $element = $(element);

    if ($element.hasClass('was-search-helpful')) {
      this.element = $element;
    } else {
      this.element = $(document.createElement('div')).addClass(
        'was-search-helpful');
      $element.append(this.element);
    }

    this._create();
  };

  $.extend(
    AutodeskSearchHelpful.prototype, {
      defaultOptions: {
        server: '//beehive-dev.autodesk.com',
        event: 'search_helpful',
        i18n: Drupal.settings.feedback_l10n_app_strings,
        cssClasses: {
          base: 'helpful-container',
          header: '',
          question: '',
          questionButtons: 'helpful-button small-button',
          commentSubmitButton: ''
        },
        useTrackEvent: false,
        commentsEnabled: true,
        commentsMaxLength: 300
      },

      clearSelection: function() {
        this._setValue(undefined);
      },

      _setValue: function(value) {
        // setValue automatically calls eventlog
            this.loggingHelper.setValue(value);
      },

      _disableButtons: function() {
        this.buttonYes.detach();
        this.buttonNo.detach();
      },

      _enableButtons: function() {
        this.theButtons.append(this.buttonYes,
          this.buttonNo);
      },

      _limitLength: function(textarea) {
        var fn = {
          commonFilter: function(e) {
            var maxlen = parseInt(textarea
              .attr('maxlength'), 10) || 300;
            var val = $.trim(textarea.val());

            if (val.length > maxlen) {
              textarea.val(val.substring(0, maxlen));
              e.preventDefault();
            }
          },
          downFilter: function(e) {
            var maxlen = parseInt(textarea
              .attr('maxlength'), 10) || 300;
            var val = $.trim(textarea.val());

            if (val.length === maxlen && e.which > 32)
              e.preventDefault();
          }
        };

        return $(textarea).on({
          keypress: fn.commonFilter,
          keyup: fn.commonFilter,
          blur: fn.commonFilter,
          keydown: fn.downFilter
        });
      },

      _onStateChange: function(value) {
        if (value !== undefined)
          this.__userStateChanged = true;

        this.refresh();
      },

      _create: function() {
        var template = '<div class="{{cssClasses.base}}">' +
        '<h3 class="{{cssClasses.header}}">{{i18n.HELPFUL_HEADER}}</h3>' +
        '<div class="helpful-body">' +
        '<span class="helpful-text {{cssClasses.question}}">{{i18n.HELPFUL_QUESTION}}</span>' +
        '<div class="helpful-action-buttons">' +
        '<button class="helpful-action helpful-yes {{cssClasses.questionButtons}}">{{i18n.HELPFUL_YES}}</button>' +
        '<button class="helpful-action helpful-no {{cssClasses.questionButtons}}">{{i18n.HELPFUL_NO}}</button>' +
        '</div>' + '<div class="feedback-question">' +
        '<select  name="feedback_options" id="feedback_option_select">' +
        '<option value="">-- {{i18n.FEEDBACK_QN_PLACEHOLDER}} --</option>' +
        '<option value="install">{{i18n.FEEDBACK_QN_INSTALL}}</option>' +
        '<option value="technical">{{i18n.FEEDBACK_QN_TECHNICAL}}</option>' +
        '<option value="download">{{i18n.FEEDBACK_QN_DOWNLOAD}}</option>' +
        '<option value="narrow_search">{{i18n.FEEDBACK_QN_NARROW}}</option>' +
        '<option value="customer_support">{{i18n.FEEDBACK_QN_CUST_SUPP}}</option>' +
        '<option value="too_slow">{{i18n.FEEDBACK_QN_TOO_SLOW}}</option>' +
        '<option value="irrelevant">{{i18n.FEEDBACK_QN_IRRELEVANT}}</option>' +
        '<option value="same_info">{{i18n.FEEDBACK_QN_SAME_INFO}}</option>' +
        '<option value="other">{{i18n.FEEDBACK_QN_OTHER}}</option><optgroup label=""></optgroup>' +
        '</select>' +'</div>' +
        '<div class="feedback_contactus"><a href="/contactus">{{i18n.FEEDBACK_CONTACT_US_LINK}}</a></div>' +
        '</div>' + '<div class="helpful-comment-wrap">' + '<p class="helpful-comment-title"></p>' +
        '<form class="helpful-comment-form">' +
        '<textarea class="helpful-comment-field" maxlength="{{commentsMaxLength}}"></textarea>' +
        '<span class="helpful-comment-counter"></span>' +
        '<button type="button" class="helpful-action helpful-comment-button {{cssClasses.commentSubmitButton}}">{{i18n.HELPFUL_COMMENT_SUBMIT}}</button>' +
        '</form>' + '</div>' + '</div>';

        this.element.empty().append(
          AKPAnalytics.util.interpolateText(template,
            this.options));

        this.helpfulBody = this.element.find('.helpful-body');
        this.theButtons = this.element.find('.helpful-action-buttons');
        this.question = this.element.find('.helpful-text');

        this.buttonYes = this.theButtons.find('.helpful-yes').data('adsk-ui-response', {
          value: true
        }).detach();
        this.buttonNo = this.theButtons.find('.helpful-no')
          .data('adsk-ui-response', {
            value: false
          }).detach();
        this.buttonClear = this.theButtons.find(
          '.helpful-reset').detach();

        this.commentsWrap = this.element.find(
          '.helpful-comment-wrap').detach();
        this.commentsTitle = this.commentsWrap
          .find('.helpful-comment-title');
        this.commentsCounter = this.commentsWrap
          .find('.helpful-comment-counter');
        this.commentsField = this.commentsWrap.find(
          '.helpful-comment-field').val('');
        this.feedbackQnOptions = this.helpfulBody.find(
          'div.feedback-question').detach();
        this.feedbackContactUs = this.helpfulBody.find(
          'div.feedback_contactus').detach();

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
          onStateChange: $.proxy(
            this._onStateChange, this)
        });
        return this.refresh();
      },

      _destroy: function() {
        this.element.empty();
      },

      _bindEvents: function() {
        var self = this;

        this.element.on('click',
          '.helpful-action-buttons .helpful-action',
          function() {
            var data = $(this).data(
              'adsk-ui-response') || {};
              if(search_analytics_params!==undefined && analytics_data !==undefined){
                resyncAnalyticDataForSearch(search_analytics_params);
                self.loggingHelper.options.data=analytics_data;
                self.loggingHelper._contentId=encodeURIComponent(self.loggingHelper.options.data.u); //Resetting the content Id that will be stored in local storage to retain the state.
              }
              
            self._setValue(data.value);
          });

        this.element
          .on(
            'click',
            '.helpful-comment-wrap .helpful-comment-button',
            function() {
              var comment = $
                .trim(self.commentsField
                  .val());
              var data = self.loggingHelper
                .getData();

              $.extend(data, {
                uc: comment,
                e: data.e + '_feedback'
              });

              self.loggingHelper._logger
                .log(data);
              self.__userFeedbackGiven = true;

              self.refresh();
            });

        this.element.on('submit', '.helpful-comment-form',
          function(e) {
            e.preventDefault();
          });

            /* This below listener will listen to the custom event triggered from
             * autodes_faceted_search app whenever the user changed the search Facet
             * (add facet filter or remove).
             * */
        $(document).on('resetSearchFeedbackStatus',
                function(e) {
                  self.__userFeedbackGiven = false;
                  self.clearSelection();
                  self._destroy();
                  self._create();
                  e.preventDefault();
                });

        this.element.on('keyup', '.helpful-comment-field',
          function() {
            self._updateCounter();
          });
        this.element
          .on(
            'change',
            "#feedback_option_select",
            function() {
              self._showCSLink = false;
              self._showCommentSection = false;
              var feedbackIssue = this.options[this.selectedIndex].text;
              var feedbackValue = this.value;
              var data = self.loggingHelper
                .getData();
              if (feedbackValue !== "") {
                if (feedbackValue === "customer_support") {
                  self._showCSLink = true;
                  self._showCommentSection = false;
                } else {
                  self._showCSLink = false;
                  self._showCommentSection = true;
                  $.extend(data, {
                    si: feedbackIssue
                  });
                  self.loggingHelper.options.data=data;//to save the Feeback Issue value.
                }
              }
              self.refresh();
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
          this.commentsCounter
            .removeClass('limit-reached');
        }
      },

      refresh: function() {
        var currentValue = this.loggingHelper.getValue();

        if (currentValue !== undefined) {
          var feedback = currentValue ? this.options.i18n.HELPFUL_POSITIVEFEEDBACK : this.options.i18n.HELPFUL_NEGATIVEFEEDBACK;

          this.question
            .html(this.options.i18n.HELPFUL_POSITIVEFEEDBACK);
          this.buttonClear.appendTo(this.theButtons);
          this._disableButtons();

          if (!currentValue) {
            if (this.options.commentsEnabled && this.__userStateChanged && !this.__userFeedbackGiven) {
              this.question.html(feedback);
              this.helpfulBody
                .after(this.feedbackQnOptions);
              if (this._showCSLink) {
                this.feedbackQnOptions
                  .after(this.feedbackContactUs);
              } else {
                this.feedbackContactUs.detach();
              }
              //Show Comments section
              if (this._showCommentSection) {
                var formTitle = this.options.i18n.HELPFUL_COMMENT;
                this.commentsTitle.html(formTitle);
                this.feedbackQnOptions
                  .after(this.commentsWrap);
              } else {
                this.commentsWrap.detach();
              }
            } else {
              this.feedbackQnOptions.detach();
              this.commentsWrap.detach();

              if (this.__userFeedbackGiven) {
                this.helpfulBody
                  .html('<span class="helpful-text">' + this.options.i18n.HELPFUL_COMMENT_SENT + '</span>');
              }
            }
          }
        } else {
          this.question.html(this.options.i18n.HELPFUL_QUESTION);
          this.buttonClear.detach();
          this._enableButtons();
          this.feedbackQnOptions.detach();
          this.feedbackContactUs.detach();
          this.commentsWrap.detach();
        }
        return this;
      }
    });

  $.fn.searchHelpful = function(options) {
    if (this.data('autodeskSearchHelpful')) {
      if (options === 'option') {
        return this.data('autodeskSearchHelpful').setOptions(arguments[1]);
      } else {
        return this.data('autodeskSearchHelpful');
      }
    } else {
      var widget = new AutodeskSearchHelpful(this, options);

      this.data('autodeskSearchHelpful', widget);
      return widget;
    }
  };
  

})(jQuery, window, document);
