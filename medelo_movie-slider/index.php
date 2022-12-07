<?php
require_once 'config.php';
$movie = $mysqli->query("SELECT * FROM `movies` ORDER BY `Title`;");

$movieArray = array();

while ($movies = $movie->fetch_assoc()) {
  array_push($movieArray, $movies);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="bootstrap.min.css">
  <title>Movie Slider</title>
</head>
<body>
  <div class="container">
    <div class="row text-center mt-3">
      <div class="col-md-6 m-auto">
        <input type="text" class="form-control" id="searchBar">
        <select id="searchOption" multiple class="d-none form-control"></select>
      </div>
    </div>

    <div class="d-flex flex-row justify-content-around align-items-center">
      <div class="text-left">
        <div role="button" class="col h2 btn btn-outline-primary" id="prev">
          Previous
        </div>      
      </div>
      <div class="text-center mt-3">
        <img class="shadow-lg" id="images" alt="No image found to fetch">
      </div>
      <div class="text-right">
        <div role="button" class="col h2 btn btn-outline-primary" id="Next">
          <!-- <input type="button" class="btn btn-primary" value="Next"> -->Next
        </div>
      </div>

    </div>
    <div class="d-flex flex-row justify-content-around mt-3">
      <div class="col-md-8">
        <div class="card-shadow-lg">
          <div class="card-body bg-dark text-light mb-4">
            <p><strong>TITLE : <span class="fw-bold fst-italic h5" id="title"></span></strong></p>
            <p><strong>GENRE : <span class="fw-bold fst-italic h5" id="genre"></span></strong></p>
            <p><strong>DIRECTOR'S : <span class="fw-bold fst-italic h5" id="directors"></span></strong></p>
            <p><strong>ACTORS : <span class="fw-bold fst-italic h5" id="actors"></span></strong></p>
            <p><strong>AWARDS : <span class="fw-bold fst-italic h5" id="awards"></span></strong></p>
          </div>
        </div>
      </div>
    </div>

    <script src="jquery-3.6.0.min.js"></script>
    <script src="bootstrap.bundle.min.js"></script>

    <script>
      $(document).ready(function() {
        // alert("Please wait...");
        let movieIndex = 0;
        var movieArr = <?php echo json_encode($movieArray);?>

        // console.log(movieArr);

        $('#prev').click(function () {
          movieIndex --;
          if(movieIndex < 0){
            movieIndex = movieArr.length -1;
          }
          update_UI(movieIndex);
        });

        $('#Next').click(function () {
          movieIndex ++;
          if(movieIndex > movieArr.length - 1){
            movieIndex = 0;
          }
          update_UI(movieIndex);
        });


        $('#searchBar').keyup(function(e) {
          // console.log(e.target.value);
          let searchItem = e.target.value;
          // let select = '';
          if(searchItem.length == 0){
            $('#searchOption').addClass('d-none');
            return;
          }
          let filteredMovie = [];
          movieArr.forEach((movies, index) => {
            if(movies.Title.toLowerCase().startsWith(searchItem.toLowerCase())){
              filteredMovie.push([movies.Title, index]);
            }
          })
          
          
          let option = '';
          filteredMovie.forEach((movies) => {
            option += `<option value="${movies[1]}">${movies[0]}</option>`;
          })
          // console.log(option);
          $('#searchOption').html(option).removeClass('d-none');
        });
        
        $('#searchOption').change(function() {
          // alert(this.value);
          movieIndex = this.value;
          update_UI(movieIndex);
        })


        update_UI();

        function update_UI(movieIndex = 0) {
          $('#images').attr('src', 'images/' + movieArr[movieIndex].imdbID + '.jpg');
          $('#title').text(movieArr[movieIndex].Title);
          $('#genre').text(movieArr[movieIndex].Genre);
          $('#directors').text(movieArr[movieIndex].Director);
          $('#awards').text(movieArr[movieIndex].Awards);
        }
      });

    </script>
  </div>
</body>
</html>