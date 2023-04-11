$(document).ready(function(){
  var offset = 0;
  getBucketBooks(offset);

  //Load More Button Functionality
  $('.load-more').click(function(){
    getBucketBooks(offset);
  });

   //All Functions here

  //Function to fetch all the books from the bucketlist database
  function getBucketBooks(e) {
    $.post('/fetchBucketList', { offset: offset}, function(data, status) {
      if (status == 'success') {
        alert(data.message);
      }
    });
  }
  
});