<?php require APPROOT . '/views/inc/header.php'; ?>

<main role="main">
    <section class="jumbotron text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>Battle Field</h1>
                    <p class="lead text-muted">Let's see a battle!</p>
                    <p>
                        <a id="start-battle" style="display: none;" class="btn btn-danger my-2">Begin Battle</a>
                        <a id="battle-again" style="display: none;" class="btn btn-dark my-2">Repeate the slaughter</a>
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

<?php require APPROOT . '/views/inc/footer.php'; ?>
