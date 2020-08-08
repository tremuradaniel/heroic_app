$("button").on("click", function(event) {
    event.preventDefault();
    request = $.ajax({
        url: "./routes/begin_fight.php",
        type: "get",
        data: []
    });

    request.done(function (response, textStatus, jqXHR){
        // Log a message to the console
        console.log("Hooray, it worked!");
        console.log("response", response);
        console.log("textStatus", textStatus);
    });
});