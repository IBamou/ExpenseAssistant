# AI Extraction Design

## Overview
A Laravel queued job (`ExtractExpensesDuRecu`) receives a `Receipt`, calls the `laravel/ai` SDK configured for Groq, sends a structured prompt enforcing a JSON schema, parses the response, and creates `Expense` records. All inside a database transaction.

## Architecture / Design Decisions

### Decision: `laravel/ai` SDK with Groq driver
- **Context:** Official Laravel AI abstraction, interchangeable provider
- **Options considered:** Direct HTTP calls to Groq, `laravel/ai` SDK
- **Chosen approach:** `laravel/ai` SDK — already in the project brief. Configured via `config/ai.php` using Groq as the provider.
- **Trade-offs:** Slightly more abstraction overhead, but providers are swappable without changing job code.
- **Impact:** `composer require laravel/ai`, `config/ai.php`, `.env` key

### Decision: Structured prompt with JSON schema
- **Context:** The AI must return parseable, shaped JSON
- **Options considered:** Free-text extraction with regex parsing, structured output
- **Chosen approach:** `laravel/ai` structured output using `ChatRequest::withOutputSchema()` with a PHP class or array schema matching the required JSON contract
- **Trade-offs:** Structured output is guaranteed by the SDK — no manual `json_decode` crash risk
- **Impact:** Prompt in the Job files includes the schema definition

### Decision: Database transaction per receipt
- **Context:** A batch of expenses must be saved atomically
- **Options considered:** Save one-by-one, wrap in transaction
- **Chosen approach:** Wrap expense creation in `DB::transaction()` inside the job. If any expense fails, the whole batch rolls back and status stays `pending` or becomes `failed`.
- **Trade-offs:** Slightly more database time, but data integrity is paramount for Brahim.

### Decision: No Laravel Data/Validation for AI output
- **Context:** The AI output may have minor type mismatches (string instead of integer for quantity)
- **Options considered:** Laravel Data DTO, manual validation, cast on model
- **Chosen approach:** Validate AI output with a simple array validation in the job — check types, coerce `prix_unitaire` and `quantité` to correct types. Model casts handle the rest.
- **Trade-offs:** Less structural strictness than Data DTO, but simpler to maintain.

### Decision: Job dispatch in ReceiptController@store
- **Context:** The job must start when a receipt is submitted
- **Options considered:** Event/Listener, dispatch in controller, observer
- **Chosen approach:** `ExtractExpensesDuRecu::dispatch($receipt)` in the `store` method, after the receipt is created. Clean, immediate, easy to find.
- **Impact:** Modify `ReceiptController@store` (or the Livewire `Create::submit()`)

## Implementation Plan
1. Write OpenSpec artifacts
2. `composer require laravel/ai`
3. Create `config/ai.php` with Groq driver config
4. Add `GROQ_API_KEY` to `.env`
5. Update `StoreReceiptRequest` / Livewire Create to add validation note
6. Create `app/Jobs/ExtractExpensesDuRecu.php`
7. Write the prompt with JSON schema
8. Write expense creation logic with type coercion
9. Wire job dispatch in Livewire `Create::submit()`
10. Handle failure: catch exceptions, set status to `failed`
11. Test with `AI::fake()` in Pest

## Data Model / Schema Changes
None — `receipts` and `expenses` tables already exist.

## Job Flow
```
Create::submit() → Receipt created (pending)
                 → ExtractExpensesDuRecu::dispatch($receipt)
                                                    ↓
                              AI::chat() → structured output
                                                    ↓
                              Validate + coerce types
                                                    ↓
                              DB::transaction → create Expense rows
                              Receipt::update(['status' => 'processed'])
                                                    ↓
                              On failure → Receipt::update(['status' => 'failed'])
```

## AI Prompt Design
The job sends a prompt that:
1. Describes the role: "You are an expense extraction assistant for a grocery store"
2. Describes the receipt text format (Darija, abbreviations, mixed languages)
3. Provides the exact JSON schema as structured output
4. Instructs category mapping to the 5 enum values

## Error Handling
- `\Exception` or AI SDK exception → set status to `failed`, log error, store raw payload for debugging
- Malformed AI response (missing `articles` key) → same failure path
- Queue retry: 1 try (configurable in `queue:work --tries=1`)

## Testing Strategy
- Use `AI::fake()` to return fake structured JSON
- Test valid AI response creates Expense records
- Test invalid AI response sets status to `failed`
- Test partial bad data (missing field) → handled gracefully
- Test dispatch is actually queued
