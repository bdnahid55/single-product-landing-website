<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'customer_name'    => $this->customer_name ? trim($this->customer_name) : null,
            'customer_phone'   => $this->customer_phone ? trim(str_replace([' ', '-'], '', $this->customer_phone)) : null,
            'customer_address' => $this->customer_address ? trim($this->customer_address) : null,
            'customer_notes'   => $this->customer_notes ? trim($this->customer_notes) : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name'       => ['required', 'string', 'max:255'],
            'customer_phone'      => ['required', 'string', 'max:14', 'regex:/^(?:\+?880|0)?1[1-9]\d{8}$/'],
            'customer_address'    => ['required', 'string', 'max:255'],
            'delivery_area'       => ['required', 'in:inside_dhaka,outside_dhaka'],
            'customer_notes'      => ['nullable', 'string', 'max:255'],
            'product_id'          => ['nullable', 'exists:products,id'],
            'quantity'            => ['nullable', 'integer', 'min:1', 'max:50'],
            'selected_products'   => ['nullable', 'array'],
            'selected_products.*' => ['exists:products,id'],
            'quantities'          => ['nullable', 'array'],
            'items'               => ['nullable', 'array'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_name.required'     => 'অনুগ্রহ করে আপনার নাম প্রদান করুন।',
            'customer_name.max'          => 'নামটি ২৫৫ অক্ষরের বেশি হতে পারবে না।',
            'customer_phone.required'    => 'অনুগ্রহ করে আপনার সচল মোবাইল নম্বর প্রদান করুন।',
            'customer_phone.regex'       => 'অনুগ্রহ করে একটি সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (যেমন: 017xxxxxxxx)।',
            'customer_address.required'  => 'অনুগ্রহ করে আপনার সম্পূর্ণ ডেলিভারি ঠিকানা প্রদান করুন।',
            'customer_address.max'       => 'ঠিকানাটি ২৫৫ অক্ষরের বেশি হতে পারবে না।',
            'delivery_area.required'     => 'অনুগ্রহ করে ডেলিভারি এলাকা (ঢাকার ভিতরে বা বাইরে) নির্বাচন করুন।',
            'delivery_area.in'           => 'নির্বাচিত ডেলিভারি এলাকাটি গ্রহণযোগ্য নয়।',
            'selected_products.*.exists' => 'নির্বাচিত পণ্যটি ডাটাবেজে পাওয়া যায়নি।',
        ];
    }
}
