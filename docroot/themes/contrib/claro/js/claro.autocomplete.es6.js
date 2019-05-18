/**
 * @file
 * Claro's enhancement for autocomplete form element.
 */

(($, Drupal) => {
  Drupal.behaviors.claroAutoCompete = {
    attach(context) {
      $(context)
        .find('input.form-autocomplete')
        .once('claroAutoComplete')
        .each(function() {
          const timeout = 400;
          let classRemoveTimeout;
          const classRemove = $autoCompleteElem => {
            $autoCompleteElem.removeClass('is-autocompleting');
          };

          $(this).on('input autocompletesearch autocompleteresponses', function(
            event,
          ) {
            if (event && event.type && event.type === 'autocompletesearch') {
              $(this).addClass('is-autocompleting');
            }
            clearTimeout(classRemoveTimeout);
            classRemoveTimeout = setTimeout(classRemove, timeout, $(this));
          });
        });
    },
  };
})(jQuery, Drupal);
