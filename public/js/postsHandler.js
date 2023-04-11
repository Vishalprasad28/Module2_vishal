$(document).ready(function(){
  var offset = 0;
  getBooks(offset);
  
  //Load More Button Functionality
  $('.load-more').click(function(){
    getBooks(offset);
  });

  //add to bucket
  $('.books').on('click', '#btn', function(){
    $(this).toggleClass('added');
    if ($(this).hasClass('added')) {
      addToBucket($(this).parent().attr('book_id'), 1);
    }
    else {
      addToBucket($(this).parent().attr('book_id'), 0);
    }
    // addToBucket();
  });
  
  //All Functions here

  //Function to fetch all the books from the database
  function getBooks(e) {
    $.post('/fetchBooks', { offset: offset}, function(data, status) {
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