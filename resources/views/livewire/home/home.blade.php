<div>
    <div class="container mx-auto mt-4" x-data="{tab:0}">
        <div class="grid grid-cols-1 px-4 sm:px-6 mb-20 xl:mb-0">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 sm:col-span-12 md:col-span-12 xl:col-span-8 2xl:col-span-8">
                    <div class="bg-white/70 backdrop-blur-lg py-8 px-6 md:px-12 2xl:px-24 sm:py-4  shadow-lg rounded-lg  sm:h-72 flex flex-col items-center justify-center border dark:bg-gray-900/50 dark:border-black">
                        <div class="grid grid-cols-1 md:grid-cols-2 items-center justify-center dark:text-white">
                            <div class="space-y-2 flex flex-col order-last sm:order-first">
                                <h1 class="text-3xl font-bold text-center sm:text-left">
                                    Welcome to <span class="text-primary-500">CSC-CA</span>
                                </h1>
                                <h4 class="text-gray-500 text-sm dark:text-white text-center sm:text-left">
                                    Control Access Management System (CAMS) for a Financing Management System (FMS) is a 
                                    specialized security framework designed to safeguard sensitive financial data and operations. 
                                </h4>
                                <div class="pt-4">
                                    <x-button class="py-2 w-full sm:w-fit"  href="{{ route('userManagement') }}" icon="arrow-circle-right" primary label=" Go to User Management" />
                                </div>
                            </div>
                            <div class="flex justify-center items-center ml-0 sm:-ml-6">
                                <img src="{{asset('herodashboard.png')}}" class="w-auto h-64 sm:h-80" alt="Hero"/>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-span-12 sm:col-span-12 md:col-span-12 xl:col-span-4 2xl:col-span-4">

                    <div class="hidden xl:block">
                        <div class="bg-white/70 dark:bg-gray-900/50 dark:border-black  dark:text-white backdrop-blur-lg p-4 shadow-lg rounded-lg h-72 flex flex-col items-center justify-center px-12 border">
                            <x-avatar size="w-24 h-24" class="border-primary-700 border-2" src="{{asset('profile.jpeg')}}" />
                            <h1 class="pt-2">
                                {{ auth()->user()->name }}
                            </h1>
                            <h1 class="pt-1 text-gray-500 text-sm dark:text-white"> 
                                {{ auth()->user()->email }}
                            </h1>
                            <div class="w-full mt-5">
                                <x-button class="py-3 w-full"  href="{{route('profile')}}" outline black label="Edit Profile" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
