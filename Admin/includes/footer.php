<div class="container-fluid">
<footer class=footer>
  <p>This website is Copyright © by Aimua jeffrey 2007 - 2018 World Wide Rights Reserved.
</p>

</footer>
</div>
<script>
// function to take entry details from modal to size and Quantity Preview field
function updateSizes(){
var sizeString = '';
  for(i=1; i<=8; i++){
    if(jQuery('#size'+i).val() != ''){
      sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+',';
    }
  }
  jQuery('#sizes').val(sizeString);
}
// function for jquery ajax
function get_child_options(selected){
  if(typeof selected === 'undefined'){
    var selected = '';
  }
  var parentID = jQuery('#parent'). val();
  jQuery.ajax({
    url: '/business/admin/parsers/child_catigories.php',
    type:'POST',
    data: {parentID : parentID, selected: selected},
    success: function (data){
      jQuery('#child').html(data);

    },
    error: function(){alert("something went wrong with the child option.")},
  });
}
  jQuery('select[name="parent"]').change(function(){
    get_child_options();
  });
</script>



<!-- this licks are here because of bootstrap modal -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>
