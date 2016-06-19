/**
 * Restfulize any hiperlink that contains a data-method attribute by
 * creating a mini form with the specified method and adding a trigger
 * within the link.
 * Requires jQuery!
 *
 * Ex:
 *     <a href="post/1" data-method="delete">destroy</a>
 *
 */
$(function () {
    var $button = $('[data-method]');

    $button.append(function () {
        return "\n" +
        "<form action='" + $(this).attr('href') + "' method='POST' style='display:none'>\n" +
        "   <input type='hidden' name='_method' value='" + $(this).attr('data-method') + "'>\n" +
        "   <input type='hidden' name='_token' value='" + $(this).attr('data-token') + "'>\n" +
        "</form>\n";
    })
    .removeAttr('href')
    .attr('style', 'cursor:pointer;');

   $button.on('click', function() {
       if (confirm('Confirm?')) {
           $(this).find("form").submit();
       }
   });
    
});