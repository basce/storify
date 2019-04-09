$(document).ready(function() {
/*
  https://github.com/brianreavis/selectize.js/issues/470
  Selectize doesn't display anything to let the user know there are no results.
  This plugin allows us to render a no results message when there are no
  results are found to select for.
*/

Selectize.define( 'no_results', function( options ) {
  var self = this;

  options = $.extend({
    message: 'No results found.',

    html: function(data) {
      return (
        '<div class="selectize-dropdown ' + data.classNames + '">' +
          '<div class="selectize-dropdown-content">' +
            '<div class="no-results">' + data.message + '</div>' +
          '</div>' +
        '</div>'
      );
    }
  }, options );

  self.displayEmptyResultsMessage = function () {
    this.$empty_results_container.css('top', this.$control.outerHeight());
    this.$empty_results_container.css('width', this.$control.outerWidth());
    this.$empty_results_container.show();
    this.$control.addClass("dropdown-active");
  };

  self.refreshOptions = (function () {
    var original = self.refreshOptions;

    return function () {
      original.apply(self, arguments);
      if (this.hasOptions || !this.lastQuery) {
        this.$empty_results_container.hide()
      } else {
        this.displayEmptyResultsMessage();
      }
    }
  })();

  self.onKeyDown = (function () {
    var original = self.onKeyDown;

    return function ( e ) {
      original.apply( self, arguments );
      if ( e.keyCode === 27 ) {
        this.$empty_results_container.hide();
      }
    }
  })();

  self.onBlur = (function () {
    var original = self.onBlur;

    return function () {
      original.apply( self, arguments );
      this.$empty_results_container.hide();
      this.$control.removeClass("dropdown-active");
    };
  })();

  self.setup = (function() {
    var original = self.setup;
    return function() {
      original.apply(self, arguments);
      self.$empty_results_container = $(options.html($.extend({
        classNames: self.$input.attr('class')
      }, options)));
      self.$empty_results_container.insertBefore(self.$dropdown);
      self.$empty_results_container.hide();
    };
  })();
});
});