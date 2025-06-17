<?php

namespace App\Livewire;

use App\Contract\CartServiceInterface;
use App\Data\CartData;
use App\Data\CheckoutData;
use App\Data\CustomerData;
use App\Data\RegionData;
use App\Data\ShippingData;
use App\Rules\ValidPaymentHash;
use App\Rules\ValidPaymentMethodHash;
use App\Rules\ValidShippingHash;
use App\Services\PaymentMethodQueryService;
use App\Services\RegionQueryService;
use App\Services\ShippingMethodService;
use Illuminate\Support\Collection;
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
        'destination_region_code' => null,
        'shipping_hash' => null,
        'payment_method_hash' => null
    ];

    public array $region_selector = [
        'keyword' => null,
        'region_selected' => null
    ];

    public array $shipping_selector = [
        'shipping_method' => null
    ];

    public array $payment_method_selector = [
        'payment_method_selected' => null
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

        if ($this->cart->total_quantity <= 0) {
            return redirect()->route('cart');
        }

        $this->calculateTotal();
    }

    public function rules()
    {
        return [
            'data.full_name'    => ['required', 'min:3', 'max:255'],
            'data.email'        => ['required','lowercase','email', 'max:255'],
            'data.phone'        => ['required','numeric','min:7','digits_between:12,15'],
            'data.address_line' => ['required','min:10','max:255'],
            'data.destination_region_code' => ['required','exists:regions,code'],
            'data.shipping_hash' => ['required', new ValidShippingHash()],
            'data.payment_method_hash' => ['required', new ValidPaymentMethodHash],
        ];
    }

    public function calculateTotal()
    {
        data_set($this->summaries,'sub_total', $this->cart->total);
        data_set($this->summaries, 'sub_total_formatted', $this->cart->total_formatted);
        
        $shipping_cost = $this->shippingMethod?->cost ?? 0;
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

    public function getRegionsProperty(RegionQueryService $query_service) : DataCollection
    {
        // $data = [
        //     [
        //         'code' => '001',
        //         'province' => 'Jawa Barat',
        //         'city' => 'Bandung',
        //         'district' => 'District',
        //         'sub_district' => 'Sub District',
        //         'postal_code' => '1234',
        //     ],
        //     [
        //         'code' => '002',
        //         'province' => 'Jawa Barat1',
        //         'city' => 'Bandung1',
        //         'district' => 'District',
        //         'sub_district' => 'Sub District',
        //         'postal_code' => '1123234',
        //     ],
        // ];
        if (!data_get($this->region_selector, 'keyword')) {
            $data = [];
            return new DataCollection(RegionData::class, []);
        }

        return $query_service->searchRegionByName(
            data_get($this->region_selector, 'keyword')
        );
    }

    public function getRegionProperty(RegionQueryService $query_service) : ?RegionData
    {
        $region_selected = data_get($this->region_selector, 'region_selected');
        if (!$region_selected) {
            return null;
        }
        // return $this->regions->toCollection()->first(fn(RegionData $region) => $region->code == $region_selected);
        return $query_service->searchRegionByCode($region_selected);
    }

    public function updatedRegionSelectorRegionSelected($value)
    {
        // dd($value);
        data_set($this->data, 'destination_region_code', $value);
    }

    /** @return DataCollection<ShippingData> */ 
    public function getShippingMethodsProperty(
        RegionQueryService $region_query,
        ShippingMethodService $shipping_service
    ) : DataCollection|Collection
    {
        if (! data_get($this->data, 'destination_region_code')) {
            return new DataCollection(ShippingData::class, []);
        }

        $origin_code = config('shipping.shipping_origin_code');

        return $shipping_service->getShippingMethods(
            $region_query->searchRegionByCode($origin_code),
            $region_query->searchRegionByCode(data_get($this->data, 'destination_region_code')),
            $this->cart
        )->toCollection()->groupBy('service');
    }

    public function getShippingMethodProperty(ShippingMethodService $shipping_service) : ?ShippingData
    {
        if (empty(data_get($this->data, 'shipping_hash')) || empty(data_get($this->data, 'destination_region_code'))){
            return null;
        }
        $data = $shipping_service->getShippingMethod(data_get($this->data, 'shipping_hash'));

        if ($data == null) {
            $this->addError('shipping_hash', "Shipping Cost Error");
            redirect()->route('checkout');
        }

        return $data;
    }

    public function updatedShippingSelectorShippingMethod($value)
    {
        data_set($this->data, 'shipping_hash', $value);
        $this->calculateTotal();
    }

    public function getPaymentMethodsProperty(PaymentMethodQueryService $query_service) : DataCollection
    {
        return $query_service->getPaymentMethods();
    }

    public function updatedPaymentMethodSelectorPaymentMethodSelected($value)
    {
        // dd($value);
        data_set($this->data, 'payment_method_hash', $value);
    }

    public function placeAnOrder()
    {
        $validated = $this->validate();
        // dd($validated, data_get($validated, 'shipping_hash'));
        $shipping_method = app(ShippingMethodService::class)->getShippingMethod(data_get($validated, 'data.shipping_hash'));
        $payment_method = app(PaymentMethodQueryService::class)->getPaymentMethodByHash(data_get($validated,'data.payment_method_hash'));

        $checkout = CheckoutData::from([
            'customer' => CustomerData::from(data_get($validated, 'data')),
            'address_line' => data_get($validated, 'data.address_line'),
            'origin' => $shipping_method->origin,
            'destination' => $shipping_method->destination,
            'cart' => $this->cart,
            'shipping' => $shipping_method,
            'payment' => $payment_method
        ]);
        dd($checkout);
        // dd($this->data);
    }

    public function render()
    {
        return view('livewire.checkout', [
            'cart' => $this->cart
        ]);
    }
}
