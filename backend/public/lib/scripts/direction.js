$(function(){$("form#directions_form,        form#mobile_directions_form").submit(function(o){var i=$(this).closest("form").find('input[name="direction"]:checked').val();return 0==i?$.post(DIRECTION_DROP_SELECTED,{},function(){window.location.reload()}):$.post(DIRECTION_SELECT,{id:i},function(){window.location.reload()}),!1}),$("a.direction").click(function(){var o=$(this).data("id");return $.post(DIRECTION_SELECT,{id:o},function(){window.location.reload()}),!1})});