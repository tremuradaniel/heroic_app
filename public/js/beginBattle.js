
alert();

$("#start-battle").on("click", function(event) {
    alert();
    event.preventDefault();
    request = $.ajax({
        url: "startBattle.php",
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