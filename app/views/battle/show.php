<?php require APPROOT . '/views/inc/header.php'; ?>

<main role="main">
    <section class="jumbotron text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Battle Field</h1>
                    <div class="arena py-5 bg-light">
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
                </div>
            </div>   
        </div>
    </section>
</main>

<?php require APPROOT . '/views/inc/footer.php'; ?>
