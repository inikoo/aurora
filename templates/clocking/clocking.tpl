{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 Jun 2022 at 18:46 Kuala Lumpur Malaysia
 Copyright (c) 2022, Inikoo

 Version 3
-->

*}

<!DOCTYPE html>
<html class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com" ?plugins=forms></script>
    <script src="https://unpkg.com/navigo"></script>
    <script src="https://unpkg.com/@victoryoalli/alpinejs-moment@1.0.0/dist/moment.min.js" defer></script>

    <script src="https://unpkg.com/@victoryoalli/alpinejs-timeout@1.0.0/dist/timeout.min.js" defer></script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <title>Aurora Clocking</title>

    <style>
        [x-cloak] {
            display: none !important;
        }

    </style>


</head>


{literal}
    <div class="fixed inset-0 w-full h-full flex bg-stone-300 items-center justify-center">



        <div class="bg-white rounded-lg  px-24 py-4  bg-stone-100" x-data="app()">
            <div class="font-thin px-2 pb-3 text-4xl text-stone-700"  x-timeout:1000="$el.innerText=$moment().format('LT')"></div>

            <div class="font-thin px-2 pb-3  text-stone-700">Enter your pin code</div>
            <div class="flex">
                <template x-for="(l,i) in pinlength" :key="`codefield_${i}`">
                    <input :autofocus="i == 0" :id="`codefield_${i}`" type="password"
                           class="h-16 w-12 border mx-2 rounded-lg flex items-center text-center font-thin text-3xl"
                           value="" maxlength="1" max="9" min="0" inputmode="decimal" @keyup="stepForward(i)"
                           @keydown.backspace="stepBack(i)" @focus="resetValue(i)"></input>
                </template>
            </div>

            <div class="h-8">
                <div  x-text="msg" :class="error ?  'text-red-600' : 'text-emerald-600' "  class="mt-5 px-2 pb-4 text-sm "></div>
            </div>



        </div>

    </div>




{/literal}


{literal}
<script type="text/javascript">
    function app() {
        return {
            pinlength: 4,
            msg: '',
            error:true,
            formLoading:false,
            resetValue(i) {
                if(this.formLoading){
                    return;
                }
                this.msg = "";
                for (x = 0; x < this.pinlength; x++) {
                    if (x >= i) document.getElementById(`codefield_${x}`).value = ''
                }
            },
            stepForward(i) {
                if(this.formLoading){
                    return;
                }
                this.msg = "";
                if (document.getElementById(`codefield_${i}`).value && i != this.pinlength - 1) {
                    document.getElementById(`codefield_${i+1}`).focus()
                    document.getElementById(`codefield_${i+1}`).value = ''
                }
                this.checkPin()
            },
            stepBack(i) {
                if(this.formLoading){
                    return;
                }
               if(i>0) {
                   this.msg = "";
                   if (document.getElementById(`codefield_${i - 1}`).value && i != 0) {
                       document.getElementById(`codefield_${i - 1}`).focus()
                       document.getElementById(`codefield_${i - 1}`).value = ''
                   }
               }
            },
            checkPin() {
                let code = ''
                for (i = 0; i < this.pinlength; i++) {
                    code = code + document.getElementById(`codefield_${i}`).value
                }
                if (code.length == this.pinlength) {
                    this.validatePin(code)
                }
            },
            validatePin(code) {

                if(this.formLoading){
                    return;
                }

                this.formLoading=true;
                const formData = new FormData();
                formData.append('pin', code);

                for (x = 0; x < this.pinlength; x++) {
                    document.getElementById(`codefield_${x}`).value = ''
                }
                document.getElementById("codefield_0").focus();

                fetch('clocking.php', {
                    method: "POST",
                    body:formData,
                })

                    .then(res => {
                        return res.json();
                    }).then(data =>{

                    this.msg = data.msg

                    if(data.status===400){
                        this.error=true;

                    }else{
                        this.error=false;
                    }


                })
                    .catch(() => {
                        this.msg = "Something went wrong.";
                    })
                    .finally(() => {
                        this.formLoading = false;
                    });


            }
        }
    }
</script>
{/literal}