@extends('layouts.app')

@section('content')
    <section class="header tw__relative tw__pt-16 tw__items-center tw__flex tw__h-screen" id="header">
        <div class="tw__container tw__mx-auto tw__items-center tw__flex tw__flex-wrap">
            <div class="tw__w-full md:tw__w-8/12 lg:tw__w-6/12 xl:tw__w-6/12 tw__px-4">
                <div class="tw__pt-6 lg:tw__pt-18 sm:tw__pt-0">
                    <h2 class="tw__font-semibold tw__text-4xl lg:tw__text-6xl tw__text-blueGray-600">{{ $wtitle ?? env('APP_NAME') }}</h2>
                    
                    <div class="tw__mt-2">
                        <p class="tw__text-lg tw__inline-block tw__bg-gray-700 tw__text-white tw__py-2 tw__px-4">{{ $wdescription ?? 'Just a Skeleton of Website' }}</p>
                    </div>

                    <div class="tw__mt-12">
                        <a class="tw__text-white tw__font-bold tw__px-6 tw__py-4 tw__rounded-lg tw__outline-none focus:tw__outline-none tw__mr-1 tw__mb-1 sabg-primary tw__uppercase tw__text-sm tw__shadow hover:tw__shadow-lg tw__ease-linear tw__transition-all tw__duration-150" href="#">Lihat Produk</a>
                        <a class="tw__ml-1 tw__text-white tw__font-bold tw__px-6 tw__py-4 tw__rounded-lg tw__outline-none focus:tw__outline-none tw__mr-1 tw__mb-1 sabg-alt tw__uppercase tw__text-sm tw__shadow hover:tw__shadow-lg" href="#" target="_blank">Daftar Toko</a>
                    </div>
                </div>
            </div>
        </div>
        {{-- <img class="tw__absolute tw__top-0 tw__b-auto tw__right-0 tw__pt-16 sm:tw__w-6/12 tw__-mt-48 sm:tw__mt-0 tw__w-10/12" src="{{ asset('images/GeometricIllustration (3).svg') }}" alt="..." style="max-height:860px"> --}}
    </section>
    
    <section class="statistic">

    </section>
@endsection