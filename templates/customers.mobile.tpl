<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--4-col">
        <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored">
            <i class="material-icons">add</i>
        </button>


    </div>
    <div class="mdl-cell mdl-cell--4-col">
        <table class="mdl-data-table mdl-js-data-table  mdl-shadow--2dp" style="width:100%">
            <thead>
            <tr>
                <th colspan=2 class="mdl-data-table__cell--non-numeric">{t}Contacts by number of orders{/t}</th>

            </tr>
            </thead>
            <tbody>


            <tr class="discreet">
                <td class="mdl-data-table__cell--non-numeric">{t}Without orders{/t}</td>
                <td>{$customers_by_number_of_orders['Without Orders']}</td>
            </tr>
            <tr>
                <td class="mdl-data-table__cell--non-numeric">{t}With orders{/t}</td>
                <td>{$customers_by_number_of_orders['With Orders']}</td>
            </tr>
            <tr >
                <td class="mdl-data-table__cell--non-numeric">{t}Total{/t}</td>
                <td>{$total}</td>
            </tr>
            </tbody>
        </table>



    </div>

</div>


