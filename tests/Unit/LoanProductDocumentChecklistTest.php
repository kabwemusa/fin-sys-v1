<?php

namespace Tests\Unit;

use App\Models\LoanProduct;
use Tests\TestCase;

class LoanProductDocumentChecklistTest extends TestCase
{
    public function test_collateral_products_require_collateral_proof(): void
    {
        $product = new LoanProduct([
            'requires_collateral' => true,
        ]);

        $requiredTypes = collect($product->requiredDocumentTypes());

        $this->assertTrue($requiredTypes->contains('nrc'));
        $this->assertTrue($requiredTypes->contains('collateral_proof'));
    }

    public function test_salary_products_only_require_nrc_by_default(): void
    {
        $product = new LoanProduct([
            'requires_collateral' => false,
        ]);

        $this->assertSame(['nrc'], $product->requiredDocumentTypes());
    }
}
