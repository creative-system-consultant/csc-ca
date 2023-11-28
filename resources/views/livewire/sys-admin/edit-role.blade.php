<div>
    <div wire:loading wire:target="setState">
        @include('misc.loading')
    </div>
    <x-container title="Edit Role" routeBackBtn="{{ route('roles.index') }}" titleBackBtn="List Role" disableBackBtn="true">
        <div class="gap-4 my-2 mb-4">
            <x-input wire:model="name" label="Role Name" placeholder="" class="uppercase "/>
        </div>

        <div class="grid grid-cols-1" x-data="{ tab:0 }" >
            <div class="w-full bg-white border rounded-lg shadow-md dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700">
                <div class="flex flex-wrap justify-start sm:justify-start">
                    @foreach ($system as $systems)
                        <x-tab.title name="{{ $systems->id }}" wire:click="setState({{ $systems->id }})">
                            <div class="flex items-center text-sm spcae-x-2">
                                <x-icon name="collection" class="w-5 h-5 mr-2"/>
                                <h1>{{ $systems->description }}</h1>
                            </div>
                        </x-tab.title>
                    @endforeach
                </div>
            </div>

            {{-- @if($setIndex == '0') --}}
                <!-- FMS -->
                <div class="px-2">
                    <div class="mt-10">
                        <div class="pb-4 mb-4 font-semibold border-b dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center pl-2 mt-3 space-x-2">
                                    <x-checkbox
                                        id=""
                                        wire:model=""
                                        value=""
                                        md
                                    />
                                    <h1 class="font-medium dark:text-white">
                                        {{ $currentSystemData->description }} PERMISSIONS
                                    </h1>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <x-label label="Search : " />
                                    <div class="w-64">
                                        <x-input
                                            wire:model.live="search"
                                            label=""
                                            placeholder="Search Module"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <div>
                                @foreach ($modules as $module)
                                    <div class="grid grid-cols-12">
                                        <div class="flex items-start col-span-2 py-3 border bg-gray-50 dark:bg-gray-900 text-primary-600 dark:border-gray-600">
                                            <div class="flex items-center pl-2 font-medium">
                                                <x-checkbox
                                                    id=""
                                                    wire:model=""
                                                    value=""
                                                    md
                                                />
                                                <div class="flex space-x-1 items-start px-4 text-[0.7rem]">
                                                    <x-icon name="collection" class="w-4 h-4"/>
                                                    <h1>{{ $module->description }}</h1>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- @dump($currentSystem)
                                        @dump($module->id)
                                        @dump($permissions) --}}
                                        
                                    
                                        <div class="col-span-10 border dark:border-gray-600">
                                            <div class="grid grid-cols-4 px-3 py-4 gap-x-0 gap-y-4">
                                                @foreach ($permissions->where('system_id', $currentSystem)->where('module_id', $module->id) as $permission)
                                                    <div class="flex items-center space-x-2">
                                                        <x-checkbox
                                                            id=""
                                                            wire:model="selectedPermission"
                                                            value=""
                                                            md
                                                        />
                                                        <x-label class="text-[0.7rem]" label="{{ $permission->name }}"/>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            {{-- @elseif($setIndex  == '1')
                <!-- SISKOP -->
                <div class="px-2">
                    <div class="mt-10">
                        <div class="pb-4 mb-4 font-semibold border-b dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center pl-2 mt-3 space-x-2">
                                    <x-checkbox
                                        id=""
                                        wire:model=""
                                        value=""
                                        md
                                    />
                                    <h1 class="font-medium dark:text-white">
                                        Siskop Permissions
                                    </h1>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <x-label label="Search : " />
                                    <div class="w-64">
                                        <x-input
                                            wire:model.live="search"
                                            label=""
                                            placeholder="Search Module"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <div>
                                <div class="grid grid-cols-12">
                                    <div class="flex items-start col-span-2 py-3 border bg-gray-50 dark:bg-gray-900 text-primary-600 dark:border-gray-600">
                                        <div
                                            class="flex items-center pl-2 font-medium">
                                                <x-checkbox
                                                    id=""
                                                    wire:model=""
                                                    value=""
                                                    md
                                                />
                                            <div class="flex space-x-1 items-start px-4 text-[0.7rem]">
                                                <x-icon name="collection" class="w-4 h-4"/>
                                                <h1>Siskop Module</h1>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-10 border dark:border-gray-600">
                                        <div class="grid grid-cols-4 px-3 py-4 gap-x-0 gap-y-4">
                                            <div class="flex items-center space-x-2">
                                                <x-checkbox
                                                    id=""
                                                    wire:model="selectedPermission"
                                                    value=""
                                                    md
                                                />
                                                <x-label class="text-[0.68rem]" label="test"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif --}}

            <div class="flex justify-end px-2 py-4 bg-gray-50 dark:bg-gray-900">
                <div class="flex items-center space-x-2">
                    <x-button flat label="Cancel" href="{{ route('roles.index') }}"  />
                    <x-button primary label="Save" />
                </div>
            </div>

        </div>
    </x-container>
</div>
