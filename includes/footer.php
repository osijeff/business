
<div class="container-fluid">
<footer class="footer">
  <p>This website is Copyright © by Aimua jeffrey 2007 - 2018 World Wide Rights Reserved</P>

</footer>
</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->


    <script
			  src="https://code.jquery.com/jquery-3.3.1.js"
			  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
			  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
    jQuery(window).scroll(function(){
      var vscroll = jQuery(this).scrollTop();
      jQuery('#logotext').css({
        "transform" : "translate(0px, "+vscroll/3+"px)"
      });

      var vscroll = jQuery(this).scrollTop();
      jQuery('#back-flower').css({
        "transform" : "translate("+vscroll/5+"px, -"+vscroll/12+"px)"
      });

      var vscroll = jQuery(this).scrollTop();
      jQuery('#fore-flower').css({
        "transform" : "translate(0px, -"+vscroll/3+"px)"
      });

    });
// details modal
function detailsmodal(id){
  var data = {"id" : id};
  jQuery.ajax({
  url : '/business/includes/details.php',
  method : "POST",
  data : data,
  success: function(data){
    jQuery('body').append(data);
    jQuery('#details-modal').modal('toggle');
  },
  error: function(){
    alert("something went wrong!");
  }
});
}
//function to update cart in lesson 29
function update_cart(mode,edit_id,edit_size){
  var data = {"mode" : mode, "edit_id" : edit_id,  "edit_size" : edit_size};
  jQuery.ajax({
    url : '/business/admin/parsers/update_cart.php',
    method : "POST",
    data : data,
    success : function(){
      location.reload();},
    error : function(){
      alert("something went wrong");}
  });
}
function add_to_cart(){
  jQuery('#modal_errors').html("");
  var size = jQuery('#size').val();
  var quantity = jQuery('#quantity').val();
  //var available = jQuery('#available').val();
  var available = parseInt(jQuery('#available').val());
  var error = '';
  var data = jQuery('#add_product_form').serialize();
    if (size =='' || quantity == '' || quantity == 0) {
      error += '<p class="text-danger text-center">You must choose a size and quantity.</p>';
      jQuery('#modal_errors').html(error);
      return;
    } else if (quantity > available) {
      error += '<p class="text-danger text-center">There are only '+available+' available.</p>';
      jQuery('#modal_errors').html(error);
      return;
    }else{
      jQuery.ajax({
        url : '/business/admin/parsers/add_cart.php',
        method : "POST",
        data : data,
        success: function(){
          location.reload();
        },
        error: function(){alert("page not yet available");}
      });
    }

}
</script>

  </body>
</html>
