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
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <title>Aurora Clocking</title>

    <style>
        [x-cloak] {
            display: none !important;
        }

    </style>


</head>

<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <img class="mx-auto h-12 w-auto" src="../../art/aurora_log_v2_orange.png" alt="Aurora">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Aurora Clocking V1</h2>

    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form x-data="login()" @submit.prevent="submitForm" class="space-y-6" action="#" method="POST">
                <div>
                    <label for="access-key" class="block text-sm font-medium text-gray-700"> Access Key </label>
                    <div class="mt-1">
                        <input x-model="formData.accessKey" id="access-key" name="access-key" type="password"
                               autocomplete="current-password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>


                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Sign in
                    </button>
                </div>
                <div class="text-red-600" x-text="formMessage"></div>
            </form>



        </div>
    </div>
</div>

<script>
    function login() {
        return {
            formData: {
                accessKey: "",
            },
            formMessage: "",
            submitForm() {

                this.formMessage = "";
                this.formLoading = false;
                this.buttonText = "Submitting...";


                const formData = new FormData();
                formData.append('accessKey', this.formData.accessKey);

                fetch('login.php', {
                    method: "POST",
                    body:formData,
                })

                    .then(res => {
                        return res.json();
                    }).then(data =>{
                        this.formData.accessKey = "";

                        if(data.status===200){
                            location.reload();


                        }else{
                            this.formMessage = data.msg
                        }


                    })
                    .catch(() => {
                        this.formMessage = "Something went wrong.";
                    })
                    .finally(() => {
                        this.formLoading = false;
                        this.buttonText = "Submit";
                    });
            },

        }
    }

</script>