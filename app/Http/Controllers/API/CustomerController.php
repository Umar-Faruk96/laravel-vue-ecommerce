<?php

namespace App\Http\Controllers\API;

use App\Enums\AddressType;
use App\Enums\CustomerStatus;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CustomerListResource;
use App\Http\Resources\CustomerResource;
use App\Models\Country;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
	public function index(Request $request) : JsonResource
	{
		$perPage = $request->get('per_page', 10);
		$search = $request->get('search', '');
		$sortBy = $request->get('sort_by', 'updated_at');
		$sortTo = $request->get('sort_to', 'desc');
		
		$query = Customer::query()->orderBy($sortBy, $sortTo);
		
		if($search) {
			$query->where(DB::raw("CONCAT_WS(' ', first_name, last_name )"), 'like', "%$search%")
				->orWhere('email', 'like', "%$search%")
				->orWhere('phone', 'like', "%$search%")
				->orWhere('status', 'like', "%$search%");
		}
		
		return CustomerListResource::collection($query->paginate($perPage));
	}
	
	public function show(Customer $customer) : JsonResource
	{
		return CustomerResource::make($customer);
	}
	
	public function store(CreateCustomerRequest $request) : JsonResource
	{
		$customerData = $request->validated();
		
		$customerData['status'] = $customerData['status'] ? CustomerStatus::Active->value : CustomerStatus::Disabled->value;
		$customerData['created_by'] = request()->user()->id;
		$customerData['updated_by'] = request()->user()->id;
		
		$customer = Customer::create($customerData);
		
		$customer->shippingAddress()->create([
			'customer_id' => $customer->user_id,
			'type' => AddressType::Shipping,
			...$customerData['shippingAddress']
		]);
		
		$customer->billingAddress()->create([
			'customer_id' => $customer->user_id,
			'type' => AddressType::Billing,
			...$customerData['billingAddress']
		]);
		
		return CustomerResource::make($customer);
	}
	
	public function update(UpdateCustomerRequest $request, Customer $customer) : JsonResource
	{
		$customerData = $request->validated();
		
		$customerData['status'] = $customerData['status'] ? CustomerStatus::Active->value : CustomerStatus::Disabled->value;
		$customerData['updated_by'] = $customerData['id'] ?? request()->user()->id;
		
		$customer->update($customerData);
		
		/*$customerData['shippingAddress'] = [
			'customer_id' => $customer->user_id,
			'type' => AddressType::Shipping->value,
			...$customerData['shippingAddress']
		];*/
		
		// $customer->shippingAddress()->update($customerData['shippingAddress']);
		
		$customer->shippingAddress()->updateOrCreate(['customer_id' => $customer->user_id, 'type' => AddressType::Shipping], $customerData['shippingAddress']);
		$customer->billingAddress()->updateOrCreate(['customer_id' => $customer->user_id, 'type' => AddressType::Billing], $customerData['billingAddress']);
		
		return CustomerResource::make($customer);
	}
	
	public function destroy(Customer $customer) : JsonResponse
	{
		$customer->delete();
		
		return response()->json([
			'message' => 'Customer deleted successfully'
		]);
	}
	
	public function getCountries() : AnonymousResourceCollection
	{
		return CountryResource::collection(Country::query()->orderBy('name', 'asc')->get());
	}
}