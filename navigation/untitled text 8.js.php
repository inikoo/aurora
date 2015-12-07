 events: {
            "click": function() {
                change_view('timesheet.group/{if $tipo=='months'}month{elseif $tipo=='weeks'}week{elseif $tipo=='days'}day{/if}/'  +this.model.get("key"))
            }
        },
    