<?php

namespace App\Livewire;

use App\Contract\CartServiceInterface;
use App\Data\CartData;
use App\Data\RegionData;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Number;
use Livewire\Component;
use Spatie\LaravelData\DataCollection;

class Checkout extends Component
{
    public array $data = [
        'full_name' => null,
        'email' => null,
        'phone' => null,
        'address_line' => null,
        'destination_region_code' => null
    ];

    public array $region_selector = [
        'keyword' => null,
        'region_selected' => null
    ];

    public array $summaries = [
        'sub_total' => 0,
        'sub_total_formatted' => '-',
        'shipping_total' => 0,
        'shipping_total_formatted' => '-',
        'grand_total' => 0,
        'grand_total_formatted' => '-'
    ];

    public function mount()
    {
    //     dd(
    //     Gate::inspect('is_stock_available')->allowed()
    // );
        if (!Gate::inspect('is_stock_available')->allowed()) {
            return redirect()->route('cart');
        }
        $this->calculateTotal();
    }

    public function rules()
    {
        return [
            'data.full_name'    => ['required', 'min:3', 'max:255'],
            'data.email'        => ['required','email', 'max:255'],
            'data.phone'        => ['required', 'min:7','max:13'],
            'data.address_line' => ['required', 'numeric','min:10','max:255'],
            'data.destination_region_code' => ['required']
        ];
    }

    public function calculateTotal()
    {
        data_set($this->summaries,'sub_total', $this->cart->total);
        data_set($this->summaries, 'sub_total_formatted', $this->cart->total_formatted);
        
        $shipping_cost = 0;
        data_set($this->summaries, 'shipping_total', $shipping_cost);
        data_set($this->summaries, 'shipping_total_formatted', Number::currency($shipping_cost));

        $grand_total = $this->cart->total + $shipping_cost;
        data_set($this->summaries, 'grand_total', $grand_total);
        data_set($this->summaries, 'grand_total_formatted', Number::currency($grand_total));
    }
    public function getCartProperty(CartServiceInterface $cart) : CartData
    {
        return $cart->all();
    }

    public function getRegionsProperty() : DataCollection
    {
        $data = [
            [
                'code' => '001',
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'district' => 'District',
                'sub_district' => 'Sub District',
                'postal_code' => '1234',
            ],
            [
                'code' => '002',
                'province' => 'Jawa Barat1',
                'city' => 'Bandung1',
                'district' => 'District',
                'sub_district' => 'Sub District',
                'postal_code' => '1123234',
            ],
        ];
        if (!data_get($this->region_selector, 'keyword')) {
            $data = [];
        }
        return new DataCollection(RegionData::class, $data);
    }

    public function getRegionProperty() : ?RegionData
    {
        $region_selected = data_get($this->region_selector, 'region_selected');
        if (!$region_selected) {
            return null;
        }
        return $this->regions->toCollection()->first(fn(RegionData $region) => $region->code == $region_selected);
    }

    public function updatedRegionSelectorRegionSelected($value)
    {
        // dd($value);
        data_set($this->data, 'destination_region_code', $value);
    }

    public function placeAnOrder()
    {
        $this->validate();

        dd($this->data);
    }

    public function render()
    {
        return view('livewire.checkout', [
            'cart' => $this->cart
        ]);
    }
}
