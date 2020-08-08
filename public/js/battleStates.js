let state = 0; // initial state
let displayedStats = [];
$(document).ready(function() {
    console.log( "ready!" );
    stateHandler(state);
    setInitializeBattleEventHandler();     
    setStartBattleEventHandler();
});

function stateHandler (state) {
    switch (state) {
        case 0:
            // set initial state
            $("#initialize-battle").show();
            $("#start-battle").hide();
            $("#end-battle").hide();
            $("#repete-battle").hide();
            $(".arena").hide();
            break;
        case 1:
            // battle
            $("#start-battle").show();
            $("#initialize-battle").hide();
            $("#end-battle").hide();
            $("#repete-battle").hide();
            $(".arena").show();
        default:
            break;
    }
}

function setInitializeBattleEventHandler() {
    $("#initialize-battle").click(function() {
        event.preventDefault();
        request = $.ajax({
            url: "battleStates.php?state=0",
            type: "get",
            data: [],
            success: function(data) {
                populateArena(data);
            }
        });

        // request.done(function (response, textStatus){
        //     console.log("beginBattle!");
        //     console.log("response", response);
        //     console.log("textStatus", textStatus);
        // });
        state = 1;
        stateHandler(state);
    });
}

function setStartBattleEventHandler() {
    $("#start-battle").click(function() {
        event.preventDefault();
        request = $.ajax({
            url: "battleStates.php?state=1",
            type: "get",
            data: { 
                'warriors': sessionStorage.getItem("Warriors")
            },
            success: function(data) {
                console.log(data);
            }
        });
        state = 2;
        stateHandler(state);
    });
}

function populateArena(data) {
    console.log('populateArena');
    console.log('data returned', data);
    let stats = JSON.parse(data);
    sessionStorage.Warriors = data;
    displayStats(stats);
}

function displayStats (stats) {
    stats.map(stat => {
        let list = "<table class='table' style='color: white'>";
        for (let [key, value] of Object.entries(stat)) {
            list += "<tr>" + 
                        "<td style='text-transform: capitalize;'>" + key + "</td>" +
                        "<td>" + value + "</td>" +
                    "</tr>";
        }
        list += "</table>";
        displayedStats.push(list);
    });
    $('#hero-stats').append(displayedStats[0]);
    $('#beast-stats').append(displayedStats[1]);
}