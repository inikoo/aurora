{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 April 2022 at 9:30 Tranava Slovakia
 Copyright (c) 2022, Inikoo

 Version 3
-->

*}
<div class="px-4 mt-6 sm:px-6 lg:px-8">
    <h2 class="text-gray-500 text-xs font-medium uppercase tracking-wide">My stack</h2>
    <ul role="list" class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 xl:grid-cols-4 mt-3">
        <li class="relative col-span-1 flex shadow-sm rounded-md">
            <div class="flex-shrink-0 flex items-center justify-center w-16 bg-pink-600 text-white text-sm font-medium rounded-l-md">GA</div>
            <div class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                <div class="flex-1 px-4 py-2 text-sm truncate">
                    <a href="#" class="text-gray-900 font-medium hover:text-gray-600"> GraphQL API </a>
                    <p class="text-gray-500">12 Members</p>
                </div>
                <div  x-data="{ isOpen: false }" class="flex-shrink-0 pr-2">
                    <button type="button" class="w-8 h-8 bg-white inline-flex items-center justify-center text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500" id="pinned-project-options-menu-0-button" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Open options</span>
                        <!-- Heroicon name: solid/dots-vertical -->
                        <svg @click="isOpen = !isOpen"  class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                        </svg>
                    </button>
                    <div x-cloak x-show="isOpen" @click.outside="isOpen = false"

                         class="z-10 mx-3 origin-top-right absolute right-10 top-3 w-48 mt-1 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 focus:outline-none"

                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"

                         role="menu" aria-orientation="vertical" aria-labelledby="pinned-project-options-menu-0-button" tabindex="-1">
                        <div class="py-1" role="none">
                            <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                            <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="pinned-project-options-menu-0-item-0">View</a>
                        </div>
                        <div class="py-1" role="none">
                            <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="pinned-project-options-menu-0-item-1">Removed from pinned</a>
                            <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="pinned-project-options-menu-0-item-2">Share</a>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        <!-- More items... -->
    </ul>
</div>