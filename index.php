<?php
session_start();
  if(isset($_GET["auth"]) && $_GET["auth"] = "OQ2020") { 
  
  // Setting secure string
  $password = mt_rand();
  $_SESSION["AUTH"] = $password;
  
  ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">


    <title>Orbit Quiz Generator</title>
  </head>
  <body>
      <div class="container container-fluid my-4">
          <img src="resources/orbit_logo.png" class="img-fluid mx-auto d-block" style="max-width:300px;"/>
          <div class="card">
              <div class="card-header">Orbit Quiz Generator</div>
              <div class="card-body">
                  <div id="form">
                      <div class="card-text my-2">
                          Welcome to the Orbit quiz presentation generator. This tool will allow you to easily generate the presentation for a live episode of the orbit quiz night. Simply enter the categories and questions below and in seconds you will have a fully made presentation ready to go.
                      </div>
                      <hr class="my-3"/>
                  <h3>Categories</h3>
                  <div class="card-text">Please enter the categories like this (One on each line):<br/>
                    1,TV & Film<br/>
                    Round,Category
                  </div>
                  <div class="alert alert-primary my-2" role="alert">If you require a comma within your category label, use an underscore instead of a comma.<br/><span class="font-italic"><b>Example:</b><br/>
                    Incorrect: 1,Jack, Jill, and rad are my favourite friends...<br/>
                    Correct: 1,Jack<code class="font-weight-bold">_</code> Jill<code class="font-weight-bold">_</code> and brad are my favourite friends..</span></div>
                  <textarea rows="3" class="form-control my-3" id="categories" placeholder="1,TV & Film">1,TV & Film
2,Science and nature
3,Music
4,Sports and pastimes
5,Places
6,Food and drink
7,History
8,Arts and Literature</textarea>

                  
                  <hr/>
                  <h3>Questions & Answers</h3>
                  <div class="card-text">Please enter the questions like this (One on each line):<br/>
                    1,What does this mean?,My Answer<br/>
                    Round,Question,Answer
                  </div>
                  <div class="alert alert-primary my-2" role="alert">If you require a comma within your question or answer label, use an underscore instead of a comma.<br/><span class="font-italic"><b>Example:</b><br/>
                    Incorrect: 1,Jack, Jill, and rad are my favourite friends..., They sure are<br/>
                    Correct: 1,Jack<code class="font-weight-bold">_</code> Jill<code class="font-weight-bold">_</code> and brad are my favourite friends..,They sure are</span></div>
                <textarea rows="7" class="form-control my-3" id="content" placeholder="1,What year was the iPhone released?,2007"></textarea>
                <button id="process" class="btn btn-info" onclick="textToArray();">Process</button> 
                </div>
                <p id="loader" style=""></p>
                </div>
            <div class="card-footer text-muted">
               Created by Liam McClelland
            </div>
          </div>
          
          </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script
  src="https://code.jquery.com/jquery-3.5.0.min.js"
  integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        function textToArray(){
  var questions = [];    
  var categories = [];
  var nameList = $("#content").val();
  var nameList2 = $("#categories").val();
  
  if(nameList.length < 3) {
      alert("Please provide at least one question");
      return;
  }

  $.each(nameList.split(/\n/), function (i, name) {     

      // empty string check
      if(name != ""){

            var input = name.split(",");
            questions.push(input);

      } 
      
      
});

$.each(nameList2.split(/\n/), function (i, name){
    
            var input = name.split(",");
            categories.push(input);
    
    
});

//console.log("data", {"quiz": questions, "categories":categories});
//return;

$("#process").html("<span class='spinner-border spinner-border-sm' role='status'></span> Processing...");
$("#process").attr("disabled", "disabled");
$("#process").addClass("disabled");
$.ajax({
    url: window.location.protocol + "//" + window.location.hostname + "/process.php?auth=<?php print base64_encode($password); ?>&run=runit",
    method: "POST",
    data: {"quiz": questions, "categories":categories},
    success: function(result) {
        
        $("#form").slideUp();
        
        $("#loader").html(result);
        $("#loader").prepend("<h3 class='my-2'>Your presentation is ready to download.</h3>");
        console.log(result);
    }
});

console.log("Questions", questions);
console.log("Categories", categories);
}
    </script>
  </body>
</html>
    
<?php } else {
    
    http_response_code(401);
    
}