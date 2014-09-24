$(function () {
    var $table = $("table");
    var sortable_start_item = $table.data('start');
    var sortable_url = $table.data('sort-url');
    var sortable_start_pos = null;
    var sortable_end_pos = null;
    var new_url = null;

    $(".sortable").sortable({
        placeholder: "ui-state-highlight",
        start: function (event, ui) {
            sortable_start_pos = $(ui.item).data('sort-id');
        },
        update: function (event, ui) {
            sortable_end_pos = ui.item.index() + sortable_start_item;
            new_url = sortable_url.replace("replace_old_id", sortable_start_pos);
            new_url = new_url.replace("replace_new_id", sortable_end_pos);

            $('#sort-submit').parent('form').attr('action', new_url).submit();
        }
    });

});