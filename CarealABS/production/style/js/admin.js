$('.formulaire div').hide();
var action;
$('.lef a').click(function(){
  action=$(this).attr('class');
  action='form-'+action;
  $('.formulaire div').hide(500);
  $('.'+action).stop().show(500);
});