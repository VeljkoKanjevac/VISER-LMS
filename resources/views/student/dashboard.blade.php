<x-app-layout>
    <x-slot name="header">
        <h1 class="h3 mb-0">
            Moj nalog
        </h1>
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h5">
                        Dobrodošao, {{ auth()->user()->name }}!
                    </h2>

                    <p class="text-muted mb-0">
                        Ovde će se nalaziti tvoji kursevi, lekcije i konsultacije.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
