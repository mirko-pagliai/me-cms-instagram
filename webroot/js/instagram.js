/*!
 * This file is part of me-cms-instagram.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/me-cms-instagram
 * @license     https://opensource.org/licenses/mit-license.php MIT License
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