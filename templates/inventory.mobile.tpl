<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--4-col">
        <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored">
            <i class="material-icons">add</i>
        </button>


    </div>
    <div class="mdl-cell mdl-cell--4-col">
        <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp" style="width:100%">
            <thead>
            <tr>
                <th colspan=2 class="mdl-data-table__cell--non-numeric">{t}Parts by status{/t}</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="mdl-data-table__cell--non-numeric">{t}In Process{/t}</td>
                <td>{$parts_by_status['In Process']}</td>
            </tr>
            <tr>
                <td class="mdl-data-table__cell--non-numeric">{t}Active{/t}</td>
                <td>{$parts_by_status['In Use']}</td>
            </tr>
            <tr class="discreet">
                <td class="mdl-data-table__cell--non-numeric">{t}Discontinuing{/t}</td>
                <td>{$parts_by_status['Discontinuing']}</td>
            </tr>
            <tr class="very_discreet">
                <td class="mdl-data-table__cell--non-numeric">{t}Discontinued{/t}</td>
                <td>{$parts_by_status['Not In Use']}</td>
            </tr>
            </tbody>
        </table>



    </div>

</div>


