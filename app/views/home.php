<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>ORDERUS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" type="text/css" href="css/main.css">
  </head>
  <body>

    <header>
      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between">
          <a href="#" class="navbar-brand d-flex align-items-center">
            <strong>ORDERUS</strong>
          </a>
        </div>
      </div>
    </header>

  <main role="main">

    <section class="jumbotron text-center">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h1>Orderus</h1>
            <p class="lead text-muted">Let's see a battle!</p>
            <p>
              <a id="initialize-battle" style="display: none;" class="btn btn-warning my-2">Approach the battle field</a>
              <a id="start-battle" style="display: none;" class="btn btn-danger my-2">Begin Battle</a>
              <a id="battle-again" style="display: none;" class="btn btn-dark my-2">Continue the slaughter</a>
              <a id="next-round" style="display: none;" class="btn btn-dark my-2">Next round</a>
            </p>
          </div>
          <div class="col-md-6" id="log-zone" style="display: none;">
            <div class="form-group">
              <label for="battleLog">Battle Log | Round: <span id="roundCounter"></span></label>
            </div>
          </div>
        </div>        
      </div>
    </section>

    <div class="arena py-5 bg-light" style="display: none;">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
              <div class="card-body">
                <h2>Orderus</h2>
                <div id="hero-stats"></div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
              <div class="card-body">
                <h2>Beast</h2>
                <div id="beast-stats"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  <!-- Modal -->
  <div class="modal fade show" id="endingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">The battle is over!</h5>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  </main>
  <!-- JS, Popper.js, and jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  <!-- Custom scripts -->
  <script src="js/battleStates.js"></script>
</html>