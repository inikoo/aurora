{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 April 2022 at 9:35 Tranava Slovakia
 Copyright (c) 2022, Inikoo

 Version 3
-->

*}

<div
        x-data="{
        orders: { },
        'isLoading': true,
        async retrievePosts() {
            let data = await (await fetch('/apps/picking/orders.php')).json();
            this.orders=data.orders

        }
    }"


        x-init="retrievePosts"
>

    <!-- Orders list (only on smallest breakpoint) -->
    <div class="mt-10 sm:hidden">
        <div class="px-4 sm:px-6">
            <h2 class="text-gray-500 text-xs font-medium uppercase tracking-wide">Order sms</h2>
        </div>
        <ul role="list" class="mt-3 border-t border-gray-200 divide-y divide-gray-100">
            <li>
                <a href="#" class="group flex items-center justify-between px-4 py-4 hover:bg-gray-50 sm:px-6">
              <span class="flex items-center truncate space-x-3">
                <span class="w-2.5 h-2.5 flex-shrink-0 rounded-full bg-pink-600" aria-hidden="true"></span>
                <span class="font-medium truncate text-sm leading-6">
                  GraphQL API
                  <span class="truncate font-normal text-gray-500">in Engineering</span>
                </span>
              </span>
                    <!-- Heroicon name: solid/chevron-right -->
                    <svg class="ml-4 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                              clip-rule="evenodd"/>
                    </svg>
                </a>
            </li>

            <!-- More projects... -->
        </ul>
    </div>

    <!-- Orders table (small breakpoint and up) -->
    <div class="hidden mt-8 sm:block">
        <div class="align-middle inline-block min-w-full border-b border-gray-200">
            <table class="min-w-full">
                <thead>
                <tr class="border-t border-gray-200">
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        scope="col">
                        <span class="lg:pl-2">Order</span>
                    </th>
                    <th class="hidden md:table-cell px-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        scope="col">Submit Date
                    </th>

                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        scope="col">Waiting
                    </th>
                    <th class="pr-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        scope="col"></th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                <template x-for="order in orders" :key="order.id">
                    <tr>
                        <td class="px-6 py-3 max-w-0 w-full whitespace-nowrap text-sm font-medium text-gray-900">
                            <div class="flex items-center space-x-3 lg:pl-2">
                                <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full bg-pink-600"
                                     aria-hidden="true"></div>
                                <a href="#" class="truncate hover:text-gray-600">
                      <span x-text="order.number">


                      </span>
                                </a>
                            </div>
                        </td>
                        <td x-text="order.date" class="hidden md:table-cell px-6 py-3 whitespace-nowrap text-sm text-gray-500 text-right">

                        </td>

                        <td class="px-6 py-3 text-sm text-gray-500 font-medium whitespace-nowrap">33 hrs</td>
                        <td x-data="{ isOpen: false }"
                            class="relative px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                            <button type="button"
                                    class="w-8 h-8 bg-white inline-flex items-center justify-center text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                                    id="pinned-project-options-menu-0-button" aria-expanded="false"
                                    aria-haspopup="true">
                                <span class="sr-only">Open options</span>
                                <!-- Heroicon name: solid/dots-vertical -->
                                <svg @click="isOpen = !isOpen" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                            </button>
                            <div x-cloak x-show="isOpen" @click.outside="isOpen = false"

                                 class="z-10 mx-3 origin-top-right absolute right-14 top-0 w-48 mt-1 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 focus:outline-none"

                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"

                                 role="menu" aria-orientation="vertical"
                                 aria-labelledby="pinned-project-options-menu-0-button" tabindex="-1">
                                <div class="py-1" role="none">
                                    <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                                    <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem"
                                       tabindex="-1" id="pinned-project-options-menu-0-item-0">View</a>
                                </div>
                                <div class="py-1" role="none">
                                    <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem"
                                       tabindex="-1" id="pinned-project-options-menu-0-item-1">Removed from pinned</a>
                                    <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem"
                                       tabindex="-1" id="pinned-project-options-menu-0-item-2">Share</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>

</div>