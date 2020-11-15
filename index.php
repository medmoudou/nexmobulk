<!doctype html>
<html lang="en">
    <head>
          <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            
        <title>Nexmo API</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>





        <style>
          .badge-warning {
              color: white;
          }
          #result {
              background-color: #efefef;
              font-size: 0.85em;
          }
          label.error {
              color: red;
          }
        </style>
    </head>
    <body>


        <div class="container">
            <br>
            <br>
            <div class="card">
                <div class="card-header">
                    Nexmo API info
                </div>
                <div class="card-body">
                    <div class="col">

                      <form name="apiForm" id="apiForm">
                        <div class="row">
                          <div class="col-md-4 mb-3">
                              <label for="api_key">Nexmo API Key</label>
                              <input type="text" class="form-control" name="api_key" id="api_key" placeholder="API Key" required>
                          </div>
                          <div class="col-md-4 mb-3">
                              <label for="api_secret">Nexmo API Secret</label>
                              <input type="text" class="form-control" name="api_secret" id="api_secret" placeholder="API Secret" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 mb-3">
                              <input type="submit" class="btn btn-info mb-2" name="btnConfirm" value="Connecter" />
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="col">
                        <span id="message"></span>
                  </div>
                </div>
            </div>
            <br>

  <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nexmo bulk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Connect your Nexmo API first !
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
            <br>
            <div class="card">
                <div class="card-header">
                    Message info
                </div>
                <div class="card-body">
                    <div class="col">

                      <form name="sendForm" id="sendForm">
                        <div class="row">
                          <div class="col-md-4 mb-3">
                              <label for="sender_name">Sender name</label>
                              <input type="text" class="form-control" name="sender_name" id="sender_name" placeholder="Sender" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-4 mb-3">
                              <label for="s_message">Message <span class="badge badge-warning" id="charMsg">0</span> </label> 
                              <textarea class="form-control" id="s_message" name ="s_message" rows="6" onkeyup="countChar(this)" required></textarea>
                          </div> 
                          <div class="col-md-4 mb-3">
                              <label for="number_list">Numbers list <span class="badge badge-warning" id="nbrMsg">0</span></label>
                              <textarea class="form-control" id="number_list" name="number_list" rows="6" onkeyup="countNums(this)" required></textarea>
                          </div> 
                        </div>                      
                        <div class="row">
                          <div class="col-md-4 mb-3">
                              <input type="submit" class="btn btn-info mb-2" name="btnSend" value="Send message" />
                          </div>
                        </div>
                        <label for="logs">Logs :</label>
                        <div class="alert alert-light" role="alert" id='result'>
                        </div>
                      </form>

                    </div>
                  
                </div>
            </div>
        </div> 

        <script>


$(document).ready(function () {
    $('#number_list').focusout(function () {
        var text = $('#number_list').val();
        text = text.replace(/(?:(?:\r\n|\r|\n)\s*){2}/gm, "");
        $(this).val(text);
    });
});



            var APIKEY;
            var APISECRET;
            var CONNECTED = false;
            var cpt=0;
            $(document).ready(function(){
                $("#apiForm").validate({
                    submitHandler: function(form)
                    {

                      var text = $("#api_key").val();
                      if(text === ""){
                          $("#api_key").css({"border" : "1px solid red", "color" : "red"}).val("this is required");
                      }else{
                          // the function you want to do with the form
                      }
                        var fomr = $('#apiForm')[0];
                        var formData = new FormData(fomr);
                        $.ajax({
                        type: 'POST',
                        url: "nexmo.php",
                        dataType: "json",
                        data:formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        success: function(data)
                        {
                            if(data.code == 1){
                                
                                CONNECTED = false;
                            }
                            else
                            {
                                CONNECTED = true;
                                APIKEY = $("#api_key").val();
                                APISECRET =  $("#api_secret").val();
                                
                            }
                            $('#message').html(data.msg);
                            
                        }
                        });
                        return false;
                    }
                });

            });

            $(document).ready(function(){
                $("#sendForm").validate({
                    submitHandler: function(form)
                    {
                        if (CONNECTED){
                            $("#result").html("");
                            cpt = 0;
                            var sender_name = $("#sender_name").val();
                            var s_message = $("#s_message").val();
                            var number_list = $("#number_list").val().split('\n');
                            for(var i = 0;i < number_list.length;i++){
                                $.ajax({
                                    type: 'POST',
                                    url: "test.php",
                                    data: {sender_name:sender_name, s_message:s_message, number_list:number_list[i], api_key:APIKEY, api_secret: APISECRET},
                                    //cache:false,
                                    //contentType: false,
                                    //processData: false,
                                    success: function(data)
                                    {
                                         //var X = data + X;
                                        //alert(X);
                                        //$('#result').html(X);
                                        $('#result').append(data);
                                        if(data.indexOf('OK') != -1){
                                            cpt++;
                                        }
                                    }
                                });
                            }
                        }
                        else
                        {
                            $('#exampleModal').modal('show')
                        }
                        
                        //return false;
                    }
                });
            });



            // $(document).ready(function(){
            //     $("#sendForm").validate({
            //         submitHandler: function(form)
            //         {
            //             var fr = $('#sendForm')[0];
            //             var frdata = new FormData(fr);
            //              $.ajax({
            //                 type: 'POST',
            //                 url: "test.php",
            //                 data:frdata,
            //                 cache:false,
            //                 contentType: false,
            //                 processData: false,
            //                 success: function(data)
            //                 {
            //                      //var X = data + X;
            //                     //alert(X);
            //                     //$('#result').html(X);
            //                     $('#result').append(data);
            //                 }
            //             });
            //             //return false;
            //         }
            //     });
            // });


            // $(document).ready(function(){
            //     $("#sendForm").validate({
            //         submitHandler: function(form)
            //         {
            //             var i;
            //             var lines = $('#numberlist').val().split('\n');
            //             for (i = 0; i < lines.length; i++) { 
            //                 var fomr = $('form')[0];
            //                 var formData = new FormData(fomr);
            //                 $.ajax({
            //                 type: 'POST',
            //                 url: "test.php",
            //                 data:formData,
            //                 cache:false,
            //                 contentType: false,
            //                 processData: false,
            //                 success: function(data)
            //                 {
            //                     //var X = data + X;
            //                     //alert(X);
            //                     //$('#result').html(X);
            //                     $('#result').append(data);
                                
            //                 }
            //                 });
            //                 //return false;
            //             }
                        
            //         }
            //     });

            // });

            function countChar(val) {
              var len = val.value.length;
              $('#charMsg').text(len);
            };
            function countNums(val) {
              var len = val.value.split("\n").length;
              $('#nbrMsg').text(len);
            };
        </script>

    </body>
</html>