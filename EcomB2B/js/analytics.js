/*Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 February 2019 at 14:56:07 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo
 Version 3.0*/


function onProductClick(element) {
  ga('auTracker.ec:addProduct', $(element).data('analytics'));
  ga('auTracker.ec:setAction', 'click', {list: $(element).data('list')});


  var link=$(element).attr('href')
  console.log($(element).data('analytics'))
  ga('auTracker.send', 'event', 'UX', 'click', $(element).data('list'), {
    hitCallback: function() {
      document.location = link;
    }
  });
}