<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

 <div class="container"><br><br><br><br>
        <a href="#" class="btn btn-info"   id="Characters_id">Pull People/Characters</a>
   
  </div>



<table class="table table-responsive table-hover">
      <thead>
        <tr><th>ID</th> <th>name</th></tr>
    </thead>
    <tbody id="apent_id">


    </tbody>
</table>
<br><br>

<table class="table table-responsive table-hover">
  <thead>
   <tr><th>Name</th> <th>Films-url</th></tr>
</thead>
<tbody id="apent_id1">


</tbody>
</table>




<div class="container">


  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">



        <table class="table table-responsive table-hover">
          <thead>
           <tr>  <th>Films-url</th></tr>
       </thead>


       <thead id="apent_modal">
       </thead>




   </table>

   <div id="apent_modal1"> </div>



</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>

</div>
</div>

<script type="text/javascript">

    var globle='';

    $(document).on("click","#Characters_id",function() {
      var  arr=Array();
      $('#apent_id').empty().append();
      $.ajax({
          type:'POST',
          url:"{{URL::to('assigment')}}",
          data:{
              "_token": "{{ csrf_token() }}",
              "id": 10
          },
          success:function(msg){
            globle=msg;
            $(msg).each(function( index,val ) {
               var myJSON = JSON.stringify(val.films);
               arr.push('  <tr class="clickable"   >  <td class="btn btn-link"> Pull FilmsM</td>  <td>'+val.name+'</td></tr>  </tr> ');

           });

            $('#apent_id').empty().append(arr);

        }


    });


  })







    $(document).on("click",".clickable",function() {   
      var name=$(this).closest('tr').find("td").eq(1).text();
      // var arrdata= $(this).closest('tr').attr('id');
      // var dataa= $('.'+arrdata).text();

      var  arr2=Array();
      var  arr3=Array();
      $('#apent_modal1').html();

      $(globle).each(function( index,val ) {
         if(val.name==name){
          $(val.films).each(function( index1,val1 ) {
            arr2.push('  <tr class="clickable1"  >     <td>'+val1+'</td>  </tr>   ');

            arr3.push('<input type="hidden" name="films[]" value="'+val1+'" >');
            


        });
          arr3.push('<input type="hidden" name="name" value="'+name+'" >');
          $('#apent_modal').empty().append(arr2); 
          $('#apent_modal1').empty().append('<form id="get_data">'+arr3.join(',')+' <a href="#" class="btn btn-info"   id="Save_Data">Save Data</a> <input type="hidden" name="_token" value="{{ csrf_token() }}" >  </form>   '); 
          $('#myModal').modal('show');

          return  false;
      }

  });
  });


    $(document).on("click","#Save_Data",function() { 


      var  arr1=Array();
     // console.log($("#get_data").serialize());

      $.ajax({
       type:"post",
       url:"{{URL::to('assigment-add')}}",
       data:$("#get_data").serialize(),

       success:function(msg){  

          $('#apent_id1').empty().append();

          console.log(msg);

          if(msg.status==1){
            alert('data already exist....' );
        }

        if(msg.status==0){
           $(msg.messages).each(function( index,val ) {

            arr1.push('  <tr    id="row'+index+'"  > <td> <td>'+val.name+'</td></td> <td>'+val.films_name+'</td></tr>   ');
        });

           $('#apent_id1').empty().append(arr1);


       }

       if(msg.status==2){

         alert('something error 404' );

     }


     $('#myModal').modal('hide');
 }


});

  });



</script>

</body>



</html>
