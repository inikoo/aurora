<div class="{if $mode=='edit'}hide{/if} timezone_edit edit_object" style="margin:0px;padding: 0px" data-old_value="{$field.value}" >

    <input id="{$field.id}" type="hidden" class="input_field " value="{$field.value}"/>
    <select style="width: 500px" class="timezone_select"></select>
    <i id="{$field.id}_save_button" class="fa fa-cloud save {$edit} hide "
       onclick="save_this_field(this)"></i>
    <span id="{$field.id}_msg" class="msg"></span>
    <span id="{$field.id}_info" class="hide"></span>
</div>

<script>

     _t = (s) => {
        if (i18n !== void 0 && i18n[s]) {
            return i18n[s];
        }

        return s;
    };

     timezones = [
        {foreach from=$timezones item=timezone}
        "{$timezone}",
        {/foreach}
    ]


     i18n = {
        "Etc/GMT+12": "International Date Line West",
        "Pacific/Pago_Pago": "(SST) Samoa; Midway Island; Niue",
        "Pacific/Honolulu": "(HST) Hawaii; Cook Islands; Tahiti",
        'Pacific/Marquesas': "(MART) Taiohae; Marquesas Islands; French Polynesia",
        "America/Adak": "(HTS/HDT) Adak, Alaska, USA",
        'Pacific/Gambier': "(GAMT) Gambier Islands, French Polynesia",
        "Pacific/Pitcairn": "(PST) Pitcairn Islands",

        "America/Anchorage": "(AKST/AKDT) Alaska, USA",
        "America/Hermosillo": "(MST) Sonora, Mexico; Arizona USA",


        "America/Los_Angeles": "(PST/PDT) Pacific time (US and Canada); Tijuana",

        "America/Costa_Rica": "(CST) Central America",
        "America/Chihuahua": "(MST/MDT) Chihuahua, La Paz, Mazatlan",


        "America/Regina": "Saskatchewan, Canada",


        "America/Denver": "(MST/MDT) Mountain Time (US and Canada)",
        "America/Regina": "(CST) Saskatchewan, Canada",


        "Pacific/Galapagos": "(GALT) Galapagos Islands, Ecuador",
        "Pacific/Easter": "(EASST/EAST) Easter Island, Chile",

        "America/Cancun": "(EST) Cancún Mexico; Jamaica, Cayman",


        "America/Chicago": "(CST/CDT) Central Time (US and Canada)",

        "America/Mexico_City": "(CST/CDT) Guadalajara, Mexico City, Monterrey",

        "America/Anguilla": "(AST) Puerto Rico; Caribbean",
        "America/Asuncion": "(PYST/PYT) Asuncion, Paraguay",

        "America/Boa_Vista": "(AMT) Amazon, Brazil",
        "America/Havana": "(CST/CDT) Havana, Cuba",


        "America/Detroit": "(EST/EDT) Eastern Time (US and Canada)",


        "America/Bogota": "Bogota, Lima, Quito",
        "America/Glace_Bay": "(AST/ADT)Atlantic Time (Canada)",
        "America/Caracas": "Caracas, La Paz",
        "America/Santiago": "(CLST/CLT) Santiago",
        "America/Sao_Paulo": "Brasilia",
        "America/Argentina/Buenos_Aires": "(ART) Buenos Aires, Argentina; Georgetown",
        "America/Godthab": "Greenland",
        "Etc/GMT+2": "Mid-Atlantic",
        "Atlantic/Azores": "Azores",
        "Atlantic/Cape_Verde": "Cape Verde Islands",
        "Europe/London": "London, Edinburgh",

        "Africa/Casablanca": "Casablanca, Monrovia",
        "Atlantic/Canary": "Canary Islands",
        "Europe/Belgrade": "Belgrade, Bratislava, Budapest, Ljubljana, Prague",
        "Europe/Sarajevo": "Sarajevo, Skopje, Warsaw, Zagreb",
        "Europe/Brussels": "Brussels, Copenhagen, Madrid, Paris",
        "Europe/Amsterdam": "Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
        "Africa/Algiers": "West Central Africa",
        "Europe/Bucharest": "Bucharest",
        "Africa/Cairo": "Cairo",
        "Europe/Helsinki": "Helsinki, Kiev, Riga, Sofia, Tallinn, Vilnius",
        "Europe/Athens": "Athens, Istanbul, Minsk",
        "Asia/Jerusalem": "Jerusalem",
        "Africa/Harare": "Harare, Pretoria",
        "Europe/Moscow": "Moscow, St. Petersburg, Volgograd",
        "Asia/Kuwait": "Kuwait, Riyadh",
        "Africa/Nairobi": "Nairobi",
        "Asia/Baghdad": "Baghdad",
        "Asia/Tehran": "Tehran",
        "Asia/Dubai": "Abu Dhabi, Muscat",
        "Asia/Baku": "Baku, Tbilisi, Yerevan",
        "Asia/Kabul": "Kabul",
        "Asia/Yekaterinburg": "Ekaterinburg",
        "Asia/Karachi": "Islamabad, Karachi, Tashkent",
        "Asia/Kolkata": "Chennai, Kolkata, Mumbai, New Delhi",
        "Asia/Kathmandu": "Kathmandu",
        "Asia/Dhaka": "Astana, Dhaka",
        "Asia/Colombo": "Sri Jayawardenepura",
        "Asia/Almaty": "Almaty, Novosibirsk",
        "Asia/Rangoon": "Yangon Rangoon",
        "Asia/Bangkok": "Bangkok, Hanoi, Jakarta",
        "Asia/Krasnoyarsk": "Krasnoyarsk",
        "Asia/Shanghai": "Beijing, Chongqing, Hong Kong SAR, Urumqi",
        "Asia/Kuala_Lumpur": "Kuala Lumpur, Singapore",
        "Asia/Taipei": "Taipei",
        "Australia/Perth": "Perth",
        "Asia/Irkutsk": "Irkutsk, Ulaanbaatar",
        "Asia/Seoul": "Seoul",
        "Asia/Tokyo": "Osaka, Sapporo, Tokyo",
        "Asia/Yakutsk": "Yakutsk",
        "Australia/Darwin": "Darwin",
        "Australia/Adelaide": "Adelaide",
        "Australia/Sydney": "Canberra, Melbourne, Sydney",
        "Australia/Brisbane": "Brisbane",
        "Australia/Hobart": "Hobart",
        "Asia/Vladivostok": "Vladivostok",
        "Pacific/Guam": "Guam, Port Moresby",
        "Asia/Magadan": "Magadan, Solomon Islands, New Caledonia",
        "Pacific/Fiji": "Fiji Islands, Kamchatka, Marshall Islands",
        "Pacific/Auckland": "Auckland, Wellington",
        "Pacific/Tongatapu": "Nuku'alofa"
    }
    //const dateTimeUtc = moment("2017-06-05T19:41:03Z").utc();
    //document.querySelector(".js-TimeUtc").innerHTML = dateTimeUtc.format("ddd, DD MMM YYYY HH:mm:ss");

     selectorOptions = moment.tz.names()
        .filter(tz => {
            return timezones.includes(tz)
        })
        .reduce((memo, tz) => {
            memo.push({
                name: tz, offset: moment.tz(tz).utcOffset()
            });

            return memo;
        }, [])
        .sort((a, b) => {
            return a.offset - b.offset
        })
        .reduce((memo, tz) => {
            const timezone = tz.offset ? moment.tz(tz.name).format('Z') : '';
            {literal}
            return memo.concat(`<option value="${tz.name}">(GMT${timezone}) ${_t(tz.name)}</option>`);
            {/literal}
        }, "");

    $('.timezone_select').html(selectorOptions);

    $(".timezone_select").on("change", e => {

        console.log(e.target.value)

        $('#{$field.id}').val(e.target.value)
        $('#{$field.id}_field').addClass('valid')

        if($('.timezone_edit').data('old_value')!=e.target.value){
            $('#{$field.id}_field').addClass('changed')

        }


        //const timestamp = dateTimeUtc.unix();
        //const offset = moment.tz(e.target.value).utcOffset() * 60;
        //const dateTimeLocal = moment.unix(timestamp + offset).utc();

        //document.querySelector(".js-TimeLocal").innerHTML = dateTimeLocal.format("ddd, DD MMM YYYY HH:mm:ss");
    });

    document.querySelector(".timezone_select").value = "{$field.value}";

     event = new Event("change");
    document.querySelector(".timezone_select").dispatchEvent(event);

    $(".timezone_select").select2();
</script>
