$(document).ready(function(){
  var offset = 0;
  getBucketBooks(offset);

  //Load More Button Functionality
  $('.load-more').click(function(){
    getBucketBooks(offset);
  });

  //add to bucket
  $('.books').on('click', '#btn', function(){
    $(this).toggleClass('added');
    if ($(this).hasClass('added')) {
      //Adding to the bucket/Wish List
      addToBucket($(this).parent().attr('book_id'), 1);
    }
    else {
      //Removing from the bucket/Wish List
      addToBucket($(this).parent().attr('book_id'), 0);
      $(this).parent().remove();
    }
  });

   //All Functions here

  //Function to fetch all the books from the bucketlist database
  function getBucketBooks(e) {
    $.post('/fetchBucketList', { offset: offset}, function(data, status) {
      if (status == 'success') {
        $('.books').append(data);
        offset = $('.books').children().length;
      }
    });
  }

  //Functio to add or Remove the books to the bucketlist
  function addToBucket(e, temp) {
    var added = temp;
    $.post('/addToBucket', {added: added, bookId: e})
  }
});
