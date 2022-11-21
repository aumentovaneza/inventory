@extends('layouts.app')
@section('content')
    <main>
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Replace with your content -->
            <div class="px-4 py-8 sm:px-0">
                <a href="{{ url('/products') }}"
                   class="ml-3 mb-5 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Back
                </a>
                <div class="h-auto rounded-lg border-2 border-solid border-gray-200 p-5 pb-10">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Takeout Inventory</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            This form is for taking out inventories from a product from it's current list of inventories
                        </p>
                    </div>
                    <form
                        id="takeout-form"
                        class="space-y-8 divide-y divide-gray-200"
                        method="get"
                        action="{{ url('products/inventory/apply') }}"
                    >
                        {{csrf_field()}}
                        <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                            <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                                <div class="space-y-6 sm:space-y-5">
                                    <div
                                        class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                        <label for="product"
                                               class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                        >
                                            Products
                                        </label>
                                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                                            <select id="product"
                                                    name="product"
                                                    class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm">
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                        <label for="amount"
                                               class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                        >
                                            Quantity
                                        </label>
                                        <div class="mt-1 sm:col-span-2 sm:mt-0">
                                            <input id="quantity"
                                                   name="quantity"
                                                   type="number"
                                                   min = "1"
                                                   class="block w-16 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-5">
                            <div class="flex justify-end">
                                <button type="submit"
                                        class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if(!empty($error))
                <div class="border border-red-600 border-solid p-5 bg-red-100">
                    {{ $error['message'] }}
                    ]
                </div>
            @endif
            @if(!empty($applicationArray))
                <div class="border border-blue-600 border-solid p-5 bg-blue-100">
                    <div class="mb-3">
                        The following are applied on the inventory:
                    </div>
                    <ul class="list-disc">
                        @foreach($applicationArray as $app)
                            <li>
                                Product variant with SKU number <span class="font-bold">{{ $app['product_sku'] }}</span>
                                with a price of <span class="font-bold">${{ $app['price'] }}</span> is applied with
                                quantity <span class="font-bold">{{ $app['applied'] }}</span> where the total would be
                                <span class="font-bold">${{ $app['price'] * $app['applied']}}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if ($errors->any())
                <div class="border border-red-600 border-solid p-5 bg-red-100">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </main>
@stop
