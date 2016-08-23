/*!
 * This file is part of MeInstagram.
 * @author      Mirko Pagliai <mirko.pagliai@gmail.com>
 * @copyright   Copyright (c) 2016, Mirko Pagliai for Nova Atlantis Ltd
 * @license     http://www.gnu.org/licenses/agpl.txt AGPL License
 */

$(function () {
    //On click on the "Load more button"
    //See http://stackoverflow.com/a/16063194/1480263
    $(document).on('click', '#load-more', function (event) {
        event.preventDefault();

        $.get($(this).data('href'), function (data) {
            //Scrolls to the element
            $('html, body').animate({
                scrollTop: $("#load-more").offset().top
            }, 1500);
            //Removes the "Load more" button
            $('#load-more').remove();
            //Appends data
            $('#content').append(data);
        });

        return false;
    });
});