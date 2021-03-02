$(document).ready(function() {
    let correctPage = window.location.pathname === '/battles/show';
    if (correctPage) new Battle(1);
});

class Battle {
      
    constructor (state) {
        console.log( "ready!" );
        this.state = state; // initial state
        this.displayedStats = [];
        this.textareaLog = `<textarea disabled class="form-control" id="battle-log" rows="7" cols="50" style='resize:none; width:600px'></textarea>`;
        this.stateHandler(this.state);
        if (state === 1) {
            sessionStorage.clear();
            this.setInitializeBattleEventHandler();     
            this.setBattleEventHandler("#next-round", true);
            this.setBattleEventHandler("#start-battle");
            this.setPlayAgainEventHandler();
        } 
    }
    
    stateHandler (state) {
        switch (state) {
            case 0:
                // set initial state
                this.setInitializeBattleEventHandler();
                $("#initialize-battle").show();
                $("#battle-again").hide();
                $("#start-battle").show();
                $("#end-battle").hide();
                $("#repete-battle").hide();
                $("#roundCounter").text("-");
                // clear data
                sessionStorage.clear();
                break;
            case 1:
                // prepare battle
                $("#start-battle").show();
                $(".arena").show();
                $("#log-zone").show();
                $("#initialize-battle").hide();
                $("#end-battle").hide();
                $("#repete-battle").hide();
                $("#roundCounter").text("-");
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
        $(".form-group").append(this.textareaLog);
        let thisClassBattle = this
        $.ajax({
            url: "/battles/initializeBattle",
            type: "get",
            data: { 'initialize': true },
            success: function(data) {
                let returnedData = JSON.parse(data);
                thisClassBattle.updateBattleLog(returnedData);
                thisClassBattle.populateArena(returnedData);
            }
        });
    }
    
    setBattleEventHandler (id, triggerStateHandler) {
        let thisClassBattle = this
        $(id).click(function(event) {
            event.preventDefault();
            $.ajax({
                url: `/battles/triggerRound`,
                type: "get",
                data: { 'battleStats': sessionStorage.getItem("battleStats") },
                success: function(data) {
                    console.log(data);
                    let returnedData = JSON.parse(data);
                    thisClassBattle.updateBattleLog(returnedData);
                    thisClassBattle.populateArena(returnedData);
                    thisClassBattle.endOfBattle(returnedData);
                },
                error: function(data) {
                    console.error(data)
                }
            });
            if (triggerStateHandler) thisClassBattle.stateHandler(thisClassBattle.state);
            new Battle(2);
        });
    }

    setPlayAgainEventHandler() {
        let thisClassBattle = this
        $("#battle-again").click(function(event) {
            event.preventDefault();
            $("#battle-log").remove();
            $(".modal-body").empty();
            let state = 0;
            thisClassBattle.stateHandler(state);
        });
    }
    
    populateArena(data) {
        let stats = JSON.stringify(data);
        sessionStorage.battleStats = stats;
        this.displayStats(data);
        this.updateDisplayedRound(data);
    }

    updateDisplayedRound (data) {
        let round = data?.battle?.round;
        $("#roundCounter").text(round);
    }
    
    displayStats (data) {
        let combatants = data['combatants'];
        let stats = [combatants['hero'], combatants['beast']];

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
    
    updateBattleLog (data) {
        let logs = [];
        let sessionLogs = sessionStorage.getItem('battleStats')
            ? JSON.parse(sessionStorage.getItem('battleStats')).battle.log : []
        if (data?.battle?.log.length) logs = data.battle.log;
        for (const [key, log] of logs.entries()) {
            if (sessionLogs[key] === log) continue
            $('#battle-log').prepend('- ' + log + '\n');
        }
    }
    
    endOfBattle(data) {
        let isGameOver = data.battle.status === 'gameOver';
        if (isGameOver) {
            let log = data?.battle?.log;
            if (log.length) {
                let modalLastLog = log[log.length -1];
                let modalText = "<p>" + modalLastLog +"</p>"
                $('.modal-body').append(modalText);
                $('#endingModal').modal('show');
                this.stateHandler(3);
            }
        }
    }
    
    declareDraw(data) {
        $('.modal-body').append(data['log']);
        $('#endingModal').modal('show');
        stateHandler(state = 3);
    }

}
