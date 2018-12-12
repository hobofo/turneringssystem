<!DOCTYPE html>
<html>
<head>
    <title>HBF Rangliste</title>
    <link rel="stylesheet" type="text/css" href="../css/reset.css">
    <link rel="stylesheet" type="text/css" href="rankings.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

    <ul class="menu">
        <li display-on-click="overall-rankings" class="active">Rangliste</li>
        <li display-on-click="final-10">Final 10</li>
    </ul>

    <div class="rankings-container">


        <div class="scores-table-container active" scores-table="overall-rankings">
            <h2 class="scores-table-title">HBF Rangliste</h2>
            <table class="scores-table js-scores-table overall-rankings"></table>

            <table class="points-explanation">
                <tr>
                    <td class="tuesday">Pointgrænse for Tirsdag: Ingen grænse</td>
                </tr>
                <tr>
                    <!-- <td class="thursday">Pointgrænse for Torsdag: < 16.000 point</td> -->
                    <td class="thursday">Pointgrænse for Torsdag: Ingen grænse</td>
                </tr>
<!--                 <tr>
                    <td class="monday">Pointgrænse for Mandag: < 4.000 point</td>
                </tr> -->
                <tr>
                    <td class="wednesday">Pointgrænse for Onsdag: < 4000 point</td>
                </tr>
            </table>
        </div>

<!--         <div class="scores-table-container" scores-table="final-10">
            <h2 class="scores-table-title">Final 10</h2>
            <table class="scores-table js-scores-table final-10"></table>
        </div> -->
    </div>

    <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
    <script src="rankings.js"></script>
</body>
</html>
