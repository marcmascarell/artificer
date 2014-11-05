// restfulizer.js

/**
 * Restfulize any hiperlink that contains a data-method attribute by
 * creating a mini form with the specified method and adding a trigger
 * within the link.
 * Requires jQuery!
 *
 * Ex:
 *     <a href="post/1" data-method="delete">destroy</a>
 *     // Will trigger the route Route::delete('post/(:id)')
 *
 */
$(function(){
    $('[data-toggle="filter"]').click(function() {
        var $this = $(this);

        $this.toggleClass('active');
        $('.filters').slideToggle();
    })
});