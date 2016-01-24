$(document).ready(init);

function init() {
    populateRankingsTable();
    setupClickHandlers();
}

function populateRankingsTable() {
    $.get('rankings.php', function(entries) {
        var html = entries.map(generateRowHtml).join('');
        $('[scores-table=overall-rankings] .js-scores-table').html(html);
    });

    $.get('final-10.php', function(entries) {
        var html = entries.map(generateRowHtml).join('');
        $('[scores-table=final-10] .js-scores-table').html(html);
    });
}

function generateRowHtml(row, position) {
    var html = '';
    var rank = position + 1;
    var day = getDayByPoints(row.points);

    html += '<tr>';
    html += '<td class="rank">' + rank + '</td>';
    html += '<td class="name">' + row.name + '</td>';
    html += '<td class="points ' + day + '">' + row.points + '</td>';
    html += '</tr>';

    return html;
}

function getDayByPoints(points) {
    if (points >= 16000) { return 'tuesday'; }
    if (points >= 4000) { return 'thursday'; }
    if (points >= 512) { return 'monday'; }
    return 'wednesday';
}

function setupClickHandlers() {
    $('[display-on-click]').click(function() {

        $('[display-on-click]').removeClass('active');
        $(this).addClass('active');

        var tableToDisplay = $(this).attr('display-on-click');
        $('[scores-table]').removeClass('active');
        $('[scores-table=' + tableToDisplay + ']').addClass('active');
    });
}
