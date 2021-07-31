let home_teams = [];
let guest_teams = [];
const selectGuestTeams = $("#select_guest_teams");
const selectHomeTeams = $("#select_home_teams");
const guestTeamScore = $("#guest_team_score");
const homeTeamScore = $("#home_team_score");
const championshipTableTBody = $("#championship-table tbody");

toastr.options = {
    "closeButton" : true,
    "progressBar" : true
}
const createMatch = () => {

    const home_team_id = selectHomeTeams.val();
    const home_team_score = homeTeamScore.val();

    const guest_team_id = selectGuestTeams.val();
    const guest_team_score = guestTeamScore.val();

    const csrf = $('input[name="_token"]').val();

    const data = {
        home_team_id,
        home_team_score,
        guest_team_id,
        guest_team_score
    };
    for (let i in data) {
        if(!data[i]) {
            toastr.warning("Todos os campos são obrigatórios");
            return;
        }
    }

    $.ajax({
        url: 'matches/create',
        type: 'POST',
        dataType: 'json',
        data: data,
        headers: {
            "X-CSRF-TOKEN": csrf
        },
        success: (res) => {
           console.log(res);
        },
        error: (err) => {
            const errors = JSON.parse(err.responseText).errors;
            if(errors) {
                for(let error in errors) {
                    toastr.error(errors[error][0]);
                }
            }
        }
    });

    const closeBtn = $("#insert-modal .btn-close");
    closeBtn.click();

    guestTeamScore.val("");
    homeTeamScore.val("");

    getTeamsForSelect();
    getTableTeams();


}

const filterTeams = (teams, selected, firstOptionLabel) => {
    const firstOption = `<option>${firstOptionLabel}</option>`;

    let filteredTeams = teams.filter((team) => team.id !== selected);
    filteredTeams = filteredTeams.map(({ name, id }) => `<option value="${id}">${name}</option>`);
    filteredTeams = [firstOption, ...filteredTeams];

    return filteredTeams;
}

const removeSelectedTeam = (item, select, teams, label) => {
    let selectedTeamsValue = select.val();

    select.html(filterTeams(teams, parseInt(item.value), label));
    select.val(selectedTeamsValue);
}

const onChangeTeam = (item) => {

    if (item.id === 'select_home_teams') {
        removeSelectedTeam(item, selectGuestTeams, guest_teams, 'Visitante');
        return;
    }

    removeSelectedTeam(item, selectHomeTeams, home_teams, 'Time da casa');
}

const onlyNumbers = (item) => item.value = item.value.replace(/[^0-9]/g, '');

const getTableTeams = () => {
    $.ajax({
        url: 'teams',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            const teams = res.teams.map((team, index) => `
                <tr>
                   <td>${index + 1}º</td>
                   <td>${team.name} - ${team.abbreviation}</td>
                   <td>${team.points}</td>
                   <td>${team.matches}</td>
                   <td>${team.victories}</td>
                   <td>${team.draws}</td>
                   <td>${team.defeats}</td>
                   <td>${team.goalsFor}</td>
                   <td>${team.goalsTaken}</td>
                   <td>${team.goalDifference}</td>
                </tr>
            `);
            championshipTableTBody.html(teams);
        }
    });
}

getTableTeams();
const getTeamsForSelect = () => {
    $.ajax({
        url: 'teams/select',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            const teams = res.teams.map(({ name, id }) => `<option value="${id}">${name}</option>`);

            home_teams = res.teams;
            guest_teams = res.teams;

            const _selectHomeTeams = [ `<option selected>Time da casa</option>`, ...teams ];
            const _selectGuestTeams = [ `<option selected>Visitante</option>`, ...teams ];

            selectHomeTeams.html(_selectHomeTeams);
            selectGuestTeams.html(_selectGuestTeams);
        }
    });
}

getTeamsForSelect();


