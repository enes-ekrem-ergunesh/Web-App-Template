var BookService = {

  init: function(){
    $("#add-book-form").validate({
      submitHandler: function(form) {
        var entity = Object.fromEntries((new FormData(form)).entries());
        console.log(entity);
        BookService.add(entity);
      }
     });
  },

  list: function () {
    $.ajax({
      url: 'rest/books',
      type: 'GET',
      success: function (response) {
        // do something with the response

      },
      error: function () {
        BookService.error();
      }
    });
  },

  add: function (entity){
    $.ajax({
      url: 'rest/books',
      type: 'POST',
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        // do something with the response

      },
      error: function () {        
        BookService.error();
      }
    });
  },

  delete: function (id) {

    $.ajax({
      url: 'rest/books/'+id,
      type: 'DELETE',
      success: function (response) {
        // do something with the response

      },
      error: function () {
        BookService.error();
      }
    });
  },

  error: function () {
    console.log("something went wrong");
  },



}