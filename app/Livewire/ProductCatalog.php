<?php

declare(strict_types=1); //mengaktifkan declare static types

namespace App\Livewire;

use App\Data\ProductCollectionData;
use App\Data\ProductData;
use App\Models\Product;
use App\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;

class ProductCatalog extends Component
{
    use WithPagination;

    // queryString agar di link nya terdapat search= ketika dilakukan search
    public $queryString = [
        'select_collections' => ['except' => []],
        'search' => ['except' => []],
        'sort_by' => ['except' => 'newest']
    ];

    public array $select_collections = [];

    public string $search = '';

    public string $sort_by = 'newest'; //latest, price_asc, price_desc

    public function mount()
    {
        $this->validate();
    }

    public function rules()
    {
        return [
            'select_collections'    => 'array',
            'select_collections.*'  => 'integer|exists:tags,id',
            'search'                => 'nullable|string|min:3|max:30',
            'sort_by'               => 'in:newest,latest,price_asc,price_desc'
        ];
    }
    protected function validationAttributes()
    {
        return [
            'select_collections' => 'Collection',
            'sort_by' => 'Sort By'
        ];
    }

    public function applyFilters()
    {
        $this->validate();
        // dd($this->select_collections, $this->search, $this->sort_by);
        $this->resetPage(); //resetPage komponen bawaan livewire diambil dari WithPagination
    }

    public function resetFilters()
    {
        $this->select_collections = [];
        $this->search = '';
        $this->sort_by = 'newest';

        $this->resetErrorBag();
        $this->resetPage();
    }

    public function render()
    {
        $collections    = ProductCollectionData::collect([]);
        $products       = ProductData::collect([]);
        // Early Return
        if ($this->getErrorBag()->isNotEmpty()) {
            return view('livewire.product-catalog', compact('products', 'collections'));
        }

        $collection_result = Tag::query()->withType('collection')->withCount('Products')->get();
        // $query = Product::paginate(3); //ORM hanya bertugas konek ke db

        // Query untuk menampilkan link search= ketika user melakukan filter
        $query = Product::query();
        if ($this->search) {
            $query->where('name', 'LIKE', "%{$this->search}%");
            // dd($query->get());  
        }

        // Filtering untuk Select Collections
        if (!empty($this->select_collections)) {
            $query->whereHas('tags', function ($query) {
                $query->whereIn('id', $this->select_collections);
            });
        }

        // Filtering untuk Sort By
        switch ($this->sort_by) {
            case 'latest':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                deafult:
                $query->latest();
                break;
        }

        // DTO yang hanya mempassing data tanpa konek ke database
        // $products = ProductData::collect($query);
        $products = ProductData::collect($query->paginate(3));
        $collections = ProductCollectionData::collect($collection_result);
        return view('livewire.product-catalog', compact('products', 'collections'));
    }
}
