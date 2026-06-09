# AI Extraction Tasks

## Phase 1: OpenSpec Artifacts
- [ ] Write proposal.md
- [ ] Write spec.md (behavioral requirements)
- [ ] Write design.md (technical decisions)
- [ ] Write tasks.md (this file)

## Phase 2: Dependencies & Config
- [ ] `composer require laravel/ai`
- [ ] Create `config/ai.php` with Groq driver
- [ ] Add `GROQ_API_KEY` to `.env`

## Phase 3: Job
- [ ] Create `app/Jobs/ExtractExpensesDuRecu.php`
- [ ] Write the structured prompt with JSON schema
- [ ] Implement `AI::chat()` call with schema
- [ ] Implement type coercion (string qty → int, string price → float)
- [ ] Implement `DB::transaction` for expense creation
- [ ] Handle success: update receipt status → `processed`
- [ ] Handle failure: catch exceptions → status → `failed`

## Phase 4: Wire It Up
- [ ] Update Livewire `Create::submit()` to dispatch the job
- [ ] Verify job appears in `jobs` table on receipt submission

## Phase 5: Testing
- [ ] Pest test: `AI::fake()` with valid response → expenses created
- [ ] Pest test: `AI::fake()` with malformed response → status = failed
- [ ] Pest test: receipt stays pending if job not processed yet
- [ ] Run `php artisan test` — all passing

## Dependencies
- receipt-crud feature (receipts table, expenses table, Livewire views)
- `laravel/ai` package installed

## Verification
- [ ] All tasks completed
- [ ] `php artisan test` — all passing
- [ ] Manual: submit a receipt, run queue worker, check processed
