<?php

namespace App\Livewire\Public;

use App\Models\Collateral;
use App\Models\Document;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use App\Models\User;
use App\Notifications\ApplicationSubmitted;
use App\Notifications\NewApplicationForAdmin;
use App\Services\AccountGeneratorService;
use App\Services\LoanCalculatorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplicationForm extends Component
{
    use WithFileUploads;

    public int $currentStep = 1;
    public int $totalSteps = 6;
    public ?LoanProduct $loanProduct = null;

    // Step 1 — Personal
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $nrc_number = '';
    public string $date_of_birth = '';
    public string $gender = '';
    public string $marital_status = '';
    public string $residential_address = '';
    public string $city = '';
    public string $province = '';

    // Step 2 — Employment
    public string $employer_name = '';
    public string $employer_address = '';
    public string $job_title = '';
    public string $employment_date = '';
    public string $monthly_income = '';

    // Step 3 — Collateral (collateral-backed only)
    public string $collateral_type = '';
    public string $collateral_description = '';
    public string $collateral_value = '';
    public string $collateral_registration = '';

    // Step 3/4 — Banking
    public string $bank_name = '';
    public string $bank_account_number = '';
    public string $bank_branch = '';

    // Step 4/5 — Loan Details
    public string $amount_requested = '';
    public int $tenure_months = 12;
    public string $purpose = '';

    // Step 5/6 — Documents
    public $document_nrc = null;
    public $document_payslip = null;
    public $document_bank_statement = null;
    public $document_employment_letter = null;
    public $document_collateral_proof = null;
    public $document_selfie = null;

    public function mount(string $slug): void
    {
        $this->loanProduct = LoanProduct::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $this->totalSteps = $this->loanProduct->requires_collateral ? 7 : 6;
        $this->tenure_months = $this->loanProduct->min_tenure_months;
        $this->amount_requested = (string) $this->loanProduct->min_amount;
    }

    public function nextStep(): void
    {
        $this->validateStep();
        $this->currentStep++;
    }

    public function previousStep(): void
    {
        $this->currentStep--;
    }

    public function submit(): void
    {
        $this->validateStep();

        // Variables shared between transaction and post-commit code
        $storedPaths = [];
        $application  = null;
        $notifyData   = null;

        try {
            DB::transaction(function () use (&$storedPaths, &$application, &$notifyData) {

                $accountService = app(AccountGeneratorService::class);
                $calcService    = app(LoanCalculatorService::class);

                $result = $accountService->createFromApplication(
                    personalData: [
                        'name'                 => $this->name,
                        'email'                => $this->email,
                        'phone'                => $this->phone,
                        'nrc_number'           => $this->nrc_number,
                        'date_of_birth'        => $this->date_of_birth,
                        'gender'               => $this->gender,
                        'marital_status'       => $this->marital_status,
                        'residential_address'  => $this->residential_address,
                        'city'                 => $this->city,
                        'province'             => $this->province,
                    ],
                    employmentData: [
                        'employer_name'    => $this->employer_name,
                        'employer_address' => $this->employer_address,
                        'job_title'        => $this->job_title,
                        'employment_date'  => $this->employment_date ?: null,
                        'monthly_income'   => $this->monthly_income ?: null,
                    ],
                    bankingData: [
                        'bank_name'           => $this->bank_name,
                        'bank_account_number' => $this->bank_account_number,
                        'bank_branch'         => $this->bank_branch,
                    ]
                );

                $calc = $calcService->calculate(
                    (float) $this->amount_requested,
                    (float) $this->loanProduct->interest_rate,
                    $this->tenure_months
                );

                $application = LoanApplication::create([
                    'customer_id'       => $result['customer']->id,
                    'loan_product_id'   => $this->loanProduct->id,
                    'amount_requested'  => $this->amount_requested,
                    'tenure_months'     => $this->tenure_months,
                    'purpose'           => $this->purpose,
                    'interest_rate'     => $this->loanProduct->interest_rate,
                    'monthly_repayment' => $calc['monthly_repayment'],
                    'status'            => 'pending',
                ]);

                // Store documents and record them
                $docMap = [
                    'nrc'               => $this->document_nrc,
                    'payslip'           => $this->document_payslip,
                    'bank_statement'    => $this->document_bank_statement,
                    'employment_letter' => $this->document_employment_letter,
                    'collateral_proof'  => $this->document_collateral_proof,
                    'selfie'            => $this->document_selfie,
                ];

                foreach ($docMap as $type => $file) {
                    if (! $file) continue;

                    $path = $file->store(
                        "documents/{$result['customer']->id}/{$application->reference}",
                        'local'
                    );
                    $storedPaths[] = $path; // track for cleanup if we throw later

                    // getRealPath() bypasses Flysystem (avoids Windows metadata bug)
                    $realPath = $file->getRealPath();
                    $fileSize = ($realPath && file_exists($realPath)) ? filesize($realPath) : 0;

                    Document::create([
                        'loan_application_id' => $application->id,
                        'type'                => $type,
                        'original_filename'   => $file->getClientOriginalName(),
                        'file_path'           => $path,
                        'file_size'           => $fileSize,
                    ]);
                }

                // Collateral (asset-backed products only)
                if ($this->loanProduct->requires_collateral && $this->collateral_type) {
                    Collateral::create([
                        'loan_application_id' => $application->id,
                        'type'                => $this->collateral_type,
                        'description'         => $this->collateral_description,
                        'estimated_value'     => $this->collateral_value,
                        'registration_number' => $this->collateral_registration ?: null,
                    ]);
                }

                // Stash notification data — sent after commit so a mail failure
                // cannot roll back an already-saved application
                $notifyData = [
                    'user'           => $result['user'],
                    'plain_password' => $result['plain_password'],
                    'application'    => $application,
                ];
            });

            // ── Post-commit: fire notifications ──────────────────────────────
            // Wrapped in its own try/catch so a mailer failure never surfaces
            // as a submission error to the applicant.
            try {
                if ($notifyData['plain_password']) {
                    $notifyData['user']->notify(
                        new ApplicationSubmitted($notifyData['application'], $notifyData['plain_password'])
                    );
                }

                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new NewApplicationForAdmin($notifyData['application']));
                }
            } catch (\Throwable $mailException) {
                Log::warning('Application notifications failed after successful submission', [
                    'application_id' => $application?->id,
                    'error'          => $mailException->getMessage(),
                ]);
            }

            $this->redirect(route('loan.confirmation', $application->reference));

        } catch (ValidationException $e) {
            // Livewire handles these — re-throw so field errors display normally
            throw $e;

        } catch (\Throwable $e) {
            // Roll back already-stored files so disk stays clean
            foreach ($storedPaths as $path) {
                Storage::disk('local')->delete($path);
            }

            Log::error('Application submission failed', [
                'email' => $this->email,
                'error' => $e->getMessage(),
                'at'    => $e->getFile() . ':' . $e->getLine(),
            ]);

            $this->addError('submission', 'We could not process your application due to a system error. Please try again or contact support.');
        }
    }

    private function validateStep(): void
    {
        $isCollateral = $this->loanProduct?->requires_collateral;

        $stepRules = [
            1 => [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'nrc_number' => 'required|string|max:20',
                'date_of_birth' => 'required|date|before:-18 years',
                'gender' => 'required|in:male,female',
                'marital_status' => 'required|in:single,married,divorced,widowed',
                'residential_address' => 'required|string',
                'city' => 'required|string|max:100',
                'province' => 'required|string|max:100',
            ],
            2 => [
                'employer_name' => 'required|string|max:255',
                'job_title' => 'required|string|max:255',
                'monthly_income' => 'required|numeric|min:1',
            ],
        ];

        if ($isCollateral) {
            $stepRules[3] = [
                'collateral_type' => 'required|in:vehicle,property,equipment,other',
                'collateral_description' => 'required|string',
                'collateral_value' => 'required|numeric|min:1',
            ];
            $bankingStep = 4;
            $loanStep = 5;
            $docsStep = 6;
        } else {
            $bankingStep = 3;
            $loanStep = 4;
            $docsStep = 5;
        }

        $stepRules[$bankingStep] = [
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
        ];

        $stepRules[$loanStep] = [
            'amount_requested' => "required|numeric|min:{$this->loanProduct->min_amount}|max:{$this->loanProduct->max_amount}",
            'tenure_months' => "required|integer|min:{$this->loanProduct->min_tenure_months}|max:{$this->loanProduct->max_tenure_months}",
        ];

        $stepRules[$docsStep] = [
            'document_nrc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        if (isset($stepRules[$this->currentStep])) {
            $this->validate($stepRules[$this->currentStep]);
        }
    }

    public function render()
    {
        return view('livewire.public.application-form')
            ->layout('components.layouts.app', ['title' => 'Apply — ' . ($this->loanProduct?->name ?? 'Loan Application')]);
    }
}
