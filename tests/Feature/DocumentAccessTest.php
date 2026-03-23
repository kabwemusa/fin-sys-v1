<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Document;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_preview_and_download_customer_documents(): void
    {
        $admin = User::factory()->create([
            'phone' => '+260970000001',
            'role' => 'admin',
        ]);

        $document = $this->createDocumentForCustomer('+260970000002');

        $this->actingAs($admin)
            ->get(route('documents.preview', $document))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('documents.download', $document))
            ->assertOk();
    }

    public function test_customer_cannot_access_another_customers_documents(): void
    {
        $ownerDocument = $this->createDocumentForCustomer('+260970000003');
        $otherCustomer = $this->createCustomerUser('+260970000004');

        $this->actingAs($otherCustomer)
            ->get(route('documents.preview', $ownerDocument))
            ->assertForbidden();
    }

    public function test_customer_can_preview_their_own_document(): void
    {
        $customer = $this->createCustomerUser('+260970000005');
        $document = $this->createDocumentForCustomer('+260970000005', $customer);

        $this->actingAs($customer)
            ->get(route('documents.preview', $document))
            ->assertOk();
    }

    private function createDocumentForCustomer(string $phone, ?User $user = null): Document
    {
        $user ??= $this->createCustomerUser($phone);

        $customer = $user->customer;
        $product = LoanProduct::create([
            'name' => 'Test Product '.$phone,
            'slug' => 'test-product-'.preg_replace('/\D+/', '', $phone),
            'type' => 'salary_backed',
            'description' => 'Test product',
            'min_amount' => 500,
            'max_amount' => 10000,
            'min_tenure_months' => 3,
            'max_tenure_months' => 12,
            'interest_rate' => 4,
            'requires_collateral' => false,
            'is_active' => true,
        ]);

        $application = LoanApplication::create([
            'customer_id' => $customer->id,
            'loan_product_id' => $product->id,
            'amount_requested' => 5000,
            'tenure_months' => 6,
            'interest_rate' => 4,
            'status' => 'pending',
        ]);

        $filePath = "documents/tests/{$application->reference}/document.pdf";
        Storage::disk('local')->put($filePath, 'test document');

        return Document::create([
            'loan_application_id' => $application->id,
            'type' => 'nrc',
            'original_filename' => 'document.pdf',
            'file_path' => $filePath,
            'file_size' => strlen('test document'),
            'status' => 'pending',
        ]);
    }

    private function createCustomerUser(string $phone): User
    {
        $user = User::factory()->create([
            'phone' => $phone,
            'role' => 'customer',
        ]);

        Customer::create([
            'user_id' => $user->id,
            'nrc_number' => '123456/78/9',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'marital_status' => 'single',
            'residential_address' => 'Test address',
            'city' => 'Lusaka',
            'province' => 'Lusaka',
        ]);

        return $user->fresh()->load('customer');
    }
}
