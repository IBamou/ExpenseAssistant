# AI Extraction Proposal

## Summary
Implement the background job that calls the AI (Groq via `laravel/ai` SDK) to extract structured expense data from raw receipt text, creating typed `Expense` records and updating receipt status.

## Motivation
Receipts are submitted with `pending` status but never transition to `processed` — the AI brain is missing. Without this, the app is just a text storage tool. Brahim needs automated extraction to get value from his pasted receipts.

## What Changes

**[New Capability: ai-extraction]**
- From: Receipts stay `pending` forever, no expenses created
- To: A queued job calls Groq AI with a structured prompt, parses the guaranteed JSON output, creates `Expense` rows per article, and updates receipt status to `processed` (or `failed` on error)
- Reason: The core value proposition of the app — automatic expense extraction
- Impact: Non-breaking. Adds new Job class, AI config, and `.env` key. Livewire views already display expenses — they'll now populate automatically.

## Scope
### In Scope
- `laravel/ai` SDK installation + configuration
- `config/ai.php` with Groq driver setup
- `ExtractExpensesDuRecu` job (dispatched on receipt creation)
- JSON schema contract enforcement with fallback validation
- `processed` / `failed` status transitions
- Error handling: malformed JSON, API unreachable, schema mismatch
- PHP enum `ExpenseCategory` already exists — reused

### Out of Scope / Non-Goals
- Image/multimodal extraction (bonus feature)
- Retry logic beyond queue tries
- Dashboard financial aggregates

## Impact Analysis
- **Affected capabilities:** receipt-crud (receipts now transition from pending)
- **Affected config:** `.env` needs `GROQ_API_KEY`, `config/ai.php`
- **Breaking changes:** No
- **Rollback plan:** Remove Job dispatch from `ReceiptController@store`, comment out `laravel/ai` config
