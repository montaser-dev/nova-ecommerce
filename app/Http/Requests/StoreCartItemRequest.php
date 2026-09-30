<?php

namespace App\Http\Requests;

use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'product_variant_id' => [
                'required',
                'integer',
                'exists:product_variants,id',
            ],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $variant = ProductVariant::with('product')->find($this->product_variant_id);

                if (!$variant) {
                    return;
                }

                if ($variant->product_id !== (int) $this->product_id) {
                    $validator->errors()->add(
                        'product_variant_id',
                        'The selected variant does not belong to the selected product.'
                    );

                    return;
                }

                if ($variant->product->status !== 'active') {
                    $validator->errors()->add(
                        'product_id',
                        'This product is not available.'
                    );

                    return;
                }

                if ((int) $this->quantity > $variant->stock) {
                    $validator->errors()->add(
                        'quantity',
                        'The requested quantity exceeds available stock.'
                    );
                }
            },
        ];
    }
}
