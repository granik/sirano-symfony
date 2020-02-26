$(function () {
    $('form#directions_form,\
        form#mobile_directions_form'
    ).submit(function (e) {
        var selectedDirection = $(this).closest('form').find('input[name="direction"]:checked').val();

        if (selectedDirection == 0) {
            $.post(
                DIRECTION_DROP_SELECTED,
                {},
                function () {
                    window.location.reload();
                }
            );
        } else {
            $.post(
                DIRECTION_SELECT,
                {
                    id: selectedDirection
                },
                function () {
                    window.location.reload();
                }
            );
        }

        return false;
    });

    $('a.direction').click(function () {
        var $this = $(this);

        var selectedDirection = $this.data('id');

        $.post(
            DIRECTION_SELECT,
            {
                id: selectedDirection
            },
            function () {
                window.location.reload();
            }
        );

        return false;
    });
});