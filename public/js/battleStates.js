$(document).ready(function() {
    if (window.location.pathname === '/battles/show') new Battle(1);
});

class Battle {
    
    constructor (state) {
        this.state = state; // initial state
        this.displayedStats = [];
        this.textareaLog = `<textarea disabled class="form-control" id="battle-log" rows="7" cols="50" style='resize:none; width:600px'></textarea>`;
        console.log( "ready!" );
        this.stateHandler(this.state);
        this.setInitializeBattleEventHandler();     
        // this.setStartBattleEventHandler();
        // this.setNextRoundEventHandler();
        // this.setPlayAgainEventHandler();
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
    
    setInitializeBattleEventHandler(classThis) {
        // $("#initialize-battle").click(function() {
            // event.preventDefault();
            $(".form-group").append(this.textareaLog);
            let thisClassBattle = this
            $.ajax({
                url: "/battles/initializeBattle",
                type: "get",
                data: [],
                success: function(data) {
                    let returnedData = JSON.parse(data);
                    // let battle = new Battle();
                    thisClassBattle.populateArena(returnedData);
                    // updateBattleStats();
                    // updateBattleLog(returnedData['log']);
                }
            });
            // new Battle(1);
        // });
    }
    
    setStartBattleEventHandler() {
        console.log('setStartBattleEventHandler')
        let round = 1;
        let wasAttacker = sessionStorage.getItem("BattleStats") ? 
            JSON.parse(sessionStorage.getItem("BattleStats"))['wasAttacker'] : null;
        $("#start-battle").click(function() {
            event.preventDefault();
            let request = $.ajax({
                url: "/battles/setupRound/1",
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
                },
                error: function(data) {
                    console.error(data)
                }
            });
            new Battle(2) //this.state = 2;
            // this.stateHandler(this.state);
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
        this.displayStats(data);
    }
    
    displayStats (data) {
        let combatants = data['combatants'];
        let stats = [combatants['hero'][0], combatants['beast'][0]];

        $(".table").remove();
        this.displayedStats = [];
        stats.map(stat => {
            let list = "<table class='table' style='color: white'>";
            for (let [key, value] of Object.entries(stat)) {
                list += "<tr>" + 
                            "<td style='text-transform: capitalize;'>" + key + "</td>" +
                            "<td>" + value + "</td>" +
                        "</tr>";
            }
            list += "</table>";
            this.displayedStats.push(list);
        });
        $('#hero-stats').append(this.displayedStats[0]);
        $('#beast-stats').append(this.displayedStats[1]);
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
