# AI Extraction Tasks

## Phase 1: OpenSpec Artifacts
- [x] Write proposal.md
- [x] Write spec.md (behavioral requirements)
- [x] Write design.md (technical decisions)
- [x] Write tasks.md (this file)

## Phase 2: Dependencies & Config
- [x] `composer require laravel/ai`
- [x] Create `config/ai.php` with Groq driver (published + default set to `groq`)
- [x] Add `GROQ_API_KEY` to `.env`

## Phase 3: Job
- [x] Create `app/Jobs/ExtractExpensesDuRecu.php`
- [x] Write the structured prompt with JSON schema
- [x] Implement `StructuredAnonymousAgent` SDK call with schema
- [x] Implement type coercion (string qty → int, string price → float)
- [x] Implement `DB::transaction` for expense creation
- [x] Handle success: update receipt status → `processed`
- [x] Handle failure: catch exceptions → status → `failed`
- [x] Map unknown categories → `ExpenseCategory::Autre`

## Phase 4: Wire It Up
- [x] Update Livewire `Create::submit()` to dispatch the job
- [x] Test passes: `Queue::assertPushed(ExtractExpensesDuRecu::class)`

## Phase 5: Testing
- [x] Pest test: `StructuredAnonymousAgent::fake()` with valid response → expenses created
- [x] Pest test: `StructuredAnonymousAgent::fake()` with malformed response → status = failed
- [x] Pest test: empty articles → processed with no expenses
- [x] Pest test: API exception → status = failed
- [x] Pest test: string type coercion → correct PHP types
- [x] Pest test: unknown category → `Autre`
- [x] `php artisan test` — 32/32 passed

## Dependencies
- receipt-crud feature (receipts table, expenses table, Livewire views)
- `laravel/ai` package installed

## Verification
- [x] All tasks completed
- [x] `php artisan test` — 32/32 passed, 82 assertions
- [ ] Manual: submit a receipt, run queue worker, check processed (requires GROQ_API_KEY)
