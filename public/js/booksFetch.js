$(document).ready(function(){
  var count = 0;
  bookLoader();
  function bookLoader(){
    $.post('../Controller/BooksFetcher.php',{
      count: count
    }, function(data,status){
      if (status == 'success') {
        $('.books').append(data);
        count = $('.books').children().length;
      }
    });
  }
  function wished(e) {
    $.post('../Controller/AddtoWishlist.php',{
      id: e
    }, function(data,status){
      alert(status);
    });
  }
$('.load-more').click(function() {
  bookLoader();
})
$('.books').on('click','.book',function(){
  $(this).children("#btn").toggleClass('added');
  var id = $(this).attr('book_id');
  wished(id);
});
});
