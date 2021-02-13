$(document).ready(function() {
    let battle = new Battle();
});

class Battle { 
    constructor() {
        console.log( "ready!" );
        let state = 1; // initial state
        let displayedStats = [];
        let textareaLog = `<textarea disabled class="form-control" id="battle-log" rows="7" cols="50" style='resize:none; width:600px'></textarea>`;
        this.stateHandler(state);
        this.setInitializeBattleEventHandler();     
        this.setStartBattleEventHandler();
        this.setNextRoundEventHandler();
        this.setPlayAgainEventHandler();
    }
    
    stateHandler (state) {
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
                $("#roundCounter").text("-");
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
    
    setInitializeBattleEventHandler() {
        $("#initialize-battle").click(function() {
            event.preventDefault();
            $(".form-group").append(textareaLog);
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
    
    setStartBattleEventHandler() {
        let round = 1;
        let wasAttacker = sessionStorage.getItem("BattleStats") ? 
            JSON.parse(sessionStorage.getItem("BattleStats"))['wasAttacker'] : null;
        $("#start-battle").click(function() {
            event.preventDefault();
            request = $.ajax({
                url: "battleStates.php?state=1",
                type: "get",
                data: { 
                    'warriors': sessionStorage.getItem("Warriors"),
                    'round' : round,
                    'wasAttacker': wasAttacker
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
    
    setNextRoundEventHandler() {
        $("#next-round").click(function() {
            $round = sessionStorage.getItem("BattleStats") ? 
                JSON.parse(sessionStorage.getItem("BattleStats"))['round'] : 1;
            $wasAttacker = sessionStorage.getItem("BattleStats") ? 
                JSON.parse(sessionStorage.getItem("BattleStats"))['wasAttacker'] : null;
            event.preventDefault();
            request = $.ajax({
                url: "battleStates.php?state=1",
                type: "get",
                data: { 
                    'warriors': sessionStorage.getItem("Warriors"),
                    'round' : ++$round,
                    'wasAttacker': $wasAttacker
                },
                success: function(data) {
                    console.log(data);
                    let returnedData = JSON.parse(data);
                    updateBattleStats(returnedData['wasAttacker']);
                    updateBattleLog(returnedData['log']);
                    endOfBattle(returnedData['winner'], returnedData['log'].slice(-2));
                    if (!returnedData['draw']) populateArena(returnedData['warriors']);
                    else declareDraw(returnedData);
                }
            });
            stateHandler(state);
        });
    }
    
    setPlayAgainEventHandler() {
        $("#battle-again").click(function() {
            $("#battle-log").remove();
            $(".modal-body").empty();
            state = 0;
            stateHandler(state);
        });
    }
    
    populateArena(data) {
        let stats = JSON.stringify(data);
        sessionStorage.Warriors = stats;
        displayStats(data);
    }
    
    displayStats (stats) {
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
    
    updateBattleStats(wasAttacker) {
        if (sessionStorage.getItem("BattleStats")) {
            let battleStats = JSON.parse(sessionStorage.getItem("BattleStats"));
            let round = ++battleStats.round;
            $("#roundCounter").text(round);
            battleStats.wasAttacker = wasAttacker;
            sessionStorage.BattleStats = JSON.stringify(battleStats);
        } else {
            sessionStorage.BattleStats = JSON.stringify({
                wasAttacker: wasAttacker,
                round: 0
            });
        }
    }
    
    updateBattleLog (logs) {
        for (let log of logs) {
            $('#battle-log').append('- ' + log + '\n');
        }
    }
    
    endOfBattle(winner, logs) {
        if (winner) {
            let modalText = logs.map(log => "<p>" + log +"</p>")
            $('.modal-body').append(modalText);
            $('#endingModal').modal('show');
            stateHandler(state = 3);
        }
    }
    
    declareDraw(data) {
        $('.modal-body').append(data['log']);
        $('#endingModal').modal('show');
        stateHandler(state = 3);
    }

}

