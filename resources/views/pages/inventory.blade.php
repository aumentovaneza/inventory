@extends('layouts.app')
@section('content')
    <header>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900">Inventory Movement</h1>
        </div>
    </header>
    <main>
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Replace with your content -->
            <div class="px-4 py-8 sm:px-0">
                <div class="h-auto rounded-lg border-2 border-solid border-gray-200 p-5 pb-10">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="mt-8 flex flex-col">
                            <div class="sm:flex sm:items-center mb-10">
                                <div class="sm:flex-auto">
                                    <h1 class="text-xl font-semibold text-gray-900">Inventory</h1>
                                    <p class="mt-2 text-sm text-gray-700">A list of the inventory movement of all products.</p>
                                </div>
                                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                                    <a  href="{{ route('inventory.form') }}"
                                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"

                                    >Takeout Inventory</a>
                                </div>
                            </div>
                            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">SKU</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Quantity</th>
                                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                    <span class="sr-only">Edit</span>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                            @foreach($products as $product)
                                                @foreach($product->variants as $variant)
                                                    @foreach($variant->inventories as $inventory)
                                                        <tr>
                                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $product->name }}</td>
                                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $variant->sku }}</td>
                                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $inventory->type }}</td>
                                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $inventory->quantity }}</td>
                                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                                <button type="button" class="text-gray-600 hover:text-gray-900" disabled="true">Edit</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /End replace -->
        </div>
    </main>
@stop
