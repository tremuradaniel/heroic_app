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
            $("#log-zone").hide();
            $(".arena").hide();
            sessionStorage.clear();
            break;
        case 1:
            // battle begins - 1 round
            $("#start-battle").show();
            $("#initialize-battle").hide();
            $("#end-battle").hide();
            $("#repete-battle").hide();
            $(".arena").show();
            $("#log-zone").show();
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
        state = 1;
        stateHandler(state);
    });
}

function setStartBattleEventHandler() {
    $round = sessionStorage.getItem("BattleStats") ? 
        sessionStorage.getItem("BattleStats") : 0
    $("#start-battle").click(function() {
        event.preventDefault();
        request = $.ajax({
            url: "battleStates.php?state=1",
            type: "get",
            data: { 
                'warriors': sessionStorage.getItem("Warriors"),
                'round' : $round,
                'wasAttacker': ''
            },
            success: function(data) {
                console.log(data);
                let returnedData = JSON.parse(data);
                populateArena(returnedData['warriors']);
                updateBattleStats(returnedData['wasAttacker']);
                updateBattleLog(returnedData['log']);
            }
        });
        state = 2;
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