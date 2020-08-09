let state = 0; // initial state
let displayedStats = [];
$(document).ready(function() {
    console.log( "ready!" );
    stateHandler(state);
    setInitializeBattleEventHandler();     
    setStartBattleEventHandler();
    setNextRoundEventHandler();
    setPlayAgainEventHandler();
});

function stateHandler (state) {
    switch (state) {
        case 0:
            // set initial state
            $("#initialize-battle").show();
            $("#battle-again").hide();
            $("#start-battle").hide();
            $("#end-battle").hide();
            $("#repete-battle").hide();
            $("#log-zone").hide();
            $(".arena").hide();
            // clear data
            sessionStorage.clear();
            break;
        case 1:
            // battle begins - 1 round
            $("#start-battle").show();
            $(".arena").show();
            $("#log-zone").show();
            $("#initialize-battle").hide();
            $("#end-battle").hide();
            $("#repete-battle").hide();
            break;
        case 2:
            // new round
            $("#next-round").show();
            $("#start-battle").hide();
            $("#initialize-battle").hide();
            $("#end-battle").hide();
            $("#repete-battle").hide();
            $(".arena").show();
            $("#log-zone").show();
            break;
        case 3:
            // new battle
            $("#next-round").hide();
            $("#battle-again").show();
            // clear data
            sessionStorage.clear();
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
                let returnedData = JSON.parse(data);
                populateArena(returnedData['warriors']);
                updateBattleStats();
                updateBattleLog(returnedData['log']);
            }
        });
        stateHandler(state = 1);
    });
}

function setStartBattleEventHandler() {
    $round = sessionStorage.getItem("BattleStats") ? 
        JSON.parse(sessionStorage.getItem("BattleStats"))['round'] : 0;
    $wasAttacker = sessionStorage.getItem("BattleStats") ? 
        JSON.parse(sessionStorage.getItem("BattleStats"))['wasAttacker'] : null;
    $("#start-battle").click(function() {
        event.preventDefault();
        request = $.ajax({
            url: "battleStates.php?state=1",
            type: "get",
            data: { 
                'warriors': sessionStorage.getItem("Warriors"),
                'round' : $round,
                'wasAttacker': $wasAttacker
            },
            success: function(data) {
                console.log(data);
                let returnedData = JSON.parse(data);
                populateArena(returnedData['warriors']);
                updateBattleStats(returnedData['wasAttacker']);
                updateBattleLog(returnedData['log']);
                endOfBattle(returnedData['winner']);
            }
        });
        state = 2;
        stateHandler(state);
    });
}

function setNextRoundEventHandler() {
    $("#next-round").click(function() {
        $round = sessionStorage.getItem("BattleStats") ? 
            JSON.parse(sessionStorage.getItem("BattleStats"))['round'] : 0;
        $wasAttacker = sessionStorage.getItem("BattleStats") ? 
            JSON.parse(sessionStorage.getItem("BattleStats"))['wasAttacker'] : null;
        event.preventDefault();
        request = $.ajax({
            url: "battleStates.php?state=1",
            type: "get",
            data: { 
                'warriors': sessionStorage.getItem("Warriors"),
                'round' : $round,
                'wasAttacker': $wasAttacker
            },
            success: function(data) {
                console.log(data);
                let returnedData = JSON.parse(data);
                populateArena(returnedData['warriors']);
                updateBattleStats(returnedData['wasAttacker']);
                updateBattleLog(returnedData['log']);
                endOfBattle(returnedData['winner'], returnedData['log'].slice(-2));
            }
        });
        // state = 2;
        stateHandler(state);
    });
}

function setPlayAgainEventHandler() {
    $("#battle-again").click(function() {
        $("#battle-log").val(' ');
        state = 0;
        stateHandler(state);
    });
}

function populateArena(data) {
    let stats = JSON.stringify(data);
    sessionStorage.Warriors = stats;
    displayStats(data);
}

function displayStats (stats) {
    $(".table").remove();
    displayedStats = [];
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

function updateBattleStats(wasAttacker) {
    if (sessionStorage.getItem("BattleStats")) {
        let battleStats = JSON.parse(sessionStorage.getItem("BattleStats"));
        ++battleStats.round;
        battleStats.wasAttacker = wasAttacker;
        sessionStorage.BattleStats = JSON.stringify(battleStats);
    } else {
        sessionStorage.BattleStats = JSON.stringify({
            round: 0,
            wasAttacker: wasAttacker

        });
    }
}

function updateBattleLog (logs) {
    for (let log of logs) {
        $('#battle-log').append('- ' + log + '\n');
    }
}

function endOfBattle(winner, logs) {
    if (winner) {
        let modalText = logs.map(log => "<p>" + log +"</p>")
        $('.modal-body').append(modalText);
        $('#endingModal').modal('show');
        state = 3;
        stateHandler(state);
    }
}