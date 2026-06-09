# AI Extraction Specification

## Purpose
Background job processes raw receipt text via the `laravel/ai` SDK (Groq provider) and extracts structured expense items, persisting them as typed `Expense` records.

## Requirements

### Requirement: Dispatch-job-on-receipt-creation
The system SHALL dispatch the extraction job immediately after a receipt is created.

#### Scenario: Job queued on submission
- **GIVEN** the user submits a new receipt
- **WHEN** the receipt is saved with status `pending`
- **THEN** an `ExtractExpensesDuRecu` job is dispatched to the queue
- **AND** the user sees the "Receipt submitted for processing" message

### Requirement: Extract-structured-expenses
The system SHALL call the AI provider and create Expense records from the structured response.

#### Scenario: Successful extraction
- **GIVEN** a receipt with `pending` status
- **WHEN** the job runs and AI returns valid JSON matching the schema
- **THEN** one `Expense` row is created per `articles[]` item
- **AND** the receipt status becomes `processed`
- **AND** `raw_ai_payload` stores the full AI response JSON
- **AND** `expenses_count` reflects the number of expenses

#### Scenario: Extraction with no articles
- **GIVEN** a receipt with `pending` status
- **WHEN** the AI returns `{"articles": []}`
- **THEN** no `Expense` rows are created
- **AND** the receipt status becomes `processed`

### Requirement: Handle-extraction-failures
The system SHALL set the receipt status to `failed` when extraction cannot complete.

#### Scenario: AI API unreachable
- **GIVEN** a receipt with `pending` status
- **WHEN** the job runs and the AI API returns an error or times out
- **THEN** the receipt status becomes `failed`
- **AND** `raw_ai_payload` is set to `null`

#### Scenario: Malformed AI response
- **GIVEN** a receipt with `pending` status
- **WHEN** the AI returns non-JSON or JSON missing the `articles` key
- **THEN** the receipt status becomes `failed`

### Requirement: Type-coerce-ai-output
The system SHALL coerce AI output types to match database column types.

#### Scenario: String-to-integer coercion
- **GIVEN** the AI returns `"quantité": "3"` as a string
- **WHEN** the job processes the response
- **THEN** the Expense is saved with `quantity: 3` (integer)

#### Scenario: String-to-decimal coercion
- **GIVEN** the AI returns `"prix_unitaire": "12.50"` as a string
- **WHEN** the job processes the response
- **THEN** the Expense is saved with `unit_price: 12.50` (decimal)

### Requirement: Categorize-articles
The system SHALL map the AI's category string to one of the five `ExpenseCategory` enum values.

#### Scenario: Valid category mapping
- **GIVEN** the AI returns `"catégorie": "alimentaire"`
- **WHEN** the job processes the response
- **THEN** the Expense category is set to `ExpenseCategory::Alimentaire`

#### Scenario: Unknown category fallback
- **GIVEN** the AI returns `"catégorie": "unknown_value"`
- **WHEN** the job processes the response
- **THEN** the Expense category is set to `ExpenseCategory::Autre`
